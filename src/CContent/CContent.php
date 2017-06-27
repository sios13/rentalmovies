<?php

class CContent {
	protected $variables;
	private $db;
	private $type;

	public function __construct($variables = array()) {
		$this->variables['title'] = isset($variables->title) ? $variables->title : null;
		$this->variables['slug'] = isset($variables->title) ? $this->slugify($variables->title) : null;
		$this->variables['text'] = isset($variables->data) ? htmlentities($variables->data, null, 'UTF-8') : null;
		$this->variables['category'] = isset($variables->category) ? htmlentities($variables->category, null, 'UTF-8') : null;
		$this->variables['filter'] = isset($variables->filter) ? htmlentities($variables->filter, null, 'UTF-8') : null;
		$this->variables['published'] = isset($variables->published) ? htmlentities($variables->published, null, 'UTF-8') : null;
		$this->variables['id'] = isset($variables->id) ? $variables->id : null;
	}
	
	public function setDb(&$db) {
		$this->db = $db;
	}
	
	public function setType($type) {
		if ($type == 'update') {
			$this->type = 'update';
		} else if ($type == 'insert') {
			$this->type = 'insert';
		} else if ($type == 'delete') {
			$this->type = 'delete';
		}
	}
	
	public function getSql() {
		$sql;
		if ($this->type == 'update') {
			$sql = "
			  UPDATE kmom07_Content SET
				title     = ?,
				slug      = ?,
				url       = NULL,
				data      = ?,
				category  = ?,
				type      = 'post',
				filter    = ?,
				published = ?,
				updated   = NOW()
			  WHERE 
				id = ?
			";
		} else if ($this->type == 'insert') {
			$sql = "
			  INSERT INTO kmom07_Content (title, slug, url, data, category, type, filter, published, created)
			  VALUES (?,?,NULL,?,?,'post',?,?,NOW())
			";
		} else if ($this->type == 'delete') {
			$sql = 'DELETE FROM kmom07_Content WHERE id = ?';
		}
		return $sql;
	}
	
	public function execute() {
		$sql = $this->getSql();
		$this->setVariablesFromPost();
		extract($this->variables);
		
		if ($this->type == 'update') {
			$params = array($title, $slug, $text, $category, $filter, $published, $id);
		} else if ($this->type == 'insert') {
			$params = array($title, $slug, $text, $category, $filter, $published);
		} else if ($this->type == 'delete') {
			$params = array($id);
		}
		$result = $this->db->ExecuteQuery($sql, $params);
		
		$output = null;
		if($result) {
			$output = 'Bloggen sparades.';
		} else {
			$output = 'Bloggen sparades EJ.<br><pre>' . print_r($this->db->ErrorInfo(), 1) . '</pre>';
		}
		return $output;
	}
	
	public function setVariablesFromPost() {
		$this->variables['title'] = isset($_POST['title']) ? empty($_POST['title']) ? null : $_POST['title'] : null;
		$this->variables['slug'] = $this->slugify($_POST['title']);
		$this->variables['text'] = isset($_POST['text']) ? empty($_POST['text']) ? null : $_POST['text'] : null;
		$this->variables['category'] = isset($_POST['category']) ? empty($_POST['category']) ? null : $_POST['category'] : null;
		$this->variables['filter'] = isset($_POST['filter']) ? empty($_POST['filter']) ? null : $_POST['filter'] : null;
		$this->variables['published'] = isset($_POST['published']) ? empty($_POST['published']) ? null : $_POST['published'] : null;
		$this->variables['id'] = $_POST['id'];
	}
	
	public function makeForm() {
		if ($this->type == 'update') {
			$form = "
				<form method=post>
					<fieldset>
					<legend>Editera</legend>
					<p><input type='hidden' name='id' value='{$this->variables['id']}'/></p>
					<p><label>Titel<br/><input type='text' name='title' value='{$this->variables['title']}'/></label></p>
					<p><label>Slug<br/><input type='text' name='slug' value='{$this->variables['slug']}'/></label></p>
					<p><label>Text<br/><textarea rows='17' cols='70' name='text'>{$this->variables['text']}</textarea></label></p>
					<p><label>Kategorier<br/><input type='text' name='category' value='{$this->variables['category']}'/></label></p>
					<p><label>Filter (nl2br,bbcode,link,markdown)<br/><input type='text' name='filter' value='{$this->variables['filter']}'/></label></p>
					<p><label>Publiceringsdatum<br/><input type='text' name='published' value='{$this->variables['published']}'/></label></p>
					<p><input type='submit' name='save' value='Spara'/><input type='reset' value='Återställ'/></p>
					</fieldset>
				</form>
			";
		} else if ($this->type == 'insert') {
			$form = "
				<form method=post>
					<fieldset>
					<legend>Skapa</legend>
					<p><input type='hidden' name='id' value='{$this->variables['id']}'/></p>
					<p><label>Titel<br/><input type='text' name='title' value='{$this->variables['title']}'/></label></p>
					<p><label>Text<br/><textarea rows='17' cols='70' name='text'>{$this->variables['text']}</textarea></label></p>
					<p><label>Kategorier<br/><input type='text' name='category' value='{$this->variables['category']}'/></label></p>
					<p><label>Filter (nl2br,bbcode,link,markdown)<br/><input type='text' name='filter' value='{$this->variables['filter']}'/></label></p>
					<p><label>Publiceringsdatum<br/><input type='datetime-local' name='published' value='{$this->variables['published']}'/></label></p>
					<p><input type='submit' name='save' value='Spara'/><input type='reset' value='Återställ'/></p>
					</fieldset>
				</form>
			";
		} else if ($this->type == 'delete') {
			$form = "
				<form method=post>
					<fieldset>
					<legend>Ta bort</legend>
					<p><input type='hidden' name='id' value='{$this->variables['id']}'/></p>
					<p>".(isset($this->variables['title'])?"Vill du ta bort {$this->variables['title']}?":"")."</p>
					<p><input type='submit' name='delete' value='Ta bort'/></p>
					</fieldset>
				</form>
			";
		}
		
		return $form;
	}
	
	public function getFilter() {
		return $this->variables['filter'];
	}
	
	function slugify($str) {
		$str = mb_strtolower(trim($str));
		$str = str_replace(array('å','ä','ö'), array('a','a','o'), $str);
		$str = preg_replace('/[^a-z0-9-]/', '-', $str);
		$str = trim(preg_replace('/-+/', '-', $str), '-');
		return $str;
	}
}
