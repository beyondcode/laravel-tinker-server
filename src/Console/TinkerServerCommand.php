<?php

namespace BeyondCode\LaravelTinkerServer\Console;

use Psy\Shell;
use Psy\Configuration;
use Illuminate\Console\Command;
use Laravel\Tinker\ClassAliasAutoloader;
use BeyondCode\LaravelTinkerServer\Server;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class TinkerServerCommand extends Command
{
    protected $signature = 'tinker-server';

    public function handle()
    {
        $this->createWarningFormatter();

        $server = new Server(config('laravel-tinker-server.host'), $this->createPsyShell(), $this->output);

        $server->start();
    }

    protected function createWarningFormatter()
    {
        if (! $this->output->getFormatter()->hasStyle('warning')) {
            $style = new OutputFormatterStyle('yellow');

            $this->output->getFormatter()->setStyle('warning', $style);
        }
    }

    protected function createPsyShell()
    {
        $config = new Configuration([
            'updateCheck' => 'never'
        ]);

        $config->getPresenter()->addCasters(
            $this->getCasters()
        );

        $shell = new Shell($config);

        $path = $this->getLaravel()->basePath().DIRECTORY_SEPARATOR.'vendor/composer/autoload_classmap.php';

        ClassAliasAutoloader::register($shell, $path);

        return $shell;
    }

    protected function getCasters()
    {
        $casters = [
            'Illuminate\Support\Collection' => 'Laravel\Tinker\TinkerCaster::castCollection',
        ];
        if (class_exists('Illuminate\Database\Eloquent\Model')) {
            $casters['Illuminate\Database\Eloquent\Model'] = 'Laravel\Tinker\TinkerCaster::castModel';
        }
        if (class_exists('Illuminate\Foundation\Application')) {
            $casters['Illuminate\Foundation\Application'] = 'Laravel\Tinker\TinkerCaster::castApplication';
        }
        return $casters;
    }
}