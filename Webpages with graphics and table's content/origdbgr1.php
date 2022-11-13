<?php


/* ����������� � ���� ������. 
"ubuntuserv" - ����� �������, ��� ����� ��(� ����� ������ ������ localhost), 
"daniil2" - ���� ��� ������������ ��� ����������� � ��, 
"d170895" - ������, 
"DATANDO" - �������� ��, � ������� �������������.*/
$link = mysqli_connect("ubuntuserv", "daniil2", "d170895", "DATANDO");


//������ � ���� ������ ��� ��������� ������ � �������� �� ������� SDATA
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


//��������� ����� �������� ���������� � ����� ������
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


//����� 1000 ������� �� �������
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


//����������� ����������� ���������
require_once ('/var/www/ubuntuserv.com/jpgraph-4.3.2/src/jpgraph.php');
require_once ('/var/www/ubuntuserv.com/jpgraph-4.3.2/src/jpgraph_log.php');
require_once ('/var/www/ubuntuserv.com/jpgraph-4.3.2/src/jpgraph_line.php');
require_once ('/var/www/ubuntuserv.com/jpgraph-4.3.2/src/jpgraph_error.php');
require_once ('/var/www/ubuntuserv.com/jpgraph-4.3.2/src/jpgraph_date.php');


// �������� �������
$graph = new Graph(1300, 700, "auto");
$graph->clearTheme();
$graph->SetScale("datint");
$graph->SetY2Scale("lin");
$graph->SetShadow();
$graph->img->SetMargin(60,100,20,145); //здесь устанавливаются поля всего изображения (левое поле, правое поле, верхнее поле, нижнее поле), нижнее поле отводится под подписи оси x
$graph->xaxis->scale->SetDateFormat('d.m.Y H:i:s');


// �������� ��������� �������
$lineplot1=new LinePlot($values1, $times);
$lineplot2=new LinePlot($values2, $times);
$lineplot3=new LinePlot($values3, $times);
$lineplot4=new LinePlot($values4, $times);
$lineplot5=new LinePlot($values5, $times);
$lineplot6=new LinePlot($values6, $times);


// ���������� ������� � �������
$graph->Add($lineplot1); //�������� ������ �� ������� 1
$graph->AddY2($lineplot2); //�������� ������ �� ������� 2
$graph->Add($lineplot3);//�������� ������ �� ������� 3
$graph->AddY2($lineplot4);//�������� ������ �� ������� 4
$graph->Add($lineplot5);//�������� ������ �� ������� 5
$graph->AddY2($lineplot6);//�������� ������ �� ������� 6
$graph->yaxis->SetColor('black');//������� ��� X ������� �����
$graph->y2axis->SetColor('black');//������� ��� Y ������� �����
$graph->title->Set("Data");//������� ��������� � ��������� "Data"
$graph->xaxis->title->Set("Time");//������� ��� X "Time"
$graph->yaxis->title->Set("DHT11");//������� ��� Y1 "DHT11"
$graph->y2axis->title->Set("LV-25P");//������� ��� Y2 "LV-25P"
$graph->title->SetFont(FF_FONT1,FS_BOLD);//�������� ��������� ������ �������


//�������� �������� ��� ������ �������
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);


//���������� ���� ������� �������
$lineplot1->SetColor("blue");
$lineplot2->SetColor("red");
$lineplot3->SetColor("green");
$lineplot4->SetColor("orange");
$lineplot5->SetColor("brown");
$lineplot6->SetColor("violet");


//������� �����(��������) = 2
$lineplot1->SetWeight(2);
$lineplot2->SetWeight(2);
$lineplot3->SetWeight(2);
$lineplot4->SetWeight(2);
$lineplot5->SetWeight(2);
$lineplot6->SetWeight(2);


//���������� ����� �������
$lineplot1->SetLegend("DHT11_1");
$lineplot2->SetLegend("LV-25P_1");
$lineplot3->SetLegend("DHT11_2");
$lineplot4->SetLegend("LV-25P_2");
$lineplot5->SetLegend("DHT11_3");
$lineplot6->SetLegend("LV-25P_3");


$graph->legend->Pos(0.01,0.35,"right","center");//��������� ��������� �������
$graph->xaxis->SetLabelAngle(90); //������� ���� �������� ��� ����� �� ���


// �������� ������
$graph->Stroke();
?>
