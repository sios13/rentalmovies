<?php

class CBlog extends CContent {
	public function __construct($variables) {
		CContent::__construct($variables);
	}
	
	public function getOutput() {
		$output = "
			<h2><a href='content_blog.php?slug={$this->variables['slug']}'>{$this->variables['title']}</a></h2>
			{$this->variables['text']}
		";
		return $output;
	}
	
	public function getTitle() {
		$output = "<h2 style='padding-top:10px;'><a href='content_blog.php?slug={$this->variables['slug']}'>{$this->variables['title']}</a></h2>";
		return $output;
	}
	
	public function getText() {
		$output = "{$this->variables['text']}";
		return $output;
	}
	
	public function getSlug() {
		$output = "{$this->variables['slug']}";
		return $output;
	}
}