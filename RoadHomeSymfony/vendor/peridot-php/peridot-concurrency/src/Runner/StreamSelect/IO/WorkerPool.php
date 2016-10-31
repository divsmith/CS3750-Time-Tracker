<?php
namespace Peridot\Concurrency\Runner\StreamSelect\IO;

use Evenement\EventEmitterInterface;
use Peridot\Concurrency\Configuration;
use Peridot\Concurrency\Runner\StreamSelect\Message\ErrorMessage;
use Peridot\Concurrency\Runner\StreamSelect\Message\Message;
use Peridot\Concurrency\Runner\StreamSelect\Message\MessageBroker;
use Peridot\Concurrency\Runner\StreamSelect\Message\TestMessage;
use Peridot\Core\HasEventEmitterTrait;

/**
 * An evented WorkerPool for managing worker processes.
 *
 * @package Peridot\Concurrency\Runner\StreamSelect\IO
 */
class WorkerPool implements WorkerPoolInterface
{
    use HasEventEmitterTrait;

    /**
     * @var \Peridot\Concurrency\Configuration
     */
    protected $configuration;

    /**
     * @var array
     */
    protected $pending = [];

    /**
     * @var array
     */
    protected $running = [];

    /**
     * @var \SplObjectStorage
     */
    protected $workers;

    /**
     * @var ResourceOpenInterface
     */
    protected $resourceOpen;

    /**
     * @var MessageBroker
     */
    protected $broker;

    /**
     * The command being run by workers in this pool.
     *
     * @var string
     */
    private $command;

    /**
     * @param Configuration $configuration
     * @param EventEmitterInterface $eventEmitter
     * @param MessageBroker $broker
     * @param ResourceOpenInterface $resourceOpen
     */
    public function __construct(
        Configuration $configuration,
        EventEmitterInterface $eventEmitter,
        MessageBroker $broker,
        ResourceOpenInterface $resourceOpen = null
    ) {
        $this->workers = new \SplObjectStorage();
        $this->configuration = $configuration;
        $this->eventEmitter = $eventEmitter;
        $this->broker = $broker;
        $this->resourceOpen = $resourceOpen;
        $this->listen();
    }

    /**
     * {@inheritdoc}
     *
     * @param string $command
     * @return void
     */
    public function start($command)
    {
        $this->startWorkers($command);
        while ($this->isWorking()) {
            $worker = $this->getAvailableWorker();

            if ($worker && ! empty($this->pending)) {
                $worker->run(array_shift($this->pending));
            }

            $this->broker->read();
        }
    }

    /**
     * {@inheritdoc}
     *
     * @return null|WorkerInterface
     */
    public function getAvailableWorker()
    {
        foreach ($this->workers as $worker) {
            if (! $worker->isRunning()) {
                return $worker;
            }
        }
        return null;
    }

    /**
     * {@inheritdoc}
     *
     * If any changes are detected, then an
     * event is emitted signaling which worker has completed.
     *
     * @param string $command
     * @return void
     */
    public function startWorkers($command)
    {
        $this->command = $command;
        $processes = $this->configuration->getProcesses();
        for ($i = 0; $i < $processes; $i++) {
            $worker = new Worker($command, $this->eventEmitter, $this->resourceOpen);
            $this->attach($worker);
        }
        $this->eventEmitter->emit('peridot.concurrency.pool.start-workers', [$this]);
    }

    /**
     * {@inheritdoc}
     *
     * @param WorkerInterface $worker
     * @return bool
     */
    public function attach(WorkerInterface $worker)
    {
        if (count($this->workers) === $this->configuration->getProcesses()) {
            return false;
        }

        $this->workers->attach($worker);

        if (! $worker->isStarted()) {
            $worker->start();
        }

        $this->broker->addMessage(new TestMessage($worker->getOutputStream()));

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @param WorkerInterface $worker
     * @return void
     */
    public function detach(WorkerInterface $worker)
    {
        $worker->free();
        $this->workers->detach($worker);
    }

    /**
     * {@inheritdoc}
     *
     * @return \SplObjectStorage
     */
    public function getWorkers()
    {
        return $this->workers;
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getReadStreams()
    {
        return $this->broker->getStreams();
    }

    /**
     * {@inheritdoc}
     *
     * @param array $pending
     */
    public function setPending($pending)
    {
        $this->pending = $pending;
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getPending()
    {
        return $this->pending;
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getRunning()
    {
        return $this->running;
    }

    /**
     * {@inheritdoc}
     *
     * @param WorkerInterface $worker
     */
    public function addRunning(WorkerInterface $worker)
    {
        $this->running[] = $worker;
    }

    /**
     * {@inheritdoc}
     *
     * @return bool
     */
    public function isWorking()
    {
        $numPending = count($this->getPending());
        $numRunning = count($this->getRunning());
        return $numPending > 0 || $numRunning > 0;
    }

    /**
     * {@inheritdoc}
     *
     * @return MessageBroker
     */
    public function getMessageBroker()
    {
        return $this->broker;
    }

    /**
     * {@inheritdoc}
     *
     * @param $stream
     * @return WorkerInterface
     */
    public function getWorkerForStream($stream)
    {
        foreach ($this->workers as $worker) {
            if ($worker->hasStream($stream)) {
                return $worker;
            }
        }

        return null;
    }

    /**
     * Frees a worker and removes workers that are not running
     * from the internal collection of running workers.
     *
     * @param WorkerInterface $worker
     *
     * @return void
     */
    public function onWorkerComplete(WorkerInterface $worker)
    {
        $worker->free();
        $this->running = array_filter($this->running, function (WorkerInterface $worker) {
            return $worker->isRunning();
        });
    }

    /**
     * Match a running worker to a message resource and
     * emit a completed event for that worker.
     *
     * @param Message $message
     */
    public function onMessageEnd(Message $message)
    {
        $worker = $this->getWorkerForStream($message->getResource());
        $worker->getJobInfo()->end = microtime(true);
        $this->eventEmitter->emit('peridot.concurrency.worker.completed', [$worker]);
    }

    /**
     * Remove the worker that erred and replace it.
     *
     * @param $error
     * @param Message $message
     * @return void
     */
    public function onError($error, Message $message)
    {
        $worker = $this->getWorkerForStream($message->getResource());

        if ($worker === null) {
            return;
        }

        $this->eventEmitter->emit('peridot.concurrency.worker.error', [$error, $worker]);
        $this->detach($worker);
        $this->broker->removeMessage($message);

        $worker = new Worker($this->command, $this->eventEmitter, $this->resourceOpen);
        $this->attach($worker);
    }

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function stop()
    {
        $this->pending = [];
        $this->running = [];
    }

    /**
     * Set event listeners.
     *
     * @return void
     */
    protected function listen()
    {
        $this->eventEmitter->on('peridot.concurrency.load', [$this, 'setPending']);
        $this->eventEmitter->on('peridot.concurrency.worker.run', [$this, 'addRunning']);
        $this->eventEmitter->on('peridot.concurrency.worker.completed', [$this, 'onWorkerComplete']);
        $this->broker->on('end', [$this, 'onMessageEnd']);
        $this->broker->on('error', [$this, 'onError']);
        $this->broker->on('suite.halt', [$this, 'stop']);
    }
}
