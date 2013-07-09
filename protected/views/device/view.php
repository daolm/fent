<script src='<?php echo Yii::app()->baseUrl; ?>/js/device.js'></script>  
<?php 
    if (Yii::app()->user->hasFlash('errors')) {
        echo '<div class="row">';
        echo '<div class="danger alert">';
        echo Yii::app()->user->getFlash('errors');
        echo '</div></div>';
    }
?>
<div class="row centered">
    <h1><?php echo $device->name; ?></h1>
    <div class="row">
        <div class="eight columns image photo">
            <?php
                $this->beginWidget('Galleria');
                foreach ($device->getAllImages() as $image) {
                    echo CHtml::image($image);
                }
                $this->endWidget();
            ?>
        </div>
        <div class="four columns">
            <p>
                <?php
                if (!Yii::app()->user->isAdmin) {
                    if ($device->status == Constant::$DEVICE_NORMAL){
                        echo 'Status: <span class = "success badge"> Avalable </span>';
                    } else {
                        echo 'Status: <span class = "danger badge"> Unavalable </span>';
                    }
                } else {
                    if ($device->status) {
                        echo '<div class="danger badge btn">';
                        echo CHtml::button('Unavalable', array('id' => 'status_button', 'device_id' => $device->id));
                        echo '</div>';
                    } else {
                        echo '<div class="success badge btn">';
                        echo CHtml::button('Avalable', array('id' => 'status_button', 'device_id' => $device->id));
                        echo '</div>';
                    }
                } 
                ?>
            </p>
            <?php                                                 
                $request = $device->accepted_request;
                if ($request != null) {
                    echo '<p>Being borrowed by: '.$request->user->profile->createViewLink($request->user->profile->name).'</p>';
                    echo '<p>Expected end time: '.$request->createViewLink(DateAndTime::returnTime($request->request_end_time)).'</p>';
                } elseif (Yii::app()->user->isAdmin) {
                    echo '<div class="row">';
                    echo CHtml::beginForm(Yii::app()->createUrl('request/create'), 'post');
                    echo '<div class="field three column">';
                    echo CHtml::textField('assign_user', '',array(
                        'placeholder' => 'name or employee code',
                        'class' => 'text input',
                        ));
                    echo CHtml::textField('device_id', $device->id, array('hidden' => hidden));
                    echo '</div>';
                    echo '<div class="medium success btn">';
                    echo CHtml::submitButton('OK');
                    echo CHtml::endForm();
                    echo '</div></div>';
                }
            ?>
            <p>
                <?php
                    if ($device->serial_number != null) {
                        echo 'Serial number: '.$device->serial_number;
                    }
                ?>
            </p>
            <p>
                <?php
                    if ($device->management_number != null) {
                        echo 'Management number: '.$device->management_number;
                    }
                ?>
            </p>
            <p>
                <?php
                    if ($device->model_number != null) {
                        echo 'Model number: '.$device->model_number;
                    }
                ?>
            </p>
            <p>
                <?php
                    if ($device->maker != null) {
                        echo 'Maker number: '.$device->maker;
                    }
                ?>
            </p>
            <p>
                <?php
                    if ($device->created_at != null) {
                        echo 'Add: '.DateAndTime::returnTime($device->created_at, 'd/m/Y');
                    }
                ?>
            </p>
            <p>
                <?php
                    if ($device->updated_at != null) {
                        echo 'Update: '.DateAndTime::returnTime($device->updated_at, 'd/m/Y');
                    }
                ?>
            </p>
            <p><?php echo 'Category: '.$device->category->createViewLink(); ?></p>
            <?php
                if (Yii::app()->user->isAdmin) {
                    echo "<div class='small success btn'>";
                    echo CHtml::button('Edit', array('submit' => array('device/update', 'id' => $device->id)));
                    echo '</div>';
                    echo '&nbsp';
                    echo "<div class='small danger btn'>";
                    echo CHtml::button('Delete', array('submit' => array('device/delete', 'id' => $device->id),
                        'confirm'=>'Are you sure you want to delete this device?'));
                    echo '</div>';
                } else {
                    if ($liked) {
                        echo '<div class="small danger btn">';
                        echo CHtml::button('Unlike', array('id' => 'like-button'));
                        echo '</div>';
                    } else {
                        echo '<div class="small primary btn">';
                        echo CHtml::button('Like', array('id' => 'like-button'));
                        echo '</div>';
                    }
                }
            ?>
        </div>
    </div>
</div>

<div class="row">
    <?php
        if ($device->description != null) {
            echo 'Description: '.$device->description.'<p/>';
        }
    ?>
</div>

<div class="row">
<?php
    if (Yii::app()->user->isAdmin) {
        $this->widget('xupload.XUpload', array(
            'url' => Yii::app( )->createUrl('/device/uploadImage', array('id' => $device->id)),        
            'model' => $imageModel,        
            'htmlOptions' => array('id'=>'somemodel-form'),
            'attribute' => 'file',
            'multiple' => true,        
            )    
        );
    }
?>
</div>

<div class="row">
    <div class="six columns">

    </div></div>

<div class="row">
    <?php if (count($device->being_considered_requests) != 0){ ?>
    <div id="showbtn">
    <div class="medium info btn" >
        <?php echo CHtml::button('Show being considered requests ('.count($device->being_considered_requests).')', array('id' => 'being_considered_requests_button')); ?>
    </div>
    </div>
    <?php }else {  ?>
    <div id="showbtn" hidden="hidden">
    <div class="medium info btn" >
        <?php echo CHtml::button('Show being considered requests ('.count($device->being_considered_requests).')', array('id' => 'being_considered_requests_button')); ?>
    </div>
    </div>
    <?php } ?>
</div>
<?php $user = User::model()->findByPk(Yii::app()->user->id); ?>
<div class="row" id ="being_considered_requests" current_user="<?php echo $user->profile->name ?>" hidden="hidden"
     count_consider="<?php echo count($device->being_considered_requests) ?>" current_profile="<?php echo $user->profile_id ?>">
<?php
    foreach ($device->being_considered_requests as $request) {
        echo '<div class="row">';
        echo '<p>';                        
        echo $request->user->createViewLink($request->user->profile->name);
        echo ' sent a request to borrow this device';
        if ($request->request_start_time != null) {
            echo ' from '.DateAndTime::returnTime($request->request_start_time);
        }
        if ($request->request_end_time != null) {
            echo ' to '.DateAndTime::returnTime($request->request_end_time);
        }
        echo '. Request created at '.DateAndTime::returnTime($request->created_at);
        echo ' '.$request->createViewLink('View more');    
        echo '</div>';
    }
?>
</div>
<br>
<div class="row">
    <?php if (count($device->finished_requests) != 0){ ?>
    <div class="medium info btn">
        <?php echo CHtml::button('Show finished requests ('.count($device->finished_requests).')', array('id' => 'finished_requests_button') ); ?>
    </div>
    <?php } ?>
</div>

<div class="row" id ="finished_requests" hidden="hidden" count_finish="<?php echo count($device->finished_requests) ?>">
<?php
    foreach ($device->finished_requests as $request) {
        echo '<div class="row">';
        echo '<p>';                        
        echo $request->user->createViewLink($request->user->profile->name);
        echo ' borrowed this device';
        if ($request->start_time != null) {
            echo ' from '.DateAndTime::returnTime($request->start_time);
        }
        if ($request->end_time != null) {
            echo ' to '.DateAndTime::returnTime($request->end_time);
        }
        echo '. Request created at '.DateAndTime::returnTime($request->created_at);
        echo ' '.$request->createViewLink('View more');    
        echo '</div>';
    }
?>
</div>

<?php if (!Yii::app()->user->isAdmin) { ?>
    <div class="row">
        <fieldset class="ten centered columns" id="request_form" request_existed="<?php echo $existed; ?>">
            <legend>Borrow Request</legend>    
            <div class="field seven columns">        
                <?php echo CHtml::textArea('reason', null, array('id' => 'reason-textarea',
                    'placeholder' => 'Enter your reason here', 'rows' => 4, 'device_id' => $device->id)); ?>
            </div>   
            <div class="field four columns"> 
                <?php echo CHtml::textField('from', null, array('id' => 'from', 
                    'placeholder' => 'From', 'readonly' => 'readonly')); ?>        
            </div>
            <div class="field four columns"> 
                <?php echo CHtml::textField('to', null, array('id' => 'to', 
                    'placeholder' => 'To', 'readonly' => 'readonly')); ?>        
            </div>
            <div class="small primary btn two columns">
                <?php echo CHtml::button('Send request', array('id' => 'request-button'));?>
            </div>
        </fieldset>
    </div>

    <div class="modal" id="modal-success">
        <div class="content">
          <a class="close switch" gumby-trigger="|#modal-success"><i class="icon-cancel" /></i></a>
          <div class="row">
            <div class="ten columns centered center-text">
              <h2>Successful </h2>
              <p>You have successfully create new request!</p>
              <p>Please wait until it is accepted by admin!</p>
              <p class="btn primary medium"><a href="#" class="switch" gumby-trigger="|#modal-success">Close</a></p>
            </div>
          </div>
        </div>
     </div>

    <div class="modal" id="modal-fail">
        <div class="content">
          <a class="close switch" gumby-trigger="|#modal-fail"><i class="icon-cancel" /></i></a>
          <div class="row">
            <div class="ten columns centered center-text">
              <h2>Fail</h2>
              <p>Your request can not be created!</p>          
              <p class="btn primary medium"><a href="#" class="switch" gumby-trigger="|#modal-fail">Close</a></p>
            </div>
          </div>
        </div>
     </div>
<?php } ?>