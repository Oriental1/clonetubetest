<?php

use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\bootstrap5\Html;
use common\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;


NavBar::begin([
    'brandLabel' => Yii::$app->name,
    'brandUrl' => Yii::$app->homeUrl,
    'options' => ['class' => 'navbar-expand-lg navbar-light bg-light shadow-sm']
]);
$menuItems = [
    ['label' => 'Create', 'url' => ['/video/create']],
];
if (Yii::$app->user->isGuest) {
    $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
} else {
    $menuItems[] = [
        'label' => 'Logout ('.Yii::$app->user->identity->username.')',
        'url' => ['/site/logout'],
        'linkOptions' => [
            'data-method' => 'post'
            ]
        ];
    }
    
    
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav ms-auto'],
        'items' => $menuItems,
    ]);
NavBar::end();
?>