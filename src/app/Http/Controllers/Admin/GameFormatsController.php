<?php

namespace App\Http\Controllers\Admin;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Http\Response\Redirect;
use App\Services\Alert;
use App\Views\Page;
use App\Services\Validation\Validation;
use App\Entity\GameFormat\GameFormatRepository;

class GameFormatsController extends Controller
{
    public function index(Request $request): string
    {
        $formats = (new GameFormatRepository)
            ->all();

        return (new Page)
            ->template('pages/admin/formats/index')
            ->title('Formats,Manage')
            ->variables([
                'items' => $formats
            ])
            ->render();
    }

    public function createForm(Request $request): string
    {
        // Get all clusters as an array of ID => name
        $clusters = (new ClustersRepository)
            ->sorting(['id DESC'])
            ->all()
            ->reduce(function($result, $cluster) {
                $result[$cluster->id] = $cluster->name;
                return $result;
            }, []);

        $nextAvailableId = (new FormatsRepository)->nextAvailableId();

        return (new Page)
            ->template('pages/admin/formats/create')
            ->title('Formats,Create')
            ->variables([
                'previous' => $request->input()->previous(),
                'nextAvailableId' => $nextAvailableId,
                'clusters' => $clusters
            ])
            ->render();
    }

    public function create(Request $request): string
    {
        $request->validate([
            'id' => [
                'required',
                'is:integer',
                'not-exists:game_formats,id'
            ],
            'name' => [
                'required',
                'between:5,255',
                'not-exists:game_formats,name'
            ],
            'code' => [
                'required',
                'is:alphadash',
                'length:5',
                'not-exists:game_formats,code'
            ],
            'desc' => [
                'optional',
                'is:text',
                'min:10'
            ],
            'is-default' => [
                'optional',
                'is:boolean'
            ],
            'clusters' => [
                'required',
                'is:array',
                'not-empty',
                'are:integers'
            ],
        ]);

        $service = new CreateService($request->input()->post());
        $service->processInput();
        $service->syncDatabase();
        $service->updateLookupData();

        dump($service->debug());

        [$message, $uri] = $service->getFeedback();

        Alert::add($message, 'info');
        Redirect::toAbsoluteUrl($uri);
    }

    public function updateForm(Request $request, string $id): string
    {
        $clusters = fd_database()
            ->select(
                statement('select')
                    ->from('game_clusters')
                    ->orderBy('id DESC')
            )
            ->get();

        return (new Page)
            ->template('pages/admin/sets/update')
            ->title('Sets,Update')
            ->variables([
                'previous' => $request->input()->previous(),
                'clusters' => $clusters,
                'item' => (new Model)->byId($id)
            ])
            ->render();
    }

    public function update(Request $request, string $id): string
    {
        $request->validate([
            'cluster-id' => ['required','is:integer','exists:game_clusters,id'],
            'id' => ['required','is:integer','exists:game_sets,id'],
            'name' => ['required'],
            'code' => ['required','is:alphanumeric'],
            'count' => ['required','is:integer','between:1,255'],
            'release-date' => ['required:0','is:date'],
            'is-spoiler' => ['required:0','is:boolean'],
        ]);

        $service = new UpdateService($request->input()->post(), $id);
        $service->processInput();
        $service->syncDatabase();
        $service->syncFileSystem();
        $service->updateLookupData();
        [$message, $uri] = $service->getFeedback();
        
        Alert::add($message, 'info');
        Redirect::toAbsoluteUrl($uri);
    }

    public function deleteForm(Request $request, string $id): string
    {
        $item = fd_database()
            ->select(
                statement('select')
                    ->select([
                        'c.id cluster_id',
                        'c.name cluster_name',
                        'c.code cluster_code',
                        's.id id',
                        's.code code',
                        's.name name',
                        's.count count',
                        's.date_release release_date',
                        's.is_spoiler is_spoiler'
                    ])
                    ->from(
                        'game_sets s
                        INNER JOIN game_clusters c ON s.clusters_id = c.id'
                    )
                    ->where('s.id = :id')
            )
            ->bind([':id' => $id])
            ->first();

        return (new Page)
            ->template('pages/admin/sets/delete')
            ->title('Sets,Delete')
            ->variables([
                'item' => $item
            ])
            ->render();
    }

    public function delete(Request $request, string $id): string
    {
        $service = new DeleteService(null, $id);
        $service->syncDatabase();
        $service->syncFileSystem();
        $service->updateLookupData();
        [$message, $uri] = $service->getFeedback();
        
        Alert::add($message, 'info');
        Redirect::toAbsoluteUrl($uri);
    }
}
