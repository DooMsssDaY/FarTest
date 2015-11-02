<?php
/**
 * Класс User - модель для работы с пользователями
 */
class User
{
    /**
     * Регистрация нового пользователя
     * @return Integer <p>кол-во добавленных записей в таблице</p>
     */
	public static function register($name, $email, $password)
	{	
		// получение подключения
		$db = Database::getConnection();
		// хеширование пароля
		$password = md5($password);
		// получение полного имени таблицы с учётом префикса
		$table = Config::DB_tab_prefix."users";

		// подготовка запроса
		$result = $db->prepare("INSERT INTO $table(name, email, password) VALUES(?,?,?)") 
		or die("Не удалось подготовить запросы: (" . $db->errno . ") " . $db->error);

		// пресвоение параметров и выполнение
		$result->bind_param('sss', $name, $email, $password) or die("Не удалось привязать параметры: (" . $db->errno . ") " . $db->error);
		$result->execute() or die("Не удалось выполнить запрос: (" . $db->errno . ") " . $db->error);

		// получение колличеста внесённых записей и закрытие соединения
		$affected_rows = $result->affected_rows;
		$result->close();

		return $affected_rows;
	}

    /**
     * Проверка существования email в дазе данных
     * @return Integer <p>кол-во найденых записей в таблице</p>
     */
	public static function checkEmailExists($email)
	{
		$db = Database::getConnection();
		$table = Config::DB_tab_prefix."users";

		$result = $db->prepare("SELECT COUNT(*) as count
							  	FROM $table
							  	WHERE email = (?)")
		or die("Не удалось подготовить запросы: (" . $db->errno . ") " . $db->error);

		$result->bind_param('s', $email) or die("Не удалось привязать параметры: (" . $db->errno . ") " . $db->error);
		$result->execute() or die("Не удалось выполнить запрос: (" . $db->errno . ") " . $db->error);

		$result->bind_result($count);
		$result->fetch();
		$result->close();

		return $count;
	}

    /**
     * Проверка существования пользователя в БД
     * @return Integer <p>кол-во найденых записей в таблице</p>
     */
	public static function checkUserExists($email, $password)
	{
		$db = Database::getConnection();
		$password = md5($password);
		$table = Config::DB_tab_prefix."users";

		$result = $db->prepare("SELECT COUNT(*) as count
							  	FROM $table
							  	WHERE email = (?) AND password = (?)")
		or die("Не удалось подготовить запросы: (" . $db->errno . ") " . $db->error);

		$result->bind_param('ss', $email, $password) or die("Не удалось привязать параметры: (" . $db->errno . ") " . $db->error);
		$result->execute() or die("Не удалось выполнить запрос: (" . $db->errno . ") " . $db->error);

		$result->bind_result($count);
		$result->fetch();
		$result->close();

		return $count;
	}

    /**
     * Авторизация пользователя
     */
	public static function Auth($email, $password)
	{
		$db = Database::getConnection();
		$password = md5($password);
		$table = Config::DB_tab_prefix."users";

		$result = $db->prepare("SELECT id
							  	FROM $table
							  	WHERE email = (?) AND password = (?)")
		or die("Не удалось подготовить запросы: (" . $db->errno . ") " . $db->error);

		$result->bind_param('ss', $email, $password) or die("Не удалось привязать параметры: (" . $db->errno . ") " . $db->error);
		$result->execute() or die("Не удалось выполнить запрос: (" . $db->errno . ") " . $db->error);

		$result->bind_result($id);
		$result->fetch();
		$result->close();

		$_SESSION['user_id'] = $id;
	}

    /**
     * Проверка авторизации пользователя
     * @return Integer <p>Id пользователя</p>
     */
	public static function checklogged()
	{
		if (isset($_SESSION['user_id']))
			return $_SESSION['user_id'];

		header("Location: /login");
	}

    /**
     * Проверка статуса 'гость'
     * @return boolean <p>статус гостя</p>
     */
	public static function isGuest()
	{
		if (isset($_SESSION['user_id']))
			header("Location: /");

		return false;
	}

    /**
     * Получение пользователя по id 
     * @return Array <p>массив с данными о пользователе</p>
     */
	public static function getUserById($id)
	{
		$id = intval($id);
		$user = array();
		$table = Config::DB_tab_prefix."users";

		$db = Database::getConnection();
		$result = $db->prepare("SELECT id, name, email FROM $table WHERE id = (?)")
		or die("Не удалось подготовить запросы: (" . $db->errno . ") " . $db->error);

		$result->bind_param('i', $id) or die("Не удалось привязать параметры: (" . $db->errno . ") " . $db->error);
		$result->execute() or die("Не удалось выполнить запрос: (" . $db->errno . ") " . $db->error);

		$result->bind_result($id, $name, $email);

		while ($result->fetch())
		{
			$user['id'] = $id;
			$user['name'] = $name;
			$user['email'] = $email;
		}

		$result->close();

		return $user;
	}
}