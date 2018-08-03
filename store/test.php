<?php



spl_autoload_register(
    function ($class) {
        $class = str_replace('\\', '/', $class);

        if(0 === strpos($class,'service/message')){
            $filename = str_replace('service/message', '/lelaisoft/protocol-message', $class);
            $file = $filename. '.php';
            include_once $file;
        }

        if(0 === strpos($class,'framework')){
            $filename = str_replace('framework', '/lelaisoft/framework', $class);
            $file = $filename. '.php';
            include_once $file;
        }

        echo $file;

    }
);

new service\message\abc\def();



