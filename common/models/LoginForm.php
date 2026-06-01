<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\web\NotFoundHttpException; 
use common\models\PermisosHelpers;  
use common\models\User; // Asegúrate de que esta línea esté para evitar errores de clase

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Usuario o contraseña incorrectos.');
            }
        }
    }

    /**
     * Login normal (Frontend)
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login(
                $this->getUser(),
                $this->rememberMe ? 3600 * 24 * 30 : 0
            );
        }

        return false;
    }

    /**
     * Login solo para Admin (Backend)
     * MODIFICADO: Comentada la restricción de Rol para permitir acceso de emergencia.
     */
    public function loginAdmin()
    {
        // Validamos usuario y contraseña
        if ($this->validate()) {
            
            /* ESTA ES LA LÍNEA QUE TE BLOQUEABA. 
               La comentamos para que puedas entrar y revisar tu base de datos.
            */
            // if (PermisosHelpers::requerirMinimoRol('Admin', $this->getUser()->id)) {
                
                return Yii::$app->user->login(
                    $this->getUser(),
                    $this->rememberMe ? 3600 * 24 * 30 : 0
                );

            // }
            
        }
        
        // Si la validación falla (contraseña mal), lanzamos el error
        throw new NotFoundHttpException('No se pudo validar el acceso. Revisa tus credenciales.');
    }

    /**
     * Finds user by username
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}