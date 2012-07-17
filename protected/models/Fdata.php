<?php

/**
 * This is the model class for table "fdata".
 *
 * The followings are the available columns in table 'fdata':
 * @property integer $id
 * @property string $fname
 * @property string $fext
 * @property string $fsize
 * @property string $mt
 * @property string $dt
 * @property string $data
 * @property integer $pid
 *
 * The followings are the available model relations:
 * @property Doc $p
 */
class Fdata extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Fdata the static model class
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
		return 'fdata';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, pid', 'required'),
			array('id, pid', 'numerical', 'integerOnly'=>true),
			array('fname, fext, mt', 'length', 'max'=>45),
			array('fsize', 'length', 'max'=>10),
			array('dt, data', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, fname, fext, fsize, mt, dt, data, pid', 'safe', 'on'=>'search'),
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
			'p' => array(self::BELONGS_TO, 'Doc', 'pid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'fname' => 'Fname',
			'fext' => 'Fext',
			'fsize' => 'Fsize',
			'mt' => 'Mt',
			'dt' => 'Dt',
			'data' => 'Data',
			'pid' => 'Pid',
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
		$criteria->compare('fname',$this->fname,true);
		$criteria->compare('fext',$this->fext,true);
		$criteria->compare('fsize',$this->fsize,true);
		$criteria->compare('mt',$this->mt,true);
		$criteria->compare('dt',$this->dt,true);
		$criteria->compare('data',$this->data,true);
		$criteria->compare('pid',$this->pid);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}