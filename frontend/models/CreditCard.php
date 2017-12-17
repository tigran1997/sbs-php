<?php

/**
 * This is the model class for table "credit_card".
 *
 * The followings are the available columns in table 'credit_card':
 * @property integer $id
 * @property integer $card_type_id
 * @property integer $number
 * @property integer $cvv
 * @property string $expire_date
 * @property integer $account_id
 *
 * The followings are the available model relations:
 * @property CardTypes $cardType
 * @property Accounts $account
 */
class CreditCard extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'credit_card';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array(' card_type_id, number, cvv,  account_id', 'required'),
			array('id, card_type_id, number, cvv, account_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, card_type_id, number, cvv, expire_date, account_id', 'safe', 'on'=>'search'),
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
			'cardType' => array(self::BELONGS_TO, 'CardTypes', 'card_type_id'),
			'account' => array(self::BELONGS_TO, 'Accounts', 'account_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'card_type_id' => 'Card Type',
			'number' => 'Number',
			'cvv' => 'Cvv',
			'expire_date' => 'Expire Date',
			'account_id' => 'Account',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('card_type_id',$this->card_type_id);
		$criteria->compare('number',$this->number);
		$criteria->compare('cvv',$this->cvv);
		$criteria->compare('expire_date',$this->expire_date,true);
		$criteria->compare('account_id',$this->account_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CreditCard the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
