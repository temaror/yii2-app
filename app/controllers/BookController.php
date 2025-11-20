<?php

namespace app\controllers;

use app\models\Book;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class BookController extends RestController
{
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'only' => ['create', 'update', 'delete'],
        ];

        return $behaviors;
    }

    public function verbs()
    {
        return [
            'index' => ['GET'],
            'view' => ['GET'],
            'create' => ['POST'],
            'update' => ['PUT', 'PATCH'],
            'delete' => ['DELETE'],
        ];
    }

    public function actionIndex()
    {
        $query = Book::find();

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSizeLimit' => [1, 100],
            ],
        ]);
    }

    public function actionView($id)
    {
        $book = Book::findOne($id);
        if (!$book) {
            throw new NotFoundHttpException('Book not found.');
        }

        return $book;
    }

    public function actionCreate()
    {
        $body = Yii::$app->request->bodyParams;

        $book = new Book();
        $book->load($body, '');
        $book->created_by = Yii::$app->user->id;

        if (!$book->validate()) {
            Yii::$app->response->statusCode = 422;
            return $book->errors;
        }

        $book->save(false);
        Yii::$app->response->statusCode = 201;

        return $book;
    }

    public function actionUpdate($id)
    {
        $book = Book::findOne($id);
        if (!$book) {
            throw new NotFoundHttpException('Book not found.');
        }

        $body = Yii::$app->request->bodyParams;
        $book->load($body, '');

        if (!$book->validate()) {
            Yii::$app->response->statusCode = 422;
            return $book->errors;
        }

        $book->save(false);
        return $book;
    }

    public function actionDelete($id)
    {
        $book = Book::findOne($id);
        if (!$book) {
            throw new NotFoundHttpException('Book not found.');
        }

        $book->delete();
        Yii::$app->response->statusCode = 204;
        return null;
    }
}
