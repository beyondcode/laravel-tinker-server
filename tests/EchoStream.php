<?php

namespace BeyondCode\LaravelTinkerServer\Tests;

use React\Stream\WritableStreamInterface;

class EchoStream implements WritableStreamInterface
{
    public function on($event, callable $listener)
    {
        // TODO: Implement on() method.
    }

    public function once($event, callable $listener)
    {
        // TODO: Implement once() method.
    }

    public function removeListener($event, callable $listener)
    {
        // TODO: Implement removeListener() method.
    }

    public function removeAllListeners($event = null)
    {
        // TODO: Implement removeAllListeners() method.
    }

    public function listeners($event = null)
    {
        // TODO: Implement listeners() method.
    }

    public function emit($event, array $arguments = [])
    {
        // TODO: Implement emit() method.
    }

    public function isWritable()
    {
        return true;
    }

    public function write($data)
    {
        echo $data;
    }

    public function end($data = null)
    {
        // TODO: Implement end() method.
    }

    public function close()
    {
        // TODO: Implement close() method.
    }
}
