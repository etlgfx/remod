<?php

$autoload = parse_ini_string(file_get_contents('php://stdin'));

if (!$autoload) {
	die('Error creating autolaod section');
}

$ini = parse_ini_file(__DIR__ .'/config.ini', true);

if (isset($ini['autoload'])) {
	unset($ini['autoload']);
}

$ini['autoload'] = $autoload;

foreach ($ini as $section => $kv) {
	echo '['. $section .']'. PHP_EOL;

	foreach ($kv as $k => $v) {
		echo $k .'='. $v . PHP_EOL;
	}

	echo PHP_EOL;
}

?>
