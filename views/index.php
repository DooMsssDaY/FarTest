<?php include ROOT.'/views/layouts/header.php';?>

<h1>Личный кабинет</h1>
<div id='cabinet'>
	Здравствуйте, <?php echo $user['name'];?>
	(<a href="/logOut" title="">Выход</a>)
	<br/>
	<br/>
	<span id='load_text'>Выберите фотографии для загрузки:</span>
	<br/>
	<span id="text">(Доступные форматы <?php echo Config::AVAILABLE_EXT;?>)</span>
	<div id="loading"><img src="/template/images/loading.gif" alt="Загрузка ...."/></div>
		<form id="imageform" name='imgForm' onChange="loadImages(this);" method="post" enctype="multipart/form-data">
		    <input type="file" name="photos[]" id="photoimg" multiple="true" />
		</form>

	<br/>
	<div id='errors_div'>
		
	</div>
	<hr/>

	<?php if (empty($imgList)):?>
		<span id='previewText'>Вы пока не загрузили ни одной фотографии</span>
		<div id='photos_div'></div>
		<?php else:?>

		<span id='previewText'>Все Ваши фотографии</span>
		<div id='photos_div'>
			<?php foreach ($imgList as $img):?>
				<a href='<?php echo "$path/{$img['image']}"?>' target='_blank'>
					<img height='100' src='<?php echo "/$path/{$img['image']}"?>' class='images'>
					<a href='/deleteImg/<?php echo "{$img['id']}"?>'>
						<img height='20' src='/template/images/close.png' class='close'>
					</a>
				</a>
			<?php endforeach;?>
		<?php endif; ?>
		</div>
</div>
<?php include ROOT.'/views/layouts/footer.php';?>