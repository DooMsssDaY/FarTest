<?php
/**
 * Класс для осуществления валидации
 */
class CheckValid {

    /**
     * проверка данных с формы регистрации пользователя
     * @return array <p>список ошибок</p> 
     */
    public static function checkRegForm($name, $email, $password)
    {
        $errors = array();

        if (!self::validLogin($name))
            $errors[] = "Длинна поля Имя должна быть в диапазоне от ".Config::MIN_LANGTH_NAME." до ".Config::MAX_LANGTH_NAME.". Так же не допускаются кавычки.";

        if (!self::validEmail($email))
            $errors[] = "Не верный Email";

        if (!self::validPassword($password))
            $errors[] = "Пароль должен быть не короче ".Config::MIN_LANGTH_PASS." символов.";

        if (User::checkEmailExists($email))
            $errors[] = "Пользователь с таким Email уже существует.";

        return $errors;
    }

    /**
     * проверка данных с формы авторизации пользователя
     * @return array <p>список ошибок</p> 
     */
    public static function checkLoginForm($email, $password)
    {
        $errors = array();

        if (!self::validEmail($email))
            $errors[] = "Не верный Email";

        if (!self::validPassword($password))
            $errors[] = "Пароль должен быть не короче ".Config::MIN_LANGTH_PASS." символов.";

        if (empty($errors) && !User::checkUserExists($email, $password)) {
            $errors[] = "Пользователь с такой комбинацией email и пароля не существует.";
        }

        return $errors;
    }

    /**
     * проверка логина
     * @return boolean 
     */
    public static function validLogin($login)
    {
        if (self::isContainQuotes($login)) 
            return false;

        if (preg_match("/^\d*$/", $login)) 
            return false;

        return self::validString($login, Config::MIN_LANGTH_NAME, Config::MAX_LANGTH_NAME);
    }
    
    /**
     * проверка строки
     * @return boolean 
     */
    private function validString ($string, $min_length, $max_length)
    {
        if(!is_string($string))
            return false;

        if(strlen($string) <= $min_length)
            return false;

        if(strlen($string) > $max_length)
            return false;

        return true;
    }
    
    /**
     * проверка строки на отсутствие кавычек
     * @return boolean 
     */
    public static function isContainQuotes($string)
    {
        $array = array("\"", "'", "`", "&quot;", "&apos;");
        
        foreach($array as $key => $value){
            if (strpos($string, $value) !== false) return true;
        }

        return false;
    }

    /**
     * проверка email
     * @return boolean 
     */
    public static function validEmail($email)
    {
        if (self::isContainQuotes($email)) 
            return false;

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            return false;

        return true;
    }

    /**
     * проверка пароля
     * @return boolean 
     */
    public static function validPassword($password)
    {
        if (self::isContainQuotes($password)) 
            return false;

        if (strlen($password) < Config::MIN_LANGTH_PASS)
            return false;

        return true;
    }

    /**
     * проверка загружаемой фотографии
     * @return integer <p>Номер ошибки</p> 
     */
    public static function checkImg($uploaddir, $filename, $size)
    {   
        // допустимые для загрузки форматы файлов
        $valid_formats = explode(',', Config::AVAILABLE_EXT);
        // получение расширения файла
        $ext = Image::getExtension($filename);
        $ext = strtolower($ext);

        // проверка на существование файла с идентичным именем в папке пользователя
        if (Image::isExistsFile($uploaddir, $filename))
            return 1;

        // проверка на допустимость формата файла
        if(!in_array($ext,$valid_formats))
            return 2;

        //Проверка размера файла
        if($size > (Config::MAX_SIZE_IMG*1024)) 
            return 2;

        return 0;
    }
}