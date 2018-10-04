<?php

namespace api\modules\v1\controllers;

use common\models\User;
use yii\rest\Controller;

class AuthController extends Controller
{
    protected function verbs()
    {
        return [
            'login' => ['POST'],
        ];
    }


    public function actionLogin()
    {
        $username = !empty($_POST['username']) ? $_POST['username'] : '';
        $password = !empty($_POST['password']) ? $_POST['password'] : '';

        if (empty($username) || empty($password)) {
            $response = [
                'status' => 'error',
                'message' => 'username & password is empty!',
                'data' => '',
            ];
        } else {

            $user = User::findByUsername($username);
            if (!empty($user)) {
                if ($user->validatePassword($password)) {
                    $response = [
                        'status' => 'success',
                        'message' => 'login success!',
                        'data' => [
                            'id' => $user->id,
                            'username' => $user->username,
                            'token' => $user->auth_key,
                        ]
                    ];
                }
                else {
                    $response = [
                        'status' => 'error',
                        'message' => 'Password is wrong',
                        'data' => '',
                    ];
                }
            }
            else {
                $response = [
                    'status' => 'error',
                    'message' => 'Username is wrong',
                    'data' => '',
                ];
            }
        }
        return $response;
    }
}