<?php

namespace App\DemoMigration\Blog\Category;

use Doctrine\DBAL\Connection;
use Fregata\Migration\Migrator\Component\PusherInterface;

class CategoryPusher implements PusherInterface
{
    private Connection $target;

    public function __construct(Connection $target)
    {
        $this->target = $target;
    }

    /**
     * @param array $data single row from the CategoryPuller return value
     */
    public function push($data): int
    {
        return $this->target->createQueryBuilder()
            ->insert('blog_category')
            ->values([
                'name' => ':name'
            ])
            ->setParameters($data)
            ->execute()
        ;
    }
}
