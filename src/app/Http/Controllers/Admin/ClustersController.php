<?php

namespace App\Http\Controllers\Admin;

use App\Base\ApiException;
use App\Base\Controller;
use App\Exceptions\CrudException;
use App\Http\Request\Request;
use App\Http\Response\JsonResponse;
use App\Models\CardCluster;
use App\Services\CsrfToken;
use App\Services\Resources\Cluster\ClusterCreateService;
use App\Services\Resources\Cluster\ClusterDeleteService;
use App\Services\Resources\Cluster\ClusterUpdateService;
use App\Views\Page;

class ClustersController extends Controller
{
    public function index(Request $request): string
    {
        $items = database()
            ->select(
                statement('select')
                    ->from('game_clusters')
                    ->orderBy('id DESC')
            )
            ->get();

        return (new Page)
            ->template('pages/admin/clusters/index')
            ->title('Clusters,Manage')
            ->variables([
                'items' => $items
            ])
            ->options([
                'scripts' => ['admin/clusters'],
                'token' => CsrfToken::get()
            ])
            ->render();
    }

    public function apiShowAll(Request $request): string
    {
        $items = database()
            ->select(
                statement('select')
                    ->from('game_clusters')
                    ->orderBy('id DESC')
            )
            ->get();

        return (new JsonResponse)->setData($items)->render();
    }

    public function apiShow(Request $request, $id): string
    {
        // Read resource
        $data = (new CardCluster)->byId($id);

        // Build data
        if (empty($data)) {
            $data = [
                'error' => true,
                'message' => "Cluster ID = {$id} does not exist on FoWDB"
            ];
        }

        // Return JSON
        return (new JsonResponse)->setData($data)->render();
    }

    public function apiCreate(Request $request): string
    {
        $request->api()->validate('post', [
            'id' => ['required','is:integer','except:0'],
            'name' => ['required','except:'],
            'code' => ['required','except:']
        ]);

        try {
            $service = new ClusterCreateService($request->input()->post());
            $service->processInput();
            $service->syncDatabase();
            $service->syncFilesystem();
            $service->updateLookupData();
            [$message] = $service->getFeedback();
        } catch (CrudException $exception) {
            throw new ApiException($exception->getMessage());
        }

        $response = [
            'error' => false,
            'message' => $message
        ];
        
        return (new JsonResponse)
            ->setData($response)
            ->render();
    }

    public function apiUpdate(Request $request, $id): string
    {
        $request->api()->validate('post', [
            'id' => ['required','is:integer','except:0','exists:game_clusters,id'],
            'name' => ['required','except:'],
            'code' => ['required','except:']
        ]);

        try {
            $service = new ClusterUpdateService($request->input()->post(), $id);
            $service->processInput();
            $service->syncDatabase();
            $service->updateLookupData();
            [$message] = $service->getFeedback();
        } catch (CrudException $exception) {
            throw new ApiException($exception->getMessage());
        }

        $response = [
            'error' => false,
            'message' => $message
        ];
            
        return (new JsonResponse)
            ->setData($response)
            ->render();
    }

    public function apiDelete(Request $request, $id): string
    {
        try {
            $service = new ClusterDeleteService(null, $id);
            $service->syncDatabase();
            $service->syncFilesystem();
            $service->updateLookupData();
            [$message] = $service->getFeedback();
        } catch (CrudException $exception) {
            throw new ApiException($exception->getMessage());
        }

        $response = [
            'error' => false,
            'message' => $message
        ];
            
        return (new JsonResponse)
            ->setData($response)
            ->render();
    }
}
