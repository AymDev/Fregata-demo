<?php

namespace App\DemoMigration\Product;

use Doctrine\DBAL\Connection;
use Fregata\Migration\Migrator\Component\PullerInterface;

class ProductPuller implements PullerInterface
{
    private Connection $source;

    public function __construct(Connection $source)
    {
        $this->source = $source;
    }

    public function pull()
    {
        return $this->source->executeQuery(<<<SQL
                SELECT CONCAT(brand, ' - ', model) AS name
                    , description
                    , price
                FROM product_computer
                UNION
                SELECT title
                    , CONCAT_WS(' ', 'By', first_name, last_name, '.', description)
                    , price
                FROM product_book b
                    INNER JOIN book_author a ON b.author_id = a.id
            SQL)
            ->fetchAllAssociative();
    }

    public function count(): ?int
    {
        return $this->source->executeQuery(<<<SQL
                SELECT SUM(total)
                FROM (
                    SELECT COUNT(*) AS total FROM product_computer
                    UNION ALL 
                    SELECT COUNT(*) FROM product_book
                ) product_count
            SQL)
            ->fetchOne();
    }
}
