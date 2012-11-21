<?php

class HintData {
	public $content='';

	function __construct($content=null){
		if (!is_null($content)) $this->content=$content;
	}
}

class HintOptions {
	//public $contentLayout;
	public $fitPane=true;
	public $hideTimeOut=700;
	public $holdByMouse=true;
	public $interactivityModel=im_opaque;
	//public $layout;
	//public $overlay='overlay.label';
	//public $pane='outers';
	public $showTimeout=150;
}

class Hint {
	public $data;
	public $options;

	function __construct($content=null){
		$this->data=new HintData($content);
		$this->options=new HintOptions();
	}
}
?>