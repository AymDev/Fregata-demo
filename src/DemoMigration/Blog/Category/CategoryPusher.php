<?php

namespace App\DemoMigration\Blog\Category;

use Doctrine\DBAL\Connection;
use Fregata\Adapter\Doctrine\DBAL\ForeignKey\CopyColumnHelper;
use Fregata\Migration\Migrator\Component\PusherInterface;

class CategoryPusher implements PusherInterface
{
    private Connection $target;
    private CopyColumnHelper $columnHelper;

    public function __construct(Connection $target, CopyColumnHelper $columnHelper)
    {
        $this->target = $target;
        $this->columnHelper = $columnHelper;
    }

    /**
     * @param array $data single row from the CategoryPuller return value
     */
    public function push($data): int
    {
        // The categories are referenced in the post tables, Fregata creates a temporary column to save the old
        // referenced primary key
        // The helper will build the temporary column name using the original table and column names
        $tempPrimaryKeyColumn = $this->columnHelper->foreignColumn('blog_category', 'id');

        return $this->target->createQueryBuilder()
            ->insert('blog_category')
            ->values([
                $tempPrimaryKeyColumn => ':id',
                'name'                => ':name',
            ])
            ->setParameters($data)
            ->execute()
        ;
    }
}
