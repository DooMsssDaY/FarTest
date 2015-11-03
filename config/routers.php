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
				// сообщение подтверждения регистрации
				'regMess' => 'user/regMess',
				// подтверждение регистрации
				'confirm/([0-9]+)/([a-zA-Z0-9]+)' => 'user/confirm/$1/$2',

				// личный кабинет
				// загрузка изображения
				'loadImg' => 'cabinet/loadImg',
				// удаление изображения
				'deleteImg' => 'cabinet/deleteImg',
				// личный кабинет
				'' => 'cabinet/index', 
			);