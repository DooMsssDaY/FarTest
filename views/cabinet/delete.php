<?php include ROOT.'/views/layouts/header.php';?>

<h1>Удаление файла <?php echo $img['image']?></h1>
    <div id='forms'>
    	<img height='150' src="<?php echo "/$path/{$img['image']}"?>" alt="">
		<form method="post" onSubmit="return checkForm(this);">
			<button type="submit" name='submit'>Удалить</button>
			или <a href="/" title="">отменить удаление</a>
		</form>
	</div>

<?php include ROOT.'/views/layouts/footer.php';?>