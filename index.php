<?php
require_once 'app/bootstrap.php';

$colors = json_decode(file_get_contents(YANDEX_COLORS_PATH), true);
$yandexColors = \Kiabi\Replacer::$colors;
if ('POST' == $_SERVER['REQUEST_METHOD']) {
	$colorsNew = [];
	foreach ($colors as $key => $color) {
		$colorsNew[$key] = $color;
		$value = isset($_POST[md5($color['source'])]) ? $_POST[md5($color['source'])] : null;
		if ($value){
			$colorsNew[$key]['yandex'] = $value;
		}
	}

	@file_put_contents(YANDEX_COLORS_PATH, json_encode($colorsNew));

	header('location:'.$_SERVER['HTTP_REFERER']);
}
?>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>KIABI Yandex Colors</title>

	<link href="bundles/bootstrap3/css/bootstrap.min.css" rel="stylesheet">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
<div class="container">
	<h2>Подстановка цвета</h2>
	<form method="post">

	<table class="table table-bordered table-responsive">
		<tr>
			<th>Оригинальный цвет</th>
			<th>Вариант Яндекса</th>
		</tr>
		<?foreach ($colors as $key => $color):?>
		<tr>
			<td><?=$color['source']?></td>
			<td><select class="form-control" name="<?=$key?>">
					<option value="">Не определено</option>
					<?foreach($yandexColors as $yandexColor):?>
					<option value="<?=$yandexColor?>"<?if( $color['yandex'] == $yandexColor):?> selected="selected"<?endif;?>><?=$yandexColor?></option>
					<?endforeach;?>
				</select>
			</td>
		</tr>
		<?endforeach;?>
	</table>
	<button type="submit" class="btn btn-success btn-lg">Сохранить</button>
	</form>
</div>
<script src="bundles/jquery/jquery.min.js"></script>
<script src="bundles/bootstrap3/js/bootstrap.min.js"></script>
</body>
</html>