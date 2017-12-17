<?php
/* @var $this CustomerController */

$this->breadcrumbs=array(
	'Customer',
);
//Yii::app()->user->setState('a',777);
//p (Yii::app()->user->getState('a'));die;
//p($account);die;
$cus = Customer::model()->findByAttributes(['id'=>Yii::app()->user->getState('login')]);
$name = $cus->username;
?>

<div class="navbar navbar-default navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container"><a class="btn btn-navbar" data-toggle="collapse" data-target="#yii_bootstrap_collapse_0">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a><a href="/" class="brand">sbs</a>
            <div class="nav-collapse collapse" id="yii_bootstrap_collapse_0">
                <ul id="yw4" class="nav">
                    <li><a href="/customer/index/">Home</a></li>
                    <li><a href="/customer/logout/">Logout </a></li>
                    <li><a href="#"><?php  echo "Salaam Aleikom user ",$name," ",$cus->surname," ",$cus->email ?></a></li>

                </ul>
            </div>
        </div>
    </div>
</div>




<div class="tabbable"> <!-- Only required for left/right tabs -->
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab1" data-toggle="tab">Main</a></li>
        <li><a href="#tab2" data-toggle="tab">Transactions</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab1">
            <h3>Main</h3>

            <h2>Balances</h2>
            <table class="table thead-dark">
                <thead>
                <tr >
                    <th>#</th>
                    <th>List</th>
                    <th>Amount</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($account as $key => $value) { ?>
                    <tr href="#myModal1" data-toggle="modal" class="ccmod" id="ccmod<?php echo $value->id ?>" dd="<?php echo $value->id ?>">
                        <td id=""><?php  ?></td>
                        <td><?php echo  $value->number ?></td>
                        <td><?php echo $value->balance ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

            <button type="button" id="newAccount" class="btn btn-success">Generate New Account</button>

            <div id="myModal1" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="myModalLabel">Credit Card</h3>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="bank-name" title="BestBank">SBS Bank</div>
                        <div class="chip">
                            <div class="side left"></div>
                            <div class="side right"></div>
                            <div class="vertical top"></div>
                            <div class="vertical bottom"></div>
                        </div>
                        <div class="data">
                            <div class="pan" id="num4" title="4123 4567 8910 1112">4123 4567 8910 1112</div>
                            <div class="first-digits">4123</div>
                            <div class="exp-date-wrapper">
                                <div class="left-label">EXPIRES END</div>
                                <div class="exp-date">
                                    <div class="upper-labels">Expire Date</div>
                                    <div class="date" title="01/17">Never</div>
                                </div>
                            </div>
                            <div class="name-on-card" id ="ccname" title="John Doe">John Doe</div>
                        </div>
                        <div class="lines-down"></div>
                        <div class="lines-up"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
<!--                    <button class="btn btn-primary">Save changes</button>-->
                </div>
            </div>

            <h2>Recent Transactions</h2>
            <table class="table thead-dark">
                <thead>
                <tr>
                    <th>#</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Amount</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($recentTrans as $key => $value) { ?>
                    <tr>
                        <td><?php  ?></td>
                        <td><?php echo  $value['from_number'] ?></td>
                        <td><?php echo $value['name']," ",$value['surname'] ?></td>
                        <td><?php echo $value['amount'] ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>



            <!-- Button to trigger modal -->
            <a href="#myModal" role="button" class="btn btn-success" data-toggle="modal">New Transaction</a>

            <!-- Modal -->
            <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="myModalLabel">New Transaction</h3>
                </div>
                <div class="modal-body">
                    <form action="">
                        <select id="from">
                            <?php foreach ($account as $key => $value) { ?>
                                <option value="<?php echo $value->id ?>"
                                        number="<?php echo $value->number ?>"
                                        balance ="<?php echo $value->balance ?>"
                                >
                                    <?php echo $value->number,' / ',$value->balance,' AMD' ?>
                                </option>
                            <?php } ?>
                        </select>
                        <label for="">Amount</label>
                        <input type="text" id="amount" required placeholder="Amount">
                        <label for="">Transfer to </label>
                        <input type="text" required id="to" placeholder="Beneficiary">
                        <label for="">Description</label>
                        <textarea name="" id="description" cols="30" required rows="10" placeholder="">Transfer Between Accounts</textarea>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                    <button class="btn btn-primary" id="trans" type="submit">Save changes</button>
                </div>
            </div>


        </div>

        <div class="tab-pane" id="tab2">
            <h2> Transactions</h2>
            <table class="table thead-dark">
                <thead>
                <tr>
                    <th>#</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Amount</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($transactions as $key => $value) { ?>
                    <tr >
                        <td><?php  ?></td>
                        <td><?php echo  $value['from_number'] ?></td>
                        <td><?php echo $value['name']," ",$value['surname'] ?></td>
                        <td><?php echo $value['amount'] ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
<!--            <a href="#myModal1" role="button" class="btn" data-toggle="modal">Launch demo modal</a>-->
            <!-- Modal -->


        </div>
    </div>
</div>




<div class="container">
<!--    <p class="row">-->
<!--        You may change the content of this page by modifying-->
<!--        the file <tt>--><?php //echo __FILE__; ?><!--</tt>.-->
<!--    </p>-->




    <footer>
        Copyright © 2017 by Synopsys.<br>
        All Rights Reserved.<br>
        Powered by Me.
        <div id="syl"></div>
    </footer>

</div>






