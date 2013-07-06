<?php

/**
 * This is the model class for table "request".
 *
 * The followings are the available columns in table 'request':
 * @property integer $id
 * @property integer $status
 * @property integer $request_time
 * @property integer $start_time
 * @property integer $end_time
 * @property integer $updated_at
 * @property integer $created_at
 * @property integer $user_id
 * @property integer $device_id
 */
class Request extends ActiveRecord 
{

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Request the static model class
     */
    public static function model($className = __CLASS__) 
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() 
    {
        return 'request';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() 
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, device_id, status', 'required'),
            array('status, request_start_time, request_end_time, start_time, end_time, updated_at, created_at, user_id, device_id', 'numerical', 'integerOnly'=>true),            
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, status, request_start_time, request_end_time, start_time, end_time, updated_at, created_at, user_id, device_id', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() 
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'device' => array(self::BELONGS_TO, 'Device', 'device_id'),
            'notifications' => array(self::HAS_MANY, 'Notification', 'request_id')
        );
    }
    
    public function behaviors()
    {
        return array(            
            'ViewLinkBehavior' => array(
                'class' => 'application.components.ViewLinkBehavior',                
            )
        );
    }
    
    public function afterDelete()
    {
        foreach ($this->notifications as $notification) {
            $notification->delete();
        }
        return parent::afterDelete();
    }
    
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() 
    {
        return array(
            'id' => 'ID',
            'status' => 'Status',
            'request_start_time' => 'Request Start Time',
            'request_end_time' => 'Request End Time',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'user_id' => 'User',
            'device_id' => 'Device',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() 
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('status', $this->status);
        $criteria->compare('request_start_time', $this->request_start_time);
        $criteria->compare('request_end_time', $this->request_end_time);
        $criteria->compare('start_time', $this->start_time);
        $criteria->compare('end_time', $this->end_time);
        $criteria->compare('updated_at', $this->updated_at);
        $criteria->compare('created_at', $this->created_at);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('device_id', $this->device_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    } 
    
    public function canBeEditted()
    {
        if (Yii::app()->user->isAdmin) {
            return true;
        }
        if ($this->user_id == Yii::app()->user->getId() && $this->status == Constant::$REQUEST_BEING_CONSIDERED) {
            return true;
        }
        return false;
    }
    
    public function sendNoticeEmail($value)
    {
        $content = 'Your request to borrow ';
        $content .= CHtml::link($this->device->name, Yii::app()->createAbsoluteUrl('device/view', array('id' => $this->device_id)));
        $content .= ' has been ';
        if ($value == 'Reject') {
            $subject = 'Rejected request';
            $content .= 'rejected ';
        } else {
            $subject = 'Accepted request';
            $content .= 'accepted ';
        }
        $content .= 'at '.DateAndTime::returnTime(time(), 'H:i - d/m/Y').'.';
        MailSender::sendMail($this->user->profile->email, $subject, $content, $this->user->profile->name);
    }

    public function createNotification()
    {
        $notification = new Notification;
        $notification->request_id = $this->id;
        $notification->type = $this->status;
        if ($this->status !== Constant::$REQUEST_BEING_CONSIDERED) {
            $notification->receiver_id = $this->user_id;
        } else {
            $notification->receiver_id = null;
        }
        $result = $notification->save();
        if ($result && RedisNotification::checkRequirement()) {
            $rn = new RedisNotification();
            if ($rn->connect() && $rn->setChannelByUserId($notification->receiver_id)) {
                $rn->publishNotifications(json_encode($notification->getData()));
            }
        }
    }
} 