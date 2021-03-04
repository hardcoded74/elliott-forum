<?php
// FROM HASH: a44f7a23c45d9b0d5ff195ff226f4cdb
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->func('property', array('uix_buttonRipple', ), false)) {
		$__finalCompiled .= '
@-webkit-keyframes rippleAnimation {
  to {
    border-radius: 100%;
    opacity: 0;
    -webkit-transform: scale(2.5);
            transform: scale(2.5);
  }
}
@keyframes rippleAnimation {
  to {
    border-radius: 100%;
    opacity: 0;
    -webkit-transform: scale(2.5);
            transform: scale(2.5);
  }
}
.rippleButton {
position: relative;
}
.rippleButton .ripple-container {
	position: absolute;
	top: 0;
	left: 0;
	pointer-events: none;
	width: 100%;
	height: 100%;
	max-height: 100%;
	max-width: 100%;
	overflow: hidden;
		border-radius: inherit;
	-webkit-mask-image: url(\'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAIAAACQd1PeAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAA5JREFUeNpiYGBgAAgwAAAEAAGbA+oJAAAAAElFTkSuQmCC\');
}


.rippleButton .ripple {
  background-color: rgba(255,255,255,0.2);
  border-radius: 100%;
  -webkit-transform: scale(0);
          transform: scale(0);
  -webkit-transform-origin: center 50%;
          transform-origin: center 50%;
  display: block;
  position: absolute;
  -webkit-animation: rippleAnimation 650ms ease-out;
          animation: rippleAnimation 650ms ease-out;
-webkit-animation-play-state: running;
        animation-play-state: running;
}
.navLink .ripple {
  background-color: rgba(0,0,0,0.15);
  -webkit-animation: rippleAnimation 450ms ease-out;
}

.rippleButton:hover {cursor: pointer;}
';
	}
	return $__finalCompiled;
}
);