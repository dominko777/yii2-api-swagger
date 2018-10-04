<?php

use yii\db\Migration;

/**
 * Class m181002_080206_insert_default_users
 */
class m181002_080206_insert_default_users extends Migration
{
    public $tableUser = '{{%user}}';

    public function safeUp()
    {
        $this->batchInsert(
            $this->tableUser,
            [
                'id',
                'username',
                'email',
                'auth_key',
                'password_hash',
                'password_reset_token',
                'status',
                'created_at',
                'updated_at',
            ],
            [
                [
                    'id' => 1,
                    'username' => 'admin',
                    'email' => 'admin@admin.ua',
                    'auth_key' => 'K0I15YM2f7u8eI2ch_iO6fLi6G-X2Ga1',
                    'password_hash' => '$2y$13$bjq2O8ZrnHViS5fDouW.Ku69WhX5zquFWkCDClJR2iN1CWd7ASu3G',
                    'password_reset_token' => null,
                    'status' => '10',
                    'created_at' => time(),
                    'updated_at' => time(),
                ],
                [
                    'id' => 2,
                    'username' => 'user',
                    'email' => 'user@user.ua',
                    'auth_key' => 'fSHT6aE5blrAdObj8UYDrGvvez04RIj',
                    'password_hash' => '$2y$13$TwfNlUdeVe2Bd4msOW7KHu8Uhqm1/L4roAabVjpwA7frGDi1S/.6W',
                    'password_reset_token' => null,
                    'status' => '10',
                    'created_at' => time(),
                    'updated_at' => time(),
                ],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete($this->tableUser, 'id = 1');
        $this->delete($this->tableUser, 'id = 2');
    }

}
