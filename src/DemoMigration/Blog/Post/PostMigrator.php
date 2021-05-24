<?php

namespace App\DemoMigration\Blog\Post;

use App\DemoMigration\Blog\Category\CategoryMigrator;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\ForeignKeyConstraint;
use Fregata\Adapter\Doctrine\DBAL\ForeignKey\ForeignKey;
use Fregata\Adapter\Doctrine\DBAL\ForeignKey\Migrator\HasForeignKeysInterface;
use Fregata\Migration\Migrator\Component\Executor;
use Fregata\Migration\Migrator\Component\PullerInterface;
use Fregata\Migration\Migrator\Component\PusherInterface;
use Fregata\Migration\Migrator\DependentMigratorInterface;

/**
 * Migration of the blog posts.
 * Blog posts have a foreign key constraint to reference the category.
 *
 * See the PostPusher and CategoryPusher for more details on how the constraint is migrated.
 */
class PostMigrator implements HasForeignKeysInterface, DependentMigratorInterface
{
    private Connection $target;
    private PostPuller $puller;
    private PostPusher $pusher;
    private Executor $executor;

    public function __construct(Connection $target, PostPuller $puller, PostPusher $pusher, Executor $executor)
    {
        $this->target = $target;
        $this->puller = $puller;
        $this->pusher = $pusher;
        $this->executor = $executor;
    }

    public function getConnection(): Connection
    {
        return $this->target;
    }

    public function getForeignKeys(): array
    {
        /*
         * Get the foreign key constraints in the target database table with the DBAL SchemaManager
         * Then wrap each constrain we want to keep in a ForeignKey object.
         * The third argument of the ForeignKey constructor are column names of the constraint not allowing NULL. They
         * will accept NULL during the migration.
         */
        return array_map(
            fn(ForeignKeyConstraint $constraint) => new ForeignKey($constraint, 'blog_post', ['category_id']),
            $this->target->getSchemaManager()->listTableForeignKeys('blog_post')
        );
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

    public function getDependencies(): array
    {
        // The CategoryMigrator will be executed before the PostMigrator
        // This is just for demonstration here as the foreign key constraints are set in an after task
        return [
            CategoryMigrator::class
        ];
    }
}
