<script src='<?php echo Yii::app()->baseUrl; ?>/js/request_view_page.js'></script>
<div class="row">
    <div class="twelve columns">
        <section class="tabs">
            <ul class="tab-nav">
                <li class="active"><a href="#">New requests</a></li>
                <li><a href="#">Unexpired requests</a></li>
                <li><a href="#">Expired requests</a></li>
            </ul>
            <div class="tab-content active">
                <div class="row">
                    <div class="two columns"> <h4>User</h4> </div>
                    <div class="two columns"> <h4>Device</h4> </div>
                    <div class="two columns"> <h4>Request start time</h4> </div>
                    <div class="two columns"> <h4>Request end time</h4> </div>
                </div>
                <?php
                    foreach($new_requests as $request){
                        $this->renderPartial('_request', array('request' => $request, 'timestamp' => $timestamp));
                    }
                ?>
            </div>
            <div class="tab-content" id="unexpired_requests">
                <div class="row">
                    <div class="two columns"> <h4>User</h4> </div>
                    <div class="two columns"> <h4>Device</h4> </div>
                    <div class="two columns"> <h4>Borrowing start time</h4> </div>
                    <div class="two columns"> <h4>Request end time</h4> </div>
                    <div class="one columns"> <h4>Time left</h4> </div>
                </div>
                <?php
                    foreach($unexpired_requests as $request){
                        if ($request->request_end_time != null) {
                            $this->renderPartial('_request', array('request' => $request, 'timestamp' => $timestamp));
                        }
                    }
                ?>
            </div>
            <div class="tab-content">
                <div class="row">
                    <div class="two columns"> <h4>User</h4> </div>
                    <div class="two columns"> <h4>Device</h4> </div>
                    <div class="two columns"> <h4>Borrowing start time</h4> </div>
                    <div class="two columns"> <h4>Request end time</h4> </div>
                    <div class="one columns"> <h4>Expired time</h4> </div>
                </div>
                <?php
                    foreach($expired_requests as $request){
                        $this->renderPartial('_request', array('request' => $request, 'timestamp' => $timestamp));
                    }
                ?>
            </div>
        </section>
    </div>
</div>
