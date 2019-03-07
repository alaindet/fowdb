<?php

namespace App\Services\Sitemap;

use App\Services\Sitemap\DynamicRouteGeneratorTrait;
use App\Utils\Time;
use App\Services\FileSystem\FileSystem;

/**
 * Builds /sitemap.xml
 * https://www.sitemaps.org/protocol.html
 */
class Sitemap
{
    use DynamicRouteGeneratorsTrait;

    /**
     * Path => [frequency, priority]
     *
     * @var array
     */
    public $staticRoutes = [
        '/' => ['monthly', 0.5],
        '/cards/search' => ['monthly', 0.5],
        '/cards' => ['daily', 0.5],
        '/spoiler' => ['daily', 0.5],
        '/banlist' => ['monthly', 0.5],
        // '/rulings' => ['weekly', 0.5],
        '/errata' => ['weekly', 0.5],
        '/cr' => ['monthly', 0.5],
        '/races' => ['monthly', 0.5],
        '/formats' => ['monthly', 0.5],
        '/cards/search/help' => ['yearly', 0.5],
    ];

    /**
     * Path => [generator, frequency, priority]
     *
     * @var array
     */
    public $dynamicRoutes = [
        '/card/{parameter}' => ['cardRoutesGenerator', 'monthly', 0.5],
        '/cr/{parameter}' => ['rulesRoutesGenerator', 'monthly', 0.5],
    ];

    /**
     * Holds the value of <lastmod> as YYYY-MM-DD
     *
     * @var string
     */
    private $lastModified;

    /**
     * Absolute output path for sitemap.xml
     *
     * @var string
     */
    private $path;

    /**
     * Absolute output path for sitemap.xml.gz
     *
     * @var string
     */
    private $gzipPath;

    /**
     * The base URL of the website
     *
     * @var string
     */
    private $url;

    public function __construct()
    {
        $this->lastModified = Time::date();
        $this->path = path_root('sitemap.xml');
        $this->gzipPath = path_root('sitemap.xml.gz');
        $this->url = config('app.url');
    }

    /**
     * Builds the whole sitemap.xml document and store it
     * Stores a backup of existing sitemap.xml, optionally
     *
     * @param bool $backup
     * @return void
     */
    public function build(bool $backup = false): void
    {
        // Backup existing /sitemap.xml into /src/data/backup/
        if ($backup) {
            $basename = 'sitemap_backup_'.Time::timestamp('file').'.xml';
            $basenameGzip = 'sitemap_backup_'.Time::timestamp('file').'.xml.gz';
            $backupPath = path_data('backup/'.$basename);
            $backupGzipPath = path_data('backup/'.$basenameGzip);

            FileSystem::renameFile($this->path, $backupPath);
            FileSystem::renameFile($this->gzipPath, $backupGzipPath);
        }
        
        // Build new sitemap
        $xml = (
            '<?xml version="1.0" encoding="UTF-8"?>'.
            '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.
                $this->buildStaticRoutes().
                $this->buildDynamicRoutes().
            '</urlset>'
        );

        // Store into /sitemap.xml and /sitemap.xml.gz
        FileSystem::saveFile($this->path, $xml);
        FileSystem::saveFile($this->gzipPath, gzcompress($xml));
    }

    /**
     * Builds a <url> element
     *
     * @param string $url
     * @param string $frequency
     * @param string $priority
     * @return string
     */
    public function buildUrlElement(
        string $url,
        string $frequency,
        float $priority
    ): string
    {
        return (
            '<url>'.
                '<loc>'.$this->url.$url.'</loc>'.
                '<lastmod>'.$this->lastModified.'</lastmod>'.
                '<changefreq>'.$frequency.'</changefreq>'.
                '<priority>'.$priority.'</priority>'.
            '</url>'
        );
    }

    /**
     * Builds static routes
     *
     * @return string
     */
    public function buildStaticRoutes(): string
    {
        $result = '';

        foreach ($this->staticRoutes as $route => $info) {
            $result .= $this->buildUrlElement($route, $info[0], $info[1]);
        }

        return $result;
    }

    /**
     * Builds dynamic routes
     *
     * @return string
     */
    public function buildDynamicRoutes(): string
    {
        $result = '';

        foreach ($this->dynamicRoutes as $mask => $info) {
            $generator = $info[0];
            $frequency = $info[1];
            $priority = $info[2];
            $result .= $this->$generator($mask, $frequency, $priority);
        }

        return $result;
    }
}
