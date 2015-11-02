<?php
/**
 * Класс Image - модель для работы c изображениями
 */
class Image
{
    /**
     * Запись загружаемого имени файла в БД
     * @return integer <p>id записанного в БД изображения</p>
     */
	public static function uploadImg($user_id, $image_name)
	{
        // получение подключения
		$db = Database::getConnection();
        // получение полного имени таблицы с учётом префикса
		$table = Config::DB_tab_prefix."imgs";

        // подготовка запроса
		$result = $db->prepare("INSERT INTO $table(user_id, image) VALUES(?,?)") 
		or die("Не удалось подготовить запросы: (" . $db->errno . ") " . $db->error);

        // пресвоение параметров и выполнение
		$result->bind_param('ss', $user_id, $image_name) or die("Не удалось привязать параметры: (" . $db->errno . ") " . $db->error);
		$result->execute() or die("Не удалось выполнить запрос: (" . $db->errno . ") " . $db->error);

        // получение id внесённой записи и закрытие соединения
        $insert_id = $result->insert_id;
        $result->close();

        return $insert_id; 
	}

    /**
     * Получение расширения загружаемого файла
     * @return string <p>расширение загружаемого файла</p>
     */
	public static function getExtension($str)
	{
    	$i = strrpos($str,".");

        if(!$i)
        	return "";

        $l = strlen($str) - $i;
        $ext = substr($str,$i+1,$l);

        return $ext;
    }

    /**
     * Поиск файла в указанной директории
     * @return boolean <p>Найдено ли совпадение</p>
     */
    public static function isExistsFile($uploaddir, $filename)
    {
        $dir = opendir($uploaddir);
        
        while (($f = readdir($dir)) !== false)
            if ($f == $filename)
                return true;

        closedir($dir);
        return false;
    }

    /**
     * Получение всех изображений по id пользователя
     * @return Array <p>массив с данными о изображениях</p>
     */
    public static function getImgsByUserId($user_id)
    {
        $db = Database::getConnection();
        $table = Config::DB_tab_prefix."imgs";
        $imgsList = array();

        $result = $db->prepare("SELECT id, image FROM $table WHERE user_id = (?) ORDER BY id DESC")
        or die("Не удалось подготовить запросы: (" . $db->errno . ") " . $db->error);

        $result->bind_param('i', $user_id) or die("Не удалось привязать параметры: (" . $db->errno . ") " . $db->error);
        $result->execute() or die("Не удалось выполнить запрос: (" . $db->errno . ") " . $db->error);

        $result->bind_result($id, $image);

        $i = 0;
        while ($result->fetch())
        {
            $imgsList[$i]['id'] = $id;
            $imgsList[$i]['image'] = $image;

            $i++;
        }

        $result->close();

        return $imgsList;
    }

    /**
     * Получение фотографии по id 
     * @return Array <p>массив с данными о фотографии</p>
     */
    public static function getImgById($id)
    {
        $id = intval($id);
        $table = Config::DB_tab_prefix."imgs";
        $img = array();

        $db = Database::getConnection();
        $result = $db->prepare("SELECT * FROM $table WHERE id = (?)")
        or die("Не удалось подготовить запросы: (" . $db->errno . ") " . $db->error);

        $result->bind_param('i', $id) or die("Не удалось привязать параметры: (" . $db->errno . ") " . $db->error);
        $result->execute() or die("Не удалось выполнить запрос: (" . $db->errno . ") " . $db->error);

        $result->bind_result($id, $user_id, $image);

        while ($result->fetch())
        {
            $img['id'] = $id;
            $img['user_id'] = $user_id;
            $img['image'] = $image;
        }

        $result->close();

        return $img;
    }

    /**
     * Удаление фотографии по id
     */
    public static function deleteImgById($id)
    {
        $img = self::getImgById($id);
        $path_to_file = Config::UPLOAD_PATH.$_SESSION['user_id']."/".$img['image'];
        unlink($path_to_file);

        $db = Database::getConnection();
        $table = Config::DB_tab_prefix."imgs";

        $result = $db->prepare("DELETE FROM $table WHERE id = (?)") 
        or die("Не удалось подготовить запросы: (" . $db->errno . ") " . $db->error);

        $result->bind_param('i', $id) or die("Не удалось привязать параметры: (" . $db->errno . ") " . $db->error);
        $result->execute() or die("Не удалось выполнить запрос: (" . $db->errno . ") " . $db->error);

        $affected_rows = $result->affected_rows;
        $result->close();

    }
}