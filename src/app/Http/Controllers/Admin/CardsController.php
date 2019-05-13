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
use App\Views\Page\Page;
use App\Services\Validation\Validation;

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

        // Validate input
        $validation = new Validation;
        $validation->setData($input);
        $validation->setRules([
            
            // Required fields
            'image' => ['required','is:file'],
            'narp' => ['required','is:integer','enum:0,1,2,3'],
            'set' => ['required','except:0'],
            'number' => ['required','is:integer'],
            'back-side' => ['required','is:integer','enum:0,1,2,3,4'],
            'name' => ['required','is:text','not-empty'],
            'type' => ['required','except:0'],
            'rarity' => ['required','is:text','enum:0,c,u,r,sr,s,ar'],
            'attribute' => ['required','is:array'],

            // Optional fields
            'code' => ['optional','is:alphadash'],
            'attribute-cost' => ['optional','is:text'],
            'free-cost' => ['optional','is:integer'],
            'divinity-cost' => ['optional','is:integer'],
            'atk' => ['optional','is:integer'],
            'def' => ['optional','is:integer'],
            'race' => ['optioanl','is:text'],
            'text' => ['optioanl','is:text'],
            'flavor-text' => ['optional','is:text'],
            'artist-name' => ['optional','is:text'],

        ]);
        $validation->validate();

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

        $validation = new Validation;
        $validation->setData($input);
        $validation->setRules([
            
            // Required fields
            'narp' => ['required','is:integer','enum:0,1,2,3'],
            'set' => ['required','except:0'],
            'number' => ['required','is:integer'],
            'back-side' => ['required','is:integer','enum:0,1,2,3,4'],
            'name' => ['required','is:text','except:'],
            'type' => ['required','except:0'],
            'rarity' => ['required','enum:0,c,u,r,sr,s,ar'],
            'attribute' => ['required','is:array'],

            // Optional fields
            'image' => ['optional','is:file'],
            'code' => ['optional','is:alphadash'],
            'attribute-cost' => ['optional','is:text'],
            'free-cost' => ['optional','is:integer'],
            'divinity-cost' => ['optional','is:integer'],
            'atk' => ['optional','is:integer'],
            'def' => ['optional','is:integer'],
            'race' => ['optional','is:text'],
            'text' => ['optional','is:text'],
            'flavor-text' => ['optional','is:text'],
            'artist-name' => ['optional','is:text'],

        ]);
        $validation->validate();

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
