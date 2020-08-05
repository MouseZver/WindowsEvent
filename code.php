<?php

$command = file_get_contents ( 'config/Screen2base64.ps1' );

$command = strtr ( $command, [
	'{{x}}' => 600,
	'{{y}}' => 300,
	'{{width}}' => 77,
	'{{height}}' => 22,
	'{{file}}' => 'test.png',
] );

$s = microtime ( true );

var_dump ( $b = shell_exec ( 
	$a = 'powershell -sta "' . strtr ( $command, [
		'"' => '\"',
		PHP_EOL => ' '
	] ) . '"'
) );

printf ( '%.3f', microtime ( true ) - $s );