<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property integer $is_admin
 * @property integer $updated_at
 * @property integer $created_at
 * @property integer $profile_id
 */
class User extends ActiveRecord 
{

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return User the static model class
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
        return 'user';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() 
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(            
            array('username, password, profile_id', 'required'),
            array('username','unique'),
            array('username','unique', 'className' => 'Profile', 'attributeName' => 'employee_code'),
            array('is_admin, updated_at, created_at, profile_id', 'numerical', 'integerOnly' => true),
            array('username', 'length', 'max' => 45),
            array('password', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, username, password, is_admin, updated_at, created_at, profile_id', 'safe', 'on' => 'search'),
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
            'profile' => array(self::BELONGS_TO, 'Profile', 'profile_id'),
            'requests' => array(self::HAS_MANY, 'Request', 'user_id'),
            'accepted_requests' => array(self::HAS_MANY, 'Request', 'user_id',
                'on' => 'accepted_requests.status=1'),
            'finished_requests' => array(self::HAS_MANY, 'Request', 'user_id',
                'on' => 'finished_requests.status=3'),
            'being_considered_requests' => array(self::HAS_MANY, 'Request', 'user_id',
                'on' => 'being_considered_requests.status=0'),
            'favorites' => array(self::HAS_MANY, 'Favorite', 'user_id'),
            'favorite_devices' => array(self::HAS_MANY, 'Device', array('device_id' => 'id'), 'through' => 'favorites'),
            'notifications' => array(self::HAS_MANY, 'Notification', 'receiver_id')
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() 
    {
        return array(
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'is_admin' => 'Is Admin',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'profile_id' => 'Profile',
        );
    }
    
    public function behaviors()
    {
        return array(
            'ViewLinkBehavior' => array(
                'class' => 'application.components.ViewLinkBehavior',
                'display_attribute' => 'username',
                'view_model' => 'profile'
            )
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
        $criteria->compare('username', $this->username, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('is_admin', $this->is_admin);
        $criteria->compare('updated_at', $this->updated_at);
        $criteria->compare('created_at', $this->created_at);
        $criteria->compare('profile_id', $this->profile_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
    
    public function signUp($username, $password, $profile_id)
    {
        $this->username = $username;
        $this->password = md5($password);
        $this->profile_id = $profile_id;        
        return $this->save();
    }
    
    public function getCurrentRequests()
    {
        $criteria = new CDbCriteria;
        $criteria->condition = 'user_id=:user_id AND status=:status';
        $criteria->params = array(':user_id'=> $this->id, ':status' => Constant::$REQUEST_ACCEPTED);
        $requests = Request::model()->findAll($criteria);
        return $requests;
    }
    
    public function afterDelete()
    {
        foreach ($this->requests as $request) {
            $request->delete();
        }
        return parent::afterDelete();
    }
    
    public function getAllNotifications()
    {
        if (!$this->is_admin){
            $notifications = $this->notifications;
        } else {
            $notifications = Notification::model()->findAllByAttributes(array('type' => Constant::$REQUEST_BEING_CONSIDERED));
        }
        return $notifications;
    }
    
}