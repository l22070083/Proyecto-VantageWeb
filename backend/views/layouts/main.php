<?php
 
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap5\NavBar;
use yii\bootstrap5\Nav;
use yii\widgets\Breadcrumbs;
use common\models\PermisosHelpers;
use backend\assets\FontAwesomeAsset;
 
/**
 * @var \yii\web\View $this
 * @var string $content
 */
 
AppAsset::register($this);
FontAwesomeAsset::register($this);
 
?>
 
             <?php $this->beginPage() ?>
            
<!DOCTYPE html>
 
<html lang="<?= Yii::$app->language ?>">
 
<head>
 <meta charset="<?= Yii::$app->charset ?>"/>
    
<meta name="viewport" 
content="width=device-width, 
initial-scale=1">
    
    <?= Html::csrfMetaTags() ?>
    
<title><?= Html::encode($this->title) ?></title>
    <script>
        (function () {
            const storedTheme = localStorage.getItem('theme') || 'auto';
            const theme = storedTheme === 'auto' 
                ? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light')
                : storedTheme;
            document.documentElement.setAttribute('data-bs-theme', theme);
        })();
    </script>
            <?php $this->head() ?>
    
</head>
 
<body>
            <?php $this->beginBody() ?>
 
    <main role="main" class="flex-shrink-0">
    
    
<?php
            
  if (!Yii::$app->user->isGuest){
  
      $es_admin = PermisosHelpers::requerirMinimoRol('Admin');
 
   NavBar::begin([
 
    'brandLabel' => 'Yii 2 Build <i class="fa fa-plug"></i> Admin',
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
           'class' => 'navbar navbar-expand-md fixed-top bg-body-tertiary border-bottom',
      ],
   ]);
 
  } else {
   
    NavBar::begin([
 
      'brandLabel' => 'Yii 2 Build <i class="fa fa-plug"></i>',
      'brandUrl' => Yii::$app->homeUrl,
      'options' => [
           'class' => 'navbar navbar-expand-md fixed-top bg-body-tertiary border-bottom',
      ],
   ]);
 
            
            
  $menuItems = [
      ['label' => 'Home', 'url' => ['site/index']],
  ];
  }
   
  if (!Yii::$app->user->isGuest && $es_admin) {
 
      $menuItems[] = ['label' => 'Usuarios', 'url' => ['user/index']];
            
      $menuItems[] = ['label' => 'Perfiles', 'url' => ['perfil/index']];
            
      $menuItems[] = ['label' => 'Roles', 'url' => ['rol/index']];
                
      $menuItems[] = ['label' => 'Tipos de Usuario', 'url' => ['tipo-usuario/index']];
           
      $menuItems[] = ['label' => 'Estados', 'url' => ['estado/index']];
 
   }
  
  if (Yii::$app->user->isGuest) {
 
     $menuItems[] = ['label' => 'Login', 'url' => ['site/login']];
 
  } else {
 
      $menuItems[] = ['label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                      'url' => ['/site/logout'],
                      'linkOptions' => ['data-method' => 'post']
                ];
 
  } 
                                                                                                                  
  // Selector de tema (Light / Dark / Auto)
  echo '
  <div class="dropdown nav-item ms-md-auto me-3 align-self-center">
      <button class="btn btn-link nav-link dropdown-toggle d-flex align-items-center text-secondary border-0 p-2" id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown" aria-label="Toggle theme (auto)">
          <span class="theme-icon-active d-flex align-items-center">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-circle-half" viewBox="0 0 16 16">
                  <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16"/>
              </svg>
          </span>
          <span class="d-md-none ms-2" id="bd-theme-text">Cambiar tema</span>
      </button>
      <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
          <li>
              <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sun-fill me-2 theme-icon" viewBox="0 0 16 16">
                      <path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8M8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0m0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13m8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5M3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8m10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0m-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0m9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707M4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708"/>
                  </svg>
                  Claro
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2 ms-auto d-none" viewBox="0 0 16 16">
                      <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                  </svg>
              </button>
          </li>
          <li>
              <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-moon-stars-fill me-2 theme-icon" viewBox="0 0 16 16">
                      <path d="M6 .278a.77.77 0 0 1 .08.858 7.2 7.2 0 0 0-.8 3.412c0 4.028 3.277 7.288 7.393 7.288q.69 0 1.356-.16a.76.76 0 0 1 .8.271 7.26 7.26 0 0 1-10.988-9.086 7.2 7.2 0 0 1 3.14-4.075zm.466.964a6.26 6.26 0 0 0-3.71 3.286 6.26 6.26 0 0 0 9.316 7.706 7.3 7.3 0 0 1-1.356.125 8.3 8.3 0 0 1-8.319-8.319c0-1.037.195-2.03.548-2.946l.08-.093z"/>
                      <path d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.74 1.74 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162a1.74 1.74 0 0 0-1.097-1.097l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.74 1.74 0 0 0 1.097-1.097zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.16 1.16 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.16 1.16 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732z"/>
                  </svg>
                  Oscuro
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2 ms-auto d-none" viewBox="0 0 16 16">
                      <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                  </svg>
              </button>
          </li>
          <li>
              <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="auto" aria-pressed="true">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-circle-half me-2 theme-icon" viewBox="0 0 16 16">
                      <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16"/>
                  </svg>
                  Auto
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2 ms-auto d-none" viewBox="0 0 16 16">
                      <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                  </svg>
              </button>
          </li>
      </ul>
  </div>
  ';

  echo Nav::widget([
 
      'options' => ['class' => 'navbar-nav mb-2 mb-md-0'],
      'items' => $menuItems,
 
  ]);
 
  NavBar::end();
 
?>
 
         
<div class="container">
 
<?= Breadcrumbs::widget([
 
    'links' => isset($this->params['breadcrumbs']) ? 
    $this->params['breadcrumbs'] : [],
       
    ])?>
 
<?= $content ?>
 
        </div>
    </main>
 
    <footer class="footer">
 
        <div class="container">
 
        <p class="pull-left">&copy;PHANTOMS <?= date('Y') ?></p>  <!--nombre de la empresa de pie de página -->
        
 
        <p class="pull-right"><?= Yii::powered() ?></p>
 
        </div>
 
    </footer>
 
            <?php $this->endBody() ?>
 
</body>
</html>
 
<?php $this->endPage() ?>