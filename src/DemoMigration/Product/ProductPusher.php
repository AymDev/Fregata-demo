<?php

namespace App\DemoMigration\Product;

use Doctrine\DBAL\Connection;
use Fregata\Migration\Migrator\Component\PusherInterface;

class ProductPusher implements PusherInterface
{
    private Connection $target;

    public function __construct(Connection $target)
    {
        $this->target = $target;
    }

    /**
     * @param array $data single row from the ProductPuller return value
     */
    public function push($data): int
    {
        return $this->target->createQueryBuilder()
            ->insert('product')
            ->values([
                'name'        => ':name',
                'description' => ':description',
                'price'       => ':price',
            ])
            ->setParameters($data)
            ->execute()
        ;
    }
}
