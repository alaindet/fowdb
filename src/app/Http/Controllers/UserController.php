<?php

namespace App\Http\Controllers;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Views\Page;
use App\Legacy\Authorization;

class UserController extends Controller
{
    public function showProfile()
    {
        $level = auth()->level();

        if ($level === Authorization::ROLE_ADMIN) redirect('admin');
        if ($level === Authorization::ROLE_JUDGE) redirect('judge');
    }

    public function adminShowProfile(): string
    {
        return (new Page)
            ->template('pages/admin/profile')
            ->title('Admin Profile')
            ->variables([
                'links' => $this->menuLinks(['judge', 'admin'])
            ])
            ->render();
    }

    public function judgeShowProfile(): string
    {
        return (new Page)
            ->template('pages/judge/profile')
            ->title('Judge Profile')
            ->variables([
                'links' => $this->menuLinks(['judge'])
            ])
            ->render();
    }

    private function menuLinks(array $roles): array
    {
        $links = [
            'judge' => [
                url('cards/manage') => 'Cards',
                url('sets/manage') => 'Sets',
                url('clusters/manage') => 'Clusters',
                url('cr/manage') => 'Comprehensive Rules',
                url('restrictions/manage') => 'Banned and Limited cards',
                url('rulings/manage') => 'Rulings',
                url('images/trim') => 'Trim an image',
            ],
            'admin' => [
                url('lookup') => 'Lookup data',
                url('clint') => 'Clint commands',
                url('hash') => 'Hash a string',
                url('phpinfo') => 'PHP info',
                url('artists') => 'Artists',
            ],
        ];

        $result = [];

        foreach ($roles as $role) {
            $result = array_merge($result, $links[$role]);
        }

        return $result;
    }
}
