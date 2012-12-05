<?php

/**
 * This is the model class for table "doc".
 *
 * The followings are the available columns in table 'doc':
 * @property integer $id
 * @property string $taxon
 * @property string $opdate
 * @property string $num
 * @property string $expn
 * @property integer $status
 * @property string $author
 * @property string $inspector
 * @property string $title
 * @property string $details
 * @property string $amtrub
 * @property string $amtcur
 * @property integer $c
 * @property integer $isdelete
 * @property integer $pid
 * @property integer $hasNotice
 *
 * The followings are the available model relations:
 * @property Doc $p
 * @property Doc[] $docs
 * @property Opdate $opdate0
 * @property Status $status0
 * @property Fdata[] $fdatas
 */
class Doc extends MAr
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Doc the static model class
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
		return 'doc';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('status, c, isdelete, pid, hasNotice', 'numerical', 'integerOnly'=>true),
			array('taxon, num, expn, author, inspector', 'length', 'max'=>45),
			array('title', 'length', 'max'=>155),
			array('amtrub, amtcur', 'length', 'max'=>10),
			array('opdate, details', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, taxon, opdate, num, expn, status, author, inspector, title, details, amtrub, amtcur, c, isdelete, pid, hasNotice', 'safe', 'on'=>'search'),
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
			'docs' => array(self::HAS_MANY, 'Doc', 'pid'),
			'opdate0' => array(self::BELONGS_TO, 'Opdate', 'opdate'),
			'status0' => array(self::BELONGS_TO, 'Status', 'status'),
			'fdatas' => array(self::HAS_MANY, 'Fdata', 'pid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'taxon' => 'Taxon',
			'opdate' => 'Opdate',
			'num' => 'Num',
			'expn' => 'Expn',
			'status' => 'Status',
			'author' => 'Author',
			'inspector' => 'Inspector',
			'title' => 'Title',
			'details' => 'Details',
			'amtrub' => 'Amtrub',
			'amtcur' => 'Amtcur',
			'c' => 'C',
			'isdelete' => 'Isdelete',
			'pid' => 'Pid',
			'hasNotice' => 'Has Notice',
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
		$criteria->compare('taxon',$this->taxon,true);
		$criteria->compare('opdate',$this->opdate,true);
		$criteria->compare('num',$this->num,true);
		$criteria->compare('expn',$this->expn,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('author',$this->author,true);
		$criteria->compare('inspector',$this->inspector,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('details',$this->details,true);
		$criteria->compare('amtrub',$this->amtrub,true);
		$criteria->compare('amtcur',$this->amtcur,true);
		$criteria->compare('c',$this->c);
		$criteria->compare('isdelete',$this->isdelete);
		$criteria->compare('pid',$this->pid);
		$criteria->compare('hasNotice',$this->hasNotice);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}