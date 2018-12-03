<?php

namespace App\Http\Controllers\Admin;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Http\Response\Redirect;
use App\Services\Alert;
use App\Services\Resources\Set\SetCreateService;
use App\Services\Resources\Set\SetDeleteService;
use App\Services\Resources\Set\SetUpdateService;
use App\Views\Page;
use App\Models\CardSet;

class SetsController extends Controller
{
    public function index(Request $request): string
    {
        $database = database()
            ->select(
                statement('select')
                    ->select([
                        's.id set_id',
                        's.code set_code',
                        's.name set_name',
                        's.count set_count',
                        's.date_release set_date',
                        's.is_spoiler set_is_spoiler',
                        'c.name cluster_name',
                        'c.code cluster_code'
                    ])
                    ->from(
                        'game_sets s
                        INNER JOIN game_clusters c ON s.clusters_id = c.id'
                    )
                    ->orderBy('s.id DESC')
            )
            ->page($request->input()->get('page') ?? 1)
            ->paginationLink($request->getCurrentUrl());

        // Render the page
        return (new Page)
            ->template('pages/admin/sets/index')
            ->title('Sets,Manage')
            ->variables([
                // paginate() must be called before paginationInfo()!
                'items' => $database->paginate(),
                'pagination' => $database->paginationInfo(),
            ])
            ->render();
    }

    public function createForm(Request $request): string
    {
        $clusters = database()
            ->select(
                statement('select')
                    ->from('game_clusters')
                    ->orderBy('id DESC')
            )
            ->get();

        return (new Page)
            ->template('pages/admin/sets/create')
            ->title('Sets,Create')
            ->variables([
                'previous' => $request->input()->previous(),
                'clusters' => $clusters
            ])
            ->render();
    }

    public function create(Request $request): string
    {
        $request->validate('post', [
            'cluster-id' => ['required','is:integer','exists:game_clusters,id'],
            'id' => ['required','is:integer','!exists:game_sets,id'],
            'name' => ['required'],
            'code' => ['required','is:alphanumeric'],
            'count' => ['required','is:integer','between:1,255'],
            'release-date' => ['required:0','is:date'],
            'is-spoiler' => ['required:0','is:boolean'],
        ]);

        $service = new SetCreateService($request->input()->post());
        $service->processInput();
        $service->syncDatabase();
        $service->syncFilesystem();
        $service->updateLookupData();
        [$message, $uri] = $service->getFeedback();

        Alert::add($message, 'info');
        Redirect::toAbsoluteUrl($uri);
    }

    public function updateForm(Request $request, string $id): string
    {
        $clusters = database()
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
                'item' => (new CardSet)->byId($id)
            ])
            ->render();
    }

    public function update(Request $request, string $id): string
    {
        $request->validate('post', [
            'cluster-id' => ['required','is:integer','exists:game_clusters,id'],
            'id' => ['required','is:integer','exists:game_sets,id'],
            'name' => ['required'],
            'code' => ['required','is:alphanumeric'],
            'count' => ['required','is:integer','between:1,255'],
            'release-date' => ['required:0','is:date'],
            'is-spoiler' => ['required:0','is:boolean'],
        ]);

        $service = new SetUpdateService($request->input()->post(), $id);
        $service->processInput();
        $service->syncDatabase();
        $service->syncFilesystem();
        $service->updateLookupData();
        [$message, $uri] = $service->getFeedback();
        
        Alert::add($message, 'info');
        Redirect::toAbsoluteUrl($uri);
    }

    public function deleteForm(Request $request, string $id): string
    {
        $item = database()
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

            // TEST
            ->minify(false)

            ->render();
    }

    public function delete(Request $request, string $id): string
    {
        $service = new SetDeleteService(null, $id);
        $service->syncDatabase();
        $service->syncFilesystem();
        $service->updateLookupData();
        [$message, $uri] = $service->getFeedback();
        
        Alert::add($message, 'info');
        Redirect::toAbsoluteUrl($uri);
    }
}
