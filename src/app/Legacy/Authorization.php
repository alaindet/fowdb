<?php

namespace App\Legacy;

use App\Base\Singleton;
use App\Services\Session;
use App\Exceptions\AuthorizationException;

class Authorization
{
    use Singleton;

    /**
     * Name for the session hash
     */
    public const NAME = 'admin-hash';

    /**
     * Define user roles
     */
    public const ROLE_PUBLIC = 0;
    public const ROLE_ADMIN = 1;
    public const ROLE_USER = 2;
    public const ROLE_JUDGE = 3;
    
    /**
     * Maps some higher level roles to lower levels ones to alias them
     * Ex.: "Admin" also acts as "User" and "Judge"
     * Ex.: "Judge" also acts as "User"
     *
     * @var array
     */
    public $actsAs;

    /**
     * Maps a role to its authorization level
     *
     * @var array
     */
    public $roleToLevel;

    protected function __construct()
    {
        // Define aliases for higher level roles (more than basic users)
        $this->actsAs = [
            self::ROLE_ADMIN => [self::ROLE_USER, self::ROLE_JUDGE],
            self::ROLE_JUDGE => [self::ROLE_USER],
        ];

        // Define map: from role name to its authorization level
        $this->roleToLevel = [
            'public' => self::ROLE_PUBLIC,
            'admin' => self::ROLE_ADMIN,
            'user' => self::ROLE_USER,
            'judge' => self::ROLE_JUDGE,
        ];
    }

    public function logged(): bool
    {
        return $this->level() !== self::ROLE_PUBLIC;
    }

    /**
     * Roles are defined into __construct but this function checks the
     * database to match the same hash string
     *
     * @return integer
     */
    public function level(): int
    {
        // ERROR: No admin hash stored into session
        if (!Session::exists(self::NAME)) return self::ROLE_PUBLIC;

        $user = database()
            ->select(
                statement('select')
                    ->select('roles_id')
                    ->from('users')
                    ->where('remember_token = :hash')
                    ->limit(1)
            )
            ->bind([':hash' => Session::get(self::NAME)])
            ->first();

        // ERROR: Invalid hash stored into session
        if (empty($user)) return self::ROLE_PUBLIC;

        // Authorization level > 0
        return (int) $user['roles_id'];
    }

    /**
     * Checks if current user's level matches given role's level
     *
     * @param string|string[] $role Role to check
     * @return boolean TRUE if logged user is authorized for given role
     */
    public function check($roles): bool
    {
        // Normalize input as an array of strings (role names)
        if (is_string($roles)) $roles = [$roles];

        $requiredLevels = [];

        foreach ($roles as $role) {

            // ERROR: Invalid role
            if (!isset($this->roleToLevel[$role])) {
                throw new AuthorizationException(
                    "Role <strong>{$role}</strong> does not exist on FoWDB."
                );
            }

            $requiredLevels[] = $this->roleToLevel[$role];

        }

        $currentLevel = $this->level();
        $aliases = $this->actsAs[$currentLevel] ?? [];
        $currentLevels = array_merge([$currentLevel], $aliases);

        // Loop on each current level (including aliases)
        foreach ($currentLevels as $currentLevel) {
            if (in_array($currentLevel, $requiredLevels)) return true;
        }

        return false;
    }

    /**
     * Checks if a user level meets the passed levels
     * Bounces back if not
     * 
     * @param $requiredLevels List of allowed levels
     * @return void
     */
    public function allow(array $requiredLevels = []): void
    {
        $currentLevel = $this->level();
        $aliases = $this->actsAs[$currentLevel];
        $requiredLevels = array_merge($requiredLevels, $aliases);

        if (!in_array($currentLevel, $requiredLevels)) {
            throw new AuthorizationException(
                'You are not allowed to perform this action'
            );
        }
    }
}
