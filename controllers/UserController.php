<?php
/**
 * Контроллер UserController
 * управление пользователями
 */
class UserController
{
    /**
     * Проверка на наличие прав доступа при попытке авторизации и регистрации
     */
	public function __construct()
	{
		User::isGuest();
	}

    /**
     * Action для страницы "регистрация пользователя"
     */
	public function actionRegister()
	{
		$title = 'Регистрация пользователя';
		$name = '';
		$email = '';
		$password = '';
		$regSuccess = false;

		// если форма отправлена
		if(isset($_POST['submit']))
		{
			$name = $_POST['name'];
			$email = $_POST['email'];
			$password = $_POST['password'];
			
			// проверка отправленной формы
			$errors = CheckValid::checkRegForm($name, $email, $password);

			if (empty($errors))
			{	
				// запись нового пользователя в БД
				$regSuccess = User::register($name, $email, $password);
				header("Location: /regSuccess");
			}
		}

		require_once(ROOT.'/views/user/register.php');
		return true;
	}

    /**
     * Action для страницы "авторизация пользователя"
     */
	public function actionLogin()
	{	
		$title = 'Авторизация пользователя';
		$email = '';
		$password = '';

		if(isset($_POST['submit']))
		{
			$email = $_POST['email'];
			$password = $_POST['password'];

			$errors = CheckValid::checkLoginForm($email, $password);

			if (empty($errors))
			{
				User::Auth($email, $password);
				header("Location: /");
			}
		}

		require_once(ROOT.'/views/user/login.php');
		return true;
	}

    /**
     * Action logOut
     */
	public function actionLogout()
	{
		unset($_SESSION['user_id']);
		header("Location: /");
	}

    /**
     * Action успешной регистрации
     */
	public function actionRegSuccess()
	{
		$title = 'успех';

		require_once(ROOT.'/views/user/regSuccess.php');
		return true;
	}
}