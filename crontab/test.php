<?php
/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 17-9-19
 * Time: 下午4:38
 */

$test = 1.1;
print_r(intval($test));
echo PHP_EOL;
echo date('Y-m-d 23:59:59', strtotime('-2 days'));
echo PHP_EOL;
$a = [12 => ['wallet' => 1]];
for ($i = 0; $i < 4; $i++) {
    $a[13]['wallet'] += $i;
    $a[12]['wallet'] += $i;
}
print_r($a);
echo PHP_EOL;
print_r(array_unique(array_merge([1,2,3],[3,4,5])));
echo PHP_EOL;
$mm = [];
foreach ($mm as$kk){
    echo '11';
}