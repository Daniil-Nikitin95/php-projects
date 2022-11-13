//отправка(запись) текста в текстовый файл NDOtext
<form action "php3.php" method = "post">
<input type = "text" name = "NDOtext"" />
<input type = "submit" value = "send" />
</form>


<?php
$f = fopen("/home/any/NDO/NDOtext.txt", "a+");//открытие текстового файла NDOtext
fwrite($f, $_POST["NDOtext"]);//запись текста из файла NDOtext в переменную $f
fclose($f);//закрыть текстовый файл
$f = fopen("/home/any/NDO/NDOtext.txt", "r");//открытие текстового файла NDOtext
echo fgets($f);//вывод содержимого переменной $f на экран
fclose($f);//закрыть текстовый файл
?>