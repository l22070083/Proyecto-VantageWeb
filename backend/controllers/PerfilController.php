<?php

namespace backend\controllers;

use Yii;
use frontend\models\Perfil;
use backend\models\search\PerfilSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\PermisosHelpers;
use yii\web\ForbiddenHttpException;

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
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Perfil models.
     */
    public function actionIndex()
    {
        // Se mantiene Admin para ver la lista
        PermisosHelpers::requerirMinimoRol('Admin');

        $searchModel = new PerfilSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Perfil model.
     */
    public function actionView($id)
    {
        // Se mantiene Admin para ver un perfil individual
        PermisosHelpers::requerirMinimoRol('Admin');

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Perfil model.
     */
  public function actionCreate()
{
    // 1. Verificamos permisos primero
    PermisosHelpers::requerirMinimoRol('Admin');

    $model = new Perfil();

    if ($model->load(Yii::$app->request->post())) {
        
        // 2. CORRECCIÓN: Validamos que el usuario no sea nulo antes de asignar el ID
        if (!Yii::$app->user->isGuest) {
            $model->user_id = Yii::$app->user->identity->id;
            
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            // Si por alguna razón llegó aquí sin sesión, lo mandamos al login
            return $this->redirect(['site/login']);
        }
    }

    return $this->render('create', [
        'model' => $model,
    ]);
}
    /**
     * Updates an existing Perfil model.
     * CAMBIO: Ahora requiere 'SuperUsuario' según el libro.
     */
    public function actionUpdate($id)
    {
        // Cambiado de 'Admin' a 'SuperUsuario'
        PermisosHelpers::requerirMinimoRol('SuperUsuario');

        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Perfil model.
     * CAMBIO: Ahora requiere 'SuperUsuario' según el libro.
     */
    public function actionDelete($id)
    {
        // Cambiado de 'Admin' a 'SuperUsuario'
        PermisosHelpers::requerirMinimoRol('SuperUsuario');

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Perfil model based on its primary key value.
     */
    protected function findModel($id)
    {
        if (($model = Perfil::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La página solicitada no existe.');
    }
}