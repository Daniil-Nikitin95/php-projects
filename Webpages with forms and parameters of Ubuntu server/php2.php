<?php
date_default_timezone_set('Asia/Vladivostok');//установить часовой пояс по умолчанию
echo "Текущее время - ".date("H:i:s")//вывести на странице текущее время
?>


//отправка(запись) текста в текстовый файл NDOtext
<form action = "php3.php" method = "post">
<input type = "text" name = "NDOtext" />
<input type = "submit" value = "send" />


</form>