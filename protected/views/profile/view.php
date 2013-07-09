<script src='<?php echo Yii::app()->baseUrl; ?>/js/profile.js'></script> 

<?php
/* @var $this ProfileController */
/* @var $model Profile */

$this->breadcrumbs = array(
	'Profiles' => array('index'),
	$model->name,
);
?>

<div class="row">
    <h2><?php echo CHtml::encode($model->employee_code); ?></h2>
    </br>
    <div class="row">
        <div class="four columns image photo">
        <?php            
            echo CHtml::image($model->getMainImage());
        ?>
        </div>
        
        <div class="six columns push_one">
            <b><?php echo CHtml::encode('User'); ?>:</b>
                <?php
                    if (isset($model->user->username))
                        echo CHtml::encode($model->user->username);
                    else
                        echo CHtml::encode('No user');
                ?>
            <br />

            <b><?php echo CHtml::encode($model->getAttributeLabel('email')); ?>:</b>
            <?php echo CHtml::encode($model->email); ?>
            <br />

            <?php
                if ($model->name != null) {
                    echo CHtml::encode($model->getAttributeLabel('name')).': ';
                    echo CHtml::encode($model->name).'<br/>';
                }
            ?>

            <?php
                if ($model->phone != null) {
                    echo CHtml::encode($model->getAttributeLabel('phone')).': ';
                    echo CHtml::encode($model->phone).'<br/>';
                }
            ?>
            
            <?php
                if ($model->address != null) {
                    echo CHtml::encode($model->getAttributeLabel('address')).': ';
                    echo CHtml::encode($model->address).'<br/>';
                }
            ?>

            <?php
                if ($model->employee_code != null) {
                    echo CHtml::encode($model->getAttributeLabel('employee_code')).': ';
                    echo CHtml::encode($model->employee_code).'<br/>';
                }
            ?>

             <?php
                if ($model->position != null) {
                    echo CHtml::encode($model->getAttributeLabel('position')).': ';
                    echo CHtml::encode($model->position).'<br/>';
                }
            ?>

            <?php
                if ($model->date_of_birth != null) {
                    echo CHtml::encode($model->getAttributeLabel('date_of_birth')).': ';
                    echo CHtml::encode(DateAndTime::returnTime($model->date_of_birth)).'<br/>';
                }
            ?>

            <?php 
            if (Yii::app()->user->isAdmin){
                if (isset($model->user->username)) {
                    echo '<span class="small primary warning btn">';
                    echo CHtml::button('Delete user', array('submit' => array('user/delete', 'id' => $model->id)));
                    echo '</span>&nbsp';
                } else {
                    echo '<span class="small primary btn">';
                    echo CHtml::button('Send sign up email', array('submit' => array('profile/sendSignUpEmail', 'id' => $model->id)));
                    echo '</span>&nbsp';
                }
                echo '<span class="small secondary btn">';
                echo CHtml::button('Update profile', array('submit' => array('profile/update', 'id' => $model->id)));
                echo '</span>&nbsp';
                echo '<span class="small danger btn">';
                echo CHtml::button('Delete profile', array('submit' => array('profile/delete', 'id' => $model->id), 'confirm'=>'Do you want to delete this profile permanently?'));
                echo '</span>';
            }
            ?>
        </div>
    </div>
    <br>
    <?php if (isset($model->user)){ ?>
    <div class="row">
    <?php if (count($model->user->being_considered_requests) != 0){ ?>
    <div class="medium info btn">
        <?php echo CHtml::button('Show being considered requests ('.count($model->user->being_considered_requests).')', array('id' => 'being_considered_requests_button') ); ?>
    </div>
    <?php } ?>
    </div>
    <div class="row" id ="being_considered_requests" hidden="hidden" count_consider="<?php echo count($model->user->being_considered_requests) ?>">
    <?php
        foreach ($model->user->being_considered_requests as $request) {
            echo '<div class="row">';
            echo '<p>';                        
            echo $model->user->profile->name;
            echo ' want to borrow '.$request->device->createViewLink();
            if ($request->request_start_time != null) {
                echo ' from '.DateAndTime::returnTime($request->request_start_time);
            }
            if ($request->request_end_time != null) {
                echo ' to  '.DateAndTime::returnTime($request->request_end_time);
            }
            echo '. Request created at '.DateAndTime::returnTime($request->created_at);
            echo ' '.$request->createViewLink(' View more');    
            echo '</div>';
        }
    ?>
    </div>
    <br>
    <div class="row">
    <?php if (count($model->user->accepted_requests) != 0){ ?>
    <div class="medium info btn">
        <?php echo CHtml::button('Show borrowing ('.count($model->user->accepted_requests).')', array('id' => 'accepted_requests_button') ); ?>
    </div>
    <?php } ?>
    </div>
    <div class="row" id ="accepted_request" hidden="hidden" count_accept="<?php echo count($model->user->accepted_requests) ?>">
    <?php
        foreach ($model->user->accepted_requests as $request) {
            echo '<div class="row">';
            echo '<p>';                        
            echo $model->user->profile->name;
            echo ' is borrowing '.$request->device->createViewLink();
            if ($request->start_time != null) {
                echo ' from '.DateAndTime::returnTime($request->start_time);
            }
            if ($request->request_end_time != null) {
                echo '. Expected end time '.DateAndTime::returnTime($request->request_end_time);
            }
            echo '. Request created at '.DateAndTime::returnTime($request->created_at);
            echo ' '.$request->createViewLink(' View more');    
            echo '</div>';
        }
    ?>
    </div>
    <br>
    <div class="row">
    <?php if (count($model->user->finished_requests) != 0){ ?>
    <div class="medium info btn">
        <?php echo CHtml::button('Show borrowed ('.count($model->user->finished_requests).')', array('id' => 'finished_requests_button') ); ?>
    </div>
    <?php } ?>
    </div>
    <div class="row" id ="finished_requests" hidden="hidden" count_finish="<?php echo count($model->user->finished_requests) ?>">
    <?php
        foreach ($model->user->finished_requests as $request) {
            echo '<div class="row">';
            echo '<p>';                        
            echo $model->user->profile->name;
            echo ' borrowed '.$request->device->createViewLink();
            if ($request->start_time != null) {
                echo ' from '.DateAndTime::returnTime($request->start_time);
            }
            if ($request->end_time != null) {
                echo ' to '.DateAndTime::returnTime($request->end_time);
            }
            echo '. Request created at '.DateAndTime::returnTime($request->created_at);
            echo ' '.$request->createViewLink(' View more');    
            echo '</div>';
        }
    ?>
    </div>
    <?php } ?>
</div>