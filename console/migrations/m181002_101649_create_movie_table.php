<?php

use yii\db\Migration;

/**
 * Handles the creation of table `movie`.
 */
class m181002_101649_create_movie_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('movie', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->text(),
            'year' => $this->smallInteger(4),
            'created_at' => $this->integer(),
            'user_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-movie-user_id',
            'movie',
            'user_id'
        );

        $this->addForeignKey(
            'fk-movie-user_id',
            'movie',
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
            'fk-movie-user_id',
            'movie'
        );

        $this->dropIndex(
            'idx-movie-user_id',
            'movie'
        );

        $this->dropTable('movie');
    }
}
