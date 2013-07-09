<?php 
if ($request->status == Constant::$REQUEST_BEING_CONSIDERED){
    $status = 'Waiting';
    $badge = 'primary badge';
} elseif ($request->status == Constant::$REQUEST_FINISH) {
    $status = 'Finished';
    $badge = 'warning badge';
} elseif ($request->status == Constant::$REQUEST_REJECTED) {
    $status = 'Rejected';
    $badge = 'info badge';
} else {
    if ($request->request_end_time === null) {
        $status = 'Un-expired';
        $badge = 'success badge';
    } else {
        if (DateAndTime::getIntervalDays($request->request_end_time, $timestamp) < 0) {
            $status = 'Expired';
            $badge = 'danger badge';
        } else {
            $status = 'Un-expired';
            $badge = 'success badge';
        }
    }
}
?>

<div class="row a_request" status_code="<?php echo $status; ?>" style="font-size: 0.9em">
    <div class="two columns crop">
        <?php echo CHtml::link(CHtml::encode($request->user->profile->name), array('profile/view', 'id' => $request->user->profile->id)); ?>
    </div>
    <div class="two columns crop">
        <?php echo CHtml::link(CHtml::encode($request->device->name), array('device/view', 'id' => $request->device->id)); ?>
    </div>
    <div class="two columns">
        <?php echo DateAndTime::returnTime($request->request_start_time); ?>
    </div>
    <div class="two columns">
        <?php echo DateAndTime::returnTime($request->request_end_time); ?>
    </div>
    <div class="two columns">
        <?php echo DateAndTime::returnTime($request->start_time); ?>
    </div>
    <div class="two columns">
        <?php echo DateAndTime::returnTime($request->end_time); ?>
    </div>
    <div class="two columns <?php echo $badge; ?>">
        <?php           
            echo $status;
        ?>
    </div>
    <?php 
        echo '<div class="two columns">';
        echo '<span class="small primary btn">';
        echo $request->createViewLink('View more');
        echo '</span>';
        echo '</div>';
    ?>
</div>