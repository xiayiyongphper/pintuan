<?php
$git_pull = "git pull";
$git_status = shell_exec($git_pull);
$current_dir = dirname(__FILE__); //当前文件夹目录
$protoc_complier_file = $current_dir . '/php-protobuf-php7/protoc-gen-php.php';
$cmd = "php {$protoc_complier_file} -o ./ proto/*/*.proto";
$status = shell_exec($cmd);
exit();
