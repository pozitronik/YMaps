<?php
class Behavior {
	public $enabled=FALSE;
	public $options;

	function enable (){
		$this->enabled=TRUE;
	}

	function disable(){
		$this->enabled=FALSE;
	}
}

class B_DbClickZoomOptions {
	public $centering=TRUE;
	public $duration=200;
}

class B_DbClickZoom extends Behavior {
	function __construct(){
		$this->options=new B_DbClickZoomOptions();
	}
}

class B_DragOptions {
	public $actionCursor='grabbing';
	public $cursor='grab';
	public $inertia=true;
	public $inertiaDuration=400;
	public $tremor=2;
}

class B_Drag extends Behavior {
	function __construct(){
		$this->enabled=TRUE;
		$this->options=new B_DragOptions();
	}
}

class B_LeftMouseButtonMagnifierOptions {
	public $actionCursor='crosshair';
	public $cursor='zoom';
	public $duration=300;
}

class B_LeftMouseButtonMagnifier extends Behavior {
	function __construct(){
		$this->options=new B_LeftMouseButtonMagnifierOptions();
	}
}

class B_MultiTouchOptions {
	public $tremor=2;
}

class B_MultiTouch extends Behavior {
	function __construct(){
		$this->options=new B_MultiTouchOptions();
	}
}

class B_RightMouseButtonMagnifierOptions {
	public $actionCursor='crosshair';
	public $duration=300;
}

class B_RightMouseButtonMagnifier extends Behavior {
	function __construct(){
		$this->options=new B_RightMouseButtonMagnifierOptions();
	}
}

class B_RouteEditor extends Behavior {

}

class B_Ruler extends Behavior {
}

class B_ScrollZoomOptions {
	public $maximumDelta=5;
	public $speed=5;
}

class B_ScrollZoom extends Behavior {
	function __construct(){
		$this->options=new B_ScrollZoomOptions();
	}
}

class Behaviors {
	public $dblClickZoom;
	public $drag;
	public $leftMouseButtonMagnifier;
	public $multiTouch;
	public $rightMouseButtonMagnifier;
	public $routeEditor;
	public $ruler;
	public $scrollZoom;

	function __construct(){
		$this->dblClickZoom=new B_DbClickZoom();
		$this->drag=new B_Drag();
		$this->leftMouseButtonMagnifier=new B_LeftMouseButtonMagnifier();
		$this->multiTouch=new B_MultiTouch();
		$this->rightMouseButtonMagnifier=new B_RightMouseButtonMagnifier();
		$this->routeEditor=new B_RouteEditor();
		$this->ruler=new B_Ruler();
		$this->scrollZoom=new B_ScrollZoom();
	}
}

?>