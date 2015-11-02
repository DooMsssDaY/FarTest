<?php
/**
 * Класс для работы с маршрутами
 */
class Router
{
	/**
     * Массив всех роутов сайта
     * @var array
     */
	private $routes;

	public function __construct()
	{
		$routerPath = ROOT.'/config/routers.php';
		$this->routes = include $routerPath;
	}

    /**
     * Получение адреса страницы
     * @return string
     */
	private function getURI()
	{
		if(!empty($_SERVER['REQUEST_URI']))	
			return trim($_SERVER['REQUEST_URI'], '/');
	}

    /**
     * Подключение контроллеров
     */
	public function run()
	{
		// получение адреса
		$uri = $this->getURI();

		//проход по всем роутам
		foreach ($this->routes as $r_uri => $r_path)
		{	
			// сравнение адреса и роутов
			if (preg_match("~$r_uri~", $uri))
			{
				// Замена сегментов в адресе
				$internalRoute = preg_replace("~$r_uri~", $r_path, $uri);
				// резбивка адреса на сегменты
				$path_arr = explode('/', $internalRoute);
				// получение имени контроллера
				$controller = ucfirst((array_shift($path_arr)."Controller"));
				// получение имени действия
				$action = "action".array_shift($path_arr);
				// получение параметров
				$params = $path_arr;
				// создание экземпляра и вызов действия с передачей параметров
				$conlrollerObj = new $controller;
				$result = call_user_func_array(array($conlrollerObj, $action), $params);

				if ($result != null) {
					break;
				}
			}
		}
	}
}