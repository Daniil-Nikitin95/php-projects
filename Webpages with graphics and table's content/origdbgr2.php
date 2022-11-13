<?php


/* ����������� � ���� ������. 
"ubuntuserv" - ����� �������, ��� ����� ��(� ����� ������ ������ localhost), 
"daniil2" - ���� ��� ������������ ��� ����������� � ��, 
"d170895" - ������, 
"DATANDO" - �������� ��, � ������� �������������.*/
$link = mysqli_connect("ubuntuserv", "daniil2", "d170895", "DATANDO"); /* ����������� � ���� ������. "localhost" - ����� �������, ��� ����� ��(� ����� ������ ������ localhost), "sql_user" - ���� ��� ������������ ��� ����������� � ��, "password" - ������, "DATANIN" - �������� ��, � ������� �������������.*/


//������ � ���� ������ ��� ��������� ������ � �������� �� ������� SDATA
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


//��������� ����� �������� ���������� � ����� ������
mysqli_close($link);


$times = array();
$values7 = array();
$values8 = array();
$i = 0;
$s = 0;
$u = 0;


//����� 1000 ������� �� �������
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


//����������� ����������� ���������
require_once ('/var/www/ubuntuserv.com/jpgraph-4.3.2/src/jpgraph.php');
require_once ('/var/www/ubuntuserv.com/jpgraph-4.3.2/src/jpgraph_log.php');
require_once ('/var/www/ubuntuserv.com/jpgraph-4.3.2/src/jpgraph_line.php');
require_once ('/var/www/ubuntuserv.com/jpgraph-4.3.2/src/jpgraph_error.php');
require_once ('/var/www/ubuntuserv.com/jpgraph-4.3.2/src/jpgraph_date.php');


// �������� �������
$graph = new Graph(1300, 700);
$graph->clearTheme();
$graph->SetScale("datint");
$graph->SetY2Scale("lin");
$graph->SetShadow();
$graph->img->SetMargin(60,100,20,145); //здесь устанавливаются поля всего изображения (левое поле, правое поле, верхнее поле, нижнее поле)
$graph->xaxis->scale->SetDateFormat('d.m.Y H:i:s');


// �������� ��������� �������
$lineplot7=new LinePlot($values7, $times);
$lineplot8=new LinePlot($values8, $times);


// ���������� ������� � �������
$graph->Add($lineplot7);
$graph->AddY2($lineplot8);
$graph->yaxis->SetColor('black');
$graph->y2axis->SetColor('black');
$graph->title->Set("Data");
$graph->xaxis->title->Set("Time");
$graph->yaxis->title->Set("System CPU time");
$graph->y2axis->title->Set("User CPU time");
$graph->title->SetFont(FF_FONT1,FS_BOLD);


//�������� �������� ��� ������ �������
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);


//���������� ���� ������� �������
$lineplot7->SetColor("firebrick4");
$lineplot8->SetColor("cyan3");


//������� �����(��������) = 2
$lineplot7->SetWeight(2);
$lineplot8->SetWeight(2);


//���������� ����� �������
$lineplot7->SetLegend("System cpu time");
$lineplot8->SetLegend("User cpu time");



$graph->legend->Pos(0.01,0.35,"right","center");//��������� ��������� �������
$graph->xaxis->SetLabelAngle(90);//������� ���� �������� ��� ����� �� ���


// �������� ������
$graph->Stroke();
?>
