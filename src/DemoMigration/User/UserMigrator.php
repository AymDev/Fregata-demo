<?php

namespace App\DemoMigration\User;

use Fregata\Migration\Migrator\Component\Executor;
use Fregata\Migration\Migrator\Component\PullerInterface;
use Fregata\Migration\Migrator\Component\PusherInterface;
use Fregata\Migration\Migrator\MigratorInterface;

/**
 * This migrator migrates the user table
 */
class UserMigrator implements MigratorInterface
{
    private UserPuller $puller;
    private UserPusher $pusher;
    private Executor $executor;

    public function __construct(UserPuller $puller, UserPusher $pusher, Executor $executor)
    {
        $this->puller = $puller;
        $this->pusher = $pusher;
        $this->executor = $executor;
    }

    public function getPuller(): PullerInterface
    {
        return $this->puller;
    }

    public function getPusher(): PusherInterface
    {
        return $this->pusher;
    }

    public function getExecutor(): Executor
    {
        return $this->executor;
    }
}
