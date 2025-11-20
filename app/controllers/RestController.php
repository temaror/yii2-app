<?php

namespace app\controllers;

use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;
use yii\filters\ContentNegotiator;
use yii\web\Response;

class RestController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];

        return $behaviors;
    }
}
