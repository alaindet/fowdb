<?php

namespace App\Http\Controllers\Api;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Http\Response\JsonResponse;
use App\Services\Resources\Card\Search\Search;
use App\Models\Card as Model;

/**
 * Contains actions to respond to api/cards/* routes
 */
class CardsController extends Controller
{
    private function autocompleteError(): string
    {
        return (new JsonResponse)
            ->setData([
                'label' => 'No term',
                'value' => 'No term'
            ])
            ->render();
    }

    public function autocompleteNames(Request $request): string
    {
        $term = $request->input()->get('term', $escape = true);

        // ERROR: No term
        if (!isset($term) || $term === '') return $this->autocompleteError();

        // Fetch results from the database
        $items = fd_database()
            ->select(
                fd_statement('select')
                    ->fields(['id', 'name', 'code', 'image_path'])
                    ->from('cards')
                    ->where('name LIKE :name')
                    ->orderBy(['sets_id DESC', 'num ASC'])
                    ->limit(10)
            )
            ->bind([':name' => "%{$term}%"])
            ->get();

        // ERROR: No results
        if (empty($items)) return $this->autocompleteError();

        // Transform data
        $results = [];
        foreach ($items as $item) {

            $label = "{$item['name']} ({$item['code']})";

            $results[] = [

                // jQueryUI-specific
                'label' => $label, // Autocomplete item label
                'value' => $label, // Autocomplete item value

                // Extra information
                'id' => $item['id'],
                'image' => fd_asset($item['image_path'], 'jpg'),
                'link' => fd_url('card/'.urlencode($item['code']))

            ];
        }

        // Return JSON response
        return (new JsonResponse)->setData($results)->render();
    }

    public function checkId(Request $request, string $id): string
    {
        $card = (new Model)->byId($id);

        // ERROR: Missing card
        if (empty($card)) {
            return (new JsonResponse)
                ->setData([
                    'response' => false,
                    'message' => "No card with ID {$id} exists."
                ])
                ->render();
        }

        return (new JsonResponse)
            ->setData([
                'response' => true,
                'message' => 'Card found'
            ])
            ->render();
    }
}
