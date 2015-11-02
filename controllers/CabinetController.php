<?php
/**
 * Контроллер CabinetController
 * Личный кабинет пользователя
 */
class CabinetController
{
    /**
     * Action для страницы "Личный кабинет"
     */  
	public function actionIndex()
	{
		$title = 'Личный кабинет';

		User::checklogged();
		// получение данных о пользователе
		$user = User::getUserById($_SESSION['user_id']);

		// получение всех фотографий пользователя
		$imgList = Image::getImgsByUserId($_SESSION['user_id']);
		// путь к папке с фотографиями на сервере
		$path = Config::UPLOAD_PATH.$_SESSION['user_id'];

		include_once ROOT.'/views/index.php';
		return true;
	}

    /**
     * Action для загрузки файлов
     */  
	public function actionLoadImg()
	{
	    if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
	    {
	    	// id пользователя
	        $user_id = $_SESSION['user_id'];
    		// получение пути до папки пользователя на сервере
	        $uploaddir = Config::UPLOAD_PATH.$user_id;
	        $response = array();
	        $n_error = 0;

	        if (!empty($_FILES['photos']))
	        {
		        foreach ($_FILES['photos']['name'] as $name => $value) 
		        {
		            $filename = stripslashes($_FILES['photos']['name'][$name]);
		            $size=filesize($_FILES['photos']['tmp_name'][$name]);

		            // внесение информации о файле в выходной массив
		            $response['image']['name'] = $filename;
		            $response['image']['path'] = "$uploaddir/$filename";

		            // проверка на существование папки пользователя для фотографий
		            if(!file_exists($uploaddir))
		            	mkdir($uploaddir, 0777);
		            
		            // проверка загружаемого файла
		            if (($n_error = CheckValid::checkImg($uploaddir, $filename, $size)) != 0)
		            	break;

	                $newname=$uploaddir.'/'.$filename; 
	                //Сохраняем файл
	                if(move_uploaded_file($_FILES['photos']['tmp_name'][$name], $newname)) 
	                { 
	                    //Добавляем инфомацию в базу
	                    $response['image']['id'] = Image::uploadImg($user_id, $filename);
	                } 
	                else
	                { 
	                    $n_error = 4;
	                } 
		        }
		    }
		    else
		    {
		    	$n_error = 5;
		    }
	    }

	    $response['error'] = $n_error;

	    echo json_encode($response);
		return true;
	}

    /**
     * Action для удаления файлов
     */  
	public function actionDeleteImg($id)
	{
		$title = 'Удалить фотографию';
		// получение изображения по id
		$img = Image::getImgById($id);
		$path = Config::UPLOAD_PATH.$_SESSION['user_id'];

        if (isset($_POST['submit']))
        {
        	// удаление изображения из БД и сервера
            Image::deleteImgById($id);
            header("Location: /");
        }

		include_once ROOT.'/views/cabinet/delete.php';
		return true;
	}
}