<?php

class CDatabaseToTable {
	private $table;
	private $page;
	private $hits;
	
	private $orderby;
	private $order;
	
	public function __construct($table, $columns = array(), $hits = 4, $page = 1, $type = 'standard') {
		$offset = ($page-1)*$hits;
		$max = round((sizeof($table)/$hits)+0.4);
		$this->hits = $hits != null ? $hits : 4;
		$hits = $hits != null ? $hits : 4;
		$hits = sizeof($table)-$offset < $hits ? sizeof($table)-$offset : $hits;
		$this->page = $page != null ? $page : 1;
		
		$this->orderby = isset($_GET['orderby']) ? $_GET['orderby'] : null;
		$this->order = isset($_GET['order']) ? $_GET['order'] : null;
		
		$this->table = "<nav class='content-nav' style='text-align:center;padding:5px;'>" . $this->getHitsPerPage(array(2, 4, 8, 16, 32)) . "</nav>";
		$this->table .= "<nav class='content-nav' style='text-align:center;padding:5px;'>" . $this->getPageNavigation($hits, $this->page, $max) . "</nav>";
		$this->table .= "<table style='margin-left:auto;margin-right:auto;'>";
		$this->table .= "<tr>";
		if ($type == 'movies') {
			$this->table .= "<th></th>";
			$this->table .= "<th>titel " . $this->orderby('title') . "</th>";
			$this->table .= "<th>år " . $this->orderby('year') . "</th>";
			$this->table .= "<th>genre " . $this->orderby('category') . "</th>";
		} else if ($type == 'users') {
			$this->table .= "<th>id " . $this->orderby('id') . "</th>";
			$this->table .= "<th>namn " . $this->orderby('name') . "</th>";
			$this->table .= "<th>typ " . $this->orderby('type') . "</th>";
			$this->table .= "<th></th>";
			$this->table .= "<th></th>";
		} else if ($type == 'news') {
			$this->table .= "<th>id " . $this->orderby('id') . "</th>";
			$this->table .= "<th style='width:200px;'>titel " . $this->orderby('title') . "</th>";
			$this->table .= "<th style='width:180px;'>publicerad " . $this->orderby('published') . "</th>";
			$this->table .= "<th></th>";
			$this->table .= "<th></th>";
		} else if ($type == 'moviesedit') {
			$this->table .= "<th>id " . $this->orderby('id') . "</th>";
			$this->table .= "<th style='width:200px;'>titel " . $this->orderby('title') . "</th>";
			$this->table .= "<th style='width:180px;'>publicerad " . $this->orderby('published') . "</th>";
			$this->table .= "<th></th>";
			$this->table .= "<th></th>";
		} else {
			for ($i = 0; $i < sizeof($columns); $i++) {
				$this->table .= "<th>{$columns[$i]} " . $this->orderby($columns[$i]) . "</th>";
			}
		}
		$this->table .= "</tr>";
		for ($i = $offset; $i < ($offset+$hits); $i++) {
			$this->table .= "<tr>";
			for ($j = 0; $j < sizeof($columns); $j++) {
				if ($type == 'movies') {
					if ($columns[$j] == "image") {
						$this->table .= "<td><nav class='imglinks'><a class='imglinks' href='movies.php?id={$table[$i]->id}'><img src='img.php?src={$table[$i]->image}&amp;subdir=movies&amp;width=100&amp;sharpen&amp;save-as=jpg' alt='{$table[$i]->title}'/></a></nav></td>";
					} else if ($columns[$j] == "title") {
						$this->table .= "<td><h2><a href='movies.php?id={$table[$i]->id}'>{$table[$i]->title}</a></h2>";
						$this->table .= "<p>{$table[$i]->text}</p></td>";
					} else {
						$this->table .= "<td style='width:100px;text-align:center;'>{$table[$i]->$columns[$j]}</td>";
					}
				} else if ($type == 'users') {
					if ($columns[$j] == "uppdatera") {
						$this->table .= "<td style='width:100px;text-align:center;'><a href='admin_users_update.php?name={$table[$i]->name}'>uppdatera</a></td>";
					} else if ($columns[$j] == "radera") {
						$this->table .= "<td style='width:100px;text-align:center;'><a href='admin_users_delete.php?name={$table[$i]->name}'>radera</a></td>";
					} else {
						$this->table .= "<td style='width:100px;text-align:center;'>{$table[$i]->$columns[$j]}</td>";
					}
				} else if ($type == "news") {
					if ($columns[$j] == "uppdatera") {
						$this->table .= "<td style='width:80px;text-align:center;'><a href='content_edit.php?id={$table[$i]->id}'>uppdatera</a></td>";
					} else if ($columns[$j] == "radera") {
						$this->table .= "<td style='width:80px;text-align:center;'><a href='content_delete.php?id={$table[$i]->id}'>radera</a></td>";
					}else if ($columns[$j] == "title") {
						$this->table .= "<td style=';text-align:center;'><a href='content_blog.php?slug={$table[$i]->slug}'>{$table[$i]->title}</a></td>";
					} else {
						$this->table .= "<td style='width:100px;text-align:center;'>{$table[$i]->$columns[$j]}</td>";
					}
				} else if ($type == "moviesedit") {
					if ($columns[$j] == "uppdatera") {
						$this->table .= "<td style='width:80px;text-align:center;'><a href='movies_edit.php?id={$table[$i]->id}'>uppdatera</a></td>";
					} else if ($columns[$j] == "radera") {
						$this->table .= "<td style='width:80px;text-align:center;'><a href='movies_delete.php?id={$table[$i]->id}'>radera</a></td>";
					} else if ($columns[$j] == "title") {
						$this->table .= "<td style=';text-align:center;'><a href='movies.php?id={$table[$i]->id}'>{$table[$i]->title}</a></td>";
					} else {
						$this->table .= "<td style='width:100px;text-align:center;'>{$table[$i]->$columns[$j]}</td>";
					}
				} else {
					$this->table .= "<td style='width:100px;text-align:center;'>{$table[$i]->$columns[$j]}</td>";
				}
			}
			$this->table .= "</tr>";
		}
		$this->table .= "</table>";
		$this->table .= "<p style='width:100%;text-align:center;'>" . $this->getPageNavigation($hits, $this->page, $max) . "</p>";
	}

	function orderby($column) {
		$str = "";
		if ($this->orderby == $column && $this->order) {
			if ($this->order == 'asc') {
				$str .= "<span class='orderby'><a class='selected' href='" . $this->getQueryString(array('orderby' => $column, 'order' => 'asc', 'page' => $this->page, 'hits' => $this->hits)) . "'>&darr;</a>";
				$str .= "<a href='" . $this->getQueryString(array('orderby' => $column, 'order' => 'desc', 'page' => $this->page, 'hits' => $this->hits)) . "'>&uarr;</a></span>";
			} else if ($this->order == 'desc') {
				$str .= "<span class='orderby'><a href='" . $this->getQueryString(array('orderby' => $column, 'order' => 'asc', 'page' => $this->page, 'hits' => $this->hits)) . "'>&darr;</a>";
				$str .= "<a class='selected' href='" . $this->getQueryString(array('orderby' => $column, 'order' => 'desc', 'page' => $this->page, 'hits' => $this->hits)) . "'>&uarr;</a></span>";
			}
		} else {
			$str .= "<span class='orderby'><a href='" . $this->getQueryString(array('orderby' => $column, 'order' => 'asc', 'page' => $this->page, 'hits' => $this->hits)) . "'>&darr;</a>";
			$str .= "<a href='" . $this->getQueryString(array('orderby' => $column, 'order' => 'desc', 'page' => $this->page, 'hits' => $this->hits)) . "'>&uarr;</a></span>";
		}
		return $str;
	}
	
	function getQueryString($options, $prepend='?') {
		// parse query string into array
		$query = array();
		parse_str($_SERVER['QUERY_STRING'], $query);

		// Modify the existing query string with new options
		$query = array_merge($query, $options);

		// Return the modified querystring
		return $prepend . http_build_query($query, '', '&amp;');
	}

	function getHitsPerPage($hits) {
		$nav = "Träffar per sida: ";
		foreach($hits AS $val) {
			if ($val == $this->hits) {
				$nav .= "<a href='" . $this->getQueryString(array('hits' => $val, 'page' => 1)) . "' class='selected'>$val</a> ";
			} else {
				$nav .= "<a href='" . $this->getQueryString(array('hits' => $val, 'page' => 1)) . "'>$val</a> ";
			}
		}
		return $nav;
	}
	
	function getPageNavigation($hits, $page, $max, $min=1) {
		$nav = "";
		if ($this->page == $min) {
			$nav .="<a>&lt;&lt; </a>";
			$nav .="<a>&lt; </a>";
		} else {
			$nav .= "<a href='" . $this->getQueryString(array('page' => $min)) . "'>&lt;&lt;</a> ";
			$nav .= "<a href='" . $this->getQueryString(array('page' => ($page > $min ? $page - 1 : $min) )) . "'>&lt;</a> ";
		}

		for($i=$min; $i<=$max; $i++) {
			if ($i == $this->page) {
				$nav .= "<a href='" . $this->getQueryString(array('page' => $i)) . "' class='selected'>$i</a> ";
			} else {
				$nav .= "<a href='" . $this->getQueryString(array('page' => $i)) . "'>$i</a> ";
			}
		}
		
		if ($this->page == $max) {
			$nav .= "<a> &gt;</a>";
			$nav .= "<a> &gt;&gt;</a>";
		} else {
			$nav .= "<a href='" . $this->getQueryString(array('page' => ($page < $max ? $page + 1 : $max) )) . "'>&gt;</a> ";
			$nav .= "<a href='" . $this->getQueryString(array('page' => $max)) . "'>&gt;&gt;</a> ";
		}
		return $nav;
	}
	
	public function getTable() {
		return $this->table;
	}
}