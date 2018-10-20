<?php

namespace App\Legacy;

class Pagination {

	private $_items_per_page,
		$_table,
		$_page,
		$_pages = 1,
		$_pagelinks_count = 5,
		$_items_count = 0,
		$_user_count,
		$_limit_down = 0,
		$_limit_up,
		$_filters,
		$_db,
		$_errors = [];

	/**
	 * Accepts SQL table name to count for items or a custom count from user,
	 * The number of page to display
	 * And how many items per page to display, 20 as default
	 *
	 * @param mixed $table_or_count Table name to count on as string, or count as integer
	 * @param int $page Page to display, based on how many items per page
	 * @param int $items_per_page Items to display on each page
	 * @param array $filters Values to preserve between pages
	 * @return
	 */
	public function __construct (
		$table_or_count = null,
		$page = null,
		$items_per_page = null,
		$filters = null
	) {

		// MESSY Get global database connection and store it
		$pdo = \App\Legacy\Database::getInstance(true);
		$this->_db = $pdo;

		// Check if user passed page number, then validate or default to 1
		$this->_page = isset($page) ? (int)$page : 1;

		// Check if user passed how many items per page to visualize, default to 20
		$this->_items_per_page = isset($items_per_page) ? $items_per_page : 20;

		// Check if filters were passed
		$this->_filters = isset($filters) ? $filters : [];

		// Check if table was provided
		if (isset($table_or_count)) {

			// Check if string was passed (table name)
			if (is_string($table_or_count)) {

				// Get all table names in database
				$tabnames = $this->get_table_names();

				// Validate string
				if (in_array($table_or_count, $tabnames)) {

					// Set table name to count on
					$this->_table = $table_or_count;
				}
			}

			// Check if integer was passed (custom count based on external SQL)
			elseif (is_int($table_or_count)) {

				// Flag user count was passed
				$this->_user_count = true;

				// Change user count
				$this->_items_count = $table_or_count;
			}

			// Calculate other parameters
			$this->calculate();
		}
	}


	/**
	 * Returns list of table names from database
	 */
	private function get_table_names () {

		try {
			// Get table names from db
			$query = $this->_db->query("SHOW TABLES");
			// Fetch results
			$rs = $query->fetchAll(\PDO::FETCH_NUM);
		}
		catch (\PDOException $e) {
			$this->error("Couldn't get table names from db.");
		}

		// Check if results
		if ($rs > 0) {
			$tabnames = array();
			// Loop to format list as array
			foreach($rs as $tabname) {
				$tabnames[] = $tabname[0];
			}

			// Return list of table names
			return $tabnames;
		}
		else {
			// Return error
			return false;
		}
	}


	/**
	 * Counts items from db and stores it into $this->_items_count
	 *
	 * @return void
	 */
	private function count () {

		// Check if user did not already provide custom items count
		if (!isset($this->_user_count)) {

			try {
				// Query the database
				$query = $this->_db->query("SELECT COUNT(*) as count FROM {$this->_table}");

				// Fetch results
				$rs = $query->fetchAll(\PDO::FETCH_ASSOC);
			}
			catch (\PDOException $e) {
				$this->error("Couldn't execute query on db. --- ".$e->getMessage());
			}

			// Check if count is still null
			if (isset($rs) AND $rs > 0) {

				// Return items count
				return $rs[0]['count'];
			}
			else {
				// Add error
				$this->error("No result returned from db, can't count items.");

				// Return error
				return false;
			}
		}
		else {
			// Return items count that user provided
			return $this->_items_count;
		}
	}


	/**
	 * Utilizes user input and calculates all other parameters needed to paginate
	 *
	 * @return void
	 */
	private function calculate () {

		// Count items from db
		$this->_items_count = $this->count();

		// Check if count is valid
		if ($this->_items_count > 0) {

			// Calculate SQL lower limit (LIMIT clause, first value)
			($this->_page == 1)
				? $this->_limit_down = 0
				: $this->_limit_down = ($this->_page - 1) * $this->_items_per_page;

			// Calculate SQL upper limit (LIMIT clause, second value)
			($this->_page * $this->_items_per_page < $this->_items_count)
				? $this->_limit_up = $this->_page * $this->_items_per_page
				: $this->_limit_up = $this->_items_count;

			// Get number of pages
			$this->_pages = ceil($this->_items_count / $this->_items_per_page);
		}
	}


	/**
	 * Generates page links to display
	 */
	public function pagelinks () {

		// If first page, disable Back, else set Back equal to page-1
		$back = ($this->_page == 1) ? 1 : $this->_page - 1;
		$back_disable = ($this->_page == 1) ? " disabled" : "";

		// If last page, disable Next, else set Next equal to page+1 
		$next = ($this->_page == $this->_pages) ? $this->_pages : $this->_page + 1;
		$next_disable = ($this->_page == $this->_pages) ? " disabled" : "";

		// Base pagelinks (ex.: 7,8,9,10,11)
		$pagelinks = [
			 $this->_page - 2
			,$this->_page - 1
			,$this->_page
			,$this->_page + 1
			,$this->_page + 2
		];

		// Fix left, ex.: -1,0,1,2,3 => 1,2,3,4,5
		while ($pagelinks[0] < 1) {

			// Loop on pagelinks to increment them
			for ($i = 0, $len = count($pagelinks); $i < $len; $i++) {
				$pagelinks[$i]++;
			}
		}

		// Display fewer pagelinks when pages are less than usual displayed (5)
		if ($this->_pages < $this->_pagelinks_count) {

			// Extract sub array removing unwanted pagelinks
			$pagelinks = array_slice($pagelinks, 0, $this->_pages);
		}

		// Fix right
		while (end($pagelinks) > $this->_pages) {

			// Loop on pagelinks to decrement them
			for ($i = 0, $len = count($pagelinks); $i < $len; $i++) {
				$pagelinks[$i]--;
			}
		}

		// HTML VIEW
		// --------------------------------------------

		// Form container, Back and First buttons
		$html = "<form method='post' action=''><div class='btn-group'><!-- Back --><button type='submit' name='page' value='{$back}' class='btn btn-default btn-sm'{$back_disable}>&laquo;</button><!-- First --><button type='submit' name='page' value='1' class='btn btn-default btn-sm'{$back_disable}>First</button><!-- Page links -->";

		// Print actual pagelinks after Back and First controls
		foreach ($pagelinks as $link) {
			$crumb = ($link == $this->_page) ? 'primary' : 'default';
			$html .= "<button type='submit' name='page' value='{$link}' class='btn btn-{$crumb} btn-sm'>{$link}</button>";
		}

		// Last and Next buttons
		$html .= "<!-- /Page links --><!-- Last --><button type='submit' name='page' value='{$this->_pages}' class='btn btn-default btn-sm'{$next_disable}>Last</button><!-- Next --><button type='submit' name='page' value='{$next}' class='btn btn-default btn-sm'{$next_disable}>&raquo;</button></div>";

		// Print filters into form as well, if provided
		if (isset($this->_filters)) {

			// Loop into filters
			foreach($this->_filters as $filter) {

				// Check if filter is set
				if (\App\Legacy\Input::exists($filter) AND !is_array($filter)) {;

					// Print single filter value
					$html .= "<!-- Sticky value --><input type='hidden' name='{$filter}' value='".\App\Legacy\Input::get($filter)."' />";
				}
			}
		}

		// Close pagination form
		$html .= '</form>';

		// Return HTML
		return $html;
	}


	/**
	 * Returns offsets for LIMIT clause in SQL statement
	 */
	public function offsets () {
		return [
			'down' => $this->_limit_down
			,'up' => $this->_limit_up
		];
	}

	/**
	 * Returns TRUE if no errors or array of errors, if any
	 */
	public function errors () {
		return empty($this->_errors) ? true : $this->_errors;
	}

	/**
	 * Add error to the list of errors
	 */
	private function error ($text) {
		$this->_errors[] = $text;
	}


	/**
	 * Returns a string with all its properties listed for debugging
	 */
	public function debug () {
		return '<pre>PAGINATION DEBUG: <br />'.print_r($this, true).'</pre>';
	}


	/**
	 * Lets user add SQL filters after instantiation
	 */
	public function set_filters ($filters) {

		// Check if user passed an array of filters
		if (is_array($filters)) {
			$this->_filters = $filters;
		}
		else {
			$this->error("Couldn't add filters, you must provide an array.");
		}
	}

	/**
	 * Return string that tracks items (ex.: 0-50 out of 123)
	 */
	public function tracking () {
		return "{$this->_limit_down}-{$this->_limit_up} out of {$this->_items_count}";
	}
}
