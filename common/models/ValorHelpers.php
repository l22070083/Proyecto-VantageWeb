<?php
namespace common\models;

use Yii;
use backend\models\Rol;
use backend\models\Estado;
use backend\models\TipoUsuario;
use common\models\User;

class ValorHelpers
{
    public static function rolCoincide($rol_nombre)
    {
        if (!Yii::$app->user->isGuest && Yii::$app->user->identity->rol) {
            return Yii::$app->user->identity->rol->rol_nombre === $rol_nombre;
        }
        return false;
    }

    public static function getUsersRolValor($userId = null)
    {
        if ($userId === null) {
            if (!Yii::$app->user->isGuest && Yii::$app->user->identity->rol) {
                return Yii::$app->user->identity->rol->rol_valor;
            }
            return null;
        }

        $user = User::findOne($userId);
        if ($user && $user->rol) {
            return $user->rol->rol_valor;
        }

        return null;
    }

    public static function getRolValor($rol_nombre)
    {
        $rol = Rol::find()
            ->where(['rol_nombre' => $rol_nombre])
            ->one();

        return $rol ? $rol->rol_valor : null;
    }

    public static function esRolNombreValido($rol_nombre)
    {
        return Rol::find()
            ->where(['rol_nombre' => $rol_nombre])
            ->exists();
    }

    public static function estadoCoincide($estado_nombre)
    {
        if (!Yii::$app->user->isGuest && Yii::$app->user->identity->estado) {
            return Yii::$app->user->identity->estado->estado_nombre === $estado_nombre;
        }
        return false;
    }

    public static function getEstadoId($estado_nombre)
    {
        return Estado::find()
            ->select('id')
            ->where(['estado_nombre' => $estado_nombre])
            ->scalar(); // Más limpio y eficiente
    }

    public static function tipoUsuarioCoincide($tipo_usuario_nombre)
    {
        if (!Yii::$app->user->isGuest && Yii::$app->user->identity->tipoUsuario) {
            return Yii::$app->user->identity->tipoUsuario->tipo_usuario_nombre === $tipo_usuario_nombre;
        }
        return false;
    }
}