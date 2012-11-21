function onDragEnd(event){
	document.getElementById('position').value=event.originalEvent.target.geometry._C[0];
}

function mark_position(event){
	document.getElementById('position').value=event.get('coordPosition');
}

/**
 * Инициализатор модуля. Вызывается один раз перед использованием остальных функций.
 * Создаёт объект MAPS, содержащий в себе все созданные далее карты.
 */

function maps_init(){
	MAPS=new Object();
}


/**
 * Функция создаёт карту в блочном элементе div_id. Если карта в блоке уже существует - уничтожает её, создавая новую карту взамен.
 * @param div_id - id блочного элемента, обязательный параметр
 * @param parameters - массив параметров карты. Необязательный параметр (при его игнорировании подставляются значения по умолчанию).<br>
 * Значения массива: параметры карты Yandex Maps.
 * @options - Опции карты. Через опции карты можно задавать настройки как самой карты, так и добавленных на неё объектов. Необяательный параметро
 * @param callback - необязательный указатель на функцию, обрабатывающую щелчок на карте. Функция должна принимать один параметр, содержащий описание события и связанные с ним данные (см <a href='http://api.yandex.ru/maps/doc/jsapi/2.x/ref/reference/MapEvent.xml'>http://api.yandex.ru/maps/doc/jsapi/2.x/ref/reference/MapEvent.xml</a>
 * @link http://api.yandex.ru/maps/doc/jsapi/2.x/ref/reference/Map.xml
 */
function map_create (div_id,parameters,options,callback){
	var params=map_parameters (div_id,parameters);
	if (options===undefined) options={maxZoom:23,minZoom:0};
	if (MAPS[div_id]!==undefined) map_destroy(div_id);
	MAPS[div_id] = new ymaps.Map(div_id,params,options);
	if (callback!==undefined)MAPS[div_id].events.add('click',callback);
}

/**
 * Устанавливает одну опцию карты
 * @param div_id - id блочного элемента, обязательный параметр
 * @param key - параметр
 * @param value - значение 
 * @link http://api.yandex.ru/maps/doc/jsapi/2.x/ref/reference/option.Manager.xml#set
 */

function map_set_option (div_id,key,value) {
	MAPS[div_id].options.set(key,value);
}

/**
 * Задаёт несколько опций карты через хэш
 * @param div_id - id блочного элемента, обязательный параметр
 * @param options - массив опций
 * @link http://api.yandex.ru/maps/doc/jsapi/2.x/ref/reference/option.Manager.xml#set
 */

function map_set_options (div_id,options) {
	MAPS[div_id].options.set(options);
} 

/**
 * Функция изменяет параметры уже созданной карты.
 * @param div_id - id блочного элемента, обязательный параметр
 * @param parameters - массив параметров карты. Необязательный параметр (при его игнорировании подставляются значения по умолчанию).<br>
 * @link http://api.yandex.ru/maps/doc/jsapi/2.x/dg/concepts/map.xml#parameters
 */
function map_parameters (div_id,parameters){
	if (parameters===undefined) var parameters={};
	if (parameters.center===undefined) {
		parameters.center=[ymaps.geolocation.latitude, ymaps.geolocation.longitude];
		if (parameters.zoom===undefined) parameters.zoom=ymaps.geolocation.zoom;
	} else {
		if (parameters.zoom===undefined) parameters.zoom=12;
	}
	if (MAPS[div_id]===undefined) return (parameters);
	if (parameters.center!==undefined) MAPS[div_id].setCenter(parameters.center);
}

/**
 * Изменяет поведение карты
 * @param div_id - контейнер карты
 * @param parameters - массив поведений карты<br>
 * Значения массива должны быть в виде:<br>
 * {'behavior':state,...}<br>
 * где <b>behavior</b> - одно из поведений карты (см API), а <b>state</b> - состояние, в которое нужно переключить поведение (например, enable/disable).<br>
 * Вместо enabled/disabled допускается использование 1/0 и true/false соответственно
 */
function map_behavior(div_id,parameters){
	if (parameters===undefined) return(0);
	for (var behavior in parameters){
		if (parameters[behavior]==='enabled'||parameters[behavior]===1||parameters[behavior]===true){
			if (!(MAPS[div_id].behaviors.isEnabled(behavior))) MAPS[div_id].behaviors.enable(behavior);
		} else if (parameters[behavior]==='disabled'||parameters[behavior]===0||parameters[behavior]===false) {
			if (MAPS[div_id].behaviors.isEnabled(behavior)) MAPS[div_id].behaviors.disable(behavior);
		}
	}
}

/**
 * Добавление и удаление стандартных элементов управления
 * @link http://api.yandex.ru/maps/doc/jsapi/2.x/dg/concepts/controls.xml
 * @param div_id - контейнер карты
 * @param parameters - массив контролов карты<br>
 * Значения массива должны быть в виде:<br>
 * {{'control':name,'value':state,'top': value,'left':value},{...}}<br>
 * где <b>control</b> - один из стандартных элементов управления, а <b>state</b> - допустимое для этого элемента действие (например enable/disable)<br>
 * Если параметры top и left не заданы, контрол располагается по умолчанию
 * Вместо enabled/disabld допускается использование 1/0 и true/false соответственно
 */
function map_control_old(div_id,parameters){
	if (parameters===undefined) return(0);
	for (var control in parameters){
		if (parameters[control]['state']==='enabled'||parameters[control]['state']===1||parameters[control]['state']===true){
			if (parameters[control]['top']===undefined || parameters[control]['left']===undefined) {
				var c=undefined;
			} else {
				var c={top:parameters[control]['top'],left:parameters[control]['left']};
			}
			MAPS[div_id].controls.add(parameters[control]['control'],c);
			
		} else if (parameters[control]['state']==='disabled'||parameters[control]['state']===0||parameters[control]['state']===false) {
			MAPS[div_id].controls.remove(parameters[control]['control']);
		}
	}
}

/**
 * Добавление и удаление стандартных элементов управления
 * @link http://api.yandex.ru/maps/doc/jsapi/2.x/dg/concepts/controls.xml
 * @param div_id - контейнер карты
 * @param parameters - массив контролов карты<br>
 * Вместо enabled/disabld допускается использование 1/0 и true/false соответственно
 */

function map_control(div_id,parameters){
	if (parameters===undefined) return(0);
	for (var control in parameters){
		if (parameters[control]['enabled']==='enabled'||parameters[control]['enabled']===1||parameters[control]['enabled']===true){
			var params=parameters[control]['params'];
			var options=parameters[control]['options'];
			switch (control) {
				case 'mapTools':
					MAPS[div_id].controls.add(new ymaps.control.MapTools(params,options));
				break;
				case 'miniMap':
					MAPS[div_id].controls.add(new ymaps.control.MiniMap(params,options));
				break;
				case 'routeEditor'://Добавление RouteEditor делается через ToolBar
					MAPS[map].controls.add(new ymaps.control.ToolBar([new ymaps.control.RouteEditor(params)],options));
				break;
				case 'scaleLine':
					MAPS[div_id].controls.add(new ymaps.control.ScaleLine(options));
				break;
				case 'searchControl':
					MAPS[div_id].controls.add(new ymaps.control.SearchControl(options));
				break;				
				case 'smallZoomControl':
					MAPS[div_id].controls.add(new ymaps.control.SmallZoomControl(options));
				break;	
				case 'trafficControl':
					MAPS[div_id].controls.add(new ymaps.control.TrafficControl(params,options));
				break;	
				case 'typeSelector':
					MAPS[div_id].controls.add(new ymaps.control.TypeSelector(params,options));
				break;	
				case 'zoomControl':
					MAPS[div_id].controls.add(new ymaps.control.ZoomControl(options));
				break;	
								
				default:
					MAPS[div_id].controls.add(control);//Мы не знаем этот контрол, добавим по хешу
				break;
			}
		} else if (parameters[control]['enabled']==='disabled'||parameters[control]['enabled']===0||parameters[control]['enabled']===false) {
			MAPS[div_id].controls.remove(control);//Проверка наличия контрола в API не предусмотрена. Но оно, по крайней мере, не глючит, если мы удаляем несуществующий контрол
		}
	}
}

/**
 * Создаёт простой балун (без привязки к маркеру)
 * @link http://api.yandex.ru/maps/doc/jsapi/2.x/ref/reference/Balloon.xml
 * @param div_id - контейнер карты
 * @param data - массив содержимого балуна, обязательный параметр.
 * @param options - массив параметров балуна. Если не задан, применяются значения по умолчанию.<br>
 * Описание параметров: <a href='http://api.yandex.ru/maps/doc/jsapi/2.x/ref/reference/GeoObject.xml'>http://api.yandex.ru/maps/doc/jsapi/2.x/ref/reference/GeoObject.xml</a>
 * @param coordinates - координаты балуна. Если undefined - то балун создаётся в центре карты.
 */
function map_balloon(div_id,coordinates,data,options){
	if (data===undefined) return(0);
	if (coordinates===undefined||coordinates===null) coordinates=MAPS[div_id].getCenter();
	if (options===undefined)options={autoPan:true};
	MAPS[div_id].balloon.open(coordinates,data,options);
}

/**
 * Создаёт простой маркер (placemark)
 * @link http://api.yandex.ru/maps/doc/jsapi/2.x/ref/reference/Placemark.xml
 * @param div_id - контейнер карты
 * @param data - массив содержимого маркера, обязательный параметр.
 * @param options - массив параметров маркера. Если не задан, применяются значения по умолчанию.<br>
 * Описание параметров: <a href='http://api.yandex.ru/maps/doc/jsapi/2.x/ref/reference/GeoObject.xml'>http://api.yandex.ru/maps/doc/jsapi/2.x/ref/reference/GeoObject.xml</a>
 * @param coordinates - координаты маркера. Если undefined - то маркер создаётся в центре карты.
 * @param onDragEnd - функция, вызываемая при окончании drag'n'drop
 */
function map_placemark(div_id,coordinates,data,options,onDragEnd){
	if (data===undefined) return(0);
	if (coordinates===undefined) coordinates=MAPS[div_id].getCenter();
	if (options===undefined)options={preset: "twirl#yellowStretchyIcon",balloonCloseButton: true,hideIconOnBalloonOpen:false};
	var placemark = new ymaps.Placemark(coordinates, data, options);
	if (onDragEnd!==undefined) placemark.events.add("dragend",onDragEnd);
	MAPS[div_id].geoObjects.add(placemark);
}

/**
 * Очищает карту от маркеров
 * @param div_id - контейнер карты
 */
function map_placemark_clear(div_id){
	MAPS[div_id].geoObjects.each(
		function (geoObject) {
			MAPS[div_id].geoObjects.remove(geoObject);
		}
	);
}

/**
 * Создаёт коллекцию геообъектов из массива маркеров, помещает на карту.
 * @param div_id - контейнер карты
 * @param placemarks - массив маркеров.
 * @param options - <a href='http://api.yandex.ru/maps/doc/jsapi/2.x/ref/reference/GeoObject.xml'>набор параметров</a>, применяемых к коллекции и всем её маркерам.
 * @param bounds - 0: не масштабировать карту (по умолчанию), 1: масштабировать под точки текущей коллекции, 2: масштабировать под точки коллекции всей карты.
 * @return Возвращает собранную геоколекцию
 */
function map_placemark_collection(div_id,placemarks,options,bounds){
	var GOC = new ymaps.GeoObjectCollection();
	for (var i=0;i<placemarks.length;i++){
		var p=new ymaps.Placemark(placemarks[i].coordinates,placemarks[i].data,placemarks[i].options);
		GOC.add(p);
	}
	
	if (options!==undefined){
		for (var option in options){
			GOC.options.set(option,options[option]);
		}
	}
	MAPS[div_id].geoObjects.add(GOC);
	switch (bounds) {
		case undefined:
		case 0:
		default:	
			//do nothing		
			break;
		case 1:
			MAPS[div_id].setBounds(GOC.getBounds());//Некорректно работает в текущей версии API.
			break;
		case 2:
			MAPS[div_id].setBounds(MAPS[div_id].geoObjects.getBounds());
			break;
	}	
	return (GOC);
}


function map_geoobject (div_id,feature,options) {
	if (feature===undefined) return (0);
	//if (feature.geometry.coordinates===undefined) feature.geometry.coordinates=MAPS[div_id].getCenter();//API обрабатывает это самостоятельно
	
	var GO = new ymaps.GeoObject (feature,options);
	MAPS[div_id].geoObjects.add(GO);
}

/**
 * Прямое геокодирование
 * Показывает на карте координату по указанному адресу. Адрес передаётся в виде "Город, улица, дом"
 * @param div_id - контейнер карты
 * @param request - строковое значение адреса
 * @param options - массив дополнительных параметров геокодера (может быть опущен).
 * @param execute - функция, которая будет вызвана по завершению геокодирования. В функцию будут переданы координаты найденной точки. Может быть опущена, в таком случае выполнится стандартный обработчик.
 * @link http://api.yandex.ru/maps/doc/jsapi/2.x/ref/reference/geocode.xml
 */
function map_geocode(div_id,request,options,execute){
	if (options===undefined)options={results: 1};
	var myGeocoder = ymaps.geocode(request);
	myGeocoder.then(
		function (res) {
			if (execute===undefined){
				var ret = res.geoObjects.get(0).geometry.getCoordinates();
				map_baloon(div_id,{contentHeader:'Это здесь',contentBody:request,contentFooter:ret},undefined,ret);
				MAPS[div_id].setCenter(ret,15);
			} else {
				execute(res.geoObjects.get(0).geometry.getCoordinates());
				MAPS[div_id].setCenter(res.geoObjects.get(0).geometry.getCoordinates());
			}
		},
		function (err) {
			return (false);
		}
	);
}



/**
 * Центрирует карту на местоположении пользователя, определённом по IP
 * @param div_id - контейнер карты
 */
function map_geolocate(div_id){
	var zoom=(ymaps.geolocation.zoom)?ymaps.geolocation.zoom:10;
	MAPS[div_id].setCenter([ymaps.geolocation.latitude, ymaps.geolocation.longitude],zoom);
}

/**
 * Возвращает название города, в котором находится пользователь
 * @returns название города
 */
function map_geolocate_town(){
	return ymaps.geolocation.city;
}

/**
 * Изменяет зум на карте div_id на delta. delta может быть отрицательным (удаление) или положительным (приближение). 
 */
function map_zoom_delta(div_id,zoom_delta){
	var zoom=MAPS[div_id].getZoom()+zoom_delta;
	MAPS[div_id].setZoom(zoom);
}

function map_normalize_zoom(div_id){
	var zoom=MAPS[div_id].getZoom();
	if (zoom>17)MAPS[div_id].setZoom(17);
}

/**
 * Удаляет карту в блочном элементе div_id
 * @param div_id - id блочного элемента
 */
function map_destroy(div_id){
	MAPS[div_id].destroy();
}

/**
 * Деинициализатор модуля. Уничтожает все карты объекта MAPS
 * @param force - true: уничтожить объект MAPS
 */

function maps_destroy(force){
	for (var key in MAPS){
		MAPS[key].destroy();
	}
	if (force===true) delete MAPS;
}

maps_init();