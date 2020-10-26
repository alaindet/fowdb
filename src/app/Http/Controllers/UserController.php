<?php

namespace App\Http\Controllers;

use App\Base\Controller;
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
                url('cards/manage') => 'Game: Cards',
                url('sets/manage') => 'Game: Sets',
                url('clusters/manage') => 'Game: Clusters',
                url('rulings/manage') => 'Game: Rulings',
                url('restrictions/manage') => 'Play: Banned and Limited cards',
                url('cr/manage') => 'Play: Comprehensive Rules',
                url('images/trim') => 'Tool: Trim an image',
            ],
            'admin' => [
                url('artists') => 'Tool: Artists',
                url('lookup') => 'Admin: Lookup data',
                url('clint') => 'Admin: Clint commands',
                url('hash') => 'Admin: Hash a string',
                url('phpinfo') => 'Admin: PHP info',
            ],
        ];

        $result = [];

        foreach ($roles as $role) {
            $result = array_merge($result, $links[$role]);
        }

        return $result;
    }
}
