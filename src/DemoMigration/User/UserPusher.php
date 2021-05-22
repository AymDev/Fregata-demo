<?php

namespace App\DemoMigration\User;

use Doctrine\DBAL\Connection;
use Fregata\Migration\Migrator\Component\PusherInterface;

/**
 * Save the users to the target database.
 */
class UserPusher implements PusherInterface
{
    private Connection $target;

    public function __construct(Connection $target)
    {
        $this->target = $target;
    }

    /**
     * @param array $data a single row from the UserPuller return value
     */
    public function push($data): int
    {
        return $this->target->createQueryBuilder()
            ->insert('app_user')
            ->values([
                'username'   => ':username',
                'password'   => ':password',
                'last_login' => ':last_login',
            ])
            ->setParameters([
                'username'   => $data['name'],
                'password'   => password_hash($data['pass'], PASSWORD_DEFAULT),
                'last_login' => $data['login'],
            ])
            ->execute()
        ;
    }
}
