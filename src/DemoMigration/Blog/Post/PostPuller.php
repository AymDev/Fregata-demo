<?php

namespace App\DemoMigration\Blog\Post;

use Doctrine\DBAL\Connection;
use Fregata\Migration\Migrator\Component\PullerInterface;

class PostPuller implements PullerInterface
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
            ->from('blog_post')
            ->execute()
            ->fetchAllAssociative()
        ;
    }

    public function count(): ?int
    {
        return $this->source->createQueryBuilder()
            ->select('COUNT(*)')
            ->from('blog_post')
            ->execute()
            ->fetchOne()
        ;
    }
}
