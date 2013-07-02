<?php

class NotificationController extends Controller
{
    public function actionDelete()
    {
        if (!Yii::app()->request->isAjaxRequest) {
            $this->render('/site/error', array('code' => 403, 'message' => 'Forbidden'));
            Yii::app()->end();
        }
        if (isset($_POST['notification_id'])) {
            $notification_id = $_POST['notification_id'];
            $notification = Notification::model()->findByPk($notification_id);
            if ($notification != null) {
                $result = $notification->delete();
                if ($result){
                    echo header('HTTP/1.1 200 OK');
                } else {
                    echo header('HTTP/1.1 500 Internal Server Error');
                }
            } else {
                echo header('HTTP/1.1 424 Method Failure');
            }
        } else {
            echo header('HTTP/1.1 400 Bad request');
        }
    }

    public function actionGetNotifications()
    {
        if (!Yii::app()->request->isAjaxRequest) {
            $this->render('/site/error', array('code' => 403, 'message' => 'Forbidden'));
            Yii::app()->end();
        }
        $user = User::model()->findByPk(Yii::app()->user->getId());
        $notifications = $user->getAllNotifications();
        $results = array();
        foreach ($notifications as $noti) {
            $results[] = $noti->getData();
        }
        echo json_encode($results);
    }
}
?>