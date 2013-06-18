<div class="row">
    <div class="two columns crop">
        <?php echo CHtml::link(CHtml::encode($request->user->username), array('profile/view', 'id' => $request->user->profile->id)); ?>
    </div>
    <div class="two columns crop">
        <?php echo CHtml::link(CHtml::encode($request->device->name), array('device/view', 'id' => $request->device->id)); ?>
    </div>
    <div class="three columns crop">
        <?php echo CHtml::encode($request->reason); ?>
    </div>
    <div class="two columns">
        <?php echo DateAndTime::returnTime($request->request_start_time, 'd/m/Y'); ?>
    </div>
    <div class="two columns">
        <?php echo DateAndTime::returnTime($request->request_end_time, 'd/m/Y'); ?>
    </div>
    <div class="two columns">
        <?php echo DateAndTime::returnTime($request->start_time, 'd/m/Y'); ?>
    </div>
    <div class="two columns">
        <?php echo DateAndTime::returnTime($request->end_time, 'd/m/Y'); ?>
    </div>
    <div class="one columns">
        <?php
            if ($request->status == Constant::$REQUEST_BEING_CONSIDERED){
                $status = 'Waiting';
            } elseif ($request->status == Constant::$REQUEST_FINISH) {
                $status = 'Finished';
            } elseif ($request->status == Constant::$REQUEST_REJECTED) {
                $status = 'Rejected';
            } else {
                if ($request->request_end_time < $timestamp){
                    $status = 'Expired';
                } else {
                    $status = 'Borrowing';
                }
            }
            echo CHtml::link(CHtml::encode($status), array('#'));
        ?>
    </div>
</div>