<?php

if (! function_exists('tinker')) {
    function tinker(...$args)
    {
        /*
         * Thank you Caleb
         * See: https://github.com/calebporzio/awesome-helpers/blob/master/src/helpers/tinker.php
         */
        $namedParameters = collect(debug_backtrace())
            ->where('function', 'tinker')->take(1)
            ->map(function ($slice) {
                return array_values($slice);
            })
            ->mapSpread(function ($filePath, $lineNumber, $function, $args) {
                return file($filePath)[$lineNumber - 1];
                // "    tinker($post, new User);"
            })->map(function ($carry) {
                return str_before(str_after($carry, 'tinker('), ');');
                // "$post, new User"
            })->flatMap(function ($carry) {
                return array_map('trim', explode(',', $carry));
                // ["post", "new User"]
            })->map(function ($carry, $index) {
                return strpos($carry, '$') === 0
                    ? str_after($carry, '$')
                    : 'temp'.$index;
                // ["post", "temp1"]
            })
            ->combine($args)->all();

        $connection = new \BeyondCode\LaravelTinkerServer\Connection(config('laravel-tinker-server.host'));

        if (! $connection->write($namedParameters)) {
            dump($args);
        }
    }
}

if (! function_exists('td')) {
    function td(...$args)
    {
        tinker($args);

        die(1);
    }
}
