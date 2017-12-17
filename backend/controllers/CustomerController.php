<?php
class CustomerController extends BackendController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
            array('bootstrap.filters.BootstrapFilter - delete'),

			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Customer;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Customer']))
		{
//            $visa = App::make('credit_card_generator.visa');
//            echo $visa;
//            Yii::import('vendor.gxela.src.Gxela.CreditCardNuberGenerator.*');
		    $customer = $_POST['Customer'];

//            echo (int)('8'.str_repeat('0',8))+9;die;
//            p(intval('8'.str_repeat('0',9)));die;
//            p(intval( '8'.str_repeat('0',9)));die;
//            p(\Gxela\CreditcardNumberGenerator\CreditCardGenerator::get_visa16());die;

            $model->attributes=$customer;
            $model->addError('username' , "Username already exists");
            $find = Customer::model()->find('username = '. "'".$customer['username']."'");
            if(isset($find ['id'])){
                goto render;
            }
//            if($model->validate()){
////                $model->attributes['password'] = 777;
////                p($model->attributes['password']);die;
////                $model->attributes['password'] = md5($customer['password']);
//            }
//            p($model->getErrors());die;




			if($model->save()){
                $realPassword = $model->password ;
                $model->password = md5($model->password);
                $model->save();
                $model->password = $realPassword;
////			    p($model->getPrimaryKey());die;
                $account = new Accounts();
                $account->attributes = [
                    'customer_id' =>$model->getPrimaryKey(),
                    'balance' =>50000,
                    'account_type_id' =>BHelper::CARD_ACCOUNT,
                ];
                $newId = Accounts::model()->find(array('order'=>'id DESC'));
                $account->number =
                    intval( '8'.str_repeat('0',8
                        )) + $newId->id+1;
                $account->save();

                $creditCard = new CreditCard();
                $creditCard->attributes = [
                    'card_type_id' => BHelper::CC_VISA,
                    'number' => \Gxela\CreditcardNumberGenerator\CreditCardGenerator::get_visa16(),
                    'cvv' => rand(100,999),
                    'account_id' => $account->id

                ];
                $creditCard->save();

                $this->sendMail('info@sbs-banking.com','Registration',
                    ['name' => $customer['name'],'email' => $customer['email']],
                    ['type' => "text/html","value" =>
                        "You have been registered account to our system <br/>"
                        ."Your username is:<b> ". $customer['username'] ."</b> <br/>"
                       . "Your password is:<b> ". $realPassword  ."</b> <br/>"
                       . "Your Credit Card Number:<b> ". $creditCard->number  ."</b> <br/>"
                       . "Your Credit Card cvv:<b> ". $creditCard->cvv ."</b> <br/>"
                       . "Your Credit Card Type is Visa:<b> </b> <br/>"
                        . "Your Credit Card password:<b> ". $creditCard->cvv. 8 ."</b> <br/>"
                    ]
                    );
                $this->redirect(array('view','id'=>$model->id));

            }
		}

        render : $this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Customer']))
		{
			$model->attributes=$_POST['Customer'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Customer');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Customer('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Customer']))
			$model->attributes=$_GET['Customer'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Customer the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Customer::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Customer $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='customer-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}


	private function sendMail($from = "info@sbs-banking.com",
                              $subject ="From SBS Bank",
                              $to = ['name' => "User","email" => "test@example.com"],
                              $content = ['type' => "text/plain","value" => ""]){

        $from = new SendGrid\Email("SBS Bank Admissions", "info@sbs-banking.com");
//        $subject = "Sending with SendGrid is Fun";
//        p($to);die;
        $to = new SendGrid\Email($to['name'], $to['email']);
        $content = new SendGrid\Content($content['type'], $content['value']);
        $mail = new SendGrid\Mail($from, $subject, $to, $content);
        $apiKey = SENDGRID_API_KEY;
        $sg = new \SendGrid($apiKey);
        $response = $sg->client->mail()->send()->post($mail);
        echo $response->statusCode();
        print_r($response->headers());
        echo $response->body();
    }




}
