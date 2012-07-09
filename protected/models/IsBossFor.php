<?php

/**
 * This is the model class for table "is_boss_for".
 *
 * The followings are the available columns in table 'is_boss_for':
 * @property integer $parent
 * @property integer $child
 *
 * The followings are the available model relations:
 * @property User $parent0
 * @property User $child0
 */
class IsBossFor extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return IsBossFor the static model class
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
		return 'is_boss_for';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('parent, child', 'required'),
			array('parent, child', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('parent, child', 'safe', 'on'=>'search'),
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
			'parent0' => array(self::BELONGS_TO, 'User', 'parent'),
			'child0' => array(self::BELONGS_TO, 'User', 'child'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'parent' => 'Parent',
			'child' => 'Child',
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

		$criteria->compare('parent',$this->parent);
		$criteria->compare('child',$this->child);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}