<?php
use Peridot\Concurrency\Runner\StreamSelect\Message\Message;
use Peridot\Concurrency\Runner\StreamSelect\Message\MessageBroker;

describe('MessageBroker', function () {
    beforeEach(function () {
        $this->broker = new MessageBroker();
    });

    describe('->addMessage()', function () {
        it("should add the given message to the broker's list of messages", function () {
            $tmp = tmpfile();
            $message = new Message($tmp);
            $this->broker->addMessage($message);

            $messages = $this->broker->getMessages();

            expect($messages)->to->contain($message);
        });
    });

    describe('->removeMessage()', function () {
        it('should remove the given message', function () {
            $tmp = tmpfile();
            $message = new Message($tmp);
            $this->broker->addMessage($message);

            $this->broker->removeMessage($message);

            expect($this->broker->getMessages()->count())->to->equal(0);
        });
    });

    context('when added messages emit events', function () {
        beforeEach(function () {
            $tmp = tmpfile();
            $this->message = new Message($tmp);
            $this->broker->addMessage($this->message);
        });

        it('should emit the same event', function () {
            $val = null;
            $this->broker->on('some.event', function ($x) use (&$val) {
                $val = $x;
            });
            $this->message->emit('some.event', [1]);
            expect($val)->to->equal(1);
        });
    });

    describe('->getStreams()', function () {
        beforeEach(function () {
            $this->stream1 = tmpfile();
            $this->stream2 = tmpfile();
            $this->broker->addMessage(new Message($this->stream1));
            $this->broker->addMessage(new Message($this->stream2));
        });

        it('should return a collection of underlying message streams', function () {
            $streams = $this->broker->getStreams();
            expect($streams[0])->to->equal($this->stream1);
            expect($streams[1])->to->equal($this->stream2);
        });
    });

    describe('->read()', function () {

        it('should throw an exception if streams cant be read', function () {
            expect([$this->broker, 'read'])->to->throw('RuntimeException');
        });

        it('should not throw an exception with valid resources', function () {
            $resource = tmpfile();
            $message = new Message($resource);
            $this->broker->addMessage($message);
            expect([$this->broker, 'read'])->to->not->throw('RuntimeException');
            fclose($resource);
        });
    });
});
