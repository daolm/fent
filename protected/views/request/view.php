<script src='<?php echo Yii::app()->baseUrl; ?>/js/request_view_page.js'></script>
<div class="row">
    <div class="row">
        <?php 
            echo CHtml::label('Username: ', null); 
            echo $request->user->createViewLink() ;
        ?>
    </div>
    <div class="row">
        <?php 
            echo CHtml::label('Device: ', null); 
            echo $request->device->createViewLink() ;
        ?>
    </div>
    <HR/>
    <div class="row">
        <div class="two columns">
            <?php
            echo CHtml::label('Detail Request: ', null);
            echo "</br>" ;
            ?>
        </div>
        <div class="six columns" style="word-wrap: break-word;">
            <div class="row">       
                <?php
                    switch ($request->status) {
                    case Constant::$REQUEST_BEING_CONSIDERED:
                        $status = 'Waiting';
                        $badge = 'primary badge';
                        break;
                    case Constant::$REQUEST_FINISH:
                        $status = 'Finished';
                        $badge = 'warning badge';
                        break;
                    case Constant::$REQUEST_REJECTED:
                        $status = 'Rejected';
                        $badge = 'info badge';
                        break;
                    case Constant::$REQUEST_ACCEPTED:
                        $timestamp = time();
                        if (DateAndTime::getIntervalDays($request->request_end_time, $timestamp) < 0){
                            $status = 'Expired';
                            $badge = 'danger badge';
                        } else {
                            $status = 'Un-expired';
                            $badge = 'success badge';
                        }
                        break;
                    }
                ?>
                <div id="status">
                    <?php echo CHtml::label('Status:', null); ?>
                    <div class="<?php echo $badge; ?>">
                        <?php           
                            echo $status;
                        ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php if (Yii::app()->user->isAdmin && $status == 'Waiting'){
                        echo '<div class="row" id="button_group">';
                        echo '<div class="two columns">';
                        echo '<span class="small success btn">';
                        echo CHtml::button('Accept', array('class' => 'accept_request_btn', 'request_id' => $request->id));
                        echo '</span>';
                        echo '</div>';
                        echo '<div class="two columns">';
                        echo '<span class="small danger btn">';
                        echo CHtml::button('Reject', array('class' => 'reject_request_btn', 'request_id' => $request->id));
                        echo '</span>';
                        echo '</div>';
                        echo '</div>';
                    } elseif (Yii::app()->user->isAdmin && $request->status == Constant::$REQUEST_ACCEPTED) {
                        echo '<div class="row" id="finish_button">';
                        echo '<div class="two columns">';
                        echo '<span class="small warning btn">';
                        echo CHtml::button('Finish', array('class' => 'finish_request_btn', 'request_id' => $request->id));
                        echo '</span>';
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
            </div>
            <?php echo CHtml::label('Reason:', null); ?>
            <span class="textarea"><?php echo $request->reason; ?></span>
            <br />
            <br />
            <?php echo CHtml::label('Created at:', null); ?>
            <span class="textarea"><?php echo DateAndTime::returnTime($request->created_at); ?></span>
            <br />
            <br />
            <?php echo CHtml::label('Request time:', null); ?><br />
            <div class="five columns">
                <?php echo CHtml::label('Start:', null); ?>
                <?php echo DateAndTime::returnTime($request->request_start_time); ?>
            </div>
            <div class="six columns">
                <?php echo CHtml::label('End:', null); ?>
                <?php
                    if ($request->status == Constant::$REQUEST_FINISH || $request->status == Constant::$REQUEST_REJECTED) {
                        echo DateAndTime::returnTime($request->request_end_time);
                    } else {
                        if($request->canBeEditted()){
                            if ($request->request_start_time > Time()) {
                                echo CHtml::textField('end'+$request->id, null, array('request_id' => $request->id,
                                    'class' => 'date_end', 'request_start_time' => DateAndTime::returnTime($request->request_start_time),
                                    'placeholder' => DateAndTime::returnTime($request->request_end_time), 'readonly' => 'readonly', 'style' => 'width:100px;'));
                            } else {
                                echo CHtml::textField('end'+$request->id, null, array('request_id' => $request->id,
                                    'class' => 'date_end', 'request_start_time' => DateAndTime::returnTime(Time()),
                                    'placeholder' => DateAndTime::returnTime($request->request_end_time), 'readonly' => 'readonly', 'style' => 'width:100px;'));
                            }
                            if ($request->request_end_time !== null) {
                                echo '<i class="icon-cancel-circled" id="delete_date_end" request_id="'.$request->id.'"></i>';
                            }
                        }else {
                            echo DateAndTime::returnTime($request->request_end_time);
                        }
                    }
                ?>
            </div>
            <br />
            <br />
            <?php echo CHtml::label('Borrow time:', null); ?><br />
            <div class="five columns">
                <?php echo CHtml::label('Start:', null); ?>
                <span id="start_time"><?php echo DateAndTime::returnTime($request->start_time); ?></span>
            </div>
            <div class="five columns">
                <?php echo CHtml::label('End:', null); ?>
                <span id="end_time"><?php echo DateAndTime::returnTime($request->end_time); ?></span>
            </div>
        </div>
    </div>
    <HR/>
    <?php
        if ($request->canBedeleted()){
            echo "<div class='small danger btn'>";
            echo CHtml::button('Delete', array('submit' => array('request/delete', 'id' => $request->id),
                'confirm'=>'Are you sure you want to delete this device?'));
            echo '</div>';
        }
    ?>
</div>
