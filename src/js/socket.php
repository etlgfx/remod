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
        'module' => '4eb436c8-38fc-426f-a53f-2b5c0acc4267'
    )
));

echo stream_get_contents($f) . PHP_EOL;
fclose($f);

?>
