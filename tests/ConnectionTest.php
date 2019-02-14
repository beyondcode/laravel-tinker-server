<?php

namespace BeyondCode\LaravelTinkerServer\Tests;

use BeyondCode\LaravelTinkerServer\Connection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\PhpProcess;
use Symfony\Component\Process\Process;

class ConnectionTest extends TestCase
{
    const TINKER_SERVER_HOST = 'tcp://127.0.0.1:9914';

    public function testDump()
    {
        $connection = new Connection(self::TINKER_SERVER_HOST);

        $dumped = null;

        $process = $this->getServerProcess();

        try {
            $process->start(function ($type, $buffer) use ($process, &$dumped, $connection) {
                if (Process::ERR === $type) {
                    $process->stop();
                    $this->fail();
                } elseif ("READY\n" === $buffer) {
                    usleep(5000);
                    $result = $connection->write(['i' => 10]);

                    $this->assertTrue($result);
                } else {
                    $dumped .= $buffer;
                }
            });

            $process->wait();
        } catch (ProcessTimedOutException $e) {
            //
        }

        $this->assertSame('
>> $i
=> 10
', $dumped);
    }

    /** @test */
    public function it_detects_if_the_tinker_server_is_offline()
    {
        $connection = new Connection(self::TINKER_SERVER_HOST);

        $start = microtime(true);
        $this->assertFalse($connection->write([]));
        $this->assertLessThan(1, microtime(true) - $start);
    }

    protected function getServerProcess(): Process
    {
        $process = new PhpProcess(file_get_contents(__DIR__.'/fixtures/server.php'), null, [
            'COMPONENT_ROOT' => __DIR__.'/../',
            'TINKER_SERVER_HOST' => self::TINKER_SERVER_HOST,
        ]);

        $process->inheritEnvironmentVariables(true);

        return $process->setTimeout(3);
    }
}