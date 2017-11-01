<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\CSM\CsmCustomers */

$this->title = Yii::t('app', 'Create Csm Customers');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Csm Customers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="csm-customers-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
