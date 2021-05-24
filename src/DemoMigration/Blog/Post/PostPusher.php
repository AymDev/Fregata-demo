<?php

namespace App\DemoMigration\Blog\Post;

use Doctrine\DBAL\Connection;
use Fregata\Adapter\Doctrine\DBAL\ForeignKey\CopyColumnHelper;
use Fregata\Migration\Migrator\Component\PusherInterface;

class PostPusher implements PusherInterface
{
    private Connection $target;
    private CopyColumnHelper $columnHelper;

    public function __construct(Connection $target, CopyColumnHelper $columnHelper)
    {
        $this->target = $target;
        $this->columnHelper = $columnHelper;
    }

    public function push($data): int
    {
        // Fregata creates a temporary column to save the old foreign key value.
        // The helper can build temporary column names by providing the original table and column names
        $tempForeignKeyColumn = $this->columnHelper->localColumn('blog_post', 'category_id');

        return $this->target->createQueryBuilder()
            ->insert('blog_post')
            ->values([
                'title'               => ':title',
                $tempForeignKeyColumn => ':category_id',
                'content'             => ':content',
                'published_at'        => ':published_at',
            ])
            ->setParameters($data)
            ->execute()
        ;
    }
}
