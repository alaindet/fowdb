<?php

namespace App\Http\Controllers\Api;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Legacy\CardSearch as Search;
use App\Http\Response\JsonResponse;

/**
 * Contains actions to respond to api/cards/* routes
 */
class CardsController extends Controller
{
    public function search(Request $request): string
    {
        $search = new Search;
        $search->processFilters($request->input()->get());

        $data = [
            'response' => true,
            'cardsData' => $search->getCards(),
            'nextPage' => $search->isPagination
        ];

        return (new JsonResponse)->setData($data)->render();
    }
}
