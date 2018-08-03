<?php
/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 18-2-11
 * Time: 上午11:51
 */

require_once 'Address.php';
//require_once 'Baz.php';
$address = new Address();
$address->setName('a');
$address->setPhone('b');
print_r($address->dump());
$address->setFrom(
    [
        'name' => 'c',
        'phone' => 'd',
        'name1' => 'e',
    ]
);
print_r($address->dump());
//$baz = new Baz();
//$baz->setFrom([
//    'id' => 111,
//    'ab' => 'cd',
//]);
//print_r($baz->dump());