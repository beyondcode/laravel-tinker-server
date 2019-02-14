<?php

use Psy\Configuration;
use BeyondCode\LaravelTinkerServer\Server;
use Symfony\Component\Console\Output\ConsoleOutput;

$componentRoot = $_SERVER['COMPONENT_ROOT'] ?? __DIR__.'/../..';

$file = $componentRoot.'/vendor/autoload.php';

require $file;

$output = new ConsoleOutput();

$config = new Configuration([
    'updateCheck' => 'never',
]);

$shell = new \Psy\Shell($config);

$server = new Server(getenv('TINKER_SERVER_HOST'), $shell, $output);

echo "READY\n";

$server->start();
