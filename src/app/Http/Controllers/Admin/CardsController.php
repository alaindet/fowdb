<?php

namespace App\Http\Controllers\Admin;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Http\Response\Redirect;
use App\Models\Card as Model;
use App\Services\Alert;
use App\Services\Resources\Card\Crud\CreateService;
use App\Services\Resources\Card\Crud\DeleteService;
use App\Services\Resources\Card\Crud\UpdateService;
use App\Views\Page;

/**
 * Contains actions for JUDGE routes only
 * Public actions on cards are provided by ...\CardsController
 */
class CardsController extends Controller
{
    public function indexManage(Request $request): string
    {
        return (new Page)
            ->template('pages/admin/cards/index')
            ->title('Admin ~ Cards ~ Index')
            ->render();
    }

    public function createForm(Request $request): string
    {
        return (new Page)
            ->template('pages/admin/cards/create')
            ->title('Cards,Create')
            ->variables([ 'previous' => $request->input()->previous() ])
            ->options([
                'scripts' => ['admin/cards/form'],
                'dependencies' => [
                    'jqueryui' => true
                ],
            ])
            ->render();
    }

    public function create(Request $request): string
    {
        // Assemble input
        $input = array_merge(
            $request->input()->post(),
            $request->input()->files()
        );

        // Validate assembled input ($_POST + $_FILES)
        $request->validate('post', [
            
            // Required fields
            'image' => ['required','is:file'],
            'narp' => ['required','is:integer','enum:0,1,2,3'],
            'set' => ['required','except:0'],
            'number' => ['required','is:integer'],
            'back-side' => ['required','is:integer','enum:0,1,2,3,4'],
            'name' => ['required','except:'],
            'type' => ['required','except:0'],
            'rarity' => ['required','enum:0,c,u,r,sr,s,ar'],
            'attribute' => ['required','is:array'],

            // Optional fields
            'code' => ['required:0',],
            'attribute-cost' => ['required:0'],
            'free-cost' => ['required:0','is:integer'],
            'divinity-cost' => ['required:0','is:integer'],
            'atk' => ['required:0','is:integer'],
            'def' => ['required:0','is:integer'],
            'race' => ['required:0'],
            'text' => ['required:0'],
            'flavor-text' => ['required:0'],
            'artist-name' => ['required:0'],

        ], $input);

        $service = new CreateService($input);
        $service->processInput();
        $service->syncDatabase();
        $service->syncFileSystem();

        [$message, $uri] = $service->getFeedback();

        Alert::add($message, 'info');
        Redirect::toAbsoluteUrl($uri);
    }

    public function updateForm(Request $request, string $id): string
    {
        return (new Page)
            ->template('pages/admin/cards/update')
            ->title('Cards,Update')
            ->variables([
                'previous' => $request->input()->previous(),
                'card' => (new Model)->byId($id)
            ])
            ->options([
                'scripts' => ['admin/cards/form'],
                'dependencies' => [
                    'lightbox' => true,
                    'jqueryui' => true
                ],
            ])
            ->render();
    }

    public function update(Request $request, string $id): string
    {
        // Assemble input
        $input = array_merge(
            $request->input()->post(),
            $request->input()->files()
        );

        // Validate assembled input ($_POST + $_FILES)
        $request->validate('post', [
            
            // Required fields
            'narp' => ['required','is:integer','enum:0,1,2,3'],
            'set' => ['required','except:0'],
            'number' => ['required','is:integer'],
            'back-side' => ['required','is:integer','enum:0,1,2,3,4'],
            'name' => ['required','except:'],
            'type' => ['required','except:0'],
            'rarity' => ['required','enum:0,c,u,r,sr,s,ar'],
            'attribute' => ['required','is:array'],

            // Optional fields
            'image' => ['required:0','is:file'],
            'code' => ['required:0',],
            'attribute-cost' => ['required:0'],
            'free-cost' => ['required:0','is:integer'],
            'divinity-cost' => ['required:0','is:integer'],
            'atk' => ['required:0','is:integer'],
            'def' => ['required:0','is:integer'],
            'race' => ['required:0'],
            'text' => ['required:0'],
            'flavor-text' => ['required:0'],
            'artist-name' => ['required:0'],

        ], $input);

        $service = new UpdateService($input, $id);
        $service->processInput();
        $service->syncDatabase();
        $service->syncFileSystem();

        [$message, $uri] = $service->getFeedback();

        Alert::add($message, 'info');
        Redirect::toAbsoluteUrl($uri);
    }

    public function deleteForm(Request $request, string $id): string
    {
        $card = (new Model)->byId($id, null, ['text']);

        return (new Page)
            ->template('pages/admin/cards/delete')
            ->title('Cards,Update')
            ->variables([ 'card' => $card ])
            ->options([
                'dependencies' => [
                    'lightbox' => true,
                ],
            ])
            ->render();
    }

    public function delete(Request $request, string $id): string
    {
        $service = new DeleteService(null, $id);
        $service->syncFileSystem();
        $service->syncDatabase();
        [$message, $uri] = $service->getFeedback();
        
        Alert::add($message, 'info');
        Redirect::toAbsoluteUrl($uri);
    }
}
