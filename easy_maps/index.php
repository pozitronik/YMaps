<?php
require_once 'ymaps.php';
require_once 'html.php';
PrintHeader(array("title"=>"Тестирование класса YMaps"));
print "<div id='map' style='width:100%; height:600px;'></div>";
$map = new YMap('map');//В параметре load указываем, что не нужно загружать код API Яндекс.Карт, в параметре init указываем, что сгенерированный код выполнять не нужно.
$map->create(55.75,37.61,15,map_type_hybrid);//Укажем координаты центра карты, уровень приближения и тип. Тип может быть задан как строкой, так и одной из констант map_type_*
//Добавим несколько элементов управления.
$map->controls->zoomControl->enabled=TRUE;//Включим элемент управления "ползунок масштаба".
$map->controls->zoomControl->options->position=array('top'=>5, 'left'=>5);//Изменим его местоположение на левый верхний угол.
$placemark_centered=new Placemark();//Создадим новый маркер без указания координат
$hint=new Hint('Этот маркер <b>всегда</b> находится в центре карты');//Создадим новый хинт
$hint->options->hideTimeOut=1;//Укажем, что хинт должен исчезать без задержки
$placemark_centered->set_hint($hint);//Добавим хинт к маркеру
$placemark_centered->options->preset='twirl#darkblueStretchyIcon';//Установим для хинта один из готовых презетов
$placemark_centered->feature->properties->iconContent='Центр';//Установленный презет допускает текстовое содержимое внутри маркера, установим его
$map->placemark($placemark_centered);//Добавим маркер на карту
$placemark=new Placemark(55.75154541282902,37.61556470101905);//Создадим новый маркер с указанием координат
$placemark->options->iconImageHref='marker.png';//Изменим иконку маркера.
$balloon=new Balloon();//Создадим новый балун.
//Зададим содержимое балуна
$balloon->SetData("<b>Госуда́рственный Кремлёвский дворе́ц</b> (до 1992 года — <b>Кремлёвский дворец съездов</b>) построен в 1961 году под руководством архитектора Михаила Васильевича Посохина...",
				"Дворец съездов",
				"<a href='http://ru.wikipedia.org/wiki/Дворец_съездов' style='float:right'>Читать дальше</a>");
$placemark->set_balloon($balloon);//Добавим балун к маркеру
$placemark->feature->properties->hintContent="Щелчок для подсказки";//Можно задать содержимое всплывающей подсказки без создания объекта Hint
$map->placemark($placemark);//Добавим маркер на карту
$map->draw();//Вставка кода карты.
?>

<!--
$map->behaviors->dblClickZoom->enable();
$map->behaviors->scrollZoom->enabled=TRUE;
$map->behaviors->scrollZoom->options->speed=0.4;
$map->behaviors->drag->enabled=TRUE;
/*Элементы управления*/
/*mapTools*/
/*
$map->controls->mapTools->enabled=TRUE;
$map->controls->mapTools->options->position=array('left'=>150,'top'=>150);
$map->controls->mapTools->state=array('drag');

$map->controls->miniMap->state->expanded=false;
$map->controls->miniMap->options->size=array(400,400);
$map->controls->miniMap->options->position=array('left'=>150,'bottom'=>150);
$map->controls->miniMap->options->zoomOffset=-4;
$map->controls->miniMap->enabled=TRUE;

$map->controls->routeEditor->enabled=TRUE;
$map->controls->routeEditor->options->position=array('right'=>15,'bottom'=>35);

$map->controls->scaleLine->enabled=TRUE;
$map->controls->scaleLine->options->position=array('right'=>350,'bottom'=>55);

$map->controls->searchControl->enabled=true;
$map->controls->searchControl->options->position=array('top'=>50,'left'=>150);

$map->controls->smallZoomControl->enabled=TRUE;
$map->controls->smallZoomControl->options->position=array('bottom'=>5,'left'=>150);

$map->controls->trafficControl->enabled=TRUE;
$map->controls->trafficControl->state->providerKey=traffic_type_archive;
$map->controls->trafficControl->state->shown=true;
$map->controls->trafficControl->options->position=array('bottom'=>5,'left'=>415);

$map->controls->typeSelector->enabled=TRUE;
$map->controls->typeSelector->options->position['left']=30;
$map->controls->typeSelector->options->fixTitle=true;
$map->controls->typeSelector->params->mapTypes=array('yandex#map', 'yandex#publicMap');

$map->controls->zoomControl->enabled=TRUE;
$map->controls->zoomControl->options->position['left']=400;*/

$balloon=new Balloon();
$balloon->SetData('center', 'header', 'footer');
$balloon->options->closeButton=TRUE;
$balloon->options->shadow=FALSE;

$hint=new Hint();
$hint->data->content='ОЛОЛОЛОЛОЛОЛО';


$placemark=new Placemark();
$placemark->set_center(53.205226,50.191184);
$placemark->set_balloon($balloon);
$placemark->options->draggable=TRUE;
$placemark->set_hint($hint);

//$placemark->



$map->placemark($placemark);


-->