<?php

use yii\db\Migration;

/**
 * Handles the creation of table `favorite_movie`.
 */
class m181002_112625_create_favorite_movie_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('favorite_movie', [
            'id' => $this->primaryKey(),
            'movie_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-favorite_movie-movie_id',
            'favorite_movie',
            'movie_id'
        );

        $this->addForeignKey(
            'fk-favorite_movie-movie_id',
            'favorite_movie',
            'movie_id',
            'movie',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-favorite_movie-user_id',
            'favorite_movie',
            'user_id'
        );

        $this->addForeignKey(
            'fk-favorite_movie-user_id',
            'favorite_movie',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey(
            'fk-favorite_movie-movie_id',
            'favorite_movie'
        );

        $this->dropIndex(
            'idx-favorite_movie-movie_id',
            'favorite_movie'
        );

        $this->dropForeignKey(
            'fk-favorite_movie-user_id',
            'favorite_movie'
        );

        $this->dropIndex(
            'idx-favorite_movie-user_id',
            'favorite_movie'
        );

        $this->dropTable('favorite_movie');
    }
}
