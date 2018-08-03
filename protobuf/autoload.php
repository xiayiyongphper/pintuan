<?php
/**
 * Created by PhpStorm.
 * User: wangyang
 * Date: 18-6-12
 * Time: 下午9:11
 */

spl_autoload_register(
    function ($class) {
        static $namespacesMap = [
            'message' => 'message',
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