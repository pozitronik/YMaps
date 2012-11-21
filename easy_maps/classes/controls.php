<?php
class Control {
	public $enabled=FALSE;
	public $options;
	
	function enable (){
		$this->enabled=TRUE;
	}

	function disable(){
		$this->enabled=FALSE;
	}
}

class MapToolsParams {
	public $items=array('default');
}

class MapToolsOptions {
	public $position=array('top'=>5,'right'=>5);
}

class MapTools extends Control{
	public $params;
	function __construct(){
		$this->params=new MapToolsParams();
		$this->options=new MapToolsOptions();
	}
}

class MiniMapState {
	public $expanded=TRUE;
	public $type=map_type_map;
}

class MiniMapOptions {
	public $position=array('left'=>5,'bottom'=>5);
	public $size=array(128,90);
	public $zoomOffset=5;
}

class MiniMap extends Control{
	public $state;
	function __construct(){
		$this->state=new MiniMapState();
		$this->options=new MiniMapOptions();
	}
}

class RouteEditorParams {

}

class RouteEditorOptions {
	public $position=array('top'=>5,'left'=>98);
}

class RouteEditor extends Control{
	public $params;
	function __construct(){
		$this->params=new RouteEditorParams();
		$this->options=new RouteEditorOptions();
	}
}

class ScaleLineOptions {
	public $position=array('right'=>7,'bottom'=>50);
}

class ScaleLine extends Control{
	function __construct(){
		$this->options=new ScaleLineOptions();
	}
}

class SearchControlOptions {
	public $boundedBy;
	public $kind='house';
	public $noCentering=FALSE;
	public $noPlacemark=FALSE;
	public $noPopup=FALSE;
	public $position=array('top'=>5,'left'=>104);
	public $provider=map_type_map;
	public $resultsPerPage=3;
	public $strictBounds=FALSE;
	public $useMapBounds=FALSE;
	public $width=240;
}

class SearchControl extends Control{
	function __construct(){
		$this->options=new SearchControlOptions();
	}
}

class SmallZoomControlOptions {
	public $duration=200;
	public $position=array('top'=>75,'left'=>5);
}

class SmallZoomControl extends Control{
	function __construct(){
		$this->options=new SmallZoomControlOptions();
	}
}

class TrafficControlState {
	public $providerKey=traffic_type_actual;
	public $shown=FALSE;
}

class TrafficControlOptions {
	public $position=array('top'=>5,'right'=>120);
}

class TrafficControl extends Control{
	public $state;
	function __construct(){
		$this->state=new TrafficControlState();
		$this->options=new TrafficControlOptions();
	}
}

class TypeSelectorParams {
	public $mapTypes=array(map_type_map,map_type_satellite,map_type_hybrid,map_type_publicmap,map_type_publicmaphybrid);
}

class TypeSelectorOptions {
	public $position=array('top'=>5,'right'=>5);
	public $fixTitle=FALSE;
}

class TypeSelector extends Control{
	public $params;
	
	function __construct(){
		$this->options=new TypeSelectorOptions();
		$this->params=new TypeSelectorParams();
	}
}

class ZoomControlOptions {
	public $noTips=FALSE;
	public $position=array('top'=>75,'left'=>5);
}

class ZoomControl extends Control{
	function __construct(){
		$this->options=new ZoomControlOptions();
	}
}

class Controls {
	public $mapTools;
	public $miniMap;
	public $routeEditor;
	public $scaleLine;
	public $searchControl;
	public $smallZoomControl;
	public $trafficControl;
	public $typeSelector;
	public $zoomControl;

	function __construct(){
		$this->mapTools=new MapTools();
		$this->miniMap=new MiniMap();
		$this->routeEditor=new RouteEditor();
		$this->scaleLine=new ScaleLine();
		$this->searchControl=new SearchControl();
		$this->smallZoomControl=new SmallZoomControl();
		$this->trafficControl=new TrafficControl();
		$this->typeSelector=new TypeSelector();
		$this->zoomControl=new ZoomControl();
	}
}
?>