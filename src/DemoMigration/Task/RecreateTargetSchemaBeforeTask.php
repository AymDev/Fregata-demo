<?php

namespace App\DemoMigration\Task;

use Doctrine\DBAL\Connection;
use Fregata\Migration\TaskInterface;

/**
 * This task is (re)creating the target schema to empty it.
 * You shouldn't need this kind of task in a real project.
 */
class RecreateTargetSchemaBeforeTask implements TaskInterface
{
    private Connection $target;

    public function __construct(Connection $target)
    {
        $this->target = $target;
    }

    public function execute(): ?string
    {
        $this->target->executeStatement(<<<SQL
            SET session_replication_role = 'replica';
            
            -- User sequence
            DROP SEQUENCE IF EXISTS app_user_seq CASCADE;
            CREATE SEQUENCE app_user_seq;
            -- User table
            DROP TABLE IF EXISTS app_user;
            CREATE TABLE app_user (
                id INT NOT NULL PRIMARY KEY DEFAULT NEXTVAL('app_user_seq'),
                username VARCHAR(50) NOT NULL,
                password VARCHAR(255) NOT NULL,
                last_login TIMESTAMP WITHOUT TIME ZONE DEFAULT NULL
            );

            -- Product sequence
            DROP SEQUENCE IF EXISTS product_seq CASCADE;
            CREATE SEQUENCE product_seq;
            -- Product table
            DROP TABLE IF EXISTS product;
            CREATE TABLE product (
                id INT NOT NULL PRIMARY KEY DEFAULT NEXTVAL('product_seq'),
                name VARCHAR(255) NOT NULL,
                description TEXT DEFAULT NULL,
                price INT NOT NULL
            );

            -- Blog: category sequence
            DROP SEQUENCE IF EXISTS blog_category_seq CASCADE;
            CREATE SEQUENCE blog_category_seq;
            -- Blog: category table
            DROP TABLE IF EXISTS blog_category CASCADE;
            CREATE TABLE blog_category (
                id INT NOT NULL PRIMARY KEY DEFAULT NEXTVAL('blog_category_seq'),
                name VARCHAR(50) NOT NULL
            );

            -- Blog: post sequence
            DROP SEQUENCE IF EXISTS blog_post_seq CASCADE;
            CREATE SEQUENCE blog_post_seq;
            -- Blog: post table
            DROP TABLE IF EXISTS blog_post;
            CREATE TABLE blog_post (
                id INT NOT NULL PRIMARY KEY DEFAULT NEXTVAL('blog_post_seq'),
                title VARCHAR(100) NOT NULL,
                category_id INT NOT NULL,
                content TEXT NOT NULL,
                published_at TIMESTAMP WITHOUT TIME ZONE DEFAULT NULL,
                CONSTRAINT fk_post_category FOREIGN KEY (category_id)
                    REFERENCES blog_category(id)
            );
            
            SET session_replication_role = 'origin';
        SQL);

        return null;
    }
}
