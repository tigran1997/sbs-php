<?php

/**
 * This is the model class for table "accounts".
 *
 * The followings are the available columns in table 'accounts':
 * @property integer $id
 * @property integer $customer_id
 * @property double $balance
 * @property integer $number
 * @property integer $account_type_id
 *
 * The followings are the available model relations:
 * @property AccountTypes $accountType
 * @property Customer $customer
 * @property CreditCard[] $creditCards
 * @property Transaction[] $transactions
 * @property Transaction[] $transactions1
 */
class Accounts extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'accounts';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id,  account_type_id', 'required'),
			array('customer_id, number, account_type_id', 'numerical', 'integerOnly'=>true),
			array('balance', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customer_id, balance, number, account_type_id', 'safe', 'on'=>'search'),
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
			'accountType' => array(self::BELONGS_TO, 'AccountTypes', 'account_type_id'),
			'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
			'creditCards' => array(self::HAS_MANY, 'CreditCard', 'account_id'),
			'transactions' => array(self::HAS_MANY, 'Transaction', 'account_destination_id'),
			'transactions1' => array(self::HAS_MANY, 'Transaction', 'account_from_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'customer_id' => 'Customer',
			'balance' => 'Balance',
			'number' => 'Number',
			'account_type_id' => 'Account Type',
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
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('balance',$this->balance);
		$criteria->compare('number',$this->number);
		$criteria->compare('account_type_id',$this->account_type_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Accounts the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
