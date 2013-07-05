<?php
/* @var $this ProfileController */
/* @var $model Profile */
?>

<div class="row"><h2>Update Profile <?php echo $model->employee_code; ?></h2></div>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>