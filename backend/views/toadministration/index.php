<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Сообщения для администрации';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-to-administration-index">

    <h1><?= Html::encode($this->title) ?></h1>
	<?/*
    <p>
        <?= Html::a('Create User To Administration', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
	*/?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

			[
				'attribute' => 'id',
				'headerOptions' => ['width' => '100'],
			],

            //'user_id',
			[
				'attribute' => 'spec',
				'value' => 'user.fio'
			],
			
            'subject',

            'statusName',

			
//			[
//				'attribute'=>'status',
//				//'label'=>'Родительская категория',
//				'format'=>'text', // Возможные варианты: raw, html
//				'content'=>function($data){
//					return $data->orderStatusTxt;
//				},
//				'filter' => OrderForm::getStatuses(),
//			],			
			

            [
				'class' => 'yii\grid\ActionColumn',
				'template' => '{update} {delete}',
			],
        ],
    ]); ?>

</div>
