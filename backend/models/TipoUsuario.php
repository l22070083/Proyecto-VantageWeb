<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "tipo_usuario".
 *
 * @property int $id
 * @property string $tipo_usuario_nombre
 * @property int $tipo_usuario_valor
 */
class TipoUsuario extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_usuario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tipo_usuario_nombre', 'tipo_usuario_valor'], 'required'],
            [['tipo_usuario_valor'], 'integer'],
            [['tipo_usuario_nombre'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tipo_usuario_nombre' => 'Tipo Usuario Nombre',
            'tipo_usuario_valor' => 'Tipo Usuario Valor',
        ];
    }
    public function getTipoUsuario()
{
    return $this->hasOne(TipoUsuario::className(), ['id' => 'tipo_usuario_id']);
}
/**
 * get tipo usuario nombre
 *
 */
public function getTipoUsuarioNombre()
{
    return $this->tipoUsuario ? $this->tipoUsuario->tipo_usuario_nombre : '- sin tipo usuario -';
}
/**
 * get lista de tipos de usuario para lista desplegable
 */
public static function getTipoUsuarioLista()
{
    $dropciones = TipoUsuario::find()->asArray()->all();
    return ArrayHelper::map($dropciones, 'id', 'tipo_usuario_nombre');
}
/**
 * get tipo usuario id
 *
 */
public function getTipoUsuarioId()
{
    return $this->tipoUsuario ? $this->tipoUsuario->id : 'ninguno';
}

}
