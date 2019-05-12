<?php

namespace App\Http\Controllers\Api;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Http\Response\JsonResponse;

/**
 * Contains actions to respond to api/artists/* routes
 */
class ArtistsController extends Controller
{
    public function autocomplete(Request $request): string
    {
        // Initialize JSON response
        $response = new JsonResponse;

        // Read input
        $term = $request->input()->get('term', $escape = true);

        // Initialize error data
        $errorData = ['label' => 'No term', 'value' => 'No term'];

        // ERROR: No term
        if (!isset($term)) return $response->setData($errorData)->render();

        // Fetch results from the database
        $items = fd_database()
            ->select(
                fd_statement('select')
                    ->fields('DISTINCT artist_name')
                    ->from('cards')
                    ->where('artist_name LIKE :artist')
                    ->orderBy('artist_name ASC')
                    ->limit(20)
            )
            ->bind([':artist' => "%{$term}%"])
            ->get();

        // ERROR: No results found!
        if (empty($items)) return $response->setData($errorData)->render();

        // Transform data
        $results = [];
        foreach ($items as $item) {
            $artist = &$item['artist_name'];
            $results[] = ['label' => $artist, 'value' => $artist];
        }

        // Return JSON response
        return $response->setData($results)->render();
    }
}
