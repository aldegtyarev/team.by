<?php
/* @var $this yii\web\View */

use yii\widgets\ListView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ButtonDropdown;

use yii\widgets\ActiveForm;



$this->title = $category->name .' | '. Yii::$app->params['sitename'];

//echo'<pre>';print_r($dataProvider);echo'</pre>';

//$this->params['breadcrumbs'][] = ['label' => 'Regions', 'url' => ['index']];

$this->params['breadcrumbs'][] = ['label' => 'Каталог специалистов', 'url' => ['index']];

foreach($parents as $parent) {
	if($parent->id <> 1)
		$this->params['breadcrumbs'][] = ['label' => $parent->name, 'url' => ['category', 'category'=>$parent->path]];
}
$this->params['breadcrumbs'][] = $category->name;

?>

<h1><?= $category->name?></h1>

<?php if(count($children))	{	?>
	<div class="catalog-category-children__list">
		<ul class="row clearfixt">
			<?php foreach($children as $c)	echo Html::tag('li', Html::a(Html::encode($c->name), ['catalog/category', 'category' => $c->path]), ['class' => 'col-lg-2 catalog-category-children__item']) ?>
		</ul>
	</div>
<?php	}	?>



<?php $form = ActiveForm::begin(['id' => 'category-sort-sw']); ?>
Сортировать по:
<?php 


echo ButtonDropdown::widget([
    'label' => 'Имени',
    'options' => [
        'class' => 'btn-lg btn-link',
        'style' => ''
    ],
	'containerOptions' => [
		'class' => 'sorting-switcher',
	],
    'dropdown' => [
        'items' => [
            [
                'label' => 'Цене',
                'url' => '#',
				'linkOptions' => ['data-sort' => 'price'],
            ],
            [
                'label' => 'Дате добавления',
                'url' => '#',
				'linkOptions' => ['data-sort' => 'created'],
            ],
        ]
    ]
]);
?>

<?php ActiveForm::end(); ?>

<?php
echo ListView::widget( [
    'dataProvider' => $dataProvider,
    'itemView' => '_item',
	'summary' => '',
	'id' => 'items-list',
	'options' => ['class' => 'list-view catalog-category-list-view'],
	'itemOptions' => ['class'=>'catalog-category-list-item']
] );
?>