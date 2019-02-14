<?php

use Psy\Configuration;
use BeyondCode\LaravelTinkerServer\Server;
use Symfony\Component\Console\Output\BufferedOutput;

$componentRoot = $_SERVER['COMPONENT_ROOT'] ?? __DIR__.'/../..';

$file = $componentRoot.'/vendor/autoload.php';

require $file;
require $_SERVER['COMPONENT_ROOT'].'/tests/fixtures/EchoStream.php';

$loop = \React\EventLoop\Factory::create();

$output = new BufferedOutput();

$config = new Configuration([
    'updateCheck' => 'never',
]);

$stdio = new \Clue\React\Stdio\Stdio($loop, null, new \EchoStream());

$shell = new \Psy\Shell($config);

$server = new Server(getenv('TINKER_SERVER_HOST'), $shell, $output, $loop, $stdio);

echo "READY\n";

$server->start();
