<?php

namespace BeyondCode\LaravelTinkerServer;

use Psy\Shell;
use Clue\React\Stdio\Stdio;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use React\Socket\ConnectionInterface;
use React\Socket\Server as SocketServer;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use BeyondCode\LaravelTinkerServer\Shell\ExecutionClosure;

class Server
{
    protected $host;

    /** @var LoopInterface */
    protected $loop;

    /** @var BufferedOutput */
    protected $shellOutput;

    /** @var Shell */
    protected $shell;

    /** @var OutputInterface */
    protected $output;

    public function __construct($host, Shell $shell, OutputInterface $output, LoopInterface $loop = null)
    {
        $loop = $loop ?? Factory::create();

        $this->host = $host;
        $this->loop = $loop;
        $this->shell = $shell;
        $this->output = $output;
        $this->shellOutput = new BufferedOutput();
    }

    public function start()
    {
        $this->shell->setOutput($this->shellOutput);

        $this->createSocketServer();

        $this->createStdio();

        $this->loop->run();
    }

    protected function createSocketServer()
    {
        $socket = new SocketServer($this->host, $this->loop);

        $socket->on('connection', function (ConnectionInterface $connection) {
            $connection->on('data', function ($data) use ($connection) {
                $unserializedData = unserialize(base64_decode($data));

                $this->shell->setScopeVariables(array_merge($unserializedData, $this->shell->getScopeVariables()));

                $this->output->write(PHP_EOL);

                collect($unserializedData)->keys()->map(function ($variableName) {
                    $this->output->writeln('>> $'.$variableName);

                    $this->executeCode('$'.$variableName);

                    $this->output->write($this->shellOutput->fetch());
                });
            });
        });
    }

    protected function createStdio()
    {
        $stdio = new Stdio($this->loop);

        $stdio->getReadline()->setPrompt('>> ');

        $stdio->on('data', function ($line) use ($stdio) {
            $line = rtrim($line, "\r\n");

            $stdio->getReadline()->addHistory($line);

            $this->executeCode($line);

            $this->output->write(PHP_EOL.$this->shellOutput->fetch());
        });
    }

    protected function executeCode($code)
    {
        (new ExecutionClosure($this->shell, $code))->execute();
    }
}
