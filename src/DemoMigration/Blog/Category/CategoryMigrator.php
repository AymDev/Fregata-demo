<?php

namespace App\DemoMigration\Blog\Category;

use Fregata\Migration\Migrator\Component\Executor;
use Fregata\Migration\Migrator\Component\PullerInterface;
use Fregata\Migration\Migrator\Component\PusherInterface;
use Fregata\Migration\Migrator\MigratorInterface;

/**
 * Migration for the blog categories
 */
class CategoryMigrator implements MigratorInterface
{
    private CategoryPuller $puller;
    private CategoryPusher $pusher;
    private Executor $executor;

    public function __construct(CategoryPuller  $puller, CategoryPusher $pusher, Executor $executor)
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
