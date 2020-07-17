<?php

declare ( strict_types = 1 );

/*
	@ Author: MouseZver
	@ Email: mouse-zver@xaker.ru
	@ php-version 7.4
*/

namespace Nouvu\Shell;

use Nouvu;
use WindowsEvent\Directory\Shell AS S;

class WindowsEvent
{
	private array $cache;
	private array $config;
	
	public function __construct ( Nouvu\Config\Config $config )
	{
		if ( PHP_OS_FAMILY != 'Windows' )
		{
			echo 'WindowsEvent: Requires OS Windows' . PHP_EOL;
		}
		
		$this -> config = $config;
	}
	
	private function shell( string $command ): ?string
	{
		return shell_exec ( 'powershell -sta "' . strtr ( $command, [ '"' => '\"', PHP_EOL => ' ' ] ) . '"' );
	}
	
	private function cache( string $name ): string
	{
		$string = $this -> config -> get( $name );
		
		return ( $this -> cache[$name] ??= static function () use ( string $string ): string
		{
			return file_get_contents ( $string );
		} )();
	}
	
	public function saveScreenshot( int $x, int $y, int $width, int $height, string $file = null ): ?string
	{
		return $this -> shell( strtr ( $this -> cache( $file ? S\Screen\File :: class : S\Screen\String :: class ), [
			'{{x}}' => $x,
			'{{y}}' => $y,
			'{{width}}' => $width ?: 1,
			'{{height}}' => $height ?: 1,
			'{{file}}' => $file
		] ) );
	}
	
	public function cursorPosition( int $x, int $y ): void
	{
		$this -> shell( strtr ( $this -> cache( S\Mouse\cursorPosition :: class ), [
			'{{x}}' => $x,
			'{{y}}' => $y,
		] ) );
	}
	
	public function mouseLClick(): void
	{
		$this -> shell( $this -> cache( S\Mouse\mouseLClick :: class ) );
	}
}
