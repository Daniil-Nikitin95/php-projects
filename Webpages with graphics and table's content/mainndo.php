<?
include("site_init.tpl"):// Включить инициализационный файл
show_header($site_name);// Вывести заголовок
include("$content.html"):// Вывести запрашиваемое содержание 
show footer();// Вывести колонтитул
?>


//ссылки на страницу, отображающую таблицу, название которой указано в ссылке после первого знака >
<a href = "secpage.php?name=mcu">MCU</a></br>
<a href = "secpage.php?name=place">Place</a></br>
<a href = "secpage.php?name=sdata">SDATA</a></br>
<a href = "secpage.php?name=sensor">Sensor</a></br>
<a href = "secpage.php?name=smc_p_cross">SMC_P_CROSS</a></br>
<a href = "secpage.php?name=s_m_cross">S_M_CROSS</a></br>

