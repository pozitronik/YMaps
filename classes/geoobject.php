<?php

class Geometry {
	public $type;
	public $coordinates=array();
}

class GeoObjectFeatureProperties {
	public $iconContent;
	public $hintContent;
	public $balloonContent;
	public $balloonContentHeader;
	public $balloonContentBody;
	public $balloonContentFooter;
}

class GeoObjectFeature {
	public $geometry;
	public $properties;

	function __construct(){
		$this->properties=new GeoObjectFeatureProperties();
		$this->geometry=new Geometry();
	}
}

class GeoObjectOptions {
	public $cursor='pointer';
	public $draggable=false;
	public $fill=true;
	public $fillColor='0066ff99';
	public $fillOpacity=1;
	public $hasBallon=true;
	public $hasHint=true;
	public $hideIconOnBalloonOpen=true;
	public $iconColor;
	public $iconContentLayout;
	public $iconContentOffset;
	public $iconContentPadding;
	public $iconContentSize;
	public $iconImageClipRect;
	public $iconImageHref;
	public $iconImageOffset;
	public $iconImageSize;
	public $iconLayout;
	public $iconMaxHeight;
	public $iconMaxWidth;
	public $iconOffset;
	public $iconShadow=false;
	public $iconShadowImageHref;
	public $iconShadowImageOffset;
	public $iconShadowImageSize;
	public $iconShadowLayout;
	public $iconShadowOffset;
	public $interactivityModel=im_geoobject;
	public $opacity=1;
	public $openBalloonOnClick=true;
	public $openEmptyBalloon=false;
	public $overlayFactory='default#interactive';
	public $pane='overlays';
	public $preset;
	public $showEmptyHint=false;
	public $showHintOnHover=true;
	public $stroke=true;
	public $strokeColor='0066ffff';
	public $strokeOpacity=1;
	public $strokeStyle;
	public $strokeWidth=1;
	public $visible=true;
	public $zIndex;
	public $zIndexActive;
	public $zIndexDrag;
	public $zIndexHover;

	public $balloonOptions;
	public $hintOptions;

	function __construct(){
		$this->balloonOptions=new BalloonOptions();
		$this->hintOptions=new HintOptions();
	}
}

class GeoObject {
	public $feature;
	public $options;

	function __construct(){
		$this->feature=new GeoObjectFeature();
		$this->options=new GeoObjectOptions();
	}
}

class Placemark extends GeoObject {

	/**
	 * Создаёт маркер.
	 * @param float $longtitude - долгота маркера.
	 * @param float $latitude - широта маркера.
	 */
	function __construct($longtitude=null,$latitude=null){
		parent::__construct();
		$this->feature->geometry->type='Point';
		if (!(is_null($longtitude) && is_null($latitude))) $this->set_center($longtitude, $latitude);
	}

	/**
	 * Задаёт положение маркера
	 * @param float $longtitude - долгота маркера.
	 * @param float $latitude - широта маркера.
	 */

	function set_center ($longtitude,$latitude) {
		$this->feature->geometry->coordinates=array($longtitude,$latitude);
	}

	/**
	 * Привязывает балун к маркеру.
	 * @param Balloon $balloon
	 */

	function set_balloon ($balloon) {
		foreach ($balloon->data as $field=>$value) {
			$b="balloon".ucfirst($field);
			$this->feature->properties->$b=$value;
		}

		foreach ($balloon->options as $field=>$value) {
			$b="balloon".ucfirst($field);
			$this->options->$b=$value;
		}

		$this->options->hasBallon=TRUE;
	}

	/**
	 * Привязывает хинт к маркеру
	 * @param Hint $hint
	 */

	function set_hint ($hint) {
		foreach ($hint->data as $field=>$value) {
			$b="hint".ucfirst($field);
			$this->feature->properties->$b=$value;
		}

		foreach ($hint->options as $field=>$value) {
			$b="hint".ucfirst($field);
			$this->options->$b=$value;
		}

		$this->options->hasHint=TRUE;
	}

}

?>