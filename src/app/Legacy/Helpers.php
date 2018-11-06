<?php

namespace App\Legacy;

use App\Services\FileSystem;

class Helpers
{
    /**
     * Will hold all the loaded data from the main .json file
     *
     * @var array
     */
    public static $helpers = [];

    /**
     * Allowed features
     * 
     * Static: read-only from a .json file
     * Dynamic: generated from the database
     *
     * @var array
     */
    public static $allowedFeatures = [
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
     * Dynamic features only
     *
     * @var array
     */
    public static $dynamicFeatures = [
        'clusters',
        'formats',
        'spoiler'
    ];

    /**
     * Reads (or imports) the helpers JSON data, then returns it
     *
     * @param string $path Ex.: clusters.6.sets.ndr, or rarities.c
     * @return mixed Array of that feat or FALSE.
     * Ex.: 'rarities' => [ 'c' => 'Common', 'r' => 'Rare', ... ]
     */
    public static function get($path = null)
    {
        // ERROR: Missing path
        if (!isset($path)) throw new Oops("Missing name for cached feature");

        // Load data is needed
        if (empty(self::$helpers)) self::loadJSON();

        // Not dot-separated path provided
        if (false === strpos($path, '.')) return self::$helpers[$path];

        // Split by the dot
        $bits = explode('.', $path);

        // Pop the first bit, then dive 1 level deep
        $first = array_shift($bits);

        // ERROR: Invalid name
        if (!in_array($first, self::$allowedFeatures)) {
            throw new Oops("Feature <strong>{$first}</strong> doesn't exist");
        }

        $result = self::$helpers[$first];

        // Loop on all bits and dive deeper if needed
        foreach ($bits as &$bit) {
            if (isset($result[$bit])) $result = $result[$bit];
        }

        return $result;
    }

    /**
     * Returns all the helpers
     *
     * @return array
     */
    public static function getAll(): array
    {
        if (empty(self::$helpers)) self::loadJSON();

        return self::$helpers;
    }

    /**
     * Stores helpers.json into prop as assoc array
     */
    private static function loadJSON()
    {
        self::$helpers = json_decode(
            FileSystem::readFile(path_cache('helpers/helpers.json')),
            $turnJsonIntoPhpArray = true
        );
    }

    /**
     * Assembles all helpers/data JSON files into one helpers.json
     * 
     * @return bool
     */
    public static function assembleAll(): bool
    {
        $data = [];
        foreach (self::$allowedFeatures as $feature) {
            $data[$feature] = json_decode(
                FileSystem::readFile(path_data("helpers/{$feature}.json")),
                true // Turn JSON into PHP array
            );
        }

        // Encode back all arrays as JSON
        $json = json_encode($data, JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES);

        // Store into filesystem
        return FileSystem::saveFile(path_cache('helpers/helpers.json'), $json);
    }

    /**
     * Re-generates *ALL* dynamic helpers
     *
     * @return bool
     */
    public static function generateAll(): bool
    {
        foreach (self::$dynamicFeatures as &$feat) {
            if (!self::generate($feat)) return false;
        }

        return true;
    }

    /**
     * Re-generates JSON file of single feature from db info
     *
     * @param string $feat to generate from db. See code for a list of allowed
     * @return bool
     */
    public static function generate(string $feat = '')
    {
        $allowed =& self::$dynamicFeatures;
        if (!in_array($feat, $allowed)) return false;

        if ($feat === 'clusters') $featArray = self::generateClusters();
        elseif ($feat === 'formats') $featArray = self::generateFormats();
        elseif ($feat === 'spoiler') $featArray = self::generateSpoiler();

        $filename = path_root("src/data/helpers/{$feat}.json");
        $flags = JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT;
        $output = json_encode($featArray, $flags);
        return file_put_contents($filename, $output) ? true : false;
    }

    /**
     * Generates CLUSTERS from db and returns final array
     *
     * @param obj $db database connection object
     * @return array final array to be JSON-ed and saved
     */
    private static function generateClusters(): array
    {
        $output = [];

        $items = database_old()->get(
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

        $cache = '';
        foreach ($items as &$item) {

            // Update cached value
            if ($cache !== $item['cid']) {
                $cache = (string) $item['cid'];
                $output[$cache]['name'] = $item['cname'];
            }

            // Add this item
            $output[$cache]['sets'][$item['scode']] = $item['sname'];
        }

        return $output;
    }

    /**
     * Generates FORMATS from db and returns final array
     *
     * @param obj $db database connection object
     * @return array final array to be JSON-ed and saved
     */
    private static function generateFormats(): array
    {
        $cache = '';
        $formatsDefault = '';
        $formatsList = [];

        $items = database_old()->get(
            "SELECT
                f.code fcode,
                f.name fname,
                f.is_default fdefault,
                c.id cid
            FROM formats f
            INNER JOIN pivot_cluster_format cf ON f.id = cf.formats_id
            INNER JOIN clusters c ON cf.clusters_id = c.id
            GROUP BY fcode, cid
            ORDER BY f.is_multi_cluster DESC, f.id DESC"
        );

        foreach ($items as &$item) {

            // Update the cache
            if ($cache !== $item['fcode']) {
                $cache = $item['fcode'];
                $formatsList[$cache]['name'] = $item['fname'];
                if ($item['fdefault'] == 1) $formatsDefault = $item['fcode'];
            }

            // Add format to list
            $formatsList[$cache]['clusters'][] = $item['cid'];
        }

        // Return final array
        return [
            'default' => $formatsDefault,
            'list' => $formatsList
        ];
    }

    /**
     * Generates SPOILER from db and returns final array
     *
     * @return array final array to be JSON-ed and saved
     */
    private static function generateSpoiler(): array
    {
        $data = database()
            ->select(statement('select')
                ->select(['code', 'name', 'count'])
                ->from('sets')
                ->where('is_spoiler = 1')
            )
            ->get();

        $state = [
            'codes' => [],
            'names' => [],
            'counts' => []
        ];

        $result = array_reduce($data, function ($result, $set) {
            $result['codes'][] = $set['code'];
            $result['names'][] = $set['name'];
            $result['counts'][] = $set['count'];
            return $result;
        }, $state);

        return $result;
    }
}
