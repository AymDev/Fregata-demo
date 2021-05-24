<?php

namespace App\DemoMigration\Product;

use Fregata\Migration\Migrator\Component\Executor;
use Fregata\Migration\Migrator\Component\PullerInterface;
use Fregata\Migration\Migrator\Component\PusherInterface;
use Fregata\Migration\Migrator\MigratorInterface;

/**
 * Migration from computer and books in the source to products in the target.
 */
class ProductMigrator implements MigratorInterface
{
    private ProductPuller $puller;
    private ProductPusher $pusher;
    private Executor $executor;

    public function __construct(ProductPuller $puller, ProductPusher $pusher, Executor $executor)
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
