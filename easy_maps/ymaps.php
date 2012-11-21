<?php
require_once 'classes/behaviors.php';
require_once 'classes/balloon.php';
require_once 'classes/controls.php';
require_once 'classes/geoobject.php';
require_once 'classes/hint.php';

/**
 * Класс для простой работы с основными функциями Yandex.Maps 2.0
 * @author Pavel Dubrovsky (dubrovsky.pn@gmail.com)
 */
const map_type_map='yandex#map';
const map_type_satellite='yandex#satelite';
const map_type_hybrid='yandex#hybrid';
const map_type_publicmap='yandex#publicMap';
const map_type_publicmaphybrid='yandex#publicMapHybrid';
const traffic_type_actual='traffic#actual';
const traffic_type_archive='traffic#archive';
const im_opaque='default#opaque';
const im_geoobject='default#geoObject';
const im_layer='default#layer';
const im_transparent='default#transparent';

class YMap{
	private $id;//id блока, с которым работает эта карта
	private $jsbuff;//Буфер для JS-кода, генерируемого классом
	private $options=array("load"=>0,"init"=>0,"package"=>"full");//Настройки инициализации объекта
	private $map_parameters=array();
	public $behaviors;
	public $controls;

	/**
	 * Инициирует объект карты
	 * @param string $div_id - обязательный параметр, id блока, в который будет заключена карта
	 * @param array $options - массив параметров, используемых при инициализации объекта:<br>
	 * * load: 0 (default) - подгрузка JS-кода Яндекс.Карт при создании объекта, 1 - при вызове карты, 2 - не загружать код (он может быть уже загружен).<br>
	 * * init: 0 (default) - выполнение кода после подгрузки JS-кода Яндекс.Карт, 1 - после загрузки страницы, 2 - не выполнять код.<br>
	 * * package: подключаемый пэкэдж Яндекс.карт. В текущей версии параметр не используется, всегда загружается package.full<br>
	 * Параметр $options может быть опущен для использования значений по умолчанию
	 * @throws Exception генерируемое исключение при отсутствии первого параметра
	 */

	function __construct($div_id,array $options=array()){
		$this->behaviors=new Behaviors();
		$this->controls=new Controls();
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

	private function buff($str){
		$this->jsbuff.=$str."\n";
	}

	private function MakeJSObject ($input_array,$object_name){
		return ("var $object_name=".json_encode($input_array).";");
	}

	/**
	 * Создаёт карту в родительском блоке с заданными координатами
	 * @param float $longtitude - долгота центра карты.
	 * @param float $latitude - широта центра карты.
	 * @param integer $zoom - приближение карты.
	 * @param string $type - тип карты<br>
	 * Параметры могут быть опущены для использования соответствующих значений, установленных ранее функцией parameters. Если параметры не были установлены, используются значения по умолчанию:<br>
	 * Для долготы и широты будут использоваться координаты местонахождения пользователя, определённые по IP.
	 * Для приближения - среднее доступное приближение (12) либо максимальное приближение, рассчитанное по определённому IP пользователя.
	 * Для типа - тип "схема".
	 */
	function create ($longtitude=null,$latitude=null,$zoom=null,$type=null){
		if (!(is_null($longtitude) && is_null($latitude))) $this->map_parameters["center"]=array($longtitude,$latitude);
		if (!is_null($zoom)) $this->map_parameters["zoom"]=$zoom;
		if (!is_null($type)) $this->map_parameters["type"]=$type;
		$this->buff("map_create($this->id,".json_encode($this->map_parameters).");");
	}

	/**
	 * Устанавливает параметры карты. Если карта уже создана - меняет её параметры на указанные.
	 * @param float $longtitude - долгота центра карты
	 * @param float $latitude - широта центра карты
	 * @param integer $zoom - приближение карты
	 * @param string $type - тип карты
	 */
	function set_parameters ($longtitude=null,$latitude=null,$zoom=null,$type=null) {
		$options = array();
		if (!(is_null($longtitude) && is_null($latitude))) $this->map_parameters["center"]=array($longtitude,$latitude);
		if (!is_null ($zoom)) $this->map_parameters['zoom']=$zoom;
		if (!is_null ($type)) $this->map_parameters['type']=$type;
		$this->buff("map_parameters($this->id,".json_encode($this->map_parameters).");");
	}

	/**
	 * Применяет поведения, установленные в $this->behaviors
	 */
	private function set_behaviors (){
		$ba=array();
		$bo=array();
		foreach ($this->behaviors as $behavior=>$parameters){//Включаем указанные поведения
			$ba[$behavior]=$parameters->enabled;
			if ($parameters->enabled) {//Для включённых поведений применяем установленные параметры
				foreach ($this->behaviors->$behavior->options as $option=>$value) {
					$bo[$behavior.ucfirst($option)]=$value;
				}
			}
		}
		$this->buff("map_behavior($this->id,".json_encode($ba).");");
		$this->buff("map_set_options($this->id,".json_encode($bo).");");
	}

	private function set_controls (){
		$ca=array();
		foreach ($this->controls as $control=>$parameters) {
			$ca[$control]['enabled']=$parameters->enabled;
			if ($parameters->enabled) {
				$ca[$control]['options']=$this->controls->$control->options;
				if (isset($this->controls->$control->state)) $ca[$control]['params']=$this->controls->$control->state;//Пытаемся хоть из чего-то получить первый параметр для JS-конструктора ymap.control.add
					elseif (isset($this->controls->$control->params)) $ca[$control]['params']=$this->controls->$control->params; else $ca[$control]['params']=null;
			}
		}
		$this->buff("map_control ($this->id,".json_encode($ca).");");
	}

	/**
	 * Создаёт на карте балун без привязки к геообъекту
	 * @param Balloon $balloon - экземпляр класса Balloon
	 */

	function balloon ($balloon){
		$bd=array();
		$bo=array();
		foreach ($balloon->data as $param=>$value) {
			$bd[$param]=$value;
		}
		foreach ($balloon->options as $option=>$value) {
			if (!is_null($value)) $bo[$option]=$value;
		}
		$this->buff("map_balloon ($this->id,".json_encode($balloon->coordinates).",".json_encode($bd).",".json_encode($bo).");");
	}

	/**
	 * Создаёт на карте геообъект отметки.
	 * @param Placemark $placemark - экземпляр класса Placemark
	 */

	function placemark ($placemark) {
		$feature_properties=array();
		$options=array();
		foreach ($placemark->feature->properties as $property=>$value) {
			if (!is_null($value))$feature_properties[$property]=$value;
		}
		foreach ($placemark->options as $option=>$value) {
			if (!is_null($value))$options[$option]=$value;
		}
		$feature=array('geometry'=>$placemark->feature->geometry,'properties'=>$feature_properties);
		$this->buff("map_geoobject ($this->id,".json_encode($feature).",".json_encode($options).");");
	}

	/**
	 * Рисует карту с учётом всех установленных парметров.<br>
	 * Если $this->options["init"] установлен в 2, функция не выводит сгенерированный код, а ведёт себя так, как будто параметр $return установлен в TRUE
	 * @param boolean $return. TRUE: Функция возвращает сгенерированный JS-код, FALSE (default) - функция выводит сгенерированный JS-код
	 * После вызова функции буфер сгенерированного кода всегда сбрасывается!
	 */
	function draw($return=FALSE){
		$this->set_behaviors();//Применим установленные поведения
		$this->set_controls();//Включим контролы
		$js='';
		if ($this->options["load"]==1) {
			$js.="<script src='http://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU' type='text/javascript'></script>";//подключаем пэкэдж яндекс.карт
			$js.="<script src='maps.js' type='text/javascript'></script>";//подключаем модуль работы с картами
		}
		switch ($this->options["init"]) {
			case 0:
			default:
				$js.="<script type='text/javascript'>ymaps.ready(".$this->id."_init);function ".$this->id."_init(){".$this->jsbuff."}</script>";
			break;
			case 1:
				$js.="<script type='text/javascript'>window.onload = function(){".$this->jsbuff."}</script>";
			break;
			case 2:
				//$js.="<script type='text/javascript'>".$this->jsbuff."</script>";
				$js=$this->jsbuff;
				$return=TRUE;
			break;
		}

		if ($return) return $js; else print ($js);
		$this->jsbuff='';
	}

	/**
	 * Возвращает сгенерированный к текущему моменту JS-код.
	 */
	function call(){
		return ($this->jsbuff);
	}

}



?>