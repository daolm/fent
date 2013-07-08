<?php
/* @var $this DeviceController */
/* @var $data Device */

?>

<div class="row">
    <div class="four columns image photo">
        <?php            
            echo CHtml::link(CHtml::image($data->getMainImage()), array('device/view', 'id' => $data->id));
        ?>
    </div>
    <div class="seven columns push_one">
        <p><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:
        <?php 
            if ($data->status == Constant::$DEVICE_NORMAL){
                echo '<span class = "success badge"> Avalable </span>';
            } else {
                echo '<span class = "danger badge"> Unavalable </span>';
            }
        ?></p>
        
        <div class="row">
        <p style="display: inline-block; float: left"><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:
        <?php echo CHtml::link(CHtml::encode($data->name), array('device/view', 'id'=>$data->id)); ?></p>
        </div>        

        <p><?php echo CHtml::encode($data->getAttributeLabel('management_number')); ?>:</p>
        <p><?php echo CHtml::encode($data->management_number); ?></p>

        <?php                                                 
            $borrower = $data->borrower;
            if ($borrower != null) {
                echo '<p>Being borrowed by: ';
                echo $borrower->profile->createViewLink($borrower->profile->name);
                echo '</p>';                       
            } 
        ?>

        <?php
            if (Yii::app()->user->isAdmin)
            {
                echo "<div class='small primary btn'>";
                echo CHtml::button('Edit', array('submit' => array('device/update', 'id' => $data->id)));
                echo '</div>';
            }
        ?>
        <?php
            if (Yii::app()->user->isAdmin)
            {
                echo "<div class='small danger btn'>";
                echo CHtml::button('Delete', array('submit' => array('device/delete', 'id' => $data->id),
                    'confirm'=>'Are you sure you want to delete this device?'));
                echo '</div>';
            }
        ?>
    </div>
</div>