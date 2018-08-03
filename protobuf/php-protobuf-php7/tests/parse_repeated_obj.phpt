--TEST--
Protocol Buffers repeated object field parsing
--SKIPIF--
<?php require 'skipif.inc' ?>
--FILE--
<?php
require 'Bar.php';
require 'Foo.php';

$bar1 = new Bar();
$bar1->setDoubleField(1.1);

$bar2 = new Bar();
$bar2->setDoubleField(2.2);

$expected = new Foo();
$expected->appendRepeatedObjField($bar1);
$expected->appendRepeatedObjField($bar2);

$actual = new Foo();
$actual->parseFromString(base64_decode('0gEJCZqZmZmZmfE/0gEJCZqZmZmZmQFA'));

var_dump($expected == $actual);
?>
--EXPECT--
bool(true)
