<?php

namespace api\modules\v1\controllers;

use common\models\FavoriteMovie;
use common\models\Movie;
use Yii;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\Url;
use yii\rest\ActiveController;
use yii\web\ServerErrorHttpException;

/**
 * @OA\Info(
 *   title="Movie API",
 *   version="1.0.0",
 *   @OA\Contact(
 *     email="lllllexxxxx@gmail.com"
 *   )
 * )
 */

// Openapi для экшена actionCreate
/**
 * @OA\Post(
 *     path="/movie/create",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 type="object",
 *                 @OA\Property(
 *                     property="name",
 *                     description="Навание нового фильма",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="user_id",
 *                     description="Id пользователя",
 *                     type="integer"
 *                 ),
 *             )
 *         )
 *     ),
 *     @OA\Parameter(
 *         description="Access token",
 *         in="path",
 *         name="access-token",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *     tags={"фильм"},
 *     operationId="actionCreate",
 *     summary="Создание фильма",
 *     description="",
 *     @OA\RequestBody(
 *         required=true,
 *         description="Данные фильма и пользователя",
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Фильм был создан",
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Недостаточно данных для создания фильма",
 *     ),
 * )
 */
class MovieController extends ActiveController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = ['class' => QueryParamAuth::className(),];
        return $behaviors;
    }

    public function actions(){
        $actions = parent::actions();
        unset($actions['update']);
        unset($actions['delete']);
        unset($actions['view']);
        unset($actions['index']);
        return $actions;
    }

    /**
     * @OA\Post(
     *     path="/movie/add-favorite",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="movie_id",
     *                     description="Id фильма",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="user_id",
     *                     description="Id пользователя",
     *                     type="integer"
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Access token",
     *         in="path",
     *         name="access-token",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     tags={"фильм", "избранное"},
     *     operationId="actionAddFavorite",
     *     summary="Добавить фильм в избранное",
     *     description="",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Данные фильма и пользователя",
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Фильм был добавлен в избранное",
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Фильм уже был добавлен в избранное",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Недостаточно данных для добавления фильма в избранное",
     *     ),
     * )
     */
    public function actionAddFavorite()
    {
        $params = Yii::$app->getRequest()->getBodyParams();
        $model = FavoriteMovie::find()->where('movie_id=:movie_id AND user_id=:user_id',
            [':movie_id'=>$params['movie_id'], ':user_id'=>$params['user_id']])->one();
        if ($model) {
            return [
                'status' => 'error',
                'message' => 'Закладка уже была добавлена!',
            ];
        }

        $model = new FavoriteMovie();

        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($model->save()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $id = implode(',', array_values($model->getPrimaryKey(true)));
            $response->getHeaders()->set('Location', Url::toRoute(['view', 'id' => $id], true));
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }

        return $model;
    }

    /**
     * @OA\Get(
     *     path="/movie/not-favorite-list",
     *     @OA\Parameter(
     *         description="Access token",
     *         in="path",
     *         name="access-token",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="User Id",
     *         in="path",
     *         name="user_id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     tags={"фильм", "избранное"},
     *     operationId="actionNotFavoriteList",
     *     summary="Получение перечня фильмов для пользователя, которые исключают фильмы добавленные в избранное",
     *     description="",
     *     @OA\Response(
     *         response=200,
     *         description="Список фильмов, которые исключают фильмы добавленные в избранное",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Недостаточно данных о пользователе",
     *     ),
     * )
     */
    public function actionNotFavoriteList()
    {
        $params = Yii::$app->request->get();
        if (!isset($params['user_id']) || !$params['user_id']) {
            Yii::$app->response->statusCode = 400;
            return [
                'status' => 'error',
                'message' => 'Недостаточно данных о пользователе',
            ];
        }
        $favorites = FavoriteMovie::find()->where('user_id=:user_id', [':user_id'=>$params['user_id']])->asArray()->all();
        $favIds = [];
        foreach ($favorites as $key => $val) {
            $favIds[] = $val['movie_id'];
        }
        $movies = Movie::find()->where(['not in','id', $favIds])->all();
        return $movies;
    }

    public
        $modelClass = 'common\models\Movie';
}