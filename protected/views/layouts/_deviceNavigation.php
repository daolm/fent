<div class="navbar" gumby-fixed="top" id="nav3">
    <div class="row">
        <!-- Toggle for mobile navigation, targeting the <ul> -->
        <a class="toggle" gumby-trigger="#nav1 > .row > ul" href="#"><i class="icon-menu"></i></a>
        <ul class="row">
            <li><?php echo CHtml::link('Home', Yii::app()->homeUrl); ?></li>
            <li><?php echo CHtml::link('Introduction', '#'); ?></li>
            <li>
                <?php echo CHtml::link('Devices', Yii::app()->createUrl('device/index')); ?>
                <div class="dropdown">
                    <ul>                    
                        <?php 
                            foreach (Yii::app()->session['category'] as $category) {
                                echo "<li>{$category->createViewLink()}</li>";
                            }
                        ?>                    
                    </ul>
                </div>
            </li>
            <li>
                <?php 
                    if (Yii::app()->user->isAdmin){
                        echo CHtml::link('Users', Yii::app()->createUrl('profile/index')); 
                    }
                ?>
            </li>
            <li>
                <?php
                    if (Yii::app()->user->isAdmin){
                        echo CHtml::link('All Requests', Yii::app()->createUrl('request/index'));
                    }
                ?>
            </li>
            <li>
                <?php echo CHtml::link(Yii::app()->user->username, '#'); ?>
                <div class="dropdown">
                    <ul>
                        <?php if (!Yii::app()->user->isAdmin) { ?>
                        <li><?php echo CHtml::link('Favorite', Yii::app()->createUrl('user/favorite')); ?></li>
                        <li><?php echo CHtml::link('History', Yii::app()->createUrl('request/index')); }?></li>
                        <li><?php echo CHtml::link('Profile', Yii::app()->createUrl('profile/view',
                            array('id' => Yii::app()->user->profileId))); ?></li>
                        <li><?php echo CHtml::link('Change password', Yii::app()->createUrl('user/changepassword')); ?></li>
                    </ul>
                </div>
            </li>
            <li><?php echo CHtml::link('Logout', array('user/signout')); ?></li>
            <li class='field'>
                <?php echo CHtml::textField(null, null, array('class'=>'search input','placeholder'=>'Search', 'id' => 'search')); ?>
            </li> 
        </ul>
    </div>
</div>
<div class="row" id="notification">
    <?php 
        $user = User::model()->findByPk(Yii::app()->user->getId());
        $notifications = $user->getAllNotifications();
        if (isset($notifications)){
            foreach ($notifications as $noti){
                switch ($noti->type) {
                    case Constant::$REQUEST_REJECTED:
                        $status = 'rejected';
                        break;
                    case Constant::$REQUEST_ACCEPTED:
                        $status = 'accepted';
                        break;
                }
                echo '<div class="row" id = "notification_'.$noti->id.'">';
                if (Yii::app()->user->isAdmin) {
                    echo 'You have new waiting';
                } else {
                    echo 'Admin '.$status.' your';
                }
                echo $noti->request->createViewLink(' request');
                echo ' at time: '.DateAndTime::returnTime($noti->created_at, 'H:i d/m/Y');
                echo '<i class="icon-cancel-circled delete_notification" notification_id="'.$noti->id.'"></i>';
                echo '</div>';
            }
        }
    ?>
</div>
<div class="row">
    <hr />
</div>