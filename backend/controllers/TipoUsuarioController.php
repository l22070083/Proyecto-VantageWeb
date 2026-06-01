<?php

namespace backend\controllers;

use Yii;
use backend\models\TipoUsuario;
use backend\models\search\TipoUsuarioSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\PermisosHelpers;

/**
 * TipoUsuarioController implements the CRUD actions for TipoUsuario model.
 */
class TipoUsuarioController extends Controller
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
     * Lists all TipoUsuario models.
     */
    public function actionIndex()
    {
        PermisosHelpers::requerirMinimoRol('Admin');

        $searchModel = new TipoUsuarioSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TipoUsuario model.
     */
    public function actionView($id)
    {
        PermisosHelpers::requerirMinimoRol('Admin');

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TipoUsuario model.
     */
    public function actionCreate()
    {
        PermisosHelpers::requerirMinimoRol('Admin');

        $model = new TipoUsuario();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing TipoUsuario model.
     */
    public function actionUpdate($id)
    {
        PermisosHelpers::requerirMinimoRol('Admin');

        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TipoUsuario model.
     */
    public function actionDelete($id)
    {
        PermisosHelpers::requerirMinimoRol('Admin');

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TipoUsuario model based on its primary key value.
     */
    protected function findModel($id)
    {
        if (($model = TipoUsuario::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La página solicitada no existe.');
    }
}