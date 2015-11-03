<?php
/**
 * Контроллер UserController
 * управление пользователями
 */
class UserController
{
    /**
     * статус подтверждения учётной записи
     */
	private $isConfirmed = false;

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

		// если форма отправлена
		if(isset($_POST['submit']))
		{
			$name = $_POST['name'];
			$email = $_POST['email'];
			$password = $_POST['password'];
			$siteName = Config::SITE_NAME;
			
			// проверка отправленной формы
			$errors = CheckValid::checkRegForm($name, $email, $password);

			if (empty($errors))
			{	
				// создание хеш-кода из имени и пароля пользователя для подтверждения регистрации
				$hash = md5($name.$password);
				// запись нового пользователя в БД
				$id_user = User::register($name, $email, $password, $hash);

				$message = "Уважаемый $name,\nВы проходите регистрацию на сайте $siteName.\nПройдите по ссылке что бы подтвердить регистрацию http://$siteName/confirm/$id_user/$hash";
				$successSend = mail($email, 'Подтверждение регистрации', $message);

				$_SESSION['self_reg'] = true;

				header("Location: /regMess");
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
				// авторизация пользователя
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
     * Action сообщения подтверждения регистрации
     */
	public function actionRegMess()
	{
		$title = 'Подтверждение регистрации';

		if($this->isConfirmed != false || !isset($_SESSION['self_reg']))
			header("Location: /");

		require_once(ROOT.'/views/user/regSuccess.php');
		return true;
	}

    /**
     * Action подтверждения регистрации
     */
	public function actionConfirm($user_id, $hashReg)
	{
		if($this->isConfirmed != false || !isset($_SESSION['self_reg']))
			header("Location: /");

		$this->isConfirmed = User::confirmReg($user_id, $hashReg);
		$title = 'Регистрация подтверждена!';

		require_once(ROOT.'/views/user/regSuccess.php');
		return true;
	}
}