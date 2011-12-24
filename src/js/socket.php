<?php

/*
$socket = socket_create(AF_UNIX, SOCK_DGRAM, 'udp');
socket_bind($socket, '/tmp/remod.php.sock');
socket_connect($socket, '/tmp/remod.sock');
socket_write($socket, "poo\n");
socket_close($socket);
 */

$f = fsockopen('unix:///tmp/remod.sock');
fwrite($f, json_encode(
    array(
        'data' => array(
            'module' => array(
                'name' => 'value'
            ),
            'module2' => array(
                'name' => 'other value'
            ),
        ),
        'config' => array(),
        'module' => array(
            'uuid' => 1234
        )
    )
));
echo fgets($f) . PHP_EOL;
fclose($f);

?>
