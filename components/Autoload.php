<?php
/**
 * Функция для автоматического подключения классов
 */
function __autoload($class_name)
{
	$paths = array(
					'/components/',
					'/config/',
					'/models/',
					'/controllers/',
				  );

	foreach ($paths as $path)
	{	
		$path = ROOT."$path".$class_name.".php";

		if (is_file($path))
			include_once $path;
	}
}