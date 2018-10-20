<?php
	
// Declare rulings array
$rulings = array();


// DEFAULTS
// -----------------------------------------------------
$orderby = 'sets_id asc, cardnum asc';


// SORTING
// ----------------------------------------------------------------

// Get sorting selected by user
if (isset($_POST['sort_rulings'])) {
	$sort = [
		'0' => 'sets_id, cardnum',
		'name' => 'cardname',
		'date' => 'created DESC, sets_id, cardnum',
		'edited' => 'FIELD(is_edited, 1, 0), sets_id, cardnum',
		'errata' => 'FIELD(is_errata, 1, 0), sets_id, cardnum'
	];
	
	// Get ORDERBY value for SQL statement
	$orderby = $sort[$_POST['sort_rulings']];
}
else {

	// Default ORDERBY value
	$orderby = 'sets_id, cardnum';
}


// PAGINATION
// ---------------------------------------------------------------------

// Set cards per page to view
$items_per_page = 40;

// Get requested page
$page = (isset($_POST['page']) AND $_POST['page'] > 0) ? (int)$_POST['page'] : 1;

// Instantiate Pagination class
$pagination = new \App\Legacy\Pagination(
	'rulings'
	,$page
	,$items_per_page
	,['sort_rulings', 'no_mobile']
);

// Get offsets
$offsets = $pagination->offsets();
$limit_down = $offsets['down'];


// QUERY THE DATABASE
// ---------------------------------------------------------------------

// Build SQL statement
$sql = "SELECT
			rulings.id as id
			,cardcode
			,cardname
			,created
			,is_edited
			,is_errata
			,SUBSTRING(ruling, 1, 75) as ruling
		FROM
			rulings INNER JOIN cards ON cards_id = cards.id
		ORDER BY
			{$orderby}
		LIMIT
			{$limit_down}, {$items_per_page}";

// Execute query on DB
try {
	$query = $pdo->query($sql);
	$result = $query->fetchAll(\PDO::FETCH_ASSOC);
}
catch (\PDOException $e) {
	die($e->getMessage());
}


// FORMAT RESULTS
// ---------------------------------------------------------------------

// Check for empty results
if (!empty($result)) {
	
	// Convert booleans to 'Yes' or 'No' for presentation
	$bool = [
		0 => 'No'
		,1 => '<strong style="color:red;">Yes</strong>'
	];
	
	// Store results in $rulings array
	foreach($result as $row) {
		$rulings[] = array (
			'id' => $row['id']
			,'code' => $row['cardcode']
			,'name' => $row['cardname']
			,'created' => $row['created']
			,'is_edited' => $bool[$row['is_edited']]
			,'is_errata' => $bool[$row['is_errata']]
			,'ruling' => $row['ruling'] . '[...]'
		);
	}
}
