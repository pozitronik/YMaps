<?php
class BalloonData  {
	public $content='';
	public $contentBody='';
	public $contentHeader='';
	public $contentFooter='';
}

class BalloonOptions {
	public $autoPan=true;
	public $autoPanDuration=500;
	public $autoPanMargin;
	public $closeButton=true;
	public $interactivityModel=im_opaque;
	public $maxHeight;
	public $maxWidth;
	public $minHeight;
	public $minWidth;
	public $offset;
	public $shadow=true;
}

class Balloon {
	public $data;
	public $options;
	public $coordinates;

	function __construct(){
		$this->data=new BalloonData();
		$this->options=new BalloonOptions();
	}

	/**
	 * Задаёт полное содержимое балуна
	 * @param string $contentBody - задаёт поле contentBody
	 * @param string $contentHeader - задаёт поле contentHeader
	 * @param string $contentFooter - задаёт поле contentFooter
	 */

	function SetData ($contentBody,$contentHeader,$contentFooter){
		$this->data->contentBody=$contentBody;
		$this->data->contentHeader=$contentHeader;
		$this->data->contentFooter=$contentFooter;
	}
}
?>