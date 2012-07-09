<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'memployee-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'surname'); ?>
		<?php echo $form->textField($model,'surname',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'surname'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>75)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'patronymic'); ?>
		<?php echo $form->textField($model,'patronymic',array('size'=>60,'maxlength'=>75)); ?>
		<?php echo $form->error($model,'patronymic'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'birth'); ?>
		<?php echo $form->textField($model,'birth'); ?>
		<?php echo $form->error($model,'birth'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sex'); ?>
		<?php echo $form->textField($model,'sex',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'sex'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'mobphone'); ?>
		<?php echo $form->textField($model,'mobphone',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'mobphone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'homephone'); ?>
		<?php echo $form->textField($model,'homephone',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'homephone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'inphone'); ?>
		<?php echo $form->textField($model,'inphone',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'inphone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'hemail'); ?>
		<?php echo $form->textField($model,'hemail',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'hemail'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->