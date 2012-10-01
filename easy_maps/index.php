<?php 
require_once 'ymaps.php';
require_once 'html.php';
PrintHeader(array("title"=>"Тестирование класса YMaps"));
print "<div id='map' style='width:100%; height:600px; background:silver'></div>";

$maps = new YMaps('map',array("init"=>2)); 
$maps->create(null,null,12,$maps::map_type_publicmaphybrid);
$maps->draw();

?>