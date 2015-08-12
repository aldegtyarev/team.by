<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="row clearfix">
	<?php $form = ActiveForm::begin(['action'=>['zakaz-spec1', 'modal'=>1]]); ?>
	<div class="col-lg-6">
		<?= $form->field($model, 'name')->textInput(['placeholder'=>'Ваше имя', 'class'=>'inputbox width100']) ?>
		<?= $form->field($model, 'phone')->textInput(['placeholder'=>'Номер телефона +375 (XX)ХХХ-ХХ-ХХ', 'class'=>'inputbox width100']) ?>
	</div>
	<div class="col-lg-6">
		<?= $form->field($model, 'comment')->textarea(['placeholder'=>'Кого вы ищете? С чем надо помочь?', 'class'=>'inputbox width100', 'rows' => 3]) ?>
	</div>
	<?= Html::submitButton('Отправить заявку', ['id'=>'send-zayavka', 'class' => 'button-red send-zayavka']) ?>
	<?php ActiveForm::end(); ?>
</div>