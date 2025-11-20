<?php

namespace app\controllers;

use app\models\User;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class UserController extends RestController
{
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'only' => ['view'],
        ];

        return $behaviors;
    }

    public function verbs()
    {
        return [
            'create' => ['POST'],
            'view' => ['GET'],
        ];
    }

    public function actionCreate()
    {
        $body = Yii::$app->request->bodyParams;

        $password = $body['password'] ?? null;
        if (!$password) {
            throw new BadRequestHttpException('Требуется пароль.');
        }

        $user = new User();
        $user->username = $body['username'] ?? null;
        $user->email = $body['email'] ?? null;
        $user->setPassword($password);

        if (!$user->validate()) {
            Yii::$app->response->statusCode = 422;
            return $user->errors;
        }

        $user->save(false);

        Yii::$app->response->statusCode = 201;
        return $user;
    }

    public function actionView($id)
    {
        $user = User::findOne($id);
        if (!$user) {
            throw new NotFoundHttpException('Пользователь не найден.');
        }

        return $user;
    }
}
