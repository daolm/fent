<?php

/**
 * This is the model class for table "device".
 *
 * The followings are the available columns in table 'device':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $maker
 * @property string $serial_number
 * @property string $management_number
 * @property string $model_number
 * @property string $status
 * @property integer $updated_at
 * @property integer $created_at
 * @property integer $category_id
 */
class Device extends ActiveRecord 
{

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Device the static model class
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
        return 'device';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() 
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, category_id, management_number', 'required'),
            array('serial_number, management_number', 'unique'),
            array('updated_at, created_at, category_id', 'numerical', 'integerOnly' => true),
            array('name, maker, serial_number, management_number, model_number, status', 'length', 'max' => 45),
            array('description', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, description, serial_number, status, updated_at, created_at, category_id', 'safe', 'on' => 'search'),
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
            'category' => array(self::BELONGS_TO, 'Category', 'category_id'),
            'requests' => array(self::HAS_MANY, 'Request', 'device_id'),
            'favorites' => array(self::HAS_MANY, 'Favorite', 'user_id'),
            'accepted_request' => array(self::HAS_ONE, 'Request', 'device_id',
                'on' => 'accepted_request.status=1'),
            'finished_requests' => array(self::HAS_MANY, 'Request', 'device_id',
                'on' => 'finished_requests.status=3'),
            'being_considered_requests' => array(self::HAS_MANY, 'Request', 'device_id',
                'on' => 'being_considered_requests.status=0'),
            'borrower' => array(self::HAS_ONE, 'User', array('user_id' => 'id'), 
                'through' => 'accepted_request'),  
        );
    }
    
    public function behaviors()
    {
        return array(
            'ImageBehavior' => array(
                'class' => 'application.components.ImageBehavior'),
            'ViewLinkBehavior' => array(
                'class' => 'application.components.ViewLinkBehavior',
                'display_attribute' => 'name',                
            )
        );
    }
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() 
    {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'serial_number' => 'Serial Number',
            'management_number' => 'Management Number',
            'model_number' => 'Model Number',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'category_id' => 'Category',
            'image' => 'Image',
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
        $criteria->compare('name', $this->name, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('serial', $this->serial, true);
        $criteria->compare('status', $this->status, true);
        $criteria->compare('updated_at', $this->updated_at);
        $criteria->compare('created_at', $this->created_at);
        $criteria->compare('category_id', $this->category_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
    
    public function scopes()
    {
        return array(
            'newest'=>array(
                'order' => 'created_at DESC',
                'limit' => 6,
            ),
        );
    }
       
     protected function afterDelete()
    {
        $requests = Request::model()->findAllByAttributes(array('device_id'=>$this->id));
        foreach ($requests as $request){
            $request->delete() ;
        }
        return parent::afterDelete();
    }
    
}