<?php


/* подключение к базе данных. 
"ubuntuserv" - адрес сервера, где лежит БД(в нашем случае всегда localhost), 
"daniil2" - твое имя пользователя для подключение к бд, 
"d170895" - пароль, 
"DATANDO" - название БД, к которой подключаешься.*/
$link = mysqli_connect("ubuntuserv", "daniil2", "d170895", "DATANDO");


//запрос в базу данных для получения данных с датчиков из таблицы SDATA
$result = mysqli_query($link,
           'SELECT DatTime,
           CASE WHEN V1.SensorValue IS NULL THEN "-" ELSE V1.SensorValue END AS "dht11_1",
           CASE WHEN V2.SensorValue IS NULL THEN "-" ELSE V2.SensorValue END AS "lv25p_1",
	   CASE WHEN V3.SensorValue IS NULL THEN "-" ELSE V3.SensorValue END AS "dht11_2",
           CASE WHEN V4.SensorValue IS NULL THEN "-" ELSE V4.SensorValue END AS "lv25p_2",
           CASE WHEN V5.SensorValue IS NULL THEN "-" ELSE V5.SensorValue END AS "dht11_3",
           CASE WHEN V6.SensorValue IS NULL THEN "-" ELSE V6.SensorValue END AS "lv25p_3"
           FROM SDATA AS D
           LEFT JOIN (SELECT id, SensorValue FROM SDATA WHERE SMC_P_CROSS_FK=1) AS V1
           ON D.id=V1.id
           LEFT JOIN (SELECT id, SensorValue FROM SDATA WHERE SMC_P_CROSS_FK=2) AS V2
           ON D.id=V2.id
           LEFT JOIN (SELECT id, SensorValue FROM SDATA WHERE SMC_P_CROSS_FK=3) AS V3
           ON D.id=V3.id
           LEFT JOIN (SELECT id, SensorValue FROM SDATA WHERE SMC_P_CROSS_FK=4) AS V4
           ON D.id=V4.id
           LEFT JOIN (SELECT id, SensorValue FROM SDATA WHERE SMC_P_CROSS_FK=5) AS V5
           ON D.id=V5.id
           LEFT JOIN (SELECT id, SensorValue FROM SDATA WHERE SMC_P_CROSS_FK=8) AS V6
           ON D.id=V6.id
           WHERE D.id>=1;');


//Закрывает ранее открытое соединение с базой данных
mysqli_close($link);


$times = array();
$values1 = array();
$values2 = array();
$values3 = array();
$values4 = array();
$values5 = array();
$values6 = array();
$i = 0;
$d1 = 0;
$l1 = 0;
$d2 = 0;
$l2 = 0;
$d3 = 0;
$l3 = 0;


//вывод 1000 записей из таблицы
while($row = mysqli_fetch_array($result) and $i<1000)
{
  $times[$i] = strtotime($row['DatTime']);
  $dht11_1 = trim($row['dht11_1']);
  if($dht11_1 != "-")
  {
    $values1[$d1] = (float)$dht11_1;
  }
  else
  {
    $values1[$d1] = $dht11_1;
  }
  ++$d1;
  $lv25p_1 = trim($row['lv25p_1']);
  if($lv25p_1 != "-")
  {
    $values2[$l1] = (float)$lv25p_1;
  }
  else
  {
    $values2[$l1] = $lv25p_1;
  }
  ++$l1;
  $dht11_2 = trim($row['dht11_2']);
  if($dht11_2 != "-")
  {
    $values3[$d2] = (float)$dht11_2;
  }
  else
  {
    $values3[$d2] = $dht11_2;
  }
  ++$d2;
  $lv25p_2 = trim($row['lv25p_2']);
  if($lv25p_2 != "-")
  {
    $values4[$l2] = (float)$lv25p_2;
  }
  else
  {
    $values4[$l2] = $lv25p_2;
  }
  ++$l2;
  $dht11_3 = trim($row['dht11_2']);
  if($dht11_3 != "-")
  {
    $values5[$d3] = (float)$dht11_3;
  }
  else
  {
    $values5[$d3] = $dht11_3;
  }
  ++$d3;
  $lv25p_3 = trim($row['lv25p_3']);
  if($lv25p_3 != "-")
  {
    $values6[$l3] = (float)$lv25p_3;
  }
  else
  {
    $values6[$l3] = $lv25p_3;
  }
  ++$l3;
  ++$i;
}


//подключение графических библиотек
require_once ('/var/www/ubuntuserv.com/jpgraph-4.3.2/src/jpgraph.php');
require_once ('/var/www/ubuntuserv.com/jpgraph-4.3.2/src/jpgraph_log.php');
require_once ('/var/www/ubuntuserv.com/jpgraph-4.3.2/src/jpgraph_line.php');
require_once ('/var/www/ubuntuserv.com/jpgraph-4.3.2/src/jpgraph_error.php');
require_once ('/var/www/ubuntuserv.com/jpgraph-4.3.2/src/jpgraph_date.php');


// Создание графика
$graph = new Graph(1300, 700, "auto");
$graph->clearTheme();
$graph->SetScale("datint");
$graph->SetY2Scale("lin");
$graph->SetShadow();
$graph->img->SetMargin(60,100,20,145); //Р·РґРµСЃСЊ СѓСЃС‚Р°РЅР°РІР»РёРІР°СЋС‚СЃСЏ РїРѕР»СЏ РІСЃРµРіРѕ РёР·РѕР±СЂР°Р¶РµРЅРёСЏ (Р»РµРІРѕРµ РїРѕР»Рµ, РїСЂР°РІРѕРµ РїРѕР»Рµ, РІРµСЂС…РЅРµРµ РїРѕР»Рµ, РЅРёР¶РЅРµРµ РїРѕР»Рµ), РЅРёР¶РЅРµРµ РїРѕР»Рµ РѕС‚РІРѕРґРёС‚СЃСЏ РїРѕРґ РїРѕРґРїРёСЃРё РѕСЃРё x
$graph->xaxis->scale->SetDateFormat('d.m.Y H:i:s');


// Создание линейного графика
$lineplot1=new LinePlot($values1, $times);
$lineplot2=new LinePlot($values2, $times);
$lineplot3=new LinePlot($values3, $times);
$lineplot4=new LinePlot($values4, $times);
$lineplot5=new LinePlot($values5, $times);
$lineplot6=new LinePlot($values6, $times);


// Добавление графика к графику
$graph->Add($lineplot1); //добавить график по датчику 1
$graph->AddY2($lineplot2); //добавить график по датчику 2
$graph->Add($lineplot3);//добавить график по датчику 3
$graph->AddY2($lineplot4);//добавить график по датчику 4
$graph->Add($lineplot5);//добавить график по датчику 5
$graph->AddY2($lineplot6);//добавить график по датчику 6
$graph->yaxis->SetColor('black');//сделать ось X черного цвета
$graph->y2axis->SetColor('black');//сделать ось Y черного цвета
$graph->title->Set("Data");//сделать заголовок с названием "Data"
$graph->xaxis->title->Set("Time");//назвать ось X "Time"
$graph->yaxis->title->Set("DHT11");//назвать ось Y1 "DHT11"
$graph->y2axis->title->Set("LV-25P");//назвать ось Y2 "LV-25P"
$graph->title->SetFont(FF_FONT1,FS_BOLD);//выделить заголовок жирным шрифтом


//выделить название оси жирным шрифтом
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);


//установить цвет каждого графика
$lineplot1->SetColor("blue");
$lineplot2->SetColor("red");
$lineplot3->SetColor("green");
$lineplot4->SetColor("orange");
$lineplot5->SetColor("brown");
$lineplot6->SetColor("violet");


//толщина линий(графиков) = 2
$lineplot1->SetWeight(2);
$lineplot2->SetWeight(2);
$lineplot3->SetWeight(2);
$lineplot4->SetWeight(2);
$lineplot5->SetWeight(2);
$lineplot6->SetWeight(2);


//Установить текст легенды
$lineplot1->SetLegend("DHT11_1");
$lineplot2->SetLegend("LV-25P_1");
$lineplot3->SetLegend("DHT11_2");
$lineplot4->SetLegend("LV-25P_2");
$lineplot5->SetLegend("DHT11_3");
$lineplot6->SetLegend("LV-25P_3");


$graph->legend->Pos(0.01,0.35,"right","center");//установка положения легенды
$graph->xaxis->SetLabelAngle(90); //указать угол поворота для меток на оси


// Показать график
$graph->Stroke();
?>
