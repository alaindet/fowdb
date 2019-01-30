<?php

namespace App\Services\Resources\Card\Search;

trait SearchBarProcessorTrait
{
    /**
     * Replaces all whitespace in a string query
     * EXCEPT whitespace contained inside two user-defined delimiters
     * 
     * Ex.:
     * 'hello `foo bar baz` world' => 'hello%foo bar baz%world'
     * 
     * @param string $query The query string
     * @param string $delimiter Later removed from result
     * @param string $temp Temporary string to distinguish whitespace
     * @return string Query string, no delimiters, distinguished whitespace
     */
    protected function queryPreserveWhitespace(
        string $query,
        string $delimiter = '`',
        string $temp = 'àè'
    ): string
    {
        // Explode query by delimiter
        // BEFORE: 'hello `foo bar baz` world'
        // AFTER:  ['hello ', 'foo bar baz', ' world']
        $bits = explode($delimiter, $query);

        // Replace whitespace with a temporary string (àè)
        // Preserves whitespace inside delimiters
        // BEFORE: ['hello ', 'foo bar', ' world']
        // AFTER:  ['hello ', 'fooàèbaràèbaz', ' world']
        for ($i = 1, $ii = count($bits); $i < $ii; $i += 2) {
            $bits[$i] = str_replace(' ', $temp, $bits[$i]);
        }

        // Implode string again
        // BEFORE: ['hello ', 'fooàèbaràèbaz', ' world']
        // AFTER:  'hello fooàèbaràèbaz world'
        $query = implode($bits);

        // Build single SQL string to be searched by LIKE operator
        // Replace space with % and \s flag with space in this order
        // BEFORE: 'hello fooàèbaràèbaz world'
        // AFTER:  'hello%foo bar baz%world'
        return str_replace([' ', $temp], ['%', ' '], $query);
    }

    /**
     * Returns the fields in which perform the text search
     *
     * @param array $fields
     * @return array
     */
    protected function querySelectFields(array $fields = []): array
    {   
        $whitelist = ['name', 'code', 'text', 'race', 'flavor_text'];

        // No fields specified, use defaults (all but flavor texts)
        if (empty($fields)) {
            array_pop($whitelist);
            return $whitelist;
        }

        $queryFields = [];

        for ($i = 0, $ii = count($fields); $i < $ii; $i++) {
            if (in_array($fields[$i], $whitelist)) {
                $queryFields[] = $fields[$i];
            }
        }

        return $queryFields;
    }
}
