<?php include ROOT.'/views/layouts/header.php';?>

<h1>Авторизация</h1>
    <div id='forms'>

		<?php if (!empty($errors)):?>
			<div class="errors">
				<?php foreach($errors as $error):?>
					<span>- <?php echo $error; ?></span><br/>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<form method="post" onSubmit="return checkForm(this);">
			<input type="email" name='email' required="required" placeholder="E-mail" value='<?php echo $email;?>'/>
			<input type="password" required="required" name='password' placeholder="Пароль"/>
			<button type="submit" name='submit'>Войти</button>
			или <a href="/register" title="">Зарегистрироваться</a>
		</form>
	</div>

<?php include ROOT.'/views/layouts/footer.php';?>
