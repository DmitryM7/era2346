<?php

/**
 * This is the model class for table "employee".
 *
 * The followings are the available columns in table 'employee':
 * @property integer $id
 * @property string $surname
 * @property string $name
 * @property string $patronymic
 * @property string $birth
 * @property string $sex
 * @property string $mobphone
 * @property string $homephone
 * @property string $inphone
 * @property string $email
 * @property string $hemail
 */
class MEmployee extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return MEmployee the static model class
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
		return 'employee';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('surname', 'length', 'max'=>100),
			array('name, patronymic', 'length', 'max'=>75),
			array('sex', 'length', 'max'=>10),
			array('mobphone, homephone, inphone, email, hemail', 'length', 'max'=>45),
			array('birth', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, surname, name, patronymic, birth, sex, mobphone, homephone, inphone, email, hemail', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'surname' => 'Surname',
			'name' => 'Name',
			'patronymic' => 'Patronymic',
			'birth' => 'Birth',
			'sex' => 'Sex',
			'mobphone' => 'Mobphone',
			'homephone' => 'Homephone',
			'inphone' => 'Inphone',
			'email' => 'Email',
			'hemail' => 'Hemail',
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
		$criteria->compare('surname',$this->surname,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('patronymic',$this->patronymic,true);
		$criteria->compare('birth',$this->birth,true);
		$criteria->compare('sex',$this->sex,true);
		$criteria->compare('mobphone',$this->mobphone,true);
		$criteria->compare('homephone',$this->homephone,true);
		$criteria->compare('inphone',$this->inphone,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('hemail',$this->hemail,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}