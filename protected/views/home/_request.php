<div class="row" id="request_<?php echo $request->id; ?>"
     profile_id="<?php echo $request->user->profile->id; ?>"
     device_id="<?php echo $request->device->id; ?>"
     name="<?php echo $request->user->profile->name; ?>"
     device_name="<?php echo $request->device->name; ?>">    
    <div class="two columns crop">
        <?php if ($request) echo CHtml::link(CHtml::encode($request->user->profile->name), array('profile/view', 'id' => $request->user->profile->id)); ?>
    </div>
    <div class="two columns crop">
        <?php echo CHtml::link(CHtml::encode($request->device->name), array('device/view', 'id' => $request->device->id)); ?>
    </div>
    <div class="two columns">
        <?php
        
            if ($request->status == 0){
                echo DateAndTime::returnTime($request->request_start_time);
            } else {
                echo DateAndTime::returnTime($request->start_time);
            }
        ?>
    </div>
    <div class="two columns">
        <?php
        if ($request->request_start_time > Time()) {
            echo CHtml::textField('end'+$request->id, null, array('request_id' => $request->id,
                'class' => 'date_end', 'request_start_time' => DateAndTime::returnTime($request->request_start_time),
                'placeholder' => DateAndTime::returnTime($request->request_end_time), 'readonly' => 'readonly', 'style' => 'width:100%'));
        } else {
            echo CHtml::textField('end'+$request->id, null, array('request_id' => $request->id,
                'class' => 'date_end', 'request_start_time' => DateAndTime::returnTime(Time()),
                'placeholder' => DateAndTime::returnTime($request->request_end_time), 'readonly' => 'readonly', 'style' => 'width:100%'));
        }
             
        ?>
    </div>
    <?php
        if ($request->status == 1){
            echo '<div class="one columns">';
            $expired = DateAndTime::getIntervalDays($request->request_end_time, $timestamp);
            if ($expired !== null) {
                if ($expired < 0) {
                    echo -1*$expired.' days';
                } else {
                    echo $expired.' days';
                }
            }
            echo '</div>';
            
            echo '<div class="two columns">';
            echo '<span class="small primary btn">';
            echo $request->createViewLink("View more");
            echo '</span>';
            echo '</div>';
            
            echo '<div class="one columns">';
            echo '<span class="small warning btn">';
            echo CHtml::button('Finish', array('class' => 'finish_request', 'request_id' => $request->id));
            echo '</span>';
            echo '</div>';
        } else {
            echo '<div class="two columns">';
            echo '<span class="small primary btn">';
            echo $request->createViewLink("View more");
            echo '</span>';
            echo '</div>';
            
            echo '<div class="one columns">';
            echo '<span class="small success btn">';
            echo CHtml::button('Accept', array('class' => 'accept_request', 'request_id' => $request->id));
            echo '</span>';
            echo '</div>';
            
            echo '<div class="one columns">';
            echo '<span class="small danger btn">';
            echo CHtml::button('Reject', array('class' => 'reject_request', 'request_id' => $request->id));
            echo '</span>';
            echo '</div>';
        }
    ?>
</div>