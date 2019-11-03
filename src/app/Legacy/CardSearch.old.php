<?php

namespace App\Legacy;

use App\Utils\Arrays;
use App\Services\Database\Database;
use App\Utils\Bitmask;
use App\Legacy\CardSearchProcessorsTrait;

class CardSearch
{
    use CardSearchProcessorsTrait;

    private $db;           // Database connection
    private $sqlPartials;  // Strings to be assembled into final SQL
    private $sqlStatement; // Complete SQL statement as string
    private $cards;        // Results from db
    private $cardsCount;   // Results count from db
    public $f = [];        // Search filters
    public $isPagination;  // Pagination flag (results > default limit)
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
        $this->db = Database::getInstance();

        // Define allowed filters
        $this->allowed = [
            'artist',
            'atk',
            'atk-operator',
            'attribute_multi',
            'attribute_selected',
            'attribute',
            'backside',
            'def',
            'def-operator',
            'divinity',
            'exact',
            'exclude',
            'format',
            'infields',
            'no_attribute_multi',
            'q',
            'quickcast',
            'race',
            'rarity',
            'set',
            'sort',
            'sortdir',
            'total_cost',
            'type',
            'type_selected',
            'xcost',
            
            'page', // Pagination-related
            'do', // LEGACY
            'attrmulti', // LEGACY
            'attrselected', // LEGACY
            'attributes', // LEGACY
            'setcode', // LEGACY
        ];

        // Fields to return
        $this->fields = [
            'id',
            'sets_id',
            'num',
            'code',
            'name',
            'image_path',
            'thumb_path'
        ];

        // Define default values for SQL partials
        $this->sqlPartials = [
            'fields'  => implode(',', $this->fields),
            'table'   => 'cards',
            'filter'  => 'TRUE',
            'sorting' => 'clusters_id DESC, sets_id DESC, num ASC',
            'limit'   => config('db.results.limit') + 1,
            'offset'  => 0
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
        if (!empty($this->f)) return $this->f;

        // ERROR: No inputs are passed
        if (empty($inputs)) return [];

        // Get allowed filters
        $allowed = array_intersect(array_keys($inputs), $this->allowed);

        // ERROR: No inputs after whitelisting
        if (empty($allowed)) return [];

        // Escape HTML code for every filter
        foreach ($allowed as $key) {

            // Alias input value
            $value = &$inputs[$key];

            // Array parameter
            if (is_array($value)) {

                // Temporary value array
                $value_array = [];

                // Loop on elements (subvalues)
                foreach ($value as $subkey => &$subvalue) {

                    // Store subvalues into temp array
                    $escaped = htmlspecialchars($subvalue, ENT_QUOTES, 'UTF-8');
                    $value_array[$subkey] = trim($escaped);
                }

                // Store value array
                $this->f[$key] = $value_array;

            // Single value parameter
            } else {
                $escaped = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                $this->f[$key] = trim($escaped);
            }
        }

        return $this->f;
    }

    /**
     * Assembles the final SQL
     *
     * @return string
     */
    public function getSQL(): string
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
    public function getCards($overwrite = false): array
    {
        // Return stored value if any
        if (!$overwrite && !empty($this->cards)) {
            return $this->cards;
        }

        // Get results from db
        $this->cards = $this->db->rawSelect($this->getSQL());

        // Check if results are more than default limit
        if (count($this->cards) > config('db.results.limit')) {

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
        $this->cardsCount = $this->db->rawCount(
            'cards',
            $this->sqlPartials['filter']
        );

        // Return cards count
        return $this->cardsCount;
    }

    /**
     * Processes search filters, updates SQL array then returns it
     * Ex.: ['fields' => .., 'where' => .., 'orderby' => ..]
     *
     * @param array $inputs Search filters
     * @return CardSearch
     */
    public function processFilters($inputs = null): CardSearch
    {
        // ERROR: No inputs provided!
        if (!isset($inputs)) return false;

        // Sanitize and parse filters (if not already)
        if (empty($this->f)) $this->getFilters($inputs);

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
                    'name',
                    'code',
                    'text',
                    'race',
                    'flavor_text',
                ]))) {

                    // Initialize empty fields
                    foreach ($this->f['infields'] as &$val) {
                        $q_fields[$val] = '';
                    }
                }
            }

            // No infields restriction, initialize ALL (default)
            else {
                $q_fields = array(
                    'name' => '',
                    'code' => '',
                    'text' => '',
                    'race' => '',
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
            // Check if specific race filter (Race/Trait input) was provided
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

            // LEGACY
            if (!is_array($this->f['format'])) {
                $this->f['format'] = [ $this->f['format'] ];
            }

            $clusters = [];
            $helper = lookup('formats.code2clusters');
            foreach ($this->f['format'] as $format) {
                $clusters = array_merge($clusters, $helper[$format]);
            }
            $clusters = array_unique($clusters);

            // Add all clusters for this format
            $_sql_f[] = 'clusters_id IN('.implode(',', $clusters).')';
        }

        // FILTER --- EXCLUDE -------------------------------------------------
        if (isset($this->f['exclude'])) {

            // Get all excludes
            foreach ($this->f['exclude'] as &$exclude) {
                
                // Decide what to exclude for every exclude selected
                switch ($exclude) {

                    // Exclude spoilers
                    case 'spoilers':
                        $spoilerIds = implode(',', lookup('spoilers.ids'));
                        $_sql_f[] = "NOT(sets_id IN ({$spoilerIds}))";
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
        if (
            isset($this->f['set']) ||
            isset($this->f['setcode']) // LEGACY
        ) {
            $sets = $this->f['set'] ?? $this->f['setcode'];
            $map = lookup('sets.code2id');

            // Multiple sets
            if (is_array($sets)) {
                if (in_array('0', $sets)) array_splice($sets, 0, 1);
                foreach ($sets as &$set) $set = $map[$set];
                $_sql_f[] = 'sets_id IN ('.implode(',', $sets).')';
            }
            
            // Single set
            elseif ($sets !== '0') {
                $_sql_f[] = "sets_id = {$map[$sets]}";
            }
        }

        // FILTER --- NUMBER --------------------------------------------------
        if (isset($this->f['num']) AND (int) $this->f['num'] != 0) {
            $_sql_f[] = "num = {$this->f['num']}";
        }


        // FILTER --- RACE OR TRAIT -------------------------------------------
        if (isset($this->f['race']) AND !empty($this->f['race'])) {

            // Alias the input for readability
            $race =& $this->f['race'];

            // REGEXP
            // It's better than LIKE because it excludes "Four Sacred Beasts" cards
            // when searching for "Beast", but it can search for "Human/Beast" cards!
            $_sql_f[] = "race REGEXP \"(^|\/){$race}(\/|$)\"";
        }


        // FILTER ---  ATTRIBUTE ----------------------------------------------
        if (
            isset($this->f['attribute']) ||
            isset($this->f['attributes']) // Legacy
        ) {
            $flags = [];
            $inputs = [
                'attribute_selected',
                'attribute_multi',
                'no_attribute_multi',
            ];
            foreach ($inputs as $input) {
                $flags[$input] = isset($this->f[$input]);
            }

            $this->processAttributeInput(
                $this->f['attribute'] ?? $this->f['attributes'],
                $flags,
                $_sql_f
            );
        }

        // FILTER --- TYPE ----------------------------------------------------
        if (isset($this->f['type'])) {

            $map = lookup('types.display'); // TYPE_NAME => TYPE_BITMASK

            // Must have ALL selected types
            if (isset($this->f['type_selected'])) {
                $bitmask = new Bitmask;
                foreach ($this->f['type'] as $type) {
                    $bitmask->addBitValue($map[$type]);
                }
                $bitval = $bitmask->getMask();
                $_sql_f[] = "type_bit & {$bitval} = {$bitval}";
            }

            // Must match AT LEAST ONE of the selected types
            else {
                $_filter = [];
                foreach ($this->f['type'] as $type) {
                    $bitmask = $map[$type];
                    $_filter[] = "type_bit & {$bitmask} = {$bitmask}";
                }
                $_sql_f[] = '('.implode(' OR ', $_filter).')';
            }
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

            $_sql_f[] = "(back_side = " . implode(" OR back_side = ", array_map(
                function($i) use ($map) { return $map[$i]; },
                array_filter(
                    $this->f['backside'],
                    function($i) use ($map) { return isset($map[$i]); }
                )
            )).")";
        }

        // FILTER --- DIVINITY ------------------------------------------------
        if (isset($this->f['divinity'])) {
            $_sql_f[] = "(divinity = "
                      . implode(" OR divinity = ", $this->f['divinity'])
                      . ")";
        }


        // FILTER --- FREE COST -----------------------------------------------
        if (isset($this->f['free_cost'])) {
            $_sql_f[] = "(free_cost = "
                      . implode(" OR free_cost = ", $this->f['free_cost'])
                      . ")";
        }

        // FILTER --- TOTAL COST ----------------------------------------------
        if (isset($this->f['total_cost'])) {
            $_sql_f[] = "(total_cost = "
                      . implode(" OR total_cost = ", $this->f['total_cost'])
                      . ")";
        }

        // FILTER --- FREE COST AS X ------------------------------------------
        if (isset($this->f['xcost'])) {
            $_sql_f[] = "free_cost < 0";
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
        if (isset($this->f['def']) && (int) $this->f['def'] != 0) {
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
        // (sets_id, num, type or rarity is set)
        if (
            !(
                isset($this->f['set']) ||
                isset($this->f['num']) ||
                isset($this->f['type']) ||
                isset($this->f['rarity'])
            )
        ) {
            $_sql_f[] = "NOT(type_bit & 4 = 4)";
        }


        // FILTER --- ARTIST NAME ---------------------------------------------
        if (isset($this->f['artist'])) {
            $_sql_f[] = "artist_name = \"{$this->f['artist']}\"";
        }


        // FILTER --- LIMIT and OFFSET ----------------------------------------
        if (isset($this->f['page'])) {
            $page = intval($this->f['page']);
            $offset = ($page - 1) * config('db.results.limit');
            $this->sqlPartials['offset'] = $offset;
        }

        // FILTER --- FLAG: QUICKCAST -----------------------------------------
        if (isset($this->f['quickcast'])) {
            $bitval = 1 << lookup('types.name2bit.Has Quickcast');
            $_sql_f[] = "type_bit & {$bitval} = {$bitval}";
        }

        // SORTING ============================================================
        if (isset($this->f['sort']) && $this->f['sort'] !== 'default') {

            $sortables = array_keys(lookup('sortables.cards'));
            if (in_array($this->f['sort'], $sortables)) {

                // Get sorting direction
                (isset($this->f['sortdir']) && $this->f['sortdir'] == 'desc')
                    ? $sortDir = 'DESC'
                    : $sortDir = 'ASC';

                switch ($this->f['sort']) {
                    
                    case 'attribute':
                        $sortField = "attribute_bit";
                        break;

                    case 'rarity':
                        $sortField = "FIELD(rarity,'c','u','r','sr','pr','s')";
                        break;
                        
                    default: // No custom sorting for other inputs
                        $sortField = $this->f['sort'];
                        break;
                }

                // Get default sorting
                $default = $this->sqlPartials['sorting'];

                // Set a new sorting
                $sorting = "{$sortField} {$sortDir}, {$default}";
                $this->sqlPartials['sorting'] = $sorting;

            }
        }

        // Build final Filters query (if no filter set, bypass it with TRUE)
        $sql_f = empty($_sql_f) ? 'TRUE' : implode(' AND ', $_sql_f);

        // Set the final WHERE clause
        $this->sqlPartials['filter'] = "{$sql_f} AND {$sql_q}";

        return $this;
    }
}
