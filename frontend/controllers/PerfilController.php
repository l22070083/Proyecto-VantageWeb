<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Perfil;
use frontend\models\search\PerfilSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl; 
use common\models\PermisosHelpers;
use common\models\RegistrosHelpers;

/**
 * PerfilController implements the CRUD actions for Perfil model.
 */
class PerfilController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'view', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'], 
                    ],
                ],
            ],
            // Segunda capa de seguridad: Verifica que el estado sea 'Activo'
            'access2' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'view', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
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

    public function actionIndex()
    {
        if ($ya_existe = RegistrosHelpers::userTiene('perfil')) {
            return $this->render('view', [
                'model' => $this->findModel($ya_existe),
            ]);
        } else {
            return $this->redirect(['create']);
        }
    }

    public function actionView()
    {
        if ($ya_existe = RegistrosHelpers::userTiene('perfil')) {
            return $this->render('view', [
                'model' => $this->findModel($ya_existe),
            ]);
        } else {
            return $this->redirect(['create']);
        }
    }

    public function actionCreate()
    {
        $model = new Perfil;
        $model->user_id = \Yii::$app->user->identity->id;      
    
        if ($ya_existe = RegistrosHelpers::userTiene('perfil')) {
            return $this->render('view', [
                'model' => $this->findModel($ya_existe),
            ]);
    
        } elseif ($model->load(Yii::$app->request->post()) && $model->save()){
            return $this->redirect(['view']);
                        
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate()
    {
        // Control de acceso por tipo de usuario (Capítulo 9)
        PermisosHelpers::requerirUpgradeA('Pago');
        
        if($model = Perfil::find()->where(['user_id' => 
            Yii::$app->user->identity->id])->one()) {
        
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view']);
            } else {
                return $this->render('update', [
                    'model' => $model, 
                ]);
            }
    
        } else {
            throw new NotFoundHttpException('No Existe el Perfil.');
        }
    }

    public function actionDelete()
    {
        $model = Perfil::find()->where(['user_id' => Yii::$app->user->identity->id])->one();
        $this->findModel($model->id)->delete();
        
        return $this->redirect(['site/index']);
    }

    /**
     * Finds the Perfil model based on its primary key value.
     */
    protected function findModel($id)
    {
        if (($model = Perfil::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}