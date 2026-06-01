<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\IdentityInterface;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;

use frontend\models\Perfil;
use backend\models\Rol;
use backend\models\Estado;
use backend\models\TipoUsuario;
use common\models\ValorHelpers;

class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return 'user';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function rules()
    {
        return [
            ['estado_id', 'default', 'value' => ValorHelpers::getEstadoId('Activo')],
            [['estado_id'],'in', 'range'=>array_keys($this->getEstadoLista())],

            [['rol_id'],'in', 'range'=>array_keys($this->getRolLista())],

            ['tipo_usuario_id', 'default', 'value' => 1],
            [['tipo_usuario_id'],'in', 'range'=>array_keys($this->getTipoUsuarioLista())],

            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'rolNombre' => Yii::t('app', 'Rol'),
            'estadoNombre' => Yii::t('app', 'Estado'),
            'perfilId' => Yii::t('app', 'Perfil'),
            'perfilLink' => Yii::t('app', 'Perfil'),
            'userLink' => Yii::t('app', 'User'),
            'username' => Yii::t('app', 'User'),
            'tipoUsuarioNombre' => Yii::t('app', 'Tipo Usuario'),
            'tipoUsuarioId' => Yii::t('app', 'Tipo Usuario'),
            'userIdLink' => Yii::t('app', 'ID'),
        ];
    }

    /* ===================== MÉTODOS DE NOMBRE (Nuevos) ===================== */

    /**
     * Devuelve el nombre del rol buscando en la relación
     */
    public function getRolNombre()
    {
        return $this->rol ? $this->rol->rol_nombre : '(not set)';
    }

    /**
     * Devuelve el nombre del estado buscando en la relación
     */
    public function getEstadoNombre()
    {
        return $this->estado ? $this->estado->estado_nombre : '(not set)';
    }

    /**
     * Devuelve el nombre del tipo de usuario buscando en la relación
     */
    public function getTipoUsuarioNombre()
    {
        return $this->tipoUsuario ? $this->tipoUsuario->tipo_usuario_nombre : '(not set)';
    }

    /* ===================== METODOS DE ENLACE ===================== */

    public function getUserIdLink()
    {
        $url = Url::to(['user/update', 'id' => $this->id]);
        return Html::a($this->id, $url);
    }

    public function getUserLink()
    {
        $url = Url::to(['user/view', 'id' => $this->id]);
        return Html::a($this->username, $url);
    }

    /**
     * He renombrado este a getPerfilLink para que coincida con tu attributeLabels
     */
    public function getPerfilLink()
    {
        if ($this->perfil) {
            $url = Url::to(['perfil/view', 'id' => $this->perfil->id]);
            return Html::a($this->perfil->id, $url);
        }
        return '<span style="color:#999">(not set)</span>';
    }

    /* ===================== LOGIN METHODS ===================== */

    public static function findIdentity($id)
    {
        return static::findOne([
            'id' => $id,
            'estado_id' => ValorHelpers::getEstadoId('Activo')
        ]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public static function findByUsername($username)
    {
        return static::findOne([
            'username' => $username,
            'estado_id' => ValorHelpers::getEstadoId('Activo')
        ]);
    }

    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'estado_id' => ValorHelpers::getEstadoId('Activo'),
        ]);
    }

    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);

        return $timestamp + $expire >= time();
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /* ===================== RELACIONES ===================== */

    public function getPerfil()
    {
        return $this->hasOne(Perfil::class, ['user_id' => 'id']);
    }

    public function getRol()
    {   
        return $this->hasOne(Rol::class, ['id' => 'rol_id']);
    }

    public function getEstado()
    {
        return $this->hasOne(Estado::class, ['id' => 'estado_id']);
    }

    public function getTipoUsuario()
    {
        return $this->hasOne(TipoUsuario::class, ['id' => 'tipo_usuario_id']);
    }

    public static function getRolLista()
    {
        return ArrayHelper::map(Rol::find()->asArray()->all(), 'id', 'rol_nombre');
    }

    public static function getEstadoLista()
    {
        return ArrayHelper::map(Estado::find()->asArray()->all(), 'id', 'estado_nombre');
    }

    public static function getTipoUsuarioLista()
    {
        return ArrayHelper::map(TipoUsuario::find()->asArray()->all(), 'id', 'tipo_usuario_nombre');
    }

    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }
}