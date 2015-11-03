<?php include ROOT.'/views/layouts/header.php';?>

<h1>Регистрация</h1>
<div id='forms'>

	<?php if(is_null($user_id)):?>
		Для завершения регистрации пройдите по ссылке, которая была выслана на, указанный Вами, E-mail.
	<?php else: ?>
		Регистрация успешно подтверждена, теперь вы можете <a href='/login'>авторезироваться</a>
	<?php endif; ?>
</div>

<?php include ROOT.'/views/layouts/footer.php';?>