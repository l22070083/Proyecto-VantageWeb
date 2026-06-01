<?php

namespace backend\controllers;

use Yii;
use backend\models\Rol;
use backend\models\search\RolSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\PermisosHelpers;

/**
 * RolController implementa las acciones CRUD para el modelo Rol.
 */
class RolController extends Controller
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
     * Lista todos los modelos Rol.
     */
    public function actionIndex()
    {
        // Solo administradores pueden ver la lista de roles
        PermisosHelpers::requerirMinimoRol('Admin');

        $searchModel = new RolSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Muestra un solo modelo Rol.
     */
    public function actionView($id)
    {
        PermisosHelpers::requerirMinimoRol('Admin');

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Crea un nuevo modelo Rol.
     */
    public function actionCreate()
    {
        PermisosHelpers::requerirMinimoRol('Admin');

        $model = new Rol();

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
     * Actualiza un modelo Rol existente.
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
     * Elimina un modelo Rol existente.
     */
    public function actionDelete($id)
    {
        PermisosHelpers::requerirMinimoRol('Admin');

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Busca el modelo Rol basado en su valor de clave primaria.
     */
    protected function findModel($id)
    {
        if (($model = Rol::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La página solicitada no existe.');
    }
}