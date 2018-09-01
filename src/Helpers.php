<?php

namespace App;

class Helpers
{
    // This will store helpers
    private static $helpers = [];
    // List of allowed features
    /*
     * Static: card-features, sort-fields, attributes, costs, types, rarities
     * Dynamic: formats, clusters, keywords
     */
    private static $allowedFeatures = [
        'cardfeatures',
        'sortfields',
        'formats',
        'clusters',
        'attributes',
        'costs',
        'types',
        'backsides',
        'rarities',
        'spoiler'
    ];

    /**
     * Reads (or imports) the helpers JSON data, then returns it
     *
     * @param string Name of the feature you need
     * @return mixed Array of that feat or FALSE. Ex.: "rarities" => ["c" => "Common","r" => "Rare"...]
     */
    public static function get($feat = null)
    {
        // Init result
        $return = null;

        // If not feature passed
        if (!isset($feat)) { return false; }

        // If helpers is empty, load helpers JSON file
        if (empty(self::$helpers)) { self::loadJSON(); }

        // Check for concatenation
        if (strpos($feat, ".") !== false) {

            $bits = explode(".", $feat); // Get single bits
            $first = array_shift($bits); // Remove the first bit
            $return = self::$helpers[$first]; // Init the result

            // Loop on the bits, go as deep as needed
            foreach ($bits as &$bit) {
                if (isset($return[$bit]) AND is_array($return[$bit])) {
                    $return = $return[$bit];
                }
            }
        }

        // No concatenation, check for allowed features
        else if (in_array($feat, self::$allowedFeatures)) {
            $return = self::$helpers[$feat];
        }

        // Return just feature array
        return $return;
    }


    /**
     * Returns all the helpers
     *
     * @return array
     */
    public static function getAll()
    {
        // Check if helpers.json is already stored (store it otherwise)
        if (empty(self::$helpers)) { self::loadJSON(); }

        // Return all helpers
        return self::$helpers;
    }


    /**
     * Stores helpers.json into prop as assoc array
     */
    private static function loadJSON()
    {
        // Assemble filename
        $filename = APP_ROOT."/app/helpers/helpers.json";
        // Store helpers in prop sa array from JSON file
        self::$helpers = json_decode(
            file_get_contents($filename), // Load JSON from filesystem
            true // JSON objects = PHP assoc array
        );
    }


    /**
     * Assembles all helpers/data JSON files into one helpers.json
     */
    public static function assembleAll()
    {
        // Will hold all JSON decoded files
        $assembled = [];
        // Base path
        $basepath = APP_ROOT."/app/helpers";
        // Loop on features
        foreach(self::$allowedFeatures as $feat) {
            // Path to current JSON
            $filename_input = "{$basepath}/data/{$feat}.json";
            // Add decoded array to final
            $assembled[$feat] = json_decode(
                file_get_contents($filename_input), true
            );
        }
        // Encode back all arrays as JSON
        $json_output = json_encode($assembled, APP_JSON_ENCODE);
        // Path to final JSON
        $filename_output = "{$basepath}/helpers.json";
        // Rename old helpers.json
        /*rename(
            // Old name
            "{$basepath}/helpers.json",
            // New name
            "{$basepath}/helpers_replacedOn_".date("Ymd_His",time()).".json"
        );*/
        // Save file
        $saved = file_put_contents($filename_output, $json_output);
        // Return result
        return $saved ? true : false;
    }


    /**
     * Re-generates *ALL* dynamic helpers
     *
     * @return bool
     */
    public static function generateAll()
    {
        // Dynamic helpers to be generated
        $dynamic = ["clusters", "formats", "spoiler"];

        // Loop on dynamic helpers and generate them
        foreach ($dynamic as $feat) {
            // Get outcome of generation
            $success = self::generate($feat);
            // Break loop if generate() returs error
            if (!$success) { break; }
        }

        // Return outcome (FALSE if some feat failed, TRUE if success)
        return $success;
    }


    /**
     * Re-generates JSON file of single feature from db info
     *
     * @param string $feat to generate from db. See code for a list of allowed
     * @return bool
     */
    public static function generate($feat = null) 
    {
        // Allowed helpers to be generated
        $allowed = ["clusters", "formats", "spoiler"];

        // Check if feature name is missing or not allowed
        if (!isset($feat) OR !in_array($feat, $allowed)) {
            return false;
        }

        // Initialize feature array as PHP array
        $featArray = [];

        // Get database connection
        $db = \App\Database::getInstance();

        // Decide based on feature
        switch ($feat) {
            case 'clusters':
                $featArray = self::generateClusters($db);
                break;
            case 'formats':
                $featArray = self::generateFormats($db);
                break;
            case 'spoiler':
                $featArray = self::generateSpoiler($db);
                break;
        }

        // Generate file name of JSON file
        $filename = APP_ROOT."/app/helpers/data/{$feat}.json";

        // Encode feature array to JSON
        $o = json_encode($featArray, APP_JSON_ENCODE|JSON_PRETTY_PRINT);

        // Try to save JSON into file and return success or failure
        return file_put_contents($filename, $o) ? true : false;
    }


    /**
     * Generates CLUSTERS from db and returns final array
     *
     * @param obj $db database connection object
     * @return array final array to be JSON-ed and saved
     */
    private static function generateClusters($db)
    {
        // Assuming database connection was passed
        $db_results = $db->get(
            "SELECT
                clusters.id as cid,
                clusters.code as ccode,
                clusters.name as cname,
                sets.code as scode,
                sets.name as sname
            FROM
                clusters INNER JOIN sets ON clusters.id = sets.clusters_id
            ORDER BY
                clusters.id DESC, sets.id DESC"
        );

        // Initialize cache
        $cache = '';
        // Initialize output
        $o = [];

        // Loop on results
        foreach($db_results as $r) {

            // Check if cached value still applies
            if ($cache != $r['cid']) {
                // Store new cache value
                $cache = (string)$r['cid'];
                // Store new cluster name
                $o[$cache]['name'] = $r['cname'];
            }

            // Store current set into its cluster
            $o[$cache]['sets'][$r['scode']] = $r['sname'];
        }

        // Return final array
        return $o;
    }


    /**
     * Generates FORMATS from db and returns final array
     *
     * @param obj $db database connection object
     * @return array final array to be JSON-ed and saved
     */
    private static function generateFormats($db)
    {
        // Assuming database connection was passed
        $db_results = $db->get(
            "SELECT
                f.code fcode,
                f.name fname,
                f.isdefault fdefault,
                c.id cid
            FROM formats f
            INNER JOIN formats_clusters fc ON f.id=fc.formats_id
            INNER JOIN clusters c ON fc.clusters_id=c.id
            GROUP BY fcode,cid
            ORDER BY f.ismulticluster desc, f.id desc"
        );

        // Initialize processing
        $cache = '';
        $formatsDefault = '';
        $formatsList = [];

        // Loop on results
        foreach($db_results as $r) {
            // Check if cached value is equal to current one
            if ($cache != $r['fcode']) {
                // Cache new value
                $cache = $r['fcode'];
                // Check for default format
                if ($r['fdefault'] == 1) { $formatsDefault = $r['fcode']; }
                // Store format name
                $formatsList[$cache]['name'] = $r['fname'];
            }
            // Add this cluster to current format
            $formatsList[$cache]['clusters'][] = $r['cid'];
        }

        // Return final array
        return [
            "default" => $formatsDefault,
            "list" => $formatsList
        ];
    }

    /**
     * Generates SPOILER from db and returns final array
     *
     * @param obj $db database connection object
     * @return array final array to be JSON-ed and saved
     */
    private static function generateSpoiler($db)
    {
        // Assuming database connection was passed
        $db_results = $db->get(
            "SELECT code, name, count FROM sets WHERE isspoiler = 1"
        );

        // Initialize output
        $o = [];

        // Check if database returned any result
        if (!empty($db_results)) {
            // Loop on results
            foreach ($db_results as $r) {
                // Populate spoiler sets
                $o["sets"][] = $r;
                // Populate spoiler codes
                $o["codes"][] = $r['code'];
                // Populate spoiler names
                $o["names"][] = $r['name'];
                // Populate spoiler counts
                $o["counts"][] = $r['count'];
            }
        }

        // Return output
        return $o;
    }
}
