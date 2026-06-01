<?php
namespace common\models;

use yii;
use backend\models\EstadoMensaje;


class RegistrosHelpers
{
    public static function userTiene($modelo_nombre)
    {
        $conexion = \Yii::$app->db;
        $userid = Yii::$app->user->identity->id;
        $sql = "SELECT id FROM $modelo_nombre WHERE user_id=:userid";
        $comando = $conexion->createCommand($sql);
        $comando->bindValue(":userid", $userid);
        $resultado = $comando->queryOne();
        if ($resultado == null) {
            return false;
        } else {
            return $resultado['id'];
        }
    }


    public static function encontrarEstadoMensaje($accion_nombre, $controlador_nombre)
    {
            
        $result =  EstadoMensaje::find('id')
                                ->where(['accion_nombre' => $accion_nombre])
                                ->andWhere(['controlador_nombre' => $controlador_nombre])
                                ->one();
                
            return isset($result['id']) ? $result['id'] : false;                     
                
                        
    }

    public static function obtenerMensajeAsunto($id)
    {
            
        $result =  EstadoMensaje::find('asunto')
                                    ->where(['id' => $id])
                                    ->one();
                
        return isset($result['asunto']) ?  $result['asunto'] :  false;
            
    }
            
    public static function obtenerMensajeCuerpo($id)
    {
            
        $result =  EstadoMensaje::find('cuerpo')
                                ->where(['id' => $id])
                                ->one();
                
        return isset($result['cuerpo']) ? $result['cuerpo'] : false;                   
                
    }
}