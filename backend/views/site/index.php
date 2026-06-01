<?php

use yii\helpers\Html;
use common\models\PermisosHelpers;

/**
 * @var yii\web\View $this
 */

$this->title = 'Admin Yii 2 Build';

$es_admin = PermisosHelpers::requerirMinimoRol('Admin');

?>


<div class="site-index">

    <div class="jumbotron">

        <h1>¡Bienvenido a Admin!</h1>

        <p class="lead">

            Ahora puede administrar usuarios, roles, y más con
            nuestras sencillas herramientas.

        </p>

        <p>

            <?php

            if (!Yii::$app->user->isGuest && $es_admin) {

                echo Html::a('Administrar Usuarios', ['user/index'], ['class' => 'btn btn-lg btn-success']);

            }

            ?>

        </p>

    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">

                <h2>Usuarios</h2>

                <p>

                    Este es el lugar para administrar usuarios.  Puede editar estados y roles desde aquí.
                    La IU es fácil de usar e intuitiva, simplemente haga clic en el link de abajo para comenzar.

                </p>

                <p>

                    <?php

                    if (!Yii::$app->user->isGuest && $es_admin) {

                        echo Html::a('Administrar Usuarios', ['user/index'], ['class' => 'btn btn-default']);

                    }

                    ?>

                </p>

            </div>
            <div class="col-lg-4">

                <h2>Roles</h2>

                <p>

                    Aquí es donde administra Roles.  Puede decidir quién es admin y quién no.  Puede
                    agregar un nuevo rol si lo desea, simplemente haciendo clic en el link de abajo para comenzar.

                </p>

                <p>

                    <?php

                    if (!Yii::$app->user->isGuest && $es_admin) {

                        echo Html::a('Administrar Roles', ['rol/index'], ['class' => 'btn btn-default']);

                    }

                    ?>

                </p>

            </div>
            <div class="col-lg-4">

                <h2>Perfiles</h2>

                <p>

                    ¿Necesita revisar Perfiles?  Este es el lugar para hacerlo.
                    Estos son fáciles de administrar via IU. Simplemente haga clic en el link de abajo para
                    administrar perfiles.

                </p>

                <p>

                    <?php

                    if (!Yii::$app->user->isGuest && $es_admin) {

                        echo Html::a('Administrar Perfiles', ['perfil/index'], ['class' => 'btn btn-default']);

                    }

                    ?>

                </p>

            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">

                <h2>Tipos de Usuario</h2>

                <p>

                    Este es el lugar para administrar tipos de usuario.  Puede editar tipos
                    de usuario desde aquí. La IU es fácil de usar e intuitiva, simplemente
                    haga clic en el link  de abajo para comenzar.

                </p>

                <p>

                    <?php

                    if (!Yii::$app->user->isGuest && $es_admin) {

                        echo Html::a('Administrar Tipos de Usuario', ['tipo-usuario/index'], ['class' => 'btn btn-default']);

                    }

                    ?>
                </p>

            </div>
            <div class="col-lg-4">

                <h2>Estados</h2>

                <p>

                    Aquí es donde administra Estados.  Puede agregar o eliminar.
                    Puede añadir nuevos estados si lo desea, simplemente haga clic en el link
                    de abajo para comenzar.

                </p>

                <p>

                    <?php

                    if (!Yii::$app->user->isGuest && $es_admin) {

                        echo Html::a('Administrar Estados', ['estado/index'], ['class' => 'btn btn-default']);

                    }

                    ?>

                </p>

            </div>
            <div class="col-lg-4">

                <h2>Placeholder</h2>

                <p>

                    ¿Necesita revisar Perfiles?  Este es el lugar para hacerlo.
                    Estos son fáciles de administrar via IU.  Simplemente haga clic en el link de abajo
                    para administrar perfiles.

                </p>

                <p>

                    <?php

                    if (!Yii::$app->user->isGuest && $es_admin) {

                        echo Html::a('Administrar Perfiles', ['perfil/index'], ['class' => 'btn btn-default']);

                    }

                    ?>

                </p>

            </div>
        </div>
    </div>
</div>