<?php

/**
 * Класс для простой работы с основными функциями Yandex.Maps 2.0
 * @author Pavel Dubrovsky (dubrovsky.pn@gmail.com)
 */
class YMaps{
	private $id;//id блока, с которым работает эта карта
	private $jsbuff;//Буфер для JS-кода, генерируемого классом
	private $options=array("load"=>0,"init"=>0,"package"=>"full");//Настройки инициализации объекта
	
	const map_type_map='yandex#map';
	const map_type_satellite='yandex#satelite';
	const map_type_hybrid='yandex#hybrid';
	const map_type_publicmap='yandex#publicMap';
	const map_type_publicmaphybrid='yandex#publicMapHybrid';
	/**
	 * Инициирует объект карты
	 * @param string $div_id - обязательный параметр, id блока, в который будет заключена карта
	 * @param array $options - массив параметров, используемых при инициализации объекта:<br>
	 * * load: 0 (default) - подгрузка JS-кода Яндекс.карт при создании объекта, 1 - при вызове карты, 2 - не загружать код (он может быть уже загружен).<br>
	 * * init: 0 (default) - отрисовка карты после подгрузки JS-кода Яндекс.карт, 1 - после загрузки страницы, 2 - не отрисовывать код (ручной вызов методом call).<br>    
	 * * package: подключаемый пэкэдж Яндекс.карт. В текущей версии параметр не используется, всегда загружается package.full<br> 
	 * Параметр $options может быть опущен для использования значений по умолчанию
	 * @throws Exception генерируемое исключение при отсутствии первого параметра
	 */
	
	function __construct($div_id,array $options=array()){
		if (isset($div_id)){
			$this->id=$div_id;
		} else {
			throw new Exception('Parent div is not defined!');
		}
		$this->options=$options+$this->options;		
		if ($this->options["load"]==0) {
			print "<script src='http://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU' type='text/javascript'></script>";//подключаем пэкэдж яндекс.карт
			print "<script src='maps.js' type='text/javascript'></script>";//подключаем модуль работы с картами
		}
	}
	
	private function MakeJSObject ($input_array,$object_name){
		return ("var $object_name=".json_encode($input_array).";");
	}
	
	/**
	 * Создаёт карту в родительском блоке с заданным координатами
	 * @param float $longtitude - долгота центра карты
	 * @param float $latitude - широта центра карты
	 * @param integer $zoom - приближение карты
	 * @param map_type $type - тип карты
	 * Параметры могут быть опущены для использования соответствующих значений по умолчанию:<br>
	 * Для долготы и широты будут использоваться координаты местонахождения пользователя, определённые по IP.
	 * Для приближения - среднее доступное приближение (12) либо максимальное приближение, рассчитанное по определённому IP пользователя.
	 * Для типа - тип "схема". 
	 */
	function create ($longtitude=null,$latitude=null,$zoom=null,$type=null){
		$options = array();
		if (!(is_null($longtitude) & is_null($latitude))){
			$options["center"]=array($longtitude,$latitude);
		}
		if (!is_null($zoom)) $options["zoom"]=$zoom;
		if (!is_null($type)) $options["type"]=$type;
		$this->jsbuff.="map_create($this->id,".json_encode($options).");";
	}
	
	/**
	 * Рисует карту с учётом всех установленных парметров.<br>
	 * Если $this->options["init"] установлен в 2, функция вернёт 0, т.к. предполагается использование функции call();
	 */
	function draw(){
		if ($this->options["load"]==1) {
			print "<script src='http://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU' type='text/javascript'></script>";//подключаем пэкэдж яндекс.карт
			print "<script src='maps.js' type='text/javascript'></script>";//подключаем модуль работы с картами
		}
		switch ($this->options["init"]) {
			case 0:
			default:
				print "<script type='text/javascript'>ymaps.ready(".$this->id."_init);function ".$this->id."_init(){";
			break;
			case 1: 	
				print "<script type='text/javascript'>window.onload = function(){";
			break;
			case 2:
				return(false);//В этом случае draw выключен
			break;
		}
		print $this->jsbuff;
		print "}</script>";
	
		$this->jsbuff='';
	}
	
	/**
	 * Возвращает с
	 */
	function call(){
		@return ($this->jsbuff);
	}
	
}



?>