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
            Notification::model()->deleteByPk($notification_id);
            echo header('HTTP/1.1 200 OK');
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