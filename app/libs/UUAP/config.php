<?php
$phpcas_path="phpcas";
$cas_host = 'uuap.baidu.com';
$cas_port = 443;
$cas_context = '';

if ($_SERVER['RUN_MODE'] == 'development') {
    $cas_host = 'itebeta.baidu.com';
    $cas_port = 443;
    $cas_context = '';
}
?>
