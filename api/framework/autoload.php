<?php

//require('framework/functions.php');


spl_autoload_register(
    function ($class) {
        static $namespacesMap = [
            'framework' => 'framework'
        ];

        $class = str_replace('\\', '/', $class);

        foreach ($namespacesMap as $name => $path) {
            if (0 === strpos($class, $name)) {
                $filename = str_replace($name, $path, $class);
                $file = $filename . '.php';
                include $file;
            }
        }
    },
    true,
    true
);


