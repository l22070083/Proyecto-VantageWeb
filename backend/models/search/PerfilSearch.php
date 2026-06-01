<?php
namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Perfil;

class PerfilSearch extends Perfil
{
    public $generoNombre;
    public $genero_id;
    public $userId;

    /**
     * Reglas de validación
     */
    public function rules()
    {
        return [
            [['id', 'genero_id'], 'integer'],
            [['nombre', 'apellido', 'fecha_nacimiento', 'generoNombre', 'userId'], 'safe'],
        ];
    }

    /**
     * Labels de atributos
     */
    public function attributeLabels()
    {
        return [
            'genero_id' => 'Género',
        ];
    }

    /**
     * Búsqueda principal
     */
    public function search($params)
    {
        $query = Perfil::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // Configuración de ordenamiento
        $dataProvider->setSort([
            'attributes' => [
                'id',
                'nombre',
                'apellido',
                'fecha_nacimiento',
                'generoNombre' => [
                    'asc' => ['genero.genero_nombre' => SORT_ASC],
                    'desc' => ['genero.genero_nombre' => SORT_DESC],
                    'label' => 'Género',
                ],
                'perfilIdLink' => [
                    'asc' => ['perfil.id' => SORT_ASC],
                    'desc' => ['perfil.id' => SORT_DESC],
                    'label' => 'ID',
                ],
                'userLink' => [
                    'asc' => ['user.username' => SORT_ASC],
                    'desc' => ['user.username' => SORT_DESC],
                    'label' => 'Usuario',
                ],
            ]
        ]);

        // Cargar parámetros
        if (!($this->load($params) && $this->validate())) {
            $query->joinWith(['genero'])
                  ->joinWith(['user']);
            return $dataProvider;
        }

        // Filtros por atributos de Perfil
        $this->addSearchParameter($query, 'id');
        $this->addSearchParameter($query, 'nombre', true);
        $this->addSearchParameter($query, 'apellido', true);
        $this->addSearchParameter($query, 'fecha_nacimiento');
        $this->addSearchParameter($query, 'genero_id');
        $this->addSearchParameter($query, 'created_at');
        $this->addSearchParameter($query, 'updated_at');
        $this->addSearchParameter($query, 'user_id');

        // Filtrar por nombre de género
        $query->joinWith(['genero' => function ($q) {
            $q->andFilterWhere(['=', 'genero.genero_nombre', $this->generoNombre]);
        }]);

        // Filtrar por usuario
        $query->joinWith(['user' => function ($q) {
            $q->andFilterWhere(['=', 'user.id', $this->userId]);
        }]);

        return $dataProvider;
    }

    /**
     * Aplica filtros de búsqueda de manera segura
     */
    protected function addSearchParameter($query, $attribute, $partialMatch = false)
    {
        // Manejo de atributos con alias (por ejemplo "genero.genero_nombre")
        if (($pos = strrpos($attribute, '.')) !== false) {
            $modelAttribute = substr($attribute, $pos + 1);
        } else {
            $modelAttribute = $attribute;
        }

        // Evita trim(null) en PHP 8+
        $value = trim($this->$modelAttribute ?? '');

        // Si el valor está vacío, no se aplica filtro
        if ($value === '') {
            return;
        }

        // Usar alias de tabla "perfil" para evitar conflictos
        $attribute = "perfil.$attribute";

        if ($partialMatch) {
            $query->andWhere(['like', $attribute, $value]);
        } else {
            $query->andWhere([$attribute => $value]);
        }
    }
}