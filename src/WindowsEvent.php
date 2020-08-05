<?php

declare ( strict_types = 1 );

/*
	@ Author: MouseZver
	@ Email: mouse-zver@xaker.ru
	@ php-version 7.4
*/

namespace Nouvu\Shell;

use Nouvu;
use Nouvu\Config\Config;
use WindowsEvent\Directory\Shell AS S;

class WindowsEvent
{
	private array $cache;
	private Config $config;
	
	public function __construct ( Config $config )
	{
		if ( PHP_OS_FAMILY != 'Windows' )
		{
			throw new \Error( 'WindowsEvent: Requires OS Windows' );
		}
		
		$this -> config = $config;
	}
	
	private function shell( string $command ): ?string
	{
		return shell_exec ( 'powershell -sta "' . strtr ( $command, [ '"' => '\"', PHP_EOL => ' ' ] ) . '"' );
	}
	
	private function cache( string $name ): string
	{
		return $this -> cache[$name] ??= file_get_contents ( $this -> config -> get( $name ) );
	}
	
	public function saveScreenshot( int $x, int $y, int $width, int $height, string $file = null ): ?string
	{
		$string = $this -> cache( $file ? 'WindowsEvent.Directory.Shell.Screen.File' : 'WindowsEvent.Directory.Shell.Screen.String' );
		
		return $this -> shell( strtr ( $string, [
			'{{x}}' => $x,
			'{{y}}' => $y,
			'{{width}}' => $width ?: 1,
			'{{height}}' => $height ?: 1,
			'{{file}}' => $file
		] ) );
	}
	
	public function cursorPosition( int $x, int $y ): void
	{
		$this -> shell( strtr ( $this -> cache( 'WindowsEvent.Directory.Shell.Mouse.cursorPosition' ), [
			'{{x}}' => $x,
			'{{y}}' => $y,
		] ) );
	}
	
	public function mouseLClick(): void
	{
		$this -> shell( $this -> cache( 'WindowsEvent.Directory.Shell.Mouse.mouseLClick' ) );
	}
}
