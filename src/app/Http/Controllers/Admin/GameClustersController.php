<?php

namespace App\Http\Controllers\Admin;

use App\Base\ApiException;
use App\Base\Controller;
use App\Base\Crud\Exceptions\CrudException;
use App\Http\Request\Request;
use App\Http\Response\JsonResponse;
use App\Models\GameCluster as Model;
use App\Services\CsrfToken;
use App\Services\Resources\GameCluster\Crud\DeleteService;
use App\Services\Resources\GameCluster\Crud\UpdateService;
use App\Views\Page;

class GameClustersController extends Controller
{
    public function index(Request $request): string
    {
        $items = fd_database()
            ->select(
                fd_statement('select')
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
        $items = fd_database()
            ->select(
                fd_statement('select')
                    ->from('game_clusters')
                    ->orderBy('id DESC')
            )
            ->get();

        return (new JsonResponse)->setData($items)->render();
    }

    public function apiShow(Request $request, $id): string
    {
        // Read resource
        $data = (new Model)->byId($id);

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
        $response = [];

        try {
            $service = new \App\Entity\GameCluster\Write\CreateService;
            $service->setInputData($request->inputObject()->post());
            $service->create();
            [$message, $type] = $service->getFeedback();
            $response["error"] = false;
            $response["message"] = $message;
        } catch (\App\Base\Exception $exception) {
            throw new ApiException($exception->getMessage());
        }

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
            $service = new UpdateService($request->input()->post(), $id);
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
            $service = new DeleteService(null, $id);
            $service->syncDatabase();
            $service->syncFileSystem();
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
