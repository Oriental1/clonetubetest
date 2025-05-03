<?php

use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\bootstrap5\Html;
use common\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\helpers\Url;

$hideNavbarRoutes = [
    'excel/edit-row',
    'excel/index',
    'excel/show-excel',
    'excel/view-table',
];

$currentRoute = Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;

// Only show the navbar if the current route is not in the hide list
if (!in_array($currentRoute, $hideNavbarRoutes)) {
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-lg navbar-light bg-light shadow-sm']
    ]);

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems[] = [
            'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
            'url' => ['/site/logout'],
            'linkOptions' => ['data-method' => 'post']
        ];
    }
?>
    <form action="<?= Url::to(['/video/search']) ?>" class="d-flex">
        <input class="form-control me-2" type="search" placeholder="Search"
            name="keyword"
            value="<?= Yii::$app->request->get('keyword') ?>">
        <button class="btn btn-outline-success">Search</button>
    </form>
<?php
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav ms-auto'],
        'items' => $menuItems,
    ]);

    NavBar::end();
}
?>
