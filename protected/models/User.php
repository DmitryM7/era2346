<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $email
 * @property string $un
 * @property string $un2
 * @property string $cert
 * @property string $dt
 * @property integer $ispseudo
 * @property string $realun
 * @property string $phone
 * @property string $position
 * @property string $surname
 * @property string $name
 * @property string $patronymic
 *
 * The followings are the available model relations:
 * @property IsBossFor[] $isBossFors
 * @property IsBossFor[] $isBossFors1
 */
class User extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return User the static model class
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
			array('ispseudo', 'numerical', 'integerOnly'=>true),
			array('email', 'length', 'max'=>150),
			array('un, un2, realun, phone', 'length', 'max'=>45),
			array('position', 'length', 'max'=>200),
			array('surname', 'length', 'max'=>105),
			array('name', 'length', 'max'=>75),
			array('patronymic', 'length', 'max'=>65),
			array('cert, dt', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, email, un, un2, cert, dt, ispseudo, realun, phone, position, surname, name, patronymic', 'safe', 'on'=>'search'),
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
			'subordinate' => array(self::MANY_MANY, 'MUser', 'is_boss_for(parent,child)'),
			'boss' => array(self::MANY_MANY, 'MUser', 'is_boss_for(child,parent)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'email' => 'Email',
			'un' => 'Un',
			'un2' => 'Un2',
			'cert' => 'Cert',
			'dt' => 'Dt',
			'ispseudo' => 'Ispseudo',
			'realun' => 'Realun',
			'phone' => 'Phone',
			'position' => 'Position',
			'surname' => 'Surname',
			'name' => 'Name',
			'patronymic' => 'Patronymic',
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
		$criteria->compare('email',$this->email,true);
		$criteria->compare('un',$this->un,true);
		$criteria->compare('un2',$this->un2,true);
		$criteria->compare('cert',$this->cert,true);
		$criteria->compare('dt',$this->dt,true);
		$criteria->compare('ispseudo',$this->ispseudo);
		$criteria->compare('realun',$this->realun,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('position',$this->position,true);
		$criteria->compare('surname',$this->surname,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('patronymic',$this->patronymic,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}