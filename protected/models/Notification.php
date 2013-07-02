<?php

/**
 * This is the model class for table "notification".
 *
 * The followings are the available columns in table 'notification':
 * @property integer $id
 * @property integer $request_id
 * @property integer $receiver_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class Notification extends ActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Notification the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'notification';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('request_id', 'required'),
            array('id, request_id, receiver_id, type, created_at, updated_at', 'numerical', 'integerOnly'=>true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, request_id, receiver_id, created_at, updated_at', 'safe', 'on'=>'search'),
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
            'user' => array(self::BELONGS_TO, 'User', 'receiver_id'),
            'request' => array(self::BELONGS_TO, 'Request', 'request_id')
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'request_id' => 'Request',
            'receiver_id' => 'Receiver',
            'type' => 'type',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('request_id',$this->request_id);
        $criteria->compare('receiver_id',$this->receiver_id);
        $criteria->compare('created_at',$this->created_at);
        $criteria->compare('updated_at',$this->updated_at);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
    
    public function getData()
    {
        if ($this->type === Constant::$REQUEST_BEING_CONSIDERED) {
            $status = 'waiting';
        } elseif ($this->type === Constant::$REQUEST_REJECTED) {
            $status = 'rejected';
        } else {
            $status = 'accepted';
        }
        return array('request_id' => $this->request_id,
            'time' => DateAndTime::returnTime($this->created_at, 'H:i d/m/Y'),
            'status' => $status,
            'notification_id' => $this->id
            );
    }
}