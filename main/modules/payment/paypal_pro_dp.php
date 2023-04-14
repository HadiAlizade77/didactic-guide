<?
  class paypal_pro_dp {
    var $code, $title, $description, $enabled;

// class constructor
    function __construct() {
      global $order;

      $this->code = 'paypal_pro_dp';
      $this->title = MODULE_PAYMENT_PAYPAL_PRO_DP_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_PAYPAL_PRO_DP_TEXT_DESCRIPTION;
      $this->priority = MODULE_PAYMENT_PAYPAL_PRO_DP_SORT_ORDER;
      $this->enabled = ((MODULE_PAYMENT_PAYPAL_PRO_DP_STATUS == 'True') ? true : false);

      if ((int)MODULE_PAYMENT_PAYPAL_PRO_DP_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_PAYPAL_PRO_DP_ORDER_STATUS_ID;
      }

      if (is_object($order)) $this->update_status();

      $this->cc_types = array('VISA' => 'Visa',
                              'VISA_DEBIT' => 'Visa Debit',
                              'VISA_ELECTRON' => 'Visa Electron',
                              'MASTERCARD' => 'MasterCard',
                              'DISCOVER' => 'Discover Card',
                              'AMEX' => 'American Express',
                              'SWITCH' => 'Maestro',
                              'SOLO' => 'Solo');

    }

// class methods
    function update_status() {
      global $order;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_PAYPAL_PRO_DP_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_PAYPAL_PRO_DP_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
        while ($check = tep_db_fetch_array($check_query)) {
          if ($check['zone_id'] < 1) {
            $check_flag = true;
            break;
          } elseif ($check['zone_id'] == $order->billing['zone_id']) {
            $check_flag = true;
            break;
          }
        }

        if ($check_flag == false) {
          $this->enabled = false;
        }
      }
    }

    function javascript_validation() {
      return false;
    }

    function selection() {
      $selection = array('id' => $this->code,
                         'module' => $this->title);

      if (MODULE_PAYMENT_PAYPAL_PRO_DP_CARD_INPUT_PAGE == 'Payment') {
        global $order;

        $types_array = array();
        while (list($key, $value) = each($this->cc_types)) {
          if ($this->isCardAccepted($key)) {
            $types_array[] = array('id' => $key,
                                   'text' => $value);
          }
        }

        $today = getdate();

        $months_array = array();
        for ($i=1; $i<13; $i++) {
          $months_array[] = array('id' => sprintf('%02d', $i), 'text' => strftime('%B',mktime(0,0,0,$i,1,2000)));
        }

        $year_valid_from_array = array();
        for ($i=$today['year']-10; $i < $today['year']+1; $i++) {
          $year_valid_from_array[] = array('id' => strftime('%Y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
        }

        $year_expires_array = array();
        for ($i=$today['year']; $i < $today['year']+10; $i++) {
          $year_expires_array[] = array('id' => strftime('%Y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
        }

        $selection['fields'] = array(array('title' => MODULE_PAYMENT_PAYPAL_PRO_DP_CARD_OWNER,
                                           'field' => tep_draw_input_field('cc_owner', $order->billing['firstname'] . ' ' . $order->billing['lastname'])),
                                     array('title' => MODULE_PAYMENT_PAYPAL_PRO_DP_CARD_TYPE,
                                           'field' => tep_draw_pull_down_menu('cc_type', $types_array)),
                                     array('title' => MODULE_PAYMENT_PAYPAL_PRO_DP_CARD_NUMBER,
                                           'field' => tep_draw_input_field('cc_number_nh-dns')),
                                     array('title' => MODULE_PAYMENT_PAYPAL_PRO_DP_CARD_VALID_FROM,
                                           'field' => tep_draw_pull_down_menu('cc_starts_month', $months_array) . '&nbsp;' . tep_draw_pull_down_menu('cc_starts_year', $year_valid_from_array) . ' ' . MODULE_PAYMENT_PAYPAL_PRO_DP_CARD_VALID_FROM_INFO),
                                     array('title' => MODULE_PAYMENT_PAYPAL_PRO_DP_CARD_EXPIRES,
                                           'field' => tep_draw_pull_down_menu('cc_expires_month', $months_array) . '&nbsp;' . tep_draw_pull_down_menu('cc_expires_year', $year_expires_array)),
                                     array('title' => MODULE_PAYMENT_PAYPAL_PRO_DP_CARD_CVC,
                                           'field' => tep_draw_input_field('cc_cvc_nh-dns', '', 'size="5" maxlength="4"')));

        if ( $this->isCardAccepted('SWITCH') || $this->isCardAccepted('SOLO') ) {
          $selection['fields'][] = array('title' => MODULE_PAYMENT_PAYPAL_PRO_DP_CARD_ISSUE_NUMBER,
                                         'field' => tep_draw_input_field('cc_issue_nh-dns', '', 'size="3" maxlength="2"') . ' ' . MODULE_PAYMENT_PAYPAL_PRO_DP_CARD_ISSUE_NUMBER_INFO);
        }
      }

      return $selection;
    }

    function pre_confirmation_check() {
      if (MODULE_PAYMENT_PAYPAL_PRO_DP_CARD_INPUT_PAGE == 'Payment') {
        if (!isset($_POST['cc_owner']) || empty($_POST['cc_owner']) || (strlen($_POST['cc_owner']) < CC_OWNER_MIN_LENGTH) || !isset($_POST['cc_type']) || !$this->isCardAccepted($_POST['cc_type']) || !isset($_POST['cc_number_nh-dns']) || empty($_POST['cc_number_nh-dns']) || (strlen($_POST['cc_number_nh-dns']) < CC_NUMBER_MIN_LENGTH)) {
          $payment_error_return = 'payment_error=' . $this->code . '&error=' . urlencode(MODULE_PAYMENT_PAYPAL_PRO_DP_ERROR_ALL_FIELDS_REQUIRED) . '&cc_owner=' . urlencode($_POST['cc_owner']) . '&cc_starts_month=' . $_POST['cc_starts_month'] . '&cc_starts_year=' . $_POST['cc_starts_year'] . '&cc_expires_month=' . $_POST['cc_expires_month'] . '&cc_expires_year=' . $_POST['cc_expires_year'];
							 	if(check_login("jobseeker"))
    				 {
           tep_redirect(tep_href_link(FILENAME_JOBSEEKER_CHECKOUT_PAYMENT, $payment_error_return, 'SSL', true, false));
					    }
					    else
					    {
          tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, $payment_error_return, 'SSL', true, false));
					    }
        }
      }

      return false;
    }

    function confirmation() {
      $confirmation = array();

      if (MODULE_PAYMENT_PAYPAL_PRO_DP_CARD_INPUT_PAGE == 'Payment') {

        $confirmation['fields'] = array(array('title' => MODULE_PAYMENT_PAYPAL_PRO_DP_CARD_OWNER,
                                              'field' => $_POST['cc_owner']),
                                        array('title' => MODULE_PAYMENT_PAYPAL_PRO_DP_CARD_TYPE,
                                              'field' => $this->cc_types[$_POST['cc_type']]),
                                        array('title' => MODULE_PAYMENT_PAYPAL_PRO_DP_CARD_NUMBER,
                                              'field' => str_repeat('X', strlen($_POST['cc_number_nh-dns']) - 4) . substr($_POST['cc_number_nh-dns'], -4)),
                                        array('title' => MODULE_PAYMENT_PAYPAL_PRO_DP_CARD_VALID_FROM,
                                              'field' => $_POST['cc_starts_month'] . '/' . $_POST['cc_starts_year']),
                                        array('title' => MODULE_PAYMENT_PAYPAL_PRO_DP_CARD_EXPIRES,
                                              'field' => $_POST['cc_expires_month'] . '/' . $_POST['cc_expires_year']),
                                        array('title' => MODULE_PAYMENT_PAYPAL_PRO_DP_CARD_CVC,
                                              'field' => $_POST['cc_cvc_nh-dns']));

        if ( (($_POST['cc_type'] == 'SWITCH') && $this->isCardAccepted('SWITCH')) || (($_POST['cc_type'] == 'SOLO') && $this->isCardAccepted('SOLO')) ) {
          if (isset($_POST['cc_issue_nh-dns']) && !empty($_POST['cc_issue_nh-dns'])) {
            $confirmation['fields'][] = array('title' => MODULE_PAYMENT_PAYPAL_PRO_DP_CARD_ISSUE_NUMBER,
                                              'field' => $_POST['cc_issue_nh-dns']);
          }
        }
      } else {
        global $order;

        $types_array = array();
        while (list($key, $value) = each($this->cc_types)) {
          if ($this->isCardAccepted($key)) {
            $types_array[] = array('id' => $key,
                                   'text' => $value);
          }
        }

        $today = getdate();

        $months_array = array();
        for ($i=1; $i<13; $i++) {
          $months_array[] = array('id' => sprintf('%02d', $i), 'text' => strftime('%B',mktime(0,0,0,$i,1,2000)));
        }

        $year_valid_from_array = array();
        for ($i=$today['year']-10; $i < $today['year']+1; $i++) {
          $year_valid_from_array[] = array('id' => strftime('%Y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
        }

        $year_expires_array = array();
        for ($i=$today['year']; $i < $today['year']+10; $i++) {
          $year_expires_array[] = array('id' => strftime('%Y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
        }

        $confirmation['fields'] = array(array('title' => MODULE_PAYMENT_PAYPAL_PRO_DP_CARD_OWNER,
                                              'field' => tep_draw_input_field('cc_owner', $order->billing['firstname'] . ' ' . $order->billing['lastname'])),
                                        array('title' => MODULE_PAYMENT_PAYPAL_PRO_DP_CARD_TYPE,
                                              'field' => tep_draw_pull_down_menu('cc_type', $types_array)),
                                        array('title' => MODULE_PAYMENT_PAYPAL_PRO_DP_CARD_NUMBER,
                                              'field' => tep_draw_input_field('cc_number_nh-dns')),
                                        array('title' => MODULE_PAYMENT_PAYPAL_PRO_DP_CARD_VALID_FROM,
                                              'field' => tep_draw_pull_down_menu('cc_starts_month', $months_array) . '&nbsp;' . tep_draw_pull_down_menu('cc_starts_year', $year_valid_from_array) . ' ' . MODULE_PAYMENT_PAYPAL_PRO_DP_CARD_VALID_FROM_INFO),
                                        array('title' => MODULE_PAYMENT_PAYPAL_PRO_DP_CARD_EXPIRES,
                                              'field' => tep_draw_pull_down_menu('cc_expires_month', $months_array) . '&nbsp;' . tep_draw_pull_down_menu('cc_expires_year', $year_expires_array)),
                                        array('title' => MODULE_PAYMENT_PAYPAL_PRO_DP_CARD_CVC,
                                              'field' => tep_draw_input_field('cc_cvc_nh-dns', '', 'size="5" maxlength="4"')));

        if ( $this->isCardAccepted('SWITCH') || $this->isCardAccepted('SOLO') ) {
          $confirmation['fields'][] = array('title' => MODULE_PAYMENT_PAYPAL_PRO_DP_CARD_ISSUE_NUMBER,
                                            'field' => tep_draw_input_field('cc_issue_nh-dns', '', 'size="3" maxlength="2"') . ' ' . MODULE_PAYMENT_PAYPAL_PRO_DP_CARD_ISSUE_NUMBER_INFO);
        }
      }

      return $confirmation;
    }

    function process_button() {
      if (MODULE_PAYMENT_PAYPAL_PRO_DP_CARD_INPUT_PAGE == 'Payment') {

        $process_button_string = tep_draw_hidden_field('cc_owner', $_POST['cc_owner']) .
                                 tep_draw_hidden_field('cc_type', $_POST['cc_type']) .
                                 tep_draw_hidden_field('cc_number_nh-dns', $_POST['cc_number_nh-dns']) .
                                 tep_draw_hidden_field('cc_starts_month', $_POST['cc_starts_month']) .
                                 tep_draw_hidden_field('cc_starts_year', $_POST['cc_starts_year']) .
                                 tep_draw_hidden_field('cc_expires_month', $_POST['cc_expires_month']) .
                                 tep_draw_hidden_field('cc_expires_year', $_POST['cc_expires_year']) .
                                 tep_draw_hidden_field('cc_cvc_nh-dns', $_POST['cc_cvc_nh-dns']);

        if ( (($_POST['cc_type'] == 'SWITCH') && $this->isCardAccepted('SWITCH')) || (($_POST['cc_type'] == 'SOLO') && $this->isCardAccepted('SOLO')) ) {
          $process_button_string .= tep_draw_hidden_field('cc_issue_nh-dns', $_POST['cc_issue_nh-dns']);
        }

        return $process_button_string;
      }

      return false;
    }
    function before_process() {
      global  $order;

      if (isset($_POST['cc_owner']) && !empty($_POST['cc_owner']) && isset($_POST['cc_type']) && $this->isCardAccepted($_POST['cc_type']) && isset($_POST['cc_number_nh-dns']) && !empty($_POST['cc_number_nh-dns'])) {
        if (MODULE_PAYMENT_PAYPAL_PRO_DP_TRANSACTION_SERVER == 'Live') {
          $api_url = 'https://api-3t.paypal.com/nvp';
        } else {
          $api_url = 'https://api-3t.sandbox.paypal.com/nvp';
        }

        $card_type = $_POST['cc_type'];
        if ( ($card_type == 'VISA_DEBIT') || ($card_type == 'VISA_ELECTRON') ) {
          $card_type = 'VISA';
        }
        $params = array('USER' => MODULE_PAYMENT_PAYPAL_PRO_DP_API_USERNAME,
                        'PWD' => MODULE_PAYMENT_PAYPAL_PRO_DP_API_PASSWORD,
                        'VERSION' => '3.2',
                        'SIGNATURE' => MODULE_PAYMENT_PAYPAL_PRO_DP_API_SIGNATURE,
                        'METHOD' => 'DoDirectPayment',
                        'PAYMENTACTION' => ((MODULE_PAYMENT_PAYPAL_PRO_DP_TRANSACTION_METHOD == 'Sale') ? 'Sale' : 'Authorization'),
                        'IPADDRESS' => tep_get_ip_address(),
                        'AMT' => ($order->info['total']),
                        'CREDITCARDTYPE' => $card_type,
                        'ACCT' => $_POST['cc_number_nh-dns'],
                        'STARTDATE' => $_POST['cc_starts_month'] . $_POST['cc_starts_year'],
                        'EXPDATE' => $_POST['cc_expires_month'] . $_POST['cc_expires_year'],
                        'CVV2' => $_POST['cc_cvc_nh-dns'],
                        'FIRSTNAME' => substr($_POST['cc_owner'], 0, strpos($_POST['cc_owner'], ' ')),
                        'LASTNAME' => substr($_POST['cc_owner'], strpos($_POST['cc_owner'], ' ')+1),
                        'STREET' => $order->billing['street_address'],
                        'CITY' => $order->billing['city'],
                        'STATE' => $order->billing['state'],
                        'COUNTRYCODE' => get_name_from_table(COUNTRIES_TABLE,'country_code', 'country_name',"'".$order->billing['country']."'"),
                        'ZIP' =>'493558',//$order->billing['postcode'],
                        'EMAIL' => $order->customer['email_address'],
                        'PHONENUM' => $order->customer['telephone'],
                        'CURRENCYCODE' => $order->info['currency'],
                        'BUTTONSOURCE' => 'ejobsite_intigration');
							 	//echo "<pre>";print_r( $params);echo "</pre>";//die();

        if ( (($_POST['cc_type'] == 'SWITCH') && $this->isCardAccepted('SWITCH')) || (($_POST['cc_type'] == 'SOLO') && $this->isCardAccepted('SOLO')) ) {
          $params['ISSUENUMBER'] = $_POST['cc_issue_nh-dns'];
        }

       

        $post_string = '';

        foreach ($params as $key => $value) {
          $post_string .= $key . '=' . urlencode(utf8_encode(trim($value))) . '&';
        }

        $post_string = substr($post_string, 0, -1);

        $response = $this->sendTransactionToGateway($api_url, $post_string);
		echo "<pre>";						print_r($response );echo "</pre>";die("dsd");
        $response_array = array();
        parse_str($response, $response_array);
        if (($response_array['ACK'] == 'Success') || ($response_array['ACK'] == 'SuccessWithWarning')) 
		{
										if(check_login("jobseeker"))
          tep_redirect(tep_href_link(FILENAME_JOBSEEKER_CHECKOUT_PROCESS, '', 'SSL'));
										else
          tep_redirect(tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL'));

		}
        elseif (($response_array['ACK'] != 'Success') && ($response_array['ACK'] != 'SuccessWithWarning')) 
		{
		 if(check_login("jobseeker"))
          tep_redirect(tep_href_link(FILENAME_JOBSEEKER_CHECKOUT_PAYMENT, 'error_message=' . stripslashes($response_array['L_LONGMESSAGE0']), 'SSL'));
		else
          tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . stripslashes($response_array['L_LONGMESSAGE0']), 'SSL'));
        }
      } else
		  {
		   if(check_login("jobseeker"))
           tep_redirect(tep_href_link(FILENAME_JOBSEEKER_CHECKOUT_PAYMENT, 'error_message=' . MODULE_PAYMENT_PAYPAL_PRO_DP_ERROR_ALL_FIELDS_REQUIRED, 'SSL'));
			else
           tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT,'product_id='.$_GET['product_id']. '&error_message=' . MODULE_PAYMENT_PAYPAL_PRO_DP_ERROR_ALL_FIELDS_REQUIRED, 'SSL'));
      }
    }

    function after_process() {
      return false;
    }

    function output_error() {
      return false;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . CONFIGURATION_TABLE . " where configuration_name = 'MODULE_PAYMENT_PAYPAL_PRO_DP_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    
    function install() {

      tep_db_query("insert into " . CONFIGURATION_TABLE . " (configuration_title, configuration_name, configuration_value, configuration_description, configuration_group_id, priority, set_function, inserted) values ('Enable PayPal Direct', 'MODULE_PAYMENT_PAYPAL_PRO_DP_STATUS', 'False', 'Do you want to accept PayPal Direct payments?', '9', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . CONFIGURATION_TABLE . " (configuration_title, configuration_name, configuration_value, configuration_description, configuration_group_id, priority, inserted) values ('API Username', 'MODULE_PAYMENT_PAYPAL_PRO_DP_API_USERNAME', '', 'The username to use for the PayPal API service.', '9', '0', now())");
      tep_db_query("insert into " . CONFIGURATION_TABLE . " (configuration_title, configuration_name, configuration_value, configuration_description, configuration_group_id, priority, inserted) values ('API Password', 'MODULE_PAYMENT_PAYPAL_PRO_DP_API_PASSWORD', '', 'The password to use for the PayPal API service.', '9', '0', now())");
      tep_db_query("insert into " . CONFIGURATION_TABLE . " (configuration_title, configuration_name, configuration_value, configuration_description, configuration_group_id, priority, inserted) values ('API Signature', 'MODULE_PAYMENT_PAYPAL_PRO_DP_API_SIGNATURE', '', 'The signature to use for the PayPal API service.', '9', '0', now())");
      tep_db_query("insert into " . CONFIGURATION_TABLE . " (configuration_title, configuration_name, configuration_value, configuration_description, configuration_group_id, priority, set_function, inserted) values ('Transaction Server', 'MODULE_PAYMENT_PAYPAL_PRO_DP_TRANSACTION_SERVER', 'Live', 'Use the live or testing (sandbox) gateway server to process transactions?', '9', '0', 'tep_cfg_select_option(array(\'Live\', \'Sandbox\'), ', now())");
      tep_db_query("insert into " . CONFIGURATION_TABLE . " (configuration_title, configuration_name, configuration_value, configuration_description, configuration_group_id, priority, set_function, inserted) values ('Transaction Method', 'MODULE_PAYMENT_PAYPAL_PRO_DP_TRANSACTION_METHOD', 'Sale', 'The processing method to use for each transaction.', '9', '0', 'tep_cfg_select_option(array(\'Authorization\', \'Sale\'), ', now())");
      tep_db_query("insert into " . CONFIGURATION_TABLE . " (configuration_title, configuration_name, configuration_value, configuration_description, configuration_group_id, priority, set_function, inserted) values ('Card Acceptance Page', 'MODULE_PAYMENT_PAYPAL_PRO_DP_CARD_INPUT_PAGE', 'Confirmation', 'The location to accept card information. Either on the Checkout Confirmation page or the Checkout Payment page.', '9', '0', 'tep_cfg_select_option(array(\'Confirmation\', \'Payment\'), ', now())");
      tep_db_query("insert into " . CONFIGURATION_TABLE . " (configuration_title, configuration_name, configuration_value, configuration_description, configuration_group_id, priority, use_function, set_function, inserted) values ('Payment Zone', 'MODULE_PAYMENT_PAYPAL_PRO_DP_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '9', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . CONFIGURATION_TABLE . " (configuration_title, configuration_name, configuration_value, configuration_description, configuration_group_id, priority, inserted) values ('Sort order of display.', 'MODULE_PAYMENT_PAYPAL_PRO_DP_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '9', '0', now())");
      tep_db_query("insert into " . CONFIGURATION_TABLE . " (configuration_title, configuration_name, configuration_value, configuration_description, configuration_group_id, priority, set_function, use_function, inserted) values ('Set Order Status', 'MODULE_PAYMENT_PAYPAL_PRO_DP_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value.', '9', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
      tep_db_query("insert into " . CONFIGURATION_TABLE . " (configuration_title, configuration_name, configuration_value, configuration_description, configuration_group_id, priority, inserted) values ('cURL Program Location', 'MODULE_PAYMENT_PAYPAL_PRO_DP_CURL', '/usr/bin/curl', 'The location to the cURL program application.', '9', '0' , now())");
      tep_db_query("insert into " . CONFIGURATION_TABLE . " (configuration_title, configuration_name, configuration_value, configuration_description, configuration_group_id, priority, set_function, inserted) values ('Accept Visa', 'MODULE_PAYMENT_PAYPAL_PRO_DP_CARDTYPE_VISA', 'True', 'Accept Visa card payments?', '9', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . CONFIGURATION_TABLE . " (configuration_title, configuration_name, configuration_value, configuration_description, configuration_group_id, priority, set_function, inserted) values ('Accept Visa Debit', 'MODULE_PAYMENT_PAYPAL_PRO_DP_CARDTYPE_VISA_DEBIT', 'True', 'Accept Visa Debit card payments?', '9', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . CONFIGURATION_TABLE . " (configuration_title, configuration_name, configuration_value, configuration_description, configuration_group_id, priority, set_function, inserted) values ('Accept Visa Electron', 'MODULE_PAYMENT_PAYPAL_PRO_DP_CARDTYPE_VISA_ELECTRON', 'True', 'Accept Visa Electron card payments?', '9', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . CONFIGURATION_TABLE . " (configuration_title, configuration_name, configuration_value, configuration_description, configuration_group_id, priority, set_function, inserted) values ('Accept MasterCard', 'MODULE_PAYMENT_PAYPAL_PRO_DP_CARDTYPE_MASTERCARD', 'True', 'Accept MasterCard card payments?', '9', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . CONFIGURATION_TABLE . " (configuration_title, configuration_name, configuration_value, configuration_description, configuration_group_id, priority, set_function, inserted) values ('Accept Discover', 'MODULE_PAYMENT_PAYPAL_PRO_DP_CARDTYPE_DISCOVER', 'True', 'Accept Discover card payments?', '9', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . CONFIGURATION_TABLE . " (configuration_title, configuration_name, configuration_value, configuration_description, configuration_group_id, priority, set_function, inserted) values ('Accept American Express', 'MODULE_PAYMENT_PAYPAL_PRO_DP_CARDTYPE_AMEX', 'True', 'Accept American Express card payments?', '9', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . CONFIGURATION_TABLE . " (configuration_title, configuration_name, configuration_value, configuration_description, configuration_group_id, priority, set_function, inserted) values ('Accept Maestro', 'MODULE_PAYMENT_PAYPAL_PRO_DP_CARDTYPE_SWITCH', 'True', 'Accept Maestro card payments?', '9', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . CONFIGURATION_TABLE . " (configuration_title, configuration_name, configuration_value, configuration_description, configuration_group_id, priority, set_function, inserted) values ('Accept Solo', 'MODULE_PAYMENT_PAYPAL_PRO_DP_CARDTYPE_SOLO', 'True', 'Accept Solo card payments?', '9', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
    }

    function remove() {
      tep_db_query("delete from " . CONFIGURATION_TABLE . " where configuration_name in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_PAYPAL_PRO_DP_STATUS', 'MODULE_PAYMENT_PAYPAL_PRO_DP_API_USERNAME', 'MODULE_PAYMENT_PAYPAL_PRO_DP_API_PASSWORD', 'MODULE_PAYMENT_PAYPAL_PRO_DP_API_SIGNATURE', 'MODULE_PAYMENT_PAYPAL_PRO_DP_TRANSACTION_SERVER', 'MODULE_PAYMENT_PAYPAL_PRO_DP_TRANSACTION_METHOD', 'MODULE_PAYMENT_PAYPAL_PRO_DP_CARD_INPUT_PAGE', 'MODULE_PAYMENT_PAYPAL_PRO_DP_ZONE', 'MODULE_PAYMENT_PAYPAL_PRO_DP_ORDER_STATUS_ID', 'MODULE_PAYMENT_PAYPAL_PRO_DP_SORT_ORDER', 'MODULE_PAYMENT_PAYPAL_PRO_DP_CURL', 'MODULE_PAYMENT_PAYPAL_PRO_DP_CARDTYPE_VISA', 'MODULE_PAYMENT_PAYPAL_PRO_DP_CARDTYPE_VISA_DEBIT', 'MODULE_PAYMENT_PAYPAL_PRO_DP_CARDTYPE_VISA_ELECTRON', 'MODULE_PAYMENT_PAYPAL_PRO_DP_CARDTYPE_MASTERCARD', 'MODULE_PAYMENT_PAYPAL_PRO_DP_CARDTYPE_DISCOVER', 'MODULE_PAYMENT_PAYPAL_PRO_DP_CARDTYPE_AMEX', 'MODULE_PAYMENT_PAYPAL_PRO_DP_CARDTYPE_SWITCH', 'MODULE_PAYMENT_PAYPAL_PRO_DP_CARDTYPE_SOLO');
    }
	 
	 function sendTransactionToGateway($url, $parameters) {
      $server = parse_url($url);

      if (!isset($server['port'])) {
        $server['port'] = ($server['scheme'] == 'https') ? 443 : 80;
      }

      if (!isset($server['path'])) {
        $server['path'] = '/';
      }

      if (isset($server['user']) && isset($server['pass'])) {
        $header[] = 'Authorization: Basic ' . base64_encode($server['user'] . ':' . $server['pass']);
      }

      if (function_exists('curl_init')) {
		$url =$server['scheme'] . '://' . $server['host'] . $server['path'] . (isset($server['query']) ? '?' . $server['query'] : '');
		$curl = curl_init($url);
        curl_setopt($curl, CURLOPT_PORT, $server['port']);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);
        $result = curl_exec($curl);
        $info = curl_getinfo($curl);
    			 echo curl_error($curl);				echo "<pre>";print_r($parameters);print_r($result);print_r( $info);echo "</pre>";die();
        curl_close($curl);
      } else {
        exec(escapeshellarg(MODULE_PAYMENT_PAYPAL_PRO_DP_CURL) . ' -d ' . escapeshellarg($parameters) . ' "' . $server['scheme'] . '://' . $server['host'] . $server['path'] . (isset($server['query']) ? '?' . $server['query'] : '') . '" -P ' . $server['port'] . ' -k', $result);
        $result = implode("\n", $result);
      }

      return $result;
    }

// format prices without currency formatting
    function format_raw($number, $currency_code = '', $currency_value = '') {
      global $currencies, $currency;

      if (empty($currency_code) || !$this->is_set($currency_code)) {
        $currency_code = $currency;
      }

      if (empty($currency_value) || !is_numeric($currency_value)) {
        $currency_value = $currencies->currencies[$currency_code]['value'];
      }

      return number_format(tep_round($number * $currency_value, $currencies->currencies[$currency_code]['decimal_places']), $currencies->currencies[$currency_code]['decimal_places'], '.', '');
    }

    function isCardAccepted($card) {
      return (isset($this->cc_types[$card]) && defined('MODULE_PAYMENT_PAYPAL_PRO_DP_CARDTYPE_' . $card) && (constant('MODULE_PAYMENT_PAYPAL_PRO_DP_CARDTYPE_' . $card) == 'True'));
    }
  }
?>
