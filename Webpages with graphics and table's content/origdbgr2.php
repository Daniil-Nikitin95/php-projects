<?php


/* подключение к базе данных. 
"ubuntuserv" - адрес сервера, где лежит БД(в нашем случае всегда localhost), 
"daniil2" - твое имя пользователя для подключение к бд, 
"d170895" - пароль, 
"DATANDO" - название БД, к которой подключаешься.*/
$link = mysqli_connect("ubuntuserv", "daniil2", "d170895", "DATANDO"); /* подключение к базе данных. "localhost" - адрес сервера, где лежит БД(в нашем случае всегда localhost), "sql_user" - твое имя пользователя для подключение к бд, "password" - пароль, "DATANIN" - название БД, к которой подключаешься.*/


//запрос в базу данных для получения данных с датчиков из таблицы SDATA
$result = mysqli_query($link,
           'SELECT DatTime,
           CASE WHEN V7.SensorValue IS NULL THEN "-" ELSE V7.SensorValue END AS "sct",
           CASE WHEN V8.SensorValue IS NULL THEN "-" ELSE V8.SensorValue END AS "uct"
           FROM SDATA AS D
           LEFT JOIN (SELECT id, SensorValue FROM SDATA WHERE SMC_P_CROSS_FK=7) AS V7
           ON D.id=V7.id
           LEFT JOIN (SELECT id, SensorValue FROM SDATA WHERE SMC_P_CROSS_FK=8) AS V8
           ON D.id=V8.id
           WHERE D.id>600000;');


//Закрывает ранее открытое соединение с базой данных
mysqli_close($link);


$times = array();
$values7 = array();
$values8 = array();
$i = 0;
$s = 0;
$u = 0;


//вывод 1000 записей из таблицы
while($row = mysqli_fetch_array($result) and $i<1000)
{
  $times[$i] = strtotime($row['DatTime']);
  $sct = trim($row['sct']);
  if($sct != "-")
  {
    $values7[$s] = (float)$sct;
  }
  else
  {
    $values7[$s] = $sct;
  }
  ++$s;
  $uct = trim($row['uct']);
  if($uct != "-")
  {
    $values8[$u] = (float)$uct;
  }
  else
  {
    $values8[$u] = $uct;
  }
  ++$u;
  ++$i;
}


//подключение графических библиотек
require_once ('/var/www/ubuntuserv.com/jpgraph-4.3.2/src/jpgraph.php');
require_once ('/var/www/ubuntuserv.com/jpgraph-4.3.2/src/jpgraph_log.php');
require_once ('/var/www/ubuntuserv.com/jpgraph-4.3.2/src/jpgraph_line.php');
require_once ('/var/www/ubuntuserv.com/jpgraph-4.3.2/src/jpgraph_error.php');
require_once ('/var/www/ubuntuserv.com/jpgraph-4.3.2/src/jpgraph_date.php');


// Создание графика
$graph = new Graph(1300, 700);
$graph->clearTheme();
$graph->SetScale("datint");
$graph->SetY2Scale("lin");
$graph->SetShadow();
$graph->img->SetMargin(60,100,20,145); //Р·РґРµСЃСЊ СѓСЃС‚Р°РЅР°РІР»РёРІР°СЋС‚СЃСЏ РїРѕР»СЏ РІСЃРµРіРѕ РёР·РѕР±СЂР°Р¶РµРЅРёСЏ (Р»РµРІРѕРµ РїРѕР»Рµ, РїСЂР°РІРѕРµ РїРѕР»Рµ, РІРµСЂС…РЅРµРµ РїРѕР»Рµ, РЅРёР¶РЅРµРµ РїРѕР»Рµ)
$graph->xaxis->scale->SetDateFormat('d.m.Y H:i:s');


// Создание линейного графика
$lineplot7=new LinePlot($values7, $times);
$lineplot8=new LinePlot($values8, $times);


// Добавление графика к графику
$graph->Add($lineplot7);
$graph->AddY2($lineplot8);
$graph->yaxis->SetColor('black');
$graph->y2axis->SetColor('black');
$graph->title->Set("Data");
$graph->xaxis->title->Set("Time");
$graph->yaxis->title->Set("System CPU time");
$graph->y2axis->title->Set("User CPU time");
$graph->title->SetFont(FF_FONT1,FS_BOLD);


//выделить название оси жирным шрифтом
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);


//установить цвет каждого графика
$lineplot7->SetColor("firebrick4");
$lineplot8->SetColor("cyan3");


//толщина линий(графиков) = 2
$lineplot7->SetWeight(2);
$lineplot8->SetWeight(2);


//Установить текст легенды
$lineplot7->SetLegend("System cpu time");
$lineplot8->SetLegend("User cpu time");



$graph->legend->Pos(0.01,0.35,"right","center");//установка положения легенды
$graph->xaxis->SetLabelAngle(90);//указать угол поворота для меток на оси


// Показать график
$graph->Stroke();
?>
