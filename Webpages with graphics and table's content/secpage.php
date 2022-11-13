<!-- ссылка на главную страницу mainndo -->
<a href = "mainndo.php">Вернуться на главную страницу</a></br></br>

<?php


//переменные для подключения к БД
$host = 'ubuntuserv';
$data = 'DATANDO';
$user = 'daniil2';
$pass = 'd170895';


//подключение к БД
$con = new mysqli($host, $user, $pass, $data);


//если выбрана ссылка на таблицу MCU, то вывести на текущей странице ее записи
if($_GET['name']=="mcu"){
  $sql="SELECT * FROM MCU";
  $result=$con->query($sql);
  while($row=$result->fetch_assoc()){
    echo $row["id"]." ".$row["Name"]." ".$row["MAC"]." ".$row["Additional"]."<br>";
  }
}


//если выбрана ссылка на таблицу Place, то вывести на текущей странице ее записи
if($_GET['name']=="place"){
  $sql="SELECT * FROM Place";
  $result=$con->query($sql);
  while($row=$result->fetch_assoc()){
    echo $row["id"]." ".$row["Location"]." ".$row["Additional"]."<br>";
  }
}


//если выбрана ссылка на таблицу SDATA, то вывести на текущей странице ее записи
if($_GET['name']=="sdata"){
  $per_page=50;
  if(empty(@$_GET['page'])||($_GET['page']<=0)){
    $page=1;
  }
  else{
    $page=(int)$_GET['page'];
  }
  $rows=$con->query("SELECT COUNT(*) FROM SDATA");
  $rows1=$rows->fetch_row();
  $total_records=$rows1[0];
  $total_pages=ceil($total_records/$per_page);
  $offset=($page-1)*$per_page;
  $result=$con->query("SELECT * FROM SDATA LIMIT ".$offset.', '.$per_page);
  while ($row = $result->fetch_assoc()){
    echo $row["id"] . " " . $row["SMC_P_CROSS_FK"] . " " . $row["DatTime"] . " " . $row["SensorValue"] . "<br>";
  }


/*так как таблица SDATA содержит сотни тысяч записей, то на одной странице отображается по 50 
записей. Внизу страницы есть ссылки Назад, В начало и Дальше, в зависимости от того, на какой 
странице Вы сейчас находитесь.*/
  if($page>1){
    echo '<a href=secpage.php?name=sdata&page='.($page-1).'>Назад</a><br>';
    echo '<a href=secpage.php?name=sdata&page=1>В начало</a><br>';
  }
  if($page<$total_records){
    echo '<a href=secpage.php?name=sdata&page='.($page+1).'>Дальше</a><br>';
  }
}


//если выбрана ссылка на таблицу Sensor, то вывести на текущей странице ее записи
if($_GET['name']=="sensor"){
  $sql="SELECT * FROM Sensor";
  $result=$con->query($sql);
  while($row=$result->fetch_assoc()){
    echo $row["id"]." ".$row["Type"]." ".$row["Data"]." ".$row["Additional"]."<br>";
  }
}


//если выбрана ссылка на таблицу SMC_P_CROSS, то вывести на текущей странице ее записи
if($_GET['name']=="smc_p_cross"){
  $sql="SELECT * FROM SMC_P_CROSS";
  $result=$con->query($sql);
  while($row=$result->fetch_assoc()){
    echo $row["id"]." ".$row["S_M_Cross_FK"]." ".$row["Place_FK"]." ".$row["Time_begin"]."<br>";
  }
}


//если выбрана ссылка на таблицу S_M_CROSS, то вывести на текущей странице ее записи
if($_GET['name']=="s_m_cross"){
  $sql="SELECT * FROM S_M_CROSS";
  $result=$con->query($sql);
  while($row=$result->fetch_assoc()){
    echo $row["id"]." ".$row["Sensor_FK"]." ".$row["MCU_FK"]."<br>";
  }
}
?>
