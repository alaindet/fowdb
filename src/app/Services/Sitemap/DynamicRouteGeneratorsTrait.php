<?php

namespace App\Services\Sitemap;

/**
 * Sitemap methods used
 * 
 * public function buildUrlElement(
 *     string $url,
 *     string $frequency,
 *     float $priority
 * ): string;
 */
trait DynamicRouteGeneratorsTrait
{
    /**
     * Builds all /code/{parameter} routes
     *
     * @param string $mask
     * @param string $frequency
     * @param float $priority
     * @return string The routes as XML
     */
    public function cardRoutesGenerator(
        string $mask,
        string $frequency,
        float $priority
    ): string
    {
        $statement = statement('select')
            ->fields('DISTINCT code')
            ->from('cards')
            ->orderBy([ 'clusters_id DESC', 'sets_id DESC', 'num ASC' ]);

        $items = database()
            ->select($statement)
            ->get();

        $items = array_column($items, 'code');

        return $this->dynamicRoutesGenerator(
            $items, $mask, $frequency, $priority
        );
    }

    /**
     * Builds all /cr/{parameter} routes
     *
     * @param string $mask
     * @param string $frequency
     * @param float $priority
     * @return string The routes as XML
     */
    public function rulesRoutesGenerator(
        string $mask,
        string $frequency,
        float $priority
    ): string
    {
        $result = '';

        $statement = statement('select')
            ->fields('version')
            ->from('game_rules')
            ->orderBy([ 'date_validity DESC', 'id DESC' ]);

        $items = database()
            ->select($statement)
            ->get();

        $items = array_column($items, 'version');

        return $this->dynamicRoutesGenerator(
            $items, $mask, $frequency, $priority
        );
    }

    /**
     * Builds all dynamic routes, given values
     *
     * @param array $values
     * @param string $mask
     * @param string $frequency
     * @param float $priority
     * @return string All dynamic routes in XML
     */
    private function dynamicRoutesGenerator(
        array &$values,
        string $mask,
        string $frequency,
        float $priority
    ): string
    {
        $result = '';

        [$urlStart, $urlEnd] = explode('{parameter}', $mask);

        foreach ($values as &$value) {
            $url = $urlStart . $value . $urlEnd;
            $result .= $this->buildUrlElement($url, $frequency, $priority);
        }

        return $result;
    }
}
