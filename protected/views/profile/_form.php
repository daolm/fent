<?php
/* @var $this ProfileController */
/* @var $model Profile */
/* @var $form CActiveForm */
?>
<script src='<?php echo Yii::app()->baseUrl; ?>/js/email_validate.js'></script>
<div class="form">

<?php $form = $this->beginWidget('CActiveForm', array(
	'id' => 'profile-form',
	'enableAjaxValidation' => false,
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data',
    ),
)); ?>
        <div class="row">
            <p class="note">Fields with <span class="required">*</span> are required.</p>
        </div>

	<div class="row">
            <?php echo $form->errorSummary($model); ?>
            <div class="field">
                <?php echo $form->labelEx($model,'email'); ?>
                <?php echo $form->textField($model,'email',array('id' => 'email', 'maxlength' => 45, 'class' => 'text input', 'placeholder' => 'example@example.com')); ?>
            </div>
        </div>
	<div class="row">
            <div class="field">
                <?php echo $form->labelEx($model,'name'); ?>
                <?php echo $form->textField($model,'name',array('maxlength' => 255, 'class' => 'text input', 'placeholder' => 'Name of employee')) ?>
            </div>
	</div>

	<div class="row">
            <div class="field">
                <?php echo $form->labelEx($model,'phone'); ?>
                <?php echo $form->textField($model,'phone', array('class' => 'text input', 'placeholder' => '000000000')); ?>
            </div>
	</div>

	<div class="row">
            <div class="field">
                <?php echo $form->labelEx($model,'address'); ?>
                <?php echo $form->textArea($model,'address',array('rows' => 4, 'class' => 'input textarea')); ?>
            </div>
	</div>

	<div class="row">
            <div class="field">
                <?php echo $form->labelEx($model,'employee_code'); ?>
                <?php echo $form->textField($model,'employee_code',array('maxlength' => 20,'class' => 'text input', 'placeholder' => 'ABCDEF')); ?>
            </div>
	</div>

	<div class="row">
            <div class="field">
                <?php echo $form->labelEx($model,'position'); ?>
                <?php echo $form->textField($model,'position',array('maxlength' => 45, 'class' => 'text input')); ?>
            </div>
	</div>

	<div class="row">
            <div class="field columns">
                <?php
                    if ($model->date_of_birth !== null) {
                        $value = DateAndTime::returnTime($model->date_of_birth);
                    } else {
                        $value = null;
                    }
                ?>
                <?php echo $form->labelEx($model,'date_of_birth'); ?>
                <?php echo $form->textField($model,'date_of_birth', array('class' => 'text input',
                    'value' => $value,
                    'placeholder' => 'dd/mm/yyyy')); ?>
            </div>
	</div>

    <div class="row">
        <?php echo $form->labelEx($model,'image'); ?>
        <?php echo CHtml::activeFileField($model, 'image'); ?>
    </div>
    <?php if(!$model->isNewRecord){ ?>
        <div class="row">
            <div class="four columns image photo">
                <?php echo CHtml::image($model->getMainImage()); ?>
            </div>    
        </div>
    <?php } ?>     
	<div class="row">
            <span class="medium primary btn">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
            </span>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->