<?php

class RequestController extends Controller {
    
    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }
        
    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */

    public function accessRules() {
    return array(
        array('allow', // allow admin user to perform 'index' actions
            'controllers' => array('request', 'rejectOrAccept'),
            'expression' => '$user->isAdmin'
        ),
    );
    }
    
    public function actionCreate()
    {
        $name_or_code = $_POST['assign_user'];
        $criteria = new CDbCriteria();
        $criteria->condition = "name = '{$name_or_code}' OR employee_code = '{$name_or_code}'";
        $profile = Profile::model()->find($criteria);
        if ($profile) {
            $device_id = $_POST['device_id'];
            $request = new Request();
            $request->status = Constant::$REQUEST_ACCEPTED;
            if (isset($profile->user->id)) {
                $request->user_id = $profile->user->id;
                $request->device_id = $device_id;
                $request->reason = 'Added by admin';
                $request->start_time = time();
                if ($request->save()) {
                    $request->createNotification();
                }                
            } else {
                Yii::app()->user->setFlash('errors', 'User has not been created!');
            } 
        } else {
            Yii::app()->user->setFlash('errors', 'Profile Not Found!');
        }
        $this->redirect(Yii::app()->request->urlReferrer);
    }
    
    public function actionIndex($status = null, $type_search = null, $from = null, $to = null, $no_time_given = null) {
        $criteria = new CDbCriteria();
        $params = array();        
        
        if ($status != null && $status != 'All') {
            if ($status == Constant::$REQUEST_UNEXPIRED) {       
                $criteria->addCondition('status=:status', 'AND');
                $params[':status'] = Constant::$REQUEST_ACCEPTED;
                $criteria->addCondition('request_end_time IS NULL OR request_end_time>:time', 'AND');
                $date = new DateTime();
                $params[':time'] = $date->getTimestamp();
            }
            if ($status == Constant::$REQUEST_EXPIRED) {
                $criteria->addCondition('status=:status', 'AND');
                $params[':status'] = Constant::$REQUEST_ACCEPTED;
                $criteria->addCondition('request_end_time<:time AND request_end_time IS NOT NULL', 'AND');
                $params[':time'] = time();
            }
            if ($status == Constant::$REQUEST_BEING_CONSIDERED || $status == Constant::$REQUEST_FINISH || $status == Constant::$REQUEST_REJECTED) {
                $criteria->addCondition('status=:status', 'AND');
                $params[':status'] = $status;
            } 
        }
        if ($type_search == null) {
            $no_time_given = true;
        } else  {
            $array_type_search = array('request_end_time', 'request_start_time', 'end_time', 'start_time');
            if (in_array($type_search, $array_type_search)) {
                if ($from) {
                    $from_time = strtotime(str_replace('/', '-', $from));
                    $criteria->addCondition("{$type_search}>=:from", 'AND');
                    $params[':from'] = $from_time;
                }
                if ($to) {
                    $to_time = strtotime(str_replace('/', '-', $to));
                    $criteria->addCondition("{$type_search}<=:to", 'AND');
                    $params[':to'] = $to_time;
                }
                if (!$from && !$to) {
                    $criteria->addCondition("{$type_search} IS NOT NULL", 'AND');                    
                }
                if ($no_time_given) {
                    if (($status || $status == 0) && $status != 'All') {
                        $criteria->addCondition("{$type_search} IS NULL AND status=:status", 'OR');  
                    } else {
                        $criteria->addCondition("{$type_search} IS NULL", 'OR');
                    }
                } 
            }
        }
        
        if (!Yii::app()->user->isAdmin){
            $criteria->addCondition('user_id = :user_id', 'AND');
            $params[':user_id'] = Yii::app()->user->getId();
        }
        $criteria->params = $params;
        $criteria->order = 'created_at DESC';
        $count = Request::model()->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = Constant::$NOTIFICATION_PAGE_SIZE;
        $pages->applyLimit($criteria);
        $requests = Request::model()->findAll($criteria);
        
        $this->render('index',array(
            'requests' => $requests,
            'pages' => $pages,
            'status' => $status,
            'type_search' => $type_search,
            'from' => $from,
            'to' => $to,
            'no_time_given' => $no_time_given,
        ));
    }
    
    public function actionRejectOrAccept() {
        if (!Yii::app()->request->isAjaxRequest) {
            $this->render('/site/error', array('code' => 403, 'message' => 'Forbidden'));                        
            Yii::app()->end();
        } 
        if (isset($_POST['request_id']) && isset($_POST['value'])) {
            $value = $_POST['value'];
            $request_id = $_POST['request_id'];
            $request = Request::model()->findByPk($request_id);
            if ($request != null) {
                if ($value == 'Reject') {
                    $request->status = Constant::$REQUEST_REJECTED;
                    $result = $request->save();
                } else {                    
                    $available = Validator::checkDeviceAvailable($request->device_id);
                    if ($available) {
                        $request->status = Constant::$REQUEST_ACCEPTED;
                        $request->start_time = time();
                        $result = $request->save();
                    } else {
                        echo header('HTTP/1.1 424 Method Failure');
                    }
                }
                if ($result){
                    echo header('HTTP/1.1 200 OK');
                    Notification::model()->deleteAllByAttributes(array('request_id' => $request->id));
                    $request->createNotification();
                } else {
                    echo header('HTTP/1.1 424 Method Failure');
                }
            } else {
                echo header('HTTP/1.1 424 Method Failure');
            }
        } else {
            echo header('HTTP/1.1 405 Method Not Allowed');
        }
    }
    
    public function actionView($id){
        $request = $this->loadModel($id);
        $this->render('/request/view', array(
            'request' => $request 
        ));
    }
    
    public function actionFinish() {
        if (!Yii::app()->request->isAjaxRequest) {
            $this->render('/site/error', array('code' => 403, 'message' => 'Forbidden'));                        
            Yii::app()->end();
        }
        if (isset($_POST['request_id'])) {
            $request_id = $_POST['request_id'];
            $request = Request::model()->findByPk($request_id);
            if ($request != null) {
                $request->status = Constant::$REQUEST_FINISH;
                $request->end_time = time();
                if ($request->save()) {
                    echo header('HTTP/1.1 200 OK');
                } else {
                    echo header('HTTP/1.1 424 Method Failure');
                }
            } else {
                echo header('HTTP/1.1 424 Method Failure');
            }
        } else {
            echo header('HTTP/1.1 405 Method Not Allowed');
        }
    }
    
    public function loadModel($id)
    {
        $model = Request::model()->findByPk($id);
        if($model === null){
            throw new CHttpException(404,'The requested page does not exist.');
        }
        return $model;
    }
    
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }
    
    public function actionEditEndTime() {
        if (!Yii::app()->request->isAjaxRequest) {
            $this->render('/site/error', array('code' => 403, 'message' => 'Forbidden'));                        
            Yii::app()->end();
        }
        if (isset($_POST['date_end']) && isset($_POST['date_end'])) {
            $request_id = $_POST['request_id'];
            $request_end_time = $_POST['date_end'];
            $request = Request::model()->findByPk($request_id);
            if ($request != null && $request->status != Constant::$REQUEST_FINISH && Constant::$REQUEST_REJECTED) {
                if ($request_end_time == null) {
                    $request->request_end_time = $request_end_time;
                } else {
                    $request->request_end_time = strtotime($request_end_time);
                }
                if ($request->save()) {
                    echo header('HTTP/1.1 200 OK');
                } else {
                    echo header('HTTP/1.1 424 Method Failure');
                }
            } else {
                echo header('HTTP/1.1 424 Method Failure');
            }
        } else {
            echo header('HTTP/1.1 405 Method Not Allowed');
        }
    }
}
?>
