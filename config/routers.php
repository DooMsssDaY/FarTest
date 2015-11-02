<?php
// роуты
return array(	
				// пользователь
				// регистрация пользователя
				'register' => 'user/register', // actionКegister in UserController	
				// авторизация пользователя
				'login' => 'user/login',
				// logOut пользователя
				'logOut' => 'user/logout',
				// Вывод сообщения об успегной регистрации
				'regSuccess' => 'user/regSuccess',

				// личный кабинет
				// загрузка изображения
				'loadImg' => 'cabinet/loadImg',
				// удаление изображения
				'deleteImg' => 'cabinet/deleteImg',
				// личный кабинет
				'' => 'cabinet/index', 
			);