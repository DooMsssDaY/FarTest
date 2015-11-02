<?php include ROOT.'/views/layouts/header.php';?>

<h1>Регистрация</h1>
    <div id='forms'>

		<?php if (!empty($errors)):?>
			<div class="errors">
				<?php foreach($errors as $error):?>
					<span>- <?php echo $error; ?></span><br/>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<form method="post" onSubmit="return checkForm(this);">
			<input type="text" name='name' required="required" placeholder="Имя" value='<?php echo $name;?>'/>
			<input type="email" name='email' required="required" placeholder="E-mail" value='<?php echo $email;?>'/>
			<input type="password" name='password' required="required" placeholder="Пароль"/>
			<button type="submit" name='submit'>Регистрация</button>
		</form>
</div>

<?php include ROOT.'/views/layouts/footer.php';?>