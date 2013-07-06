<div class="row">
    <div class="four columns image photo">
        <?php            
            echo CHtml::link(CHtml::image($data->getMainImage()), array('view', 'id' => $data->id));
        ?>
    </div>
    
    <div class="seven columns">
        <b><?php echo CHtml::encode($data->getAttributeLabel('employee_code')); ?>:</b>
        <?php echo Chtml::link(CHtml::encode($data->employee_code), array('view', 'id' => $data->id)); ?>
        <br />

        <b><?php echo CHtml::encode('User'); ?>:</b>
            <?php
                if (isset($data->user->username)) {
                    echo CHtml::encode($data->user->username);
                } else {
                    echo CHtml::encode('No user');;
                }
            ?>
        <br />

        <b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
        <?php echo CHtml::encode($data->email); ?>
        <br />

        <?php
                if ($data->name != null) {
                    echo CHtml::encode($data->getAttributeLabel('name')).': ';
                    echo CHtml::encode($data->name).'<br/>';
                }
            ?>

            <?php
                if ($data->phone != null) {
                    echo CHtml::encode($data->getAttributeLabel('phone')).': ';
                    echo CHtml::encode($data->phone).'<br/>';
                }
            ?>
    </div>
</div>
<div class="row">
    <?php
        if (Yii::app()->user->isAdmin){
            if (!isset($data->user->is_admin)) {
                if (isset($data->user->username)) {
                    echo '<span class="small primary warning btn">';
                    echo CHtml::button('Delete user', array('submit' => array('user/delete',
                        'id' => $data->id), 'confirm'=>'Do you want to delete this user permanently?'));
                    echo '</span>&nbsp';
                } else {
                    echo '<span class="small primary btn">';
                    echo CHtml::button('Send sign up email', array('submit' => array('profile/sendSignUpEmail', 'id' => $data->id)));
                    echo '</span>&nbsp';
                }
            }
            echo '<span class="small secondary btn">';
            echo CHtml::button('Update profile', array('submit' => array('profile/update', 'id' => $data->id)));
            echo '</span>&nbsp';
            if (!isset($data->user->is_admin)) {
                echo '<span class="small danger btn">';
                echo CHtml::button('Delete profile', array('submit' => array('profile/delete',
                    'id' => $data->id), 'confirm'=>'Do you want to delete this profile permanently?'));
                echo '</span>';
            }
        }
    ?>
</div>