<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl; 
use common\models\PermisosHelpers;
use common\models\RegistrosHelpers;
use frontend\models\Perfil; 

class UpgradeController extends Controller
{
    /**
     * behaviors - Control de acceso para el proceso de Upgrade
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return PermisosHelpers::requerirEstado('Activo');
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Acción principal actualizada para enviar el objeto Perfil a la vista
     */
    public function actionIndex()
    {
        // Buscamos el registro de Perfil asociado al usuario actual
        $persona = Perfil::find()->where(['user_id' => Yii::$app->user->identity->id])->one();

        // Enviamos el objeto a la vista dentro de un arreglo con la llave 'persona'
        return $this->render('index', [
            'persona' => $persona,
        ]);
    }
}