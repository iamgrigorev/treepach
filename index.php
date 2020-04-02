<?php
session_start();

?><!DOCTYPE html>
<html>
	<head>
		<title>трепач</title>
		<meta charset="utf-8">
		<meta name="keywords" content="чат, обсудить, общение">
		<meta name="description" content="чат для анонимного разговора по душам">
		<link rel="stylesheet" href="style.css">
	</head>
	<?
		include "setting.php";
		include "functions.php";

		if ($_POST['send']) {
			$logFile=fopen($chatFile,'a');
			fwrite ($logFile, session_id()."✕".date("Y.m.d H:i:s")."✕ ".strip_tags($_POST[intext])."\n");
			fclose($logFile);
		}
		if ($_POST['colsend']){
		  $proFile=file($userFile);
		  $ssid=session_id();

		  if (is_array($proFile)){
				$lastK=count($proFile)-1;
				// echo $lastK.'номер последней строки'."<br>";
		    foreach ($proFile as $key => $value) {
					// echo $ssid."<br>";
					// echo $value."<br>";
					// echo $key."строка<br>";

		      if (strpos($value,$ssid)===false){
						// echo "строка".$key."не подошла <br>";
						if ($key==$lastK) {
							$proFile[]=$ssid."✕".$_POST[color];
							// echo "не встретил и добавил"."<br>";
						}
					}
					else {
							$proFile[$key]=substr_replace($value,"✕".$_POST[color],strlen($ssid));
							// echo "совпало и заменил"."<br>";
							break;
					}
		    }
		  }
		 	else {
				$proFile[]=$ssid."✕".$_POST[color];
				// echo "массив был пуст добавим первую строку"."<br>";
			}
			$wrFile=fopen($userFile,"w+");
			// echo "выгрузили массив в файл"."<br>";
			foreach ($proFile as $value) {
				fwrite ($wrFile, str_replace("\n","",$value)."\n");
			}
			fclose($wrFile);
		}
	?>
	<body>

		<div id='listing'><?php log_to_screen($chatFile); ?></div>

		<p>ответить:</p>
		<form action="index.php" method="post">
			<input type="text" name="intext" value="" autofocus required> <br><br>
			<input type="submit" name="send" value="отправить">
			<input type="reset" value="очистить">
		</form>
		<form action="index.php" method="post">
			<p>цвет ваших сообщений:
				<select name="color" size="1" required>
					<option value="black">черный</option>
					<option value="red">красный</option>
					<option value="green">зеленый</option>
					<option value="blue">синий</option>
				</select>
			<input type="submit"  name="colsend" value="применить"></p>
		</form>
		<script>

			setInterval(function() {

					// Создаем экземпляр класса XMLHttpRequest
					const request = new XMLHttpRequest();
					// Указываем путь до файла на сервере, который будет обрабатывать наш запрос
					const url = "list.php";
					// Так же как и в GET составляем строку с данными, но уже без пути к файлу
					const params = "test="+Date.now();   // ПЕРЕДАЕМ МИЛЛИСЕКУНДЫ В КАЧЕСТВЕ ПАРАМЕТРА - ДЛЯ ПРИМЕРА
					/* Указываем что соединение у нас будет POST, говорим что путь к файлу в переменной url, и что запрос у нас
					асинхронный, по умолчанию так и есть не стоит его указывать, еще есть 4-й параметр пароль авторизации, но этот
						параметр тоже необязателен.*/
					request.open("POST", url, true);
					request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
					request.addEventListener("readystatechange", () => {
						if(request.readyState === 4 && request.status === 200) {
							document.getElementById("listing").innerHTML = request.responseText;
						}
					});

					//  Вот здесь мы и передаем строку с данными, которую формировали выше. И собственно выполняем запрос.
					request.send(params);

			}, 2000);



		</script>
	</body>
</html>
