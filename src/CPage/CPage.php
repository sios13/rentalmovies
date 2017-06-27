<?php

class CPage extends CContent {
	public function __construct($variables) {
		CContent::__construct($variables);
	}
	
	public function getTitle() {
		return $this->variables['title'];
		//return htmlentities($this->variables['title'], null, 'UTF-8');
	}
	
	public function getText() {
		return $this->variables['text'];
		//return htmlentities($this->variables['text'], null, 'UTF-8');
	}
}