<?php
namespace Peridot\Concurrency\Runner\StreamSelect\IO;

/**
 * The WorkerPoolInterface manages open worker processes.
 *
 * @package Peridot\Concurrency\Runner\StreamSelect\IO
 */
interface WorkerPoolInterface
{
    /**
     * Starts all workers and sends input to them until none
     * is left. Additionally starts polling of streams for changes.
     *
     * @param string $command
     * @return void
     */
    public function start($command);

    /**
     * Get the next available worker.
     *
     * @return WorkerInterface|null
     */
    public function getAvailableWorker();

    /**
     * Start worker processes, attaching worker processes
     * to fill the number of configured processes.
     *
     * @param string $command
     * @return void
     */
    public function startWorkers($command);

    /**
     * Attach a worker to the WorkerPool and start
     * it if it is not already started. The workers output and error streams will
     * be stored and monitored for changes.
     *
     * @return bool
     */
    public function attach(WorkerInterface $worker);

    /**
     * Remove a worker from the WorkerPool and free it and
     * any associated resources.
     *
     * @param WorkerInterface $worker
     * @return void
     */
    public function detach(WorkerInterface $worker);

    /**
     * Get all workers attached to the runner.
     *
     * @return \SplObjectStorage
     */
    public function getWorkers();

    /**
     * Get all streams being read from.
     *
     * @return array
     */
    public function getReadStreams();

    /**
     * Set the pending tests.
     *
     * @param array $pending
     * @return void
     */
    public function setPending($pending);

    /**
     * Get the number of pending tests.
     *
     * @return array
     */
    public function getPending();

    /**
     * Return a collection of running workers.
     *
     * @return array
     */
    public function getRunning();

    /**
     * Add a worker to the list of running workers in the pool.
     *
     * @param WorkerInterface $worker
     *
     * @return void
     */
    public function addRunning(WorkerInterface $worker);

    /**
     * Checks if there are any pending tests or running workers.
     *
     * @return bool
     */
    public function isWorking();

    /**
     * Get the MessageBroker used by the pool.
     *
     * @return \Peridot\Concurrency\Runner\StreamSelect\Message\MessageBroker
     */
    public function getMessageBroker();

    /**
     * Returns the worker that owns the given stream.
     *
     * @param $stream
     * @return WorkerInterface
     */
    public function getWorkerForStream($stream);

    /**
     * Immediately stop the worker pool.
     *
     * @return void
     */
    public function stop();
}
