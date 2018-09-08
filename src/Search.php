<?php

namespace App;

class Search
{
    private $db;           // Database connection
    private $sqlPartials;  // Strings to be assembled into final SQL
    private $sqlStatement; // Complete SQL statement as string
    private $cards;        // Results from db
    private $cardsCount;   // Results count from db
    public $f = [];        // Search filters
    public $isPagination;  // Pagination flag (results > default limit)
    public $areResults;    // Flag for results from database
    public $allowed;       // All and the only allowed search filters
    public $fields;        // Fields to be returned

    /**
     * Init database and default values
     *
     * @return void
     */
    public function __construct()
    {
        // Initialize database connection
        $this->db = \App\Database::getInstance();

        // Define allowed filters
        $this->allowed = [
            "do",
            "q",
            "exact",
            "infields",
            "exclude",
            "sort",
            "sortdir",
            "format",
            "cardtype",
            "backside",
            "divinity",
            "setcode",
            "attributes",
            "attrmulti", // Legacy
            "attribute_multi",
            "no_attribute_multi",
            "attrselected", // Legacy
            "attribute_selected",
            "totalcost",
            "xcost",
            "rarity",
            "race",
            "atk-operator",
            "atk",
            "def-operator",
            "def",
            "page" // Pagination-related
        ];

        // Fields to return
        $this->fields = [
            "cards.id",
            "cards.cardcode",
            "cards.cardname",
            "cards.cardnum",
            "cards.setcode",
            "cards.image_path",
            "cards.thumb_path"
        ];

        // Define default values for SQL partials
        $this->sqlPartials = [
            "fields"  => implode(",", $this->fields),
            "table"   => "cards",
            "filter"  => "TRUE",
            "sorting" => "setnum DESC, cardnum ASC",
            "limit"   => APP_RESULTS_LIMIT + 1,
            "offset"  => 0
        ];
    }

    /**
     * Checks if a filter exists, then returns it
     *
     * @param string $name of the element
     * @return mixed string or array of strings
     */
    public function getFilter($name = null)
    {
        return isset($this->f[$name]) ? $this->f[$name] : "";
    }

    /**
     * Returns sanitized and validated search filters to be processed
     *
     * @param array Usually $_GET
     * @return array
     */
    public function getFilters($inputs = null)
    {
        // Return stored value if any
        if (!empty($this->f)) {
            return $this->f;
        }

        // ERROR: No inputs are passed
        if (empty($inputs)) {
            return [];
        }

        // Get allowed filters
        $allowed = array_intersect(array_keys($inputs), $this->allowed);

        // ERROR: No inputs after whitelisting
        if (empty($allowed)) {
            return [];
        }

        // Escape HTML code for every filter
        foreach ($allowed as &$key) {

            // Alias input value
            $value =& $inputs[$key];

            // Array parameter
            if (is_array($value)) {

                // Temporary value array
                $value_array = [];

                // Loop on elements (subvalues)
                foreach ($value as $subkey => &$subvalue) {

                    // Store subvalues into temp array
                    $value_array[$subkey] = trim(htmlspecialchars($subvalue, ENT_QUOTES, 'UTF-8'));
                }

                // Store value array
                $this->f[$key] = $value_array;

            // Single value parameter
            } else {
                $this->f[$key] = trim(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
            }
        }

        // Return filters
        return $this->f;
    }

    /**
     * Adds a field to the list of returned fields. Can have an alias
     *
     * @param $field string Name of the field
     * @param $alias string Different (usually shorter) name for the field
     * @return void
     */
    public function addField($field, $alias = null)
    {
        $as = isset($alias) ? " as {$alias}" : "";
        $this->sqlPartials["fields"] .= ", {$field}{$as}";
    }

    /**
     * Adds a table, so that it performs an INNER JOIN
     *
     * @param $table string Table name to be added
     * @param $condition array Two strings, they're the fields to be joined on
     * @return void
     */
    public function addTable($table, $fields)
    {
        // Change FROM clause
        $this->sqlPartials['table'] .= " INNER JOIN {$table} ON {$fields[0]} = {$fields[1]}";
    }

    /**
     * Assembles the final SQL
     *
     * @return string
     */
    public function getSQL()
    {
        // Return stored value if any
        if (!empty($this->sqlStatement)) {
            return $this->sqlStatement;
        }

        // Generate and store assembled SQL statement
        $this->sqlStatement = "SELECT {$this->sqlPartials['fields']}
            FROM {$this->sqlPartials['table']}
            WHERE {$this->sqlPartials['filter']}
            ORDER BY {$this->sqlPartials['sorting']}
            LIMIT {$this->sqlPartials['limit']}
            OFFSET {$this->sqlPartials['offset']}";

        // Return assembled statement
        return $this->sqlStatement;
    }

    /**
     * Queries the database with assembled SQL and returns the result
     *
     * @param bool $overwrite Force to recalculte (overwrite) stored value
     * @return array cards
     */
    public function getCards($overwrite = false)
    {
        // Return stored value if any
        if (!$overwrite && !empty($this->cards)) {
            return $this->cards;
        }

        // Get results from db
        $this->cards = $this->db->get($this->getSQL());

        // Check if there were results
        if (empty($this->cards)) {
            \App\FoWDB::notify("No results. Please try changing your searching criteria.", "danger");
            $this->areResults = false;
            return false;
        }

        $this->areResults = true;

        // Check if results are more than default limit
        if (count($this->cards) > APP_RESULTS_LIMIT) {

            // Flag: pagination needed
            $this->isPagination = true;

            // Remove last extra element (needed just to trigger pagination)
            array_pop($this->cards);

        // Results less than default limit, no pagination needed
        } else {

            // Flag: pagination not needed
            $this->isPagination = false;
        }

        // Return results array
        return $this->cards;
    }

    /**
     * Counts how many cards are returned
     *
     * @return int
     */
    public function getCardsCount($overwrite = false)
    {
        // Return stored value if any
        if (!$overwrite && !empty($this->cardsCount)) {
            return $this->cardsCount;
        }

        // Get count from database
        $this->cardsCount = $this->db->getCount('cards', $this->sqlPartials['filter']);

        // Return cards count
        return $this->cardsCount;
    }

    /**
     * Processes search filters, updates SQL array then returns it
     * Ex.: ['fields' => .., 'where' => .., 'orderby' => ..]
     *
     * @param array $inputs Search filters
     * @return array SQL array containing partials of SQL statement
     */
    public function processFilters($inputs = null) {

        // ERROR: No inputs provided!
        if (!isset($inputs)) {
            return false;
        }

        // Sanitize and parse filters (if not already)
        if (empty($this->f)) {
            $this->getFilters($inputs);
        }

        // SEARCH BAR =========================================================

        if (isset($this->f['q']) && !empty($this->f['q'])) {
            
            // Copy HTML-escaped query in a separate variable to process it
            $q = $this->f['q'];

            // ESCAPE QUOTES FOR GOD'S SAKE!
            $q = str_replace(['&#039;','&quot;'], ['\'', "\\\""], $q);

            // PRESERVE WHITE SPACE IN BACKTICKS ------------------------------

            // Explode query by backticks (they preserve whitespace in FoWDB)
            $bits = explode("`", $q);

            // Replace whitespace with a flag (\s)
            // Preserves whitespace inside backticks
            for ($i = 1, $len = count($bits); $i < $len; $i += 2) {
                $bits[$i] = str_replace(" ", "\s", $bits[$i]);
            }

            // Implode string again
            $q = implode($bits);

            // Build single SQL string to be searched by LIKE operator
            // Replace space with % and \s flag with space in this order
            $q = str_replace([" ", "\s"], ["%", " "], $q);
            

            // SEARCH ONLY IN SOME FIELDS -------------------------------------

            // Check if user selected some query fields
            if (isset($this->f['infields'])) {

                // Holds selected fields
                $q_fields = array();

                // Check if inputs "infields" array has any unallowed element!
                if (empty(array_diff($this->f['infields'], [
                    'cardname',
                    'cardcode',
                    'cardtext',
                    'subtype_race',
                    'flavortext',
                    'artist_name',
                ]))) {

                    // Initialize empty fields
                    foreach ($this->f['infields'] as &$val) {
                        $q_fields["cards." . $val] = '';
                    }
                }
            }

            // No infields restriction, initialize ALL (default)
            else {
                $q_fields = array(
                    'cardname' => '',
                    'cardcode' => '',
                    'cardtext' => '',
                    'subtype_race' => '',
                );
            }

            // EXACT MATCH ----------------------------------------------------

            // Check if user wants an Exact Match
            if (isset($this->f['exact'])) {

                // Loop on query fields that user wants and add the LIKE operator
                foreach ($q_fields as $field => &$useless) {

                    // Insert whole searchbox query for this query field
                    // Ex.: "thing to search" becomes LIKE "%thing%to%search"
                    $q_fields[$field] .= $field . " LIKE \"%".$q."%\"";
                }
            }

            // No Exact Match selected
            else {

                // Explode query terms by %
                $keywords = explode("%", $q);

                // Loop on keywords
                foreach($keywords as &$keyword) {

                    // Loop on query fields
                    foreach($q_fields as $field => &$useless) {

                        ($keyword != end($keywords))
                            ? $q_fields[$field] .= $field . " LIKE \"%{$keyword}%\" OR "
                            : $q_fields[$field] .= $field . " LIKE \"%{$keyword}%\"";
                    }
                }
            }

            // OVERWRITE RACE IF ALREADY SET INTO FILTERS PANEL
            // -------------------------------------------------------------------------
            // Check if specific subtype_race filter (Race/Trait input) was provided
            if (isset($this->f['race'])) {

                // Specific filter overwrites generic searchbox terms, if present
                unset($q_fields['race']);

                // Check if selected fields are now empty
                // (Means the user just selected Races and that was overwritten above,
                // so reset whole searchbox query)
                if (empty($q_fields)) {
                    $sql_q = 'TRUE';
                }
            }

            // Build final SQL code for searchbox query
            $sql_q = "((" . implode(") OR (", $q_fields) . "))";
        }

        // No searchbox text was passed!
        else {
            $sql_q = "TRUE";
        }


        // PROCESS SEARCH FILTERS =============================================

        // Will hold all "Filters" section search filters
        $_sql_f = [];

        // FILTER --- FORMAT --------------------------------------------------
        if (isset($this->f['format'])) {

            // Get format helper
            $formats = \App\Helpers::get('formats');

            // Get clusters list for this format
            $clusters = $formats['list'][$this->f['format']]['clusters'];

            // Add all clusters for this format
            $_sql_f[] = 'block IN('.implode(",", $clusters).')';
        }

        // FILTER --- EXCLUDE -------------------------------------------------
        if (isset($this->f['exclude'])) {

            // Get all excludes
            foreach ($this->f['exclude'] as &$exclude) {
                
                // Decide what to exclude for every exclude selected
                switch ($exclude) {

                    // Exclude spoilers
                    case 'spoilers':
                        $_sql_f[] = "NOT(setcode IN (\"" .
                            implode("\",\"", \App\Helpers::get('spoiler')['codes'])
                            ."\"))";
                        break;

                    // Exclude alternates
                    case 'alternates':
                        $_sql_f[] = 'NOT(narp = 1) ';
                        break;
                        
                    // Exclude reprints
                    case 'reprints':
                        $_sql_f[] = 'NOT(narp = 2) ';
                        break;
                        
                    // Exclude normals
                    case 'basics':
                        $_sql_f[] = 'NOT(narp = 0) ';
                        break;
                }
            }
        }

        // FILTER --- SET CODE ------------------------------------------------
        if (isset($this->f['setcode'])) {

            // Multiple sets selected
            if (is_array($this->f['setcode'])) {

                // Get rid of 0 default setcode (Choose a set..) if present
                if (in_array("0", $this->f['setcode'])) {
                    array_splice($this->f['setcode'], 0, 1);
                }
                
                // Build SQL portion of setcode
                $_sql_f[] = "(setcode =\""
                          . implode("\" OR setcode = \"", $this->f['setcode'])
                          . "\")";
            
            // Single set selected
            } else {

                // Check if set is not default 0 (Choose a set..)
                if ($this->f['setcode'] != "0") {

                    // Build SQL portion of setcode
                    $_sql_f[] = "setcode = \"{$this->f['setcode']}\"";
                }
            }
        }

        // FILTER --- NUMBER --------------------------------------------------
        if (isset($this->f['cardnum']) AND (int) $this->f['cardnum'] != 0) {
            $_sql_f[] = "cardnum = {$this->f['cardnum']}";
        }


        // FILTER --- RACE OR TRAIT -------------------------------------------
        if (isset($this->f['race']) AND !empty($this->f['race'])) {

            // Alias the input for readability
            $race =& $this->f['race'];

            // REGEXP
            // It's better than LIKE because it excludes "Four Sacred Beasts" cards
            // when searching for "Beast", but it can search for "Human/Beast" cards!
            $_sql_f[] = "subtype_race REGEXP \"(^|\/){$race}(\/|$)\"";
        }


        // FILTER ---  ATTRIBUTE ----------------------------------------------
        if (isset($this->f['attributes'])) {

            // Include only selected attributes
            if (isset($this->f['attrselected']) || isset($this->f['attribute_selected'])) {
                $_sql_f[] = "((attribute LIKE \"%"
                          . implode("%\" AND attribute LIKE \"%", $this->f['attributes'])
                          . "%\") OR attribute = \""
                          . implode("\" OR attribute = \"", $this->f['attributes'])
                          ."\")";
            }

            // Include unselected attributes (includes other multi-attribute cards if attrmulti is set)
            else {
                $_sql_f[] = "(attribute LIKE \"%"
                          . implode("%\" OR attribute LIKE \"%", $this->f['attributes'])
                          . "%\")";
            }
        }

        // FILTER --- ONLY MULTI-ATTRIBUTE ------------------------------------
        if (isset($this->f['attribute_multi']) || isset($this->f['attrmulti'])) {
            $_sql_f[] = "attribute LIKE \"%/%\"";
        }

        // FILTER --- NO MULTI-ATTRIBUTE --------------------------------------
        if (isset($this->f['no_attribute_multi'])) {
            $_sql_f[] = "attribute NOT LIKE \"%/%\"";
        }

        // FILTER --- TYPE ----------------------------------------------------
        if (isset($this->f['cardtype'])) {

            // Game-specific: Chant will add also Spell:Chant-* old types to search results
            if (in_array("Addition", $this->f['cardtype'])) {
                $this->f['cardtype'] = \App\FoWDB_Array::union(
                    $this->f['cardtype'], [
                        "Addition:Resonator",
                        "Addition:J/Resonator",
                        "Addition:Ruler/J-Ruler",
                        "Addition:Field"
                    ]
                );
            }

            // // Game-specific: Chant/Rune are chants as well
            // if (in_array("Chant/Rune", $this->f['cardtype'])) {
            //     $this->f['cardtype'] = \App\FoWDB_Array::union(
            //         $this->f['cardtype'], [
            //             "Chant",
            //             "Rune"
            //         ]
            //     );
            // }

            // Game-specific: Rune are Chant/Rune as well
            if (in_array("Rune", $this->f['cardtype'])) {
                $this->f['cardtype'] = \App\FoWDB_Array::union(
                    $this->f['cardtype'], [
                        "Chant/Rune",
                        "Rune"
                    ]
                );
            }

            // Game-specific: Chant will add also Spell:Chant-* old types to search results
            if (in_array("Chant", $this->f['cardtype'])) {
                $this->f['cardtype'] = \App\FoWDB_Array::union(
                    $this->f['cardtype'], [
                        "Spell:Chant",
                        "Spell:Chant-Instant",
                        "Spell:Chant-Standby",
                        "Chant/Rune"
                    ]
                );
            }

            // Add to SQL   
            $_sql_f[] = "(cardtype = \""
                      . implode("\" OR cardtype = \"", $this->f['cardtype'])
                      . "\")";
        }


        // FILTER --- BACKSIDE ------------------------------------------------
        if (isset($this->f['backside'])) {
            
            $map = [
                "no" => 0, // No backside
                "j"  => 1, // J-ruler
                "sh" => 2, // Shift
                "jj" => 3, // Colossal J-ruler
                "in" => 4  // Inverse
            ];

            // Legacy single-value input
            if (!is_array($this->f['backside'])) {
                $this->f['backside'] = [$this->f['backside']];
            }

            $_sql_f[] = "(backside = " . implode(" OR backside = ", array_map(
                function($i) use ($map) { return $map[$i]; },
                array_filter(
                    $this->f['backside'],
                    function($i) use ($map) { return isset($map[$i]); }
                )
            )).")";
        }

        // FILTER --- BACKSIDE ------------------------------------------------
        if (isset($this->f['divinity'])) {
            $_sql_f[] = "(divinity = "
                      . implode(" OR divinity = ", $this->f['divinity'])
                      . ")";
        }


        // FILTER --- FREE COST -----------------------------------------------
        if (isset($this->f['freecost'])) {
            $_sql_f[] = "(freecost = "
                      . implode(" OR freecost = ", $this->f['freecost'])
                      . ")";
        }

        // FILTER --- TOTAL COST ----------------------------------------------
        if (isset($this->f['totalcost'])) {
            $_sql_f[] = "(totalcost = "
                      . implode(" OR totalcost = ", $this->f['totalcost'])
                      . ")";
        }

        // FILTER --- FREE COST AS X ------------------------------------------
        if (isset($this->f['xcost'])) {
            $_sql_f[] = "freecost < 0";
        }


        // FILTER --- ATTACK --------------------------------------------------
        $atkdef = [
            'lessthan'    => '<',
            'equals'      => '=',
            'greaterthan' => '>'
        ];

        if (isset($this->f['atk']) && (int) $this->f['atk'] != 0) {
            $sign = $atkdef[$this->f['atk-operator']];
            $_sql_f[] = "atk {$sign} {$this->f['atk']}";
        }


        // FILTER --- DEFENSE -------------------------------------------------
        if (isset($this->f['def']) AND (int) $this->f['def'] != 0) {
            $sign = $atkdef[$this->f['def-operator']];
            $_sql_f[] = "def {$sign} {$this->f['def']}";
        }


        // FILTER --- RARITY --------------------------------------------------
        if (isset($this->f['rarity'])) {
            $_sql_f[] = "(rarity = \""
                      . implode("\" OR rarity = \"", $this->f['rarity'])
                      . "\")";
        }


        // Build final Filters quuery (if no filter set, bypass it with TRUE)
        $sql_f = empty($_sql_f) ? 'TRUE' : implode(" AND ", $_sql_f);

        // Prevent magic stones in the results unless specified
        // (setcode, cardnum, cardtype or rarity is set)
        if (
            !(
                isset($this->f['setcode']) OR
                isset($this->f['cardnum']) OR
                isset($this->f['cardtype']) OR
                isset($this->f['rarity'])
            )
        ) {
            $_sql_f[] = "NOT(cardtype = \"Magic Stone\")";
        }

        // FILTER --- LIMIT and OFFSET ----------------------------------------
        if (isset($this->f['page'])) {
            $p = (int) $this->f['page'];
            $this->sqlPartials['offset'] = ($p - 1) * APP_RESULTS_LIMIT;
        }

        // SORTING ============================================================
        if (
            isset($this->f['sort']) &&
            in_array($this->f['sort'], array_keys(\App\Helpers::get('sortfields'))) &&
            $this->f['sort'] != "default"
        ) {
            // Get sorting direction
            $sortDir = (isset($this->f['sortdir']) AND $this->f['sortdir'] == 'desc') ? 'DESC' : 'ASC';

            switch ($this->f['sort']) {
                
                case 'attribute':
                    $sortField = "FIELD(attribute,'w','r','u','g','b','v')";
                    break;

                case 'rarity':
                    $sortField = "FIELD(rarity,'c','u','r','sr','pr','s')";
                    break;

                case 'cardtype':
                    $typesList = implode("','", \App\Helpers::get('types'));
                    $sortField = "FIELD(cardtype,'{$typesList}')";
                    break;
                    
                default: // No custom sorting for other inputs
                    $sortField = $this->f['sort'];
                    break;
            }

            // Get default sorting
            $default = $this->sqlPartials['sorting'];

            // Set a new sorting
            $this->sqlPartials['sorting'] = "{$sortField} {$sortDir}, {$default}";
        }

        // Build final Filters quuery (if no filter set, bypass it with TRUE)
        $sql_f = empty($_sql_f) ? 'TRUE' : implode(" AND ", $_sql_f);

        // Set the final WHERE clause
        $this->sqlPartials['filter'] = "{$sql_f} AND {$sql_q}";

        // Return complete SQL array to be turned into a statement
        return $this->sqlPartials;
    }
}
