<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;


?>
<div class="profile_top_block">
	<div class="container">
		<ul class="profile_top_block__list">
			<li class="pull-left profile_top_block__item">
				<a href="#" class="button-light profile_top_block__notice">Уведомления</a>
			</li>
			<li class="pull-left profile_top_block__item">
				<a href="#" class="button-light profile_top_block__contact">Связь с администрацией</a>
			</li>
			<li class="pull-left profile_top_block__item">
				<a href="#" class="button-light profile_top_block__documents">Документы</a>
			</li>
			
			<li class="pull-right profile_top_block__item profile_top_block__activity">
				<?php $form = ActiveForm::begin(['action'=>Url::to(['profile/set-activity']), 'id'=>'set-activity-frm']); ?>
					<input type="hidden" name="return_url" value="<?= Url::to('')?>">
					<input type="hidden" id="activity" name="activity" value="<?= $model->is_active?>">
			
					Текущий статус:
					<?php if ($model->is_active == 1)	{	?>
						<span class="profile_top_block__item_active">Активен</span>
						<span id="activity-btn" class="profile_top_block__status__no_active" data-active="0">Неактивен</span>
					<?php	}	else	{	?>
						<span id="activity-btn" class="profile_top_block__status__active" data-active="1">Активен</span>
						<span class="profile_top_block__item_active">Неактивен</span>					
					<?php	}	?>
				<?php ActiveForm::end(); ?>				
			</li>
		</ul>
	</div>
</div>