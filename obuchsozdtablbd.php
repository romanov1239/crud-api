<?php
$connect=new pdo("mysql:host=localhost;dbname=perci","root","");

try {
    $sql = "create table obuch5 (
   id int auto_increment primary key,
   name varchar(20) not null 
)";

    $connect->exec($sql);
    echo 'sozdano';
}catch (PDOException $e){echo 'osh__'.($e->getMessage());}
$connect=null;
