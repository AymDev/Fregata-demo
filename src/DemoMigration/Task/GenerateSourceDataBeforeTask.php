<?php

namespace App\DemoMigration\Task;

use Doctrine\DBAL\Connection;
use Faker\Generator;
use Fregata\Migration\TaskInterface;

/**
 * This before task generates fake data to migrate in the demo source database.
 * You shouldn't need this kind of task in a real project.
 */
class GenerateSourceDataBeforeTask implements TaskInterface
{
    private Connection $source;
    private Generator $faker;

    public function __construct(Connection $source, Generator $faker)
    {
        $this->source = $source;
        $this->faker = $faker;
    }
    public function execute(): ?string
    {
        $timerStart = microtime(true);
        $itemCounter = 0;

        // Load users
        for ($i = 0; $i < 1000; $i++) {
            $login= $this->faker->optional('0.85')->dateTimeBetween('-2 year');

            $itemCounter += $this->source->insert('app_user', [
                'name'  => $this->faker->userName,
                'pass'  => $this->faker->password,
                'login' => $login === null ? null : $login->format('Y-m-d H:i:s'),
            ]);
        }

        // Load computer products
        for ($i = 0; $i < 50; $i++) {
            $itemCounter += $this->source->insert('product_computer', [
                'brand'       => $this->faker->company,
                'model'       => $this->faker->word,
                'description' => $this->faker->optional()->realText(),
                'price'       => $this->faker->numberBetween(10000, 100000),
            ]);
        }

        // Load book products and authors
        for ($i = 0; $i < 10; $i++) {
            $itemCounter += $this->source->insert('book_author', [
                'first_name' => $this->faker->firstName,
                'last_name'  => $this->faker->lastName,
            ]);

            $authorId = $this->source->lastInsertId();

            for ($j = 0; $j < 5; $j++) {
                $itemCounter += $this->source->insert('product_book', [
                    'title'       => $this->faker->sentence,
                    'author_id'   => $authorId,
                    'description' => $this->faker->optional()->realText(),
                    'price'       => $this->faker->numberBetween(500, 10000),
                ]);
            }
        }

        // Load blog posts and categories
        for ($i = 0; $i < 5; $i++) {
            $itemCounter += $this->source->insert('blog_category', [
                'name' => $this->faker->word,
            ]);

            $categoryId = $this->source->lastInsertId();

            for ($j = 0; $j < 100; $j++) {
                $publishedAt = $this->faker->optional('0.9')->dateTimeBetween('-3 year');

                $itemCounter += $this->source->insert('blog_post', [
                    'title'        => $this->faker->sentence,
                    'category_id'  => $categoryId,
                    'content'      => $this->faker->realText(),
                    'published_at' => $publishedAt === null ? null : $publishedAt->format('Y-m-d H:i:s'),
                ]);
            }
        }

        // Return message with infos
        $timerStop = microtime(true);
        $timerInterval = $timerStop - $timerStart;
        $insertRate = $itemCounter / $timerInterval;
        $memory = memory_get_peak_usage(false) / (1024 * 1024);

        return sprintf(
            'Generated %d items in %.2f seconds (%.2f items/s) using %.2f MB.',
            $itemCounter,
            $timerInterval,
            $insertRate,
            $memory
        );
    }
}
