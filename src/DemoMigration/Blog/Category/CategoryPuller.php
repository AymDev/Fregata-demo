<?php

namespace App\DemoMigration\Blog\Category;

use Doctrine\DBAL\Connection;
use Fregata\Migration\Migrator\Component\PullerInterface;

class CategoryPuller implements PullerInterface
{
    private Connection $source;

    public function __construct(Connection $source)
    {
        $this->source = $source;
    }

    public function pull()
    {
        return $this->source->createQueryBuilder()
            ->select('*')
            ->from('blog_category')
            ->execute()
            ->fetchAllAssociative()
        ;
    }

    public function count(): ?int
    {
        return $this->source->createQueryBuilder()
            ->select('COUNT(*)')
            ->from('blog_category')
            ->execute()
            ->fetchOne()
        ;
    }
}
