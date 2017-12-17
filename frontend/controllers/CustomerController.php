<?php

class CustomerController extends FrontendController
{
	public function actionIndex()
	{
	    $user_id = Yii::app()->user->getState('login');
//        p(Yii::app()->user->getState('login'));die;
        $balance = Accounts::model()->findAllByAttributes([
            'customer_id' => $user_id
        ]);

        $cc = CreditCard::model()->findAll();
//        p($cc );die;

        $recTransaction = Yii::app()->db->createCommand("
        SELECT 
        
        `transaction`.amount ,
        cus.name ,
        cus.surname , 
        acc.number as from_number
        
         FROM TRANSACTION inner JOIN accounts acc on `transaction`.account_from_id= acc.id 
         inner JOIN accounts acc2 on `transaction`.account_destination_id = acc2.id 
         inner JOIN customer cus on cus.id = acc2.customer_id  
        
         where acc.customer_id = {$user_id}
         ORDER BY `transaction`.id DESC
        ")->queryAll();
        $transactions = $recTransaction;
        $recTransaction = array_slice($recTransaction, 0, 5);
//        p($user_id);die;
//        p($recTransaction );die;

        $this->render('index',[
            'account' => $balance,
            'recentTrans' =>  $recTransaction,
            'transactions' => $transactions

        ]);
	}

	public function actionGetCc(){
        $user_id = Yii::app()->user->getState('login');
	    if(isset($_POST['account_id'])){
	        $quer = "SELECT credit_card.number,customer.`name`, customer.surname FROM `credit_card` join accounts on credit_card.account_id = accounts.id
            join customer on customer.id = accounts.customer_id where customer.id = {$user_id} and
            accounts.id = {$_POST['account_id']}  ;
            ";
//	        p($quer);die;
//            $cc = CreditCard::model()->findByAttributes(['account_id'=>$_POST['account_id']]);
            $cc = Yii::app()->db->createCommand($quer)->queryAll();
//            p($cc);die;
            echo json_encode($cc);
            Yii::app()->end();
        }
    }

    public function actionAddAccount(){
        $user_id = Yii::app()->user->getState('login');
	    $customer = Customer::model()->findByAttributes([
	        'id' => $user_id,
        ]);

        $account = new Accounts();
        $account->attributes = [
            'customer_id' =>$user_id,
            'balance' =>50000,
            'account_type_id' =>BHelper::CARD_ACCOUNT,
        ];
        $newId = Accounts::model()->find(array('order'=>'id DESC'));
        $account->number =
            intval( '8'.str_repeat('0',8
                )) + $newId->id+1;
        $account->save();
//        p($account);die;
//        die;
        $creditCard = new CreditCard();
        $creditCard->attributes = [
            'card_type_id' => BHelper::CC_VISA,
            'number' => \Gxela\CreditcardNumberGenerator\CreditCardGenerator::get_visa16(),
            'cvv' => rand(100,999),
            'account_id' => $account->id

        ];
        $creditCard->save();
//        p($creditCard);die;

        Email::sendMail('info@sbs-banking.com','Registration',
            ['name' => $customer->name,'email' => $customer->email],
            ['type' => "text/html","value" =>
                "You have been added new  account to our system <br/>"
                . "Your Credit Card Number:<b> ". $creditCard->number  ."</b> <br/>"
                . "Your Credit Card cvv:<b> ". $creditCard->cvv ."</b> <br/>"
                . "Your Credit Card Type is Visa:<b> </b> <br/>"
                . "Your Credit Card password:<b> ". $creditCard->cvv. 8 ."</b> <br/>"
            ]
        );
        $obj = new stdClass();
        $obj->success = 1;
        echo json_encode($obj);
        Yii::app()->end();



    }

    public function accessRules()
    {
        return array(
//            array('allow',  // allow all users to perform 'index' and 'view' actions
//                'actions'=>array('index','view'),
//                'users'=>array('*'),
//            ),
            array('deny', // allow authenticated user to perform 'create' and 'update' actions
                'actions'=>array('test1','index'),
//                'users'=>array('*'),
                'expression' => ['CustomerController' , 'isLoggedIn'],
//                'deniedCallback' => array($this, 'redirectAUsers'),
            ),
//            array('allow', // allow admin user to perform 'admin' and 'delete' actions
//                'actions'=>array('admin','delete'),
//                'users'=>array('admin'),
//            ),
            array('deny',  // deny all users
                'actions' => ['test'],
                'users'=>array('?'),
            ),
        );
    }

    public function accessDenied(){
	    echo 'no permission';
    }

    public function actionLogin(){

        $this->redirectAUsers();
        $customer = new Customer();
        $request = Yii::app()->request;
        $formData = $request->getPost(get_class($customer), false);

        if($formData ){

            $customer->attributes = $_POST['Customer'];
            $customer->setScenario('login');
            if($customer->validate()){

                $member = $customer->findByAttributes([
                    'username' => $_POST['Customer']['username'],
                    'password' => md5($_POST['Customer']['password'])
                ]);
                if($member){
                    Yii::app()->user->setState('login' ,$member->id);
                    $this->redirect('/customer/index');

                }
            }
        }

        $this->render('login',['model' => $customer]);
    }

    public static function isLoggedIn(){
//        return true;
//        var_dump(Yii::app()->user->getState('login') == NULL);die;
        return !(Yii::app()->user->getState('login') != NULL);
    }

    public static function redirectAUsers1(){
        if(Yii::app()->user->getState('login') != NULL) {
            Yii::app()->controller->redirect('/customer/index');
        }
    }



    public function redirectAUsers(){
//        print_r(Yii::app()->user->getState('login'));
        if(Yii::app()->user->getState('login') != NULL) {
            $this->redirect('/customer/index');
        }
    }

    public function actionLogout(){
        Yii::app()->user->setState('login',NULL);
        $this->redirect('/customer/login');
    }


    public function actionTest(){
	    echo 'test'; die;
    }

    public function actionTest1(){
//        p(Yii::app()->user->getState('login'));
        echo json_encode(['success' => 0]);die;
    }






	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/



	public function actionTransfer(){
        $account = new Accounts();
        $result = $account->findByAttributes([
            'number' => $_POST['number']
        ]);
        $to= $account->findByAttributes([
            'number' => $_POST['to']
        ]);

        $obj = new stdClass();
        if(empty($result) || empty($to) ){
            $obj -> success = 0;
            $obj -> message = 'Account Number Not found';
            echo json_encode($obj);
            Yii::app()->end();
        }
        $transaction = new Transaction();
        $transaction -> account_from_id = $result->id;
        $transaction -> account_destination_id = $to->id;
        $transaction -> amount = $_POST['amount'];
        $transaction -> description = $_POST['description'];
        if($result ->balance - $_POST['amount'] < 0){
            $obj->success = 0;
            $obj->message = 'You dont have enough money';
            echo json_encode($obj);
            Yii::app()->end();
        }
        if($result ->balance - $_POST['amount'] < 0){
            $result ->balance = $result ->balance - $_POST['amount'];
        }

        if($transaction->validate()){
            if($transaction->save()){
                $result ->balance = $result ->balance - $_POST['amount'];
                $result->save();
                $to ->balance = $to ->balance + $_POST['amount'];
                $to->save();

                $customerFrom = Customer::model()->findByAttributes(['id'=>$result->customer_id]);
                $customerTo = Customer::model()->findByAttributes(['id'=>$to->customer_id]);
                $data = [
                  'from_name' => $customerFrom->name." ".$customerFrom->surname,
                    'amount' =>$_POST['amount'],
                  'to_email' => $customerTo->email,
                  'to_name' => $customerTo->name." ".$customerTo->surname,
                  'id' => $transaction->id,
                  'date' => date("Y-m-d h:i:sa"),

                ];
                Email::sendMail('info@sbs-banking.com','Transfer',
                    ['name' => $customerFrom->name,'email' => $customerFrom->email],
                    ['type' => "text/html","value" =>
                        $this->renderPartial('../layouts/transfer',['data' => $data],true)
                    ]
                );

                Email::sendMail('info@sbs-banking.com','Transfer',
                    ['name' => $customerFrom->name,'email' => $customerTo->email],
                    ['type' => "text/html","value" =>
                        $this->renderPartial('../layouts/transfer',['data' => $data],true)
                    ]
                );



                $obj->success = 1;
                echo json_encode($obj);
                Yii::app()->end();
            }
        }



        $obj->success = 0;
        $obj->message = 'Something went wrong try again';
        echo json_encode($obj);
        Yii::app()->end();




    }
}