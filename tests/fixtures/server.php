<?php

use BeyondCode\LaravelTinkerServer\Server;
use BeyondCode\LaravelTinkerServer\Tests\EchoStream;
use Psy\Configuration;
use Symfony\Component\Console\Output\BufferedOutput;

$componentRoot = $_SERVER['COMPONENT_ROOT'] ?? __DIR__.'/../..';

$file = $componentRoot.'/vendor/autoload.php';

require $file;

$loop = \React\EventLoop\Factory::create();

$output = new BufferedOutput();

$config = new Configuration([
    'updateCheck' => 'never',
]);

$stdio = new \Clue\React\Stdio\Stdio($loop, null, new EchoStream());

$shell = new \Psy\Shell($config);

$server = new Server(getenv('TINKER_SERVER_HOST'), $shell, $output, $loop, $stdio);

echo "READY\n";

$server->start();
