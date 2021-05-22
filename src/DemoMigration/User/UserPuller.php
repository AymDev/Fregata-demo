<?php

namespace App\DemoMigration\User;

use Doctrine\DBAL\Connection;
use Fregata\Migration\Migrator\Component\BatchPullerInterface;

/**
 * As there are many users in the database, the rows are fetched by batch
 */
class UserPuller implements BatchPullerInterface
{
    private ?int $count = null;
    private Connection $source;

    public function __construct(Connection $source)
    {
        $this->source = $source;
    }

    /**
     * Do not pull known inactive users
     */
    public function pull(): \Generator
    {
        $batchSize = 50;
        $offset = 0;

        while ($offset < $this->count()) {
            yield $this->source->createQueryBuilder()
                ->select('*')
                ->from('app_user', 'u')
                ->where('u.login IS NULL')
                ->orWhere('u.login >= :date')
                ->setParameter('date', (new \DateTime('-1 year'))->format('Y-m-d'))
                ->setFirstResult($offset)
                ->setMaxResults($batchSize)
                ->execute()
                ->fetchAllAssociative()
            ;

            $offset += $batchSize;
        }
    }

    public function count(): ?int
    {
        if (null === $this->count) {
            $this->count = $this->source->createQueryBuilder()
                ->select('COUNT(*)')
                ->from('app_user', 'u')
                ->where('u.login IS NULL')
                ->orWhere('u.login >= :date')
                ->setParameter('date', (new \DateTime('-1 year'))->format('Y-m-d'))
                ->execute()
                ->fetchOne();
        }
        return $this->count;
    }
}
