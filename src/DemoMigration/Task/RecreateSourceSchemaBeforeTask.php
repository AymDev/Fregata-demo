<?php

namespace App\DemoMigration\Task;

use Doctrine\DBAL\Connection;
use Fregata\Migration\TaskInterface;

/**
 * This task is (re)creating the source schema before data generation.
 * You shouldn't need this kind of task in a real project, it's here to set up a demo database.
 */
class RecreateSourceSchemaBeforeTask implements TaskInterface
{
    private Connection $source;

    public function __construct(Connection $source)
    {
        $this->source = $source;
    }

    public function execute(): ?string
    {
        $this->source->executeStatement(<<<SQL
            SET FOREIGN_KEY_CHECKS=0;

            -- User table
            DROP TABLE IF EXISTS app_user;
            CREATE TABLE app_user (
                id INT(4) NOT NULL AUTO_INCREMENT,
                name VARCHAR(50) NOT NULL,
                pass VARCHAR(255) NOT NULL,
                login DATETIME DEFAULT NULL,
                PRIMARY KEY (id)
            ) ENGINE=INNODB;

            -- Products: computer table
            DROP TABLE IF EXISTS product_computer;
            CREATE TABLE product_computer (
                id INT(4) NOT NULL AUTO_INCREMENT,
                brand VARCHAR(50) NOT NULL,
                model VARCHAR(100) NOT NULL,
                description TEXT DEFAULT NULL,
                price INT NOT NULL,
                PRIMARY KEY (id)
            ) ENGINE=INNODB;

            -- Products: book author table
            DROP TABLE IF EXISTS book_author;
            CREATE TABLE book_author (
                id INT(4) NOT NULL AUTO_INCREMENT,
                first_name VARCHAR(100) NOT NULL,
                last_name VARCHAR(100) NOT NULL,
                PRIMARY KEY (id)
            ) ENGINE=INNODB;

            -- Products: book table
            DROP TABLE IF EXISTS product_book;
            CREATE TABLE product_book (
                id INT(4) NOT NULL AUTO_INCREMENT,
                title VARCHAR(100) NOT NULL,
                author_id INT(4) NOT NULL,
                description TEXT DEFAULT NULL,
                price INT NOT NULL,
                PRIMARY KEY (id),
                CONSTRAINT fk_book_author FOREIGN KEY (author_id)
                    REFERENCES book_author(id)
            ) ENGINE=INNODB;

            -- Blog: category table
            DROP TABLE IF EXISTS blog_category;
            CREATE TABLE blog_category (
                id INT(4) NOT NULL AUTO_INCREMENT,
                name VARCHAR(50) NOT NULL,
                PRIMARY KEY (id)
            ) ENGINE=INNODB AUTO_INCREMENT=100;

            -- Blog: post table
            DROP TABLE IF EXISTS blog_post;
            CREATE TABLE blog_post (
                id INT(4) NOT NULL AUTO_INCREMENT,
                title VARCHAR(100) NOT NULL,
                category_id INT(4) NOT NULL,
                content TEXT NOT NULL,
                published_at DATETIME DEFAULT NULL,
                PRIMARY KEY (id),
                CONSTRAINT fk_post_category FOREIGN KEY (category_id)
                    REFERENCES blog_category(id)
            ) ENGINE=INNODB;
            
            SET FOREIGN_KEY_CHECKS=1;
        SQL);

        return null;
    }
}
