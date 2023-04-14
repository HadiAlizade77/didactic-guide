<?php
  class payu{

    var $code, $title, $description, $enabled;



   // class constructor

    function __construct() {

      global $order;
      $this->code = 'payu';
      $this->title = MODULE_PAYMENT_PAYU_TEXT_TITLE;
      
      $this->sort_order = MODULE_PAYMENT_PAYU_PRIORITY;

      $this->enabled = ((MODULE_PAYMENT_PAYU_STATUS == 'True') ? true : false);

	  if ((int)MODULE_PAYMENT_PAYU_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_PAYU_ORDER_STATUS_ID;
      }

	  if(MODULE_PAYMENT_PAYU_TESTMODE=='TEST')

		$this->form_action_url =  'https://test.payu.in/_payment.php';

	  else

		  $this->form_action_url =  'https://secure.payu.in/_payment.php';


    }



   


// class methods

   
    function javascript_validation() {

	
	 
	}



    function selection() {

      $selection = array('id' => $this->code,

                         'module' => $this->title);
      return $selection;

    }



    function pre_confirmation_check() {

	  return false;
	}



    function confirmation() {


	  $confirmation='';

      return $confirmation;

    }



    function process_button() {
     
		
      global $HTTP_POST_VARS, $order,$order_total_modules,$currencies;
	  
		
	     	  $hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
	$posted = array();
	//print_r($this->session->data['order_id']);
	$posted['txnid']=substr(hash('sha256', mt_rand() . microtime()), 0, 20);
	//$posted['txnid']=12345678;
		//$amt=number_format(($order->info['total'] * $currency_value[0]),2,'.','');
	   if(DEFAULT_CURRENCY!='INR')
		$amt=$currencies->get_value('INR')* $order->info['total'];
	   else
		$amt=$order->info['total'];
        echo $amt ;
    	$posted['amount']= $amt;
	$posted['firstname'] = $order->customer['firstname'];
 $posted['phone']=$order->customer['telephone'];
	$posted['key']= MODULE_PAYMENT_PAYU_MERCHANTID;
       
 $posted['productinfo']=SITE_TITLE;

	$posted['email']=$order->customer['email_address'];
	$posted['service_provider']='payu_paisa';
 $posted['udf2']=$posted['txnid'];
	$hashVarsSeq = explode('|', $hashSequence);
    $hash_string = '';
    foreach($hashVarsSeq as $hash_var) {
      $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
      $hash_string .= '|';
    }
    $hash_string .= MODULE_PAYMENT_PAYU_SALT;
	$hash = strtolower(hash('sha512', $hash_string));
	$posted['hash']=$hash;


	     if(check_login("jobseeker"))
 					{
	 					$process_button_string=tep_draw_hidden_field('surl', tep_href_link(FILENAME_JOBSEEKER_CHECKOUT_PROCESS, '', 'SSL')) .
                                               tep_draw_hidden_field('furl', tep_href_link(FILENAME_JOBSEEKER_CHECKOUT_PAYMENT, '', 'SSL')).'';
					 }
					 else
					 {
							$process_button_string=tep_draw_hidden_field('surl', tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL')) .
                                 tep_draw_hidden_field('furl', tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL')).'';
					 }

    $process_button_string .= tep_draw_hidden_field('key', $posted['key']) . 
	                           
                             tep_draw_hidden_field('amount',$posted['amount']).
							 tep_draw_hidden_field('productinfo',$posted['productinfo']).

                             tep_draw_hidden_field('firstname', $posted['firstname']) .
                             

                             tep_draw_hidden_field('email',$posted['email']) .
                             tep_draw_hidden_field('service_provider',$posted[service_provider]) .
                             tep_draw_hidden_field('udf2', $posted['udf2']).
                             tep_draw_hidden_field('phone', $posted['phone']) .



                               tep_draw_hidden_field('lastname',$order->customer['lastname']) .

                             tep_draw_hidden_field('address1',$order->customer['street_address']) .

                             tep_draw_hidden_field('address2',$order->delivery['street_address']) .
																													
							 tep_draw_hidden_field('city', $order->customer['city']) .

                             tep_draw_hidden_field('state', $order->customer['state']) .

                             tep_draw_hidden_field('postal_code', $order->customer['postcode']) .

                             tep_draw_hidden_field('country', $order->customer['country']) .
            
            
            
           

							tep_draw_hidden_field('txnid',$posted['txnid']).
							tep_draw_hidden_field('hash',$posted['hash']).
				            tep_draw_hidden_field('website', tep_href_link(FILENAME_DEFAULT, '', 'SSL')).
							   
                             tep_draw_hidden_field('curl', tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL'));
	 
    return $process_button_string;

    }

    function before_process(){

    global $order;
	  if(!empty($_POST)) {
   
  foreach($_POST as $key => $value) {
    
    $txnRs[$key] = htmlentities($value, ENT_QUOTES);
  }
}
if($txnRs['status']=='success'){
    
       $order->info['cc_number']=$txnRs['udf2']; 
      $merc_hash_vars_seq = explode('|', "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10");
      //generation of hash after transaction is = salt + status + reverse order of variables
      $merc_hash_vars_seq = array_reverse($merc_hash_vars_seq);
      
      $merc_hash_string = MODULE_PAYMENT_PAYU_SALT . '|' . $txnRs['status'];
	
      foreach ($merc_hash_vars_seq as $merc_hash_var) {
        $merc_hash_string .= '|';
        $merc_hash_string .= isset($txnRs[$merc_hash_var]) ? $txnRs[$merc_hash_var] : '';
      
      }
     
      $merc_hash =strtolower(hash('sha512', $merc_hash_string));
      if($merc_hash!=$txnRs['hash']) {
          
         
     	 if(check_login("jobseeker"))
       tep_redirect(tep_href_link(FILENAME_JOBSEEKER_CHECKOUT_PAYMENT, 'error_message=data tampered', 'SSL',true,false));
							else
       tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=data tampered', 'SSL',true,false));

      } 
      

        
}
else{
     	 if(check_login("jobseeker"))
       tep_redirect(tep_href_link(FILENAME_JOBSEEKER_CHECKOUT_PAYMENT, 'error_message=data tampered', 'SSL',true,false));
							else
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=transaction failed', 'SSL',true,false));
}
}


  


    function after_process() {
      return false;
    }


   function check() {

       if (!isset($this->_check)) {



        $check_query = tep_db_query("select configuration_value from " . CONFIGURATION_TABLE . " where configuration_name = 'MODULE_PAYMENT_PAYU_STATUS'");



        $this->_check = tep_db_num_rows($check_query);



      }



      return $this->_check;

    }



    function install() {
	
	  
      tep_db_query("insert into " . CONFIGURATION_TABLE . " (configuration_title, configuration_name, configuration_value, configuration_description, configuration_group_id, priority, set_function, inserted) values ('Enable PayUMoney Payment Module', 'MODULE_PAYMENT_PAYU_STATUS', 'True', 'Do you want to accept PayUMoney payments?', '9', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");

      tep_db_query("insert into " . CONFIGURATION_TABLE . " (configuration_title, configuration_name, configuration_value, configuration_description, configuration_group_id, priority, inserted) values ('Merchant ID', 'MODULE_PAYMENT_PAYU_MERCHANTID', '', 'Your Merchant ID of PayUMoney', '5', '0', now())");

      tep_db_query("insert into " . CONFIGURATION_TABLE . " (configuration_title, configuration_name, configuration_value, configuration_description, configuration_group_id, priority, inserted) values ('SALT', 'MODULE_PAYMENT_PAYU_SALT', '', 'Your SALT of PayUMoney', '9', '0', now())");	  

      tep_db_query("insert into " . CONFIGURATION_TABLE . " (configuration_title, configuration_name, configuration_value, configuration_description, configuration_group_id, priority, set_function, inserted) values ('Test Mode', 'MODULE_PAYMENT_PAYU_TESTMODE', 'TEST', 'Test mode used for PayUMoney', '9', '0', 'tep_cfg_select_option(array(\'TEST\', \'LIVE\'), ', now())");
      
      tep_db_query("insert into " . CONFIGURATION_TABLE . " (configuration_title, configuration_name, configuration_value, configuration_description, configuration_group_id, priority, set_function, use_function, inserted) values ('Set Order Status', 'MODULE_PAYMENT_PAYU_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', '9', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");

		 tep_db_query("insert into " . CONFIGURATION_TABLE . " (configuration_title, configuration_name, configuration_value, configuration_description, configuration_group_id, priority, inserted) values ('Sort order of display', 'MODULE_PAYMENT_PAYU_PRIORITY', '0', 'Sort order of display. Lowest is displayed first.', '9', '2', now())");


    }



    function remove() {
	
	  tep_db_query("delete from " . CONFIGURATION_TABLE . " where configuration_name in ('" . implode("', '", $this->keys()) . "')");

    }



    function keys() {

      return array('MODULE_PAYMENT_PAYU_STATUS', 'MODULE_PAYMENT_PAYU_MERCHANTID', 'MODULE_PAYMENT_PAYU_SALT', 'MODULE_PAYMENT_PAYU_TESTMODE','MODULE_PAYMENT_PAYU_ORDER_STATUS_ID','MODULE_PAYMENT_PAYU_PRIORITY');

    }

  }

?>