<?php

namespace app\controllers;

use app\models\User;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\UnauthorizedHttpException;

class AuthController extends RestController
{
    public $enableCsrfValidation = false;

    public function verbs()
    {
        return [
            'login' => ['POST'],
        ];
    }

    public function actionLogin()
    {
        $body = Yii::$app->request->bodyParams;
        $username = $body['username'] ?? null;
        $password = $body['password'] ?? null;

        if (!$username || !$password) {
            throw new BadRequestHttpException('Логин и пароль обязательное значение.');
        }

        $user = User::find()->where(['username' => $username])->one();
        if (!$user || !$user->validatePassword($password)) {
            throw new UnauthorizedHttpException('Недействительные учетные данные.');
        }

        $token = $user->generateJwt();

        return [
            'token' => $token,
            'user' => $user,
        ];
    }
}
