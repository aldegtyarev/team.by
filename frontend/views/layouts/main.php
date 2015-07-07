<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Menu;

use yii\bootstrap\Modal;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

use yii\widgets\Breadcrumbs;

use frontend\assets\AppAsset;

use frontend\widgets\Alert;

use frontend\widgets\ProfiSearch;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);

$current_controller = Yii::$app->controller->id;
$current_action = Yii::$app->controller->action->id;

$show_header_row1 = false;
$show_header_row3 = false;

if($current_controller == 'site' && $current_action == 'index') $show_header_row1 = true;
if($current_controller == 'site' && $current_action == 'index') $show_header_row3 = true;

?>


<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <div class="wrap">
		<div class="wrap__cnt <?php if($current_controller == 'site' && $current_action == 'index') echo 'wrap__cnt_main_page' ?>">
			<div class="header">
				<?php if($show_header_row1) echo $this->render('_header_row1', [], false, true); ?>
				
				<?php echo $this->render('_header_row2', [], false, true); ?>
				
				<?php if($show_header_row3) echo $this->render('_header_row3', [], false, true); ?>
			</div>

			<?php if($current_controller == 'site' && $current_action == 'index') echo ProfiSearch::widget() ?>

			<div class="container">
				<?= Breadcrumbs::widget([
					'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
				]) ?>
				<?= Alert::widget() ?>


				<?= $content ?>
			
			</div>
			
			<?php if($current_controller == 'site' && $current_action == 'index') echo $this->render('_how_it_work', [], false, true); ?>
			
			<?php if($current_controller == 'site' && $current_action == 'index') echo $this->render('_reviews_block', [], false, true); ?>


		</div>
    </div>

	<footer class="footer">
		<?php if($current_controller == 'site' && $current_action == 'reg') {	?>
			<img src="/images/reg-img-left.jpg" alt="" class="site_reg__footer_img_left">
			<img src="/images/reg-img-right.jpg" alt="" class="site_reg__footer_img_right">
		<?php 	}	?>
		<div class="container clearfix">
			<a href="<?php echo Yii::$app->params['homeUrl']; ?>" class="logo_bottom pull-left"><img src="/images/logo-bottom.png" alt=""></a>


			<div class="footer__cnt">
				<?php
				echo Menu::widget([
					'items' => [
						//['label' => 'Условия использования', 'url' => '#'],
						['label' => 'Правовые документы', 'url' => '#'],
						//['label' => 'О компании', 'url' => '#'],
						//['label' => 'Чёрный список', 'url' => '#'],
					],
					'options' => [
						'class' => 'footer_menu__list',
					],
					'itemOptions'=> [
						'class' => 'footer_menu__item',
					]
				]);
				?>
				
				<p class="footer__info">
					Единый номер для всех мобильных операторов РБ:  345-89-98<br>Время работы: 8:00 - 22:00
				</p>
				
				<p class="created_by">Разработка сайта: <a href="http://www.medialine.by" target="_blank" title="Medialine.by">Medialine.by</a></p>
			</div>
			
			<div class="footer__buttons">
				<a href="#" class="button-gray footer__reg_btn">Стать специалистом</a>
				<a href="#" class="button-gray footer__login_btn">Вход в личный кабинет специалиста</a>
			</div>


		</div>
	</footer>
	<div class="modal fade"></div>
    <?php $this->endBody() ?>
    <script>
var $buoop = {vs:{i:9,f:25,o:12.1,s:7},c:2}; 
function $buo_f(){ 
 var e = document.createElement("script"); 
 e.src = "//browser-update.org/update.min.js"; 
 document.body.appendChild(e);
};
try {document.addEventListener("DOMContentLoaded", $buo_f,false)}
catch(e){window.attachEvent("onload", $buo_f)}
	</script>
</body>
</html>
<?php $this->endPage() ?>
	