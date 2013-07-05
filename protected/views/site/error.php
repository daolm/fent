<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle=Yii::app()->name . ' - Error';
$this->breadcrumbs=array(
	'Error',
);
?>

<?php if ($code == 404) {
    echo '<div class="page_not_found">';
    echo '<div class = "image rounded">';
    echo CHtml::image(Yii::app()->baseUrl.'/images/404/404_mario.jpg', null, array('class' => 'not_found_image'));
    echo '</div>';        
    echo '</div>';
}  else {
    echo "<h2>Error {$code}</h2>";
    echo '<div class="error">';
    echo CHtml::encode($message);
    echo '</div>';
}
?>