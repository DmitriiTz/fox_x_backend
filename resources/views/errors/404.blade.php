<!DOCTYPE html>
<html lang="ru">

<head>

	<meta charset="utf-8">

	<title>Fox - King</title>
	<meta name="description" content="">

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<meta property="og:image" content="path/to/image.jpg">
	<link rel="shortcut icon" href="img/favicon/favicon.ico" type="image/x-icon">
	<link rel="apple-touch-icon" href="img/favicon/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="72x72" href="img/favicon/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="img/favicon/apple-touch-icon-114x114.png">
	<link rel="stylesheet" href="{{ asset('css/main.min.css') }}">

</head>

<body>
	
	<div class="error">
		<p class="error__number">404</p>
		<h3 class="error__title">Страница не найдена</h3>
		<p class="error__text">К сожалению, веб-страница, 
				соответствующая Вашему запросу, была 
				удалена или не существует.</p>
		<a href="{{ route('home') }}" class="button error__link">НА ГЛАВНУЮ</a>
	</div>

	<div id="outputModal"></div>
	<div id="inputModal"></div>
	<div id="helpModal"></div>
	<div id="chatRules"></div>
	<div id="outputRules"></div>
	<div id="referralInfo"></div>
	<div id="referralTable"></div>
	
	<div class="modal modal--bet" id="betModal">
	</div> 

	<div class="modal modal--bet" id="betModalCoin">
	</div>

	<div class="modal modal--bet" id="betModalCoin2">
	</div>

	<div class="modal modal--bet" id="createGame">
	</div> 

	<div class="modal modal--bet" id="waitPlayer">
	</div> 
	
	<div class="modal modal--bet" id="waitPlayer2">
	</div> 

	<div class="modal modal--bet modal--done" id="waitPlayer3">
	</div> 

	<div class="modal modal--bet modal--done" id="waitPlayer4">
	</div>
	
	<div class="modal modal--bet modal--done" id="waitPlayer5">
	</div> 

	<div class="modal modal--bet modal--wait" id="waitPlayer6">
	</div> 

	<div class="modal modal--bet modal--done-two" id="waitPlayer7">
	</div> 

	<div class="modal modal--bet modal--done-two" id="waitPlayer8">
	</div> 

	<!-- Include css and js -->

</body>
</html>
