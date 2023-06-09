<?php
/*
***********************************************************
**********# Name          : Shambhu Prasad Patnaik   #**********
**********# Company       : Aynsoft             #**********
**********# Copyright (c) www.aynsoft.com 2004  #**********
***********************************************************
*/
include_once("include_files.php");
include_once(PATH_TO_MAIN_PHYSICAL_LANGUAGE.$language.'/'.FILENAME_RECRUITER_POST_JOB);
$template->set_filenames(array('post_job' => 'post_job.htm',
                              'preview_job'=>'preview_job.htm',
                              'invoice_mail'=>'job_post_invoice_template.htm',
                              'fr_invoice_mail'=>'fr_job_post_invoice_template.htm'
                              ));
$jscript_file=PATH_TO_LANGUAGE.$language."/jscript/".'post_job_jscript_file.js';
include_once(FILENAME_BODY);

if(!check_login("recruiter"))
{
 $messageStack->add_session(LOGON_FIRST_MESSAGE, 'error');
 tep_redirect(FILENAME_RECRUITER_LOGIN);
}
//////////////////////
$hidden_fields="";
$edit=false;
$adminedit=false;
if(isset($_GET['jobID']))
{
 $job_id=(int)tep_db_prepare_input($_GET['jobID']);
 $whereClause="job_id='".tep_db_input($job_id)."' and recruiter_id='".$_SESSION['sess_recruiterid']."'";
 if($row=getAnyTableWhereData(JOB_TABLE,$whereClause))
 {
  $edit=true;
 }
 else //hacking attempt
 {
  $messageStack->add_session(MESSAGE_ERROR_JOB, 'error');
  tep_redirect(FILENAME_RECRUITER_POST_JOB);
 }
}
if(check_login('admin'))
{
 $adminedit=true;
}
/////////////////////////////////////////////////////////////////////////
$action = (isset($_POST['action']) ? $_POST['action'] : '');
if(isset($_POST['Preview_x']) || isset($_POST['Preview']))
{
 $action='preview';
}
if(isset($_POST['new_x']))
{
 $action='new';
}
if($_POST['action']=='back')
{
 $action='back';
}
/////Error code/////////
 include_once(PATH_TO_MAIN_PHYSICAL_CLASS . 'recruiter_accounts.php');
 $obj_account=new recruiter_accounts();
 if($obj_account->allocated_amount['job']=='Unlimited' || $obj_account->allocated_amount['job'] >= $obj_account->enjoyed_amount['job']+1)
 {
//  tep_redirect(tep_href_link(FILENAME_RECRUITER_POST_JOB));
 }
 elseif($obj_account->allocated_amount['job'] > $obj_account->enjoyed_amount['job'])
 {
  //$messageStack->add_session(sprintf(MESSAGE_JOB_UNSUCCESS_INSERTED,($obj_account->allocated_amount['job']-$obj_account->enjoyed_amount['job'])), 'error');
    $messageStack->add_session(ERROR_SUBSCRIPTON1, 'Error');
  tep_redirect(tep_href_link(FILENAME_RECRUITER_RATES));
//tep_redirect(tep_href_link(FILENAME_SUBSCRIPTION_ERROR));
 }
 else
 {
  if(!$edit){
  $messageStack->add_session(ERROR_SUBSCRIPTON, 'Plan Expired');
  tep_redirect(tep_href_link(FILENAME_RECRUITER_RATES));
  }
 }
		/////Error code/////////
// add/edit
if(tep_not_null($action))
{
 switch($action)
 {
  case 'new':
  case 'edit':
  case 'preview':
  case 'back':
   $title=tep_db_prepare_input($_POST['TR_job_title']);
   $reference=tep_db_prepare_input($_POST['job_reference']);
   $country=tep_db_prepare_input($_POST['country1']);

/***********JOBFAIR BEGIN*************************************/
   if(tep_not_null($_POST['post_jobfair']))
   {
    if(!tep_not_null($_POST['post_jobfair'][0]))
     $jobfair=0;
    else
     $jobfair=tep_db_prepare_input(implode(',',$_POST['post_jobfair']));
   }
/***********JOBFAIR END *************************************/

   if(isset($_POST['state']) and $_POST['state']!='')
   $state_value=tep_db_prepare_input($_POST['state']);
   elseif(isset($_POST['state1']))
   $state_value=tep_db_prepare_input($_POST['state1']);
//   print_r();
   $location=tep_db_prepare_input($_POST['location']);
   $salary=tep_db_prepare_input($_POST['salary']);

   $skills=tep_db_prepare_input($_POST['skills']);
   $skills = preg_replace("'[\s]+'", " ", $skills);
   $skills = str_replace(array(", "," ,"),array( ",",","), $skills);

   if(tep_not_null($_POST['TR_post_job_category']))
   {
    $job_category=tep_db_prepare_input(implode(',',$_POST['TR_post_job_category']));
    $job_category=remove_child_job_category($job_category);
   }

   //$summary=nl2br(stripslashes($_POST['TR_job_summary']));
   $description=stripslashes($_POST['description']);
   $full_location=tep_db_prepare_input($_POST['full_location']);
   $summary=tep_db_prepare_input($_POST['TR_job_summary']);
   $responsibility=tep_db_prepare_input($_POST['responsibility']);
   $TR_qualification=tep_db_prepare_input($_POST['TR_qualification']);
   $benefits=tep_db_prepare_input($_POST['benefits']);
   $schedule=tep_db_prepare_input($_POST['schedule']);
   //$description=tep_db_prepare_input($_POST['description']);
   $email=tep_db_prepare_input($_POST['TREF_email_address']);

   if(tep_not_null($_POST['job_type1']))
   {
    if($_POST['job_type1'][0]=='0')
     $job_type=0;
    else
     $job_type=tep_db_prepare_input(implode(',',$_POST['job_type1']));
   }
   $relocate='Yes';
   $experience=tep_db_prepare_input($_POST['TR_experience']);
   $explode_experience=explode("-",$experience);
   $min_experience=$explode_experience[0];
   $max_experience=$explode_experience[1];
   if($edit==false || $adminedit==true)
   {
    $vacancy_added_date=tep_db_prepare_input($_POST['TR_year']."-".$_POST['TR_month']."-".$_POST['TR_date']);
    $expired=tep_db_prepare_input($_POST['TR_Year']."-".$_POST['TR_Month']."-".$_POST['TR_Date']);
    //$vacancy_period=tep_db_prepare_input($_POST['TR_vacancy_period']);
    $vacancy_period=dateDiff('d',$vacancy_added_date,$expired);
   }
			$post_url=tep_db_prepare_input($_POST['post_url']);
			$url=tep_db_prepare_input($_POST['url']);
			$job_auto_renew = tep_db_prepare_input($_POST['job_auto_renew']);

   $error=false;
   /*if($edit)
   {
    if($row=getAnyTableWhereData(JOB_TABLE,"job_title='".tep_db_input($title)."' and recruiter_id ='".$_SESSION['sess_recruiterid']."' and job_id!='".$job_id."'"))
    {
     $error=true;
     $messageStack->add(ENTRY_JOB_TITLE_ERROR,'add_job');
    }
   }
   else
   {
    if($row=getAnyTableWhereData(JOB_TABLE,"job_title ='".tep_db_input($title)."' and recruiter_id='".$_SESSION['sess_recruiterid']."'"))
    {
     $error=true;
     $messageStack->add(ENTRY_JOB_TITLE_ERROR,'add_job');
    }
   }*/
   if(strlen($description) <= 0)
   {
    $error = true;
    $messageStack->add(ENTRY_DESCRIPTION_ERROR,'add_job');
   }
   if(strlen($summary) <= 0)
   {
    $error = true;
    $messageStack->add(ENTRY_VACANCY_SUMMARY_ERROR,'add_job');
   }
   if(strlen($TR_qualification) <= 0)
   {
    $error = true;
    $messageStack->add(ENTRY_VACANCY_QUAL_ERROR,'add_job');
   }
   if(!tep_not_null($_POST['TR_post_job_category'][0]))
   {
    $error = true;
    $messageStack->add(ENTRY_JOB_CATEGORY_ERROR,'add_job');
   }

   if(is_numeric($country) == false)
   {
    //$error = true;
    //$messageStack->add(ENTRY_COUNTRY_ERROR,'add_job');
   }
   /////////// check state //
   if(is_numeric($state_value))
   {
    $zone_id = 0;//echo $state_value;
    if($check_query = getAnyTableWhereData(ZONES_TABLE, "zone_country_id = '" . tep_db_input($country) . "'", "zone_country_id"))
    {
     $zone_query = tep_db_query("select distinct zone_id from " . ZONES_TABLE . " where zone_country_id = '" . tep_db_input($country) . "' and (zone_id ='" . tep_db_input($state_value) . "' )");
     if (tep_db_num_rows($zone_query) == 1)
     {
      $zone = tep_db_fetch_array($zone_query);
      $zone_id = $zone['zone_id'];
     }
     else
     {
      //$state_error=true;
      //$error = true;
      //$messageStack->add(ENTRY_STATE_ERROR_SELECT,'add_job');
     }
    }
    else
    {
     //$state_error=true;
     //$error = true;
     //$messageStack->add(ENTRY_STATE_ERROR_SELECT,'add_job');
    }
   }
   else
   {
    if(tep_not_null($state_value))
    if($row11 = getAnyTableWhereData(ZONES_TABLE, "zone_country_id = '" . tep_db_input($country) . "'", "zone_country_id"))
    {
     $state_error=true;
     $error = true;
     $messageStack->add(ENTRY_STATE_ERROR_SELECT,'add_job');
    }
    else if (strlen($state_value) <= 0)
    {
     //$state_error=true;
     //$error = true;
     //$messageStack->add(ENTRY_STATE_ERROR,'add_job');
    }
   }

   if(isset($vacancy_period) && $vacancy_period<1)
   {
    $error = true;
    $messageStack->add(ENTRY_READVERTISE_ERROR,'add_job');
   }
   if($vacancy_period >INFO_TEXT_MAX_JOB_DURATION)
   {
    $error = true;
    $messageStack->add(ENTRY_CLOSING_DATE_ERROR,'add_job');
   }
   //////////////End State check///////////////////////////
				if(strlen($post_url) > 0)
   {
				if(strlen($url)<=0)
				{
     $error = true;
     $messageStack->add(ENTRY_ENTER_URL_ERROR,'add_job');
				}
   }
   if(!$error && $action!='preview' && $action!='back')
   {
    $sql_data_array=array('job_title'=>$title,
                          'job_reference'=>$reference,
                          'job_country_id'=>$country,
                          'job_location'=>$location,
                          'job_salary'=>$salary,
		                  'job_skills'=> $skills,
                          //'job_industry_sector'=>$job_category,
                          'job_short_description'=>$summary,
                          'job_description'=>$description,
                          'job_qualification'=>$TR_qualification,
                          'job_responsibility'=>$responsibility,
                          'job_benefits'=>$benefits,
                          'job_schedule'=>$schedule,
                          'job_type'=>$job_type,
                          'job_relocate'=>$relocate,
                          'min_experience'=>$min_experience,
                          'max_experience'=>$max_experience,
						  'post_url'      => $post_url,
						  'url'           => $url,
						  'job_auto_renew' => $job_auto_renew,
 						  'add_jobfair'=>($jobfair==0?'No':'Yes'),
                         );
    if($zone_id > 0)
    {
     $sql_data_array['job_state']='null';
     $sql_data_array['job_state_id']=$zone_id;
    }
    else
    {
     $sql_data_array['job_state']=$state_value;
     $sql_data_array['job_state_id']=0;
    }
    if($row_check=getAnyTableWhereData(RECRUITER_USERS_TABLE,"email_address='".tep_db_input($email)."'","id"))
    {
     $recruiter_user_id=$row_check['id'];
     $sql_data_array['recruiter_user_id']=$recruiter_user_id;
    }
    else
    {
     $sql_data_array['recruiter_user_id']='null';
    }
    $sql_data_array['recruiter_id']=$_SESSION['sess_recruiterid'];

    $result=getLocationGeoAddress('address='.urlencode($full_location));
	if(is_array($result))
	{
     $sql_data_array['latitude']=$result['latitude'];
     $sql_data_array['longitude']=$result['longitude'];
	}

    if($edit)
    {
     if($adminedit)
     {
      $sql_data_array['job_vacancy_period']=$vacancy_added_date;
      //$sql_data_array['expired']=datetime($vacancy_period,$vacancy_added_date);
      $sql_data_array['re_adv']=$vacancy_added_date;
      $sql_data_array['expired']=$expired.' 23:59:59';
      $sql_data_array['job_vacancy_period']=$vacancy_period;
     }
     $sql_data_array['updated']='now()';

/*#####      JOBFAIR CODING -ADDING TO jobs_jobfair table BEGIN    ######*/
	//echo $jobfair;die;

	$jobfair2=explode(',',$jobfair);
	tep_db_query("delete from ".JOB_JOBFAIR_TABLE." where job_id='".$job_id."'  and recruiter_id='".$_SESSION['sess_recruiterid']."'");
	for($i=0;$i<count($jobfair2);$i++)
	{
		if(tep_not_null($jobfair))
		{
		$sql_jobfair_array=array('job_id'=>$job_id,
								'recruiter_id'=>$_SESSION['sess_recruiterid'],
								'jobfair_id'=>$jobfair2[$i],
								'inserted'=>'now()'
		 );
		tep_db_perform(JOB_JOBFAIR_TABLE,$sql_jobfair_array);
		}
	}

/*#####      JOBFAIR CODING -ADDING TO jobs_jobfair table END    ######*/

     ////////////////////////////////////////////////////
     $job_category2=explode(',',$job_category);
				 $sql_job_array=array('job_id'=>$job_id );
		  	for($i=0;$i<count($job_category2);$i++)
					{
					 if(!$job_row = getAnyTableWhereData(JOB_JOB_CATEGORY_TABLE, "job_id = '" . tep_db_input($job_id) . "' and job_category_id='".$job_category2[$i]."'", "job_category_id"))
					 {
					 	$sql_job_array['job_category_id']=$job_category2[$i];
						 tep_db_perform(JOB_JOB_CATEGORY_TABLE,$sql_job_array);
					 }
					}
					if(!tep_not_null($job_category))
					$job_category=0;
 				tep_db_query("delete from ".JOB_JOB_CATEGORY_TABLE." where job_id='".$job_id."' and job_category_id not in(".$job_category.")");
     //////////////////////////////////////////
     //print_r($sql_data_array);
     //die();
     tep_db_perform(JOB_TABLE, $sql_data_array, 'update', "job_id = '" . $job_id . "'");
	  	 if(tep_not_null($skills))
	 insertSkillTag($skills);


 	   $messageStack->add_session(MESSAGE_SUCCESS_UPDATED, 'success');
     tep_redirect(tep_href_link(FILENAME_RECRUITER_LIST_OF_JOBS));
    }
    else
    {
     include_once(PATH_TO_MAIN_PHYSICAL_CLASS . 'recruiter_accounts.php');
     $obj_account=new recruiter_accounts();
     //if($obj_account->allocated_amount['job']=='Unlimited' || $obj_account->allocated_amount['job'] >= $obj_account->enjoyed_amount['job']+(int)points1($vacancy_period))
     if($obj_account->allocated_amount['job']=='Unlimited' || $obj_account->allocated_amount['job'] >= $obj_account->enjoyed_amount['job']+1)
     {
      //$sql_data_array['job_vacancy_period']=$vacancy_period;
      $sql_data_array['re_adv']=$vacancy_added_date;
      $sql_data_array['inserted']='now()';
      $sql_data_array['expired']=$expired.' 23:59:59';
      $sql_data_array['job_vacancy_period']=$vacancy_period;
						if($obj_account->allocated_amount['featured_job']=='Yes')
      $sql_data_array['job_featured']='Yes';
						else
      $sql_data_array['job_featured']='No';
      //$sql_data_array['expired']=datetime($vacancy_period,$vacancy_added_date);
      //print_r($sql_data_array);
      //die();
      tep_db_perform(JOB_TABLE, $sql_data_array);
	  	 if(tep_not_null($skills))
	 insertSkillTag($skills);


	        $row_check=getAnyTableWhereData(JOB_TABLE,"recruiter_id='".$_SESSION['sess_recruiterid']."' order by job_id desc limit 0,1",'job_id');
      $job_id=$row_check['job_id'];

/*########  ###  JOBFAIR CODING - ADDITION to jobs_jobfair table BEGIN ### #########*/
//echo $jobfair;
$jobfair2=explode(',',$jobfair);
for($i=0;$i<count($jobfair2);$i++)
{
	if(tep_not_null($jobfair))
	{
	$sql_jobfair_array=array('job_id'=>$job_id,
							'recruiter_id'=>$_SESSION['sess_recruiterid'],
							'jobfair_id'=>$jobfair2[$i],
							'inserted'=>'now()'
	 );
	tep_db_perform(JOB_JOBFAIR_TABLE,$sql_jobfair_array);
	}
}
/*########  ###  JOBFAIR CODING - ADDITION to jobs_jobfair table END ### #########*/

      /////////////////////////////////////
				  $job_category2=explode(',',$job_category);
				  $sql_job_array=array('job_id'=>$job_id );
		  	 for($i=0;$i<count($job_category2);$i++)
					 {
					  if(!$job_row = getAnyTableWhereData(JOB_JOB_CATEGORY_TABLE, "job_id = '" . tep_db_input($job_id) . "' and job_category_id='".$job_category2[$i]."'", "job_category_id"))
						 {
							 	$sql_job_array['job_category_id']=$job_category2[$i];
								 tep_db_perform(JOB_JOB_CATEGORY_TABLE,$sql_job_array);
						 }
					 }
      /////////////////////////////////////////////

      $sql_data_array_new=array();
      $sql_data_array_new['display_id']=get_job_enquiry_code($job_id);
      tep_db_perform(JOB_TABLE, $sql_data_array_new, 'update', "job_id = '" . $job_id . "'");

      // find last id //
      $recruiter_id=$_SESSION['sess_recruiterid'];
      $now=date("Y-m-d");
      $row=getAnyTableWhereData(RECRUITER_ACCOUNT_HISTORY_TABLE,"recruiter_id='".$_SESSION['sess_recruiterid']."' and start_date <= '$now' and end_date >='$now' and plan_for='job_post'","id,job_enjoyed");
     // $sql_data_array=array('job_enjoyed'=>points1($vacancy_period)+$row['job_enjoyed']);
      $sql_data_array=array('job_enjoyed'=>1+$row['job_enjoyed']);
      tep_db_perform(RECRUITER_ACCOUNT_HISTORY_TABLE, $sql_data_array, 'update', "id = '" . $row['id'] . "'");
      //////////
      /////////////TWITER SUBMITTER//////////////

      ini_set('max_execution_time','0');
      $title_format=encode_category($title);
      if(MODULE_TWITTER_SUBMITTER=='enable')
      {
       include('./class/twitter.php');
       $twitter_obj = new twitter;
       $twitter_obj-> twitter_post_status(set_twiter_status($title,tep_href_link($job_id.'/'.$title_format.'.html')));
      }
						if(MODULE_FACEBOOK_PLUGIN_JOB_SUBMITTER=='enable' && MODULE_FACEBOOK_PLUGIN =='enable'&& MODULE_FACEBOOK_PLUGIN_SUBMITTER_ID!='')
      {
       $facebook_page_id  = MODULE_FACEBOOK_PLUGIN_SUBMITTER_ID;
       include('./class/facebook_post.php');
       $t = new FacebookPost();
      	///$p=explode(':',$facebook_page_id);
      	$d=$t->postLink($facebook_page_id,tep_href_link($job_id.'/'.$title_format.'.html'));
      }
      ////////////////////////////
    /*  if(MODULE_ONLYWIRE_SUBMITTER=='enable')
      {
       include('./class/onlywire.php');
       $onlywire_url= tep_href_link($job_id.'/'.$title_format.'.html');
       $onlywire_obj = new onlywire;
       $onlywire_obj-> onlywire_post_url($onlywire_url,$title,$summary);
      }
	  */
      //////////////////////////

      $row_user=getAnyTableWhereData(RECRUITER_LOGIN_TABLE." as rl  left outer join ".RECRUITER_TABLE." as r on (r.recruiter_id =rl.recruiter_id) "," rl.recruiter_id='".$_SESSION['sess_recruiterid']."'","rl.recruiter_email_address,r.recruiter_first_name,r.recruiter_last_name");
      $recruiter_name=tep_db_output($row_user['recruiter_first_name'].' '.$row_user['recruiter_last_name']);
      $query_string=encode_string("job_id=".$job_id."=job_id");
      $title_format=encode_category($title);
      $template->assign_vars(array(
       'recruiter_name' => $recruiter_name,
       'job_title'      => '<a href="'.tep_href_link($job_id.'/'.$title_format.'.html').'" style="color:#0000ff;">'.tep_db_output($title).'</a>',
       'expired'        => tep_db_output(formate_date($expired,'d-M-Y')),
       'site_title'     => tep_db_output(SITE_TITLE),
       'logo'           => '<a href="'.tep_href_link("").'">'.tep_image(PATH_TO_IMG.DEFAULT_SITE_LOGO,tep_db_output(SITE_TITLE),'','','class="internal-logo"').'</a>',
							'site_admin'     => '<a href="'.tep_href_link("").'">'.tep_db_output(SITE_TITLE).'</a>',
      ));
      $email_text=stripslashes($template->pparse1(TEXT_LANGUAGE.'invoice_mail'));
      tep_mail($recruiter_name,$row_user['recruiter_email_address'],JOB_POST_INVOICE_SUBJECT, $email_text, SITE_OWNER,EMAIL_FROM);
      $messageStack->add_session(MESSAGE_SUCCESS_INSERTED, 'success');
      tep_redirect(tep_href_link(FILENAME_RECRUITER_LIST_OF_JOBS));
     }
     elseif($obj_account->allocated_amount['job'] > $obj_account->enjoyed_amount['job'])
     {
      $messageStack->add_session(sprintf(MESSAGE_JOB_UNSUCCESS_INSERTED,($obj_account->allocated_amount['job']-$obj_account->enjoyed_amount['job'])), 'error');
      tep_redirect(tep_href_link(FILENAME_SUBSCRIPTION_ERROR));
     }
     else
     {
      $messageStack->add_session(ERROR_SUBSCRIPTION, 'error');
      tep_redirect(tep_href_link(FILENAME_SUBSCRIPTION_ERROR));
     }
    }
   }
 }
}

/////find recruiter state /////////////////
 $row_st=getAnyTableWhereData(RECRUITER_TABLE,"recruiter_id='".$_SESSION['sess_recruiterid']."'",'recruiter_state_id');
      $recruiter_state=$row_st['recruiter_state_id'];
///////////////////////////////////////////////////////

if($error || $action=='preview' || $action=='back')
{
 $TR_job_title=$title;
 $job_reference=$reference;
 $country1=$country;
 $state_value=$state_value;
 //$TR_state=$state;
 $salary=$salary;
 $skills= $skills;
 $post_jobfair=$jobfair;
 $post_job_category=$job_category;
 $TR_job_summary=$summary;
 $description=$description;
 $TR_qualifcation=$TR_qualification;
 $benefits=$benefits;
 $schedule=$schedule;
 $responsibility=$responsibility;
 $TREF_email_address=$email;
 $job_type1=$job_type;
 $relocate=$relocate;
 $TR_experience=$experience;
 $TR_vacancy_period=$vacancy_period;
 $expired=$expired;
 $adv_date=$vacancy_added_date;
 $post_url = $post_url;
 $url      = $url;
 $job_auto_renew = $job_auto_renew;

}
else if($edit)
{
 $TR_job_title=$row['job_title'];
 $job_reference=$row['job_reference'];
 $country1=$row['job_country_id'];
 $TR_state=$row['job_state_id'];
 if($TR_state > 0 )
 {
  $TR_state=get_name_from_table(ZONES_TABLE,'zone_name', 'zone_id',$TR_state);
 }
 else
 {
  $TR_state=$row['job_state'];
 }
 $state_value=(int)$row['job_state_id'];
 if($state_value > 0 and is_int($state_value) )
 {
  $state_value=$state_value;//get_name_from_table(ZONES_TABLE,'zone_name', 'zone_id',$state_value);
 }
 else
 {
  $state_value=$row['job_state'];
 }
 $location=$row['job_location'];
 $salary=$row['job_salary'];
 $skills=$row['job_skills'];
 $post_job_category=get_name_from_table(JOB_JOB_CATEGORY_TABLE,'job_category_id','job_id',tep_db_output($job_id));
 $post_jobfair=get_name_from_table(JOB_JOBFAIR_TABLE,'jobfair_id','job_id',tep_db_output($job_id));
 $TR_job_summary=$row['job_short_description'];
 $description=$row['job_description'];
 $TR_qualification=$row['job_qualification'];
 $benefits=$row['job_benefits'];
 $schedule=$row['job_schedule'];
 $responsibility=$row['job_responsibility'];
 $job_type=$row['job_type'];
 $relocate=$row['job_relocate'];
 $TR_experience=$row['min_experience'].'-'.$row['max_experience'];
 $TR_vacancy_period=$row['job_vacancy_period'];
 $adv_date=substr($row['re_adv'],0,10);
 $expired=substr($row['expired'],0,10);
 $post_url=$row['post_url'];
 $url = $row['url'];
 $job_auto_renew =$row['job_auto_renew'];

 if($row['recruiter_user_id']!=null)
 {
  $row_email=getAnyTableWhereData(RECRUITER_USERS_TABLE,"id='".$row['recruiter_user_id']."'","email_address");
  $job_email_address=$row_email['email_address'];
 }
 else
 {
 	$row_email=getAnyTableWhereData(RECRUITER_LOGIN_TABLE,"recruiter_id ='".$row['recruiter_id']."'",'recruiter_email_address');
  $job_email_address=$row_email['recruiter_email_address'];
 }
}
else
{
 $TR_job_title='';
 $job_reference='';
 $country1=DEFAULT_COUNTRY_ID;
 $state_value=$recruiter_state;
 $salary='';
 $skills='';
 $post_jobfair='';
 $post_job_category='';
 $TR_job_summary='';
 $description='';
 $responsibility='';
 $schedule='';
 $benefits='';
 $TR_qualification='';
 $TREF_email_address='';
 $job_type1='';
 $relocate='';
 $TR_experience='';
 $post_url='';
 $url= '';
 $job_auto_renew ='';

 $adv_date=date('Y-m-d');
 $expired=date('Y-m-d',mktime(0,0,0,date("m"),date("d")+INFO_TEXT_MAX_JOB_DURATION,date("Y")));
 if(isset($_SESSION['sess_recruiteruserid']))
 {
 $email_recruiter_id=$_SESSION['sess_recruiteruserid'];
 $row_email=getAnyTableWhereData(RECRUITER_USERS_TABLE,"id='$email_recruiter_id'","email_address");
 $job_email_address=$row_email['email_address'];
 }
 else
{
	$row_email=getAnyTableWhereData(RECRUITER_LOGIN_TABLE,"recruiter_id ='".$_SESSION['sess_recruiterid']."'",'recruiter_email_address');
 $job_email_address=$row_email['recruiter_email_address'];
}

}
if($action=='preview' && !$error)
{
 $hidden_fields='';
  $buttons='<a href="#" onclick="history.back();" class="btn btn-outline-secondary me-2">'
            .IMAGE_BACK.'
            </a>'
            .tep_image_submit(PATH_TO_BUTTON.'button_confirm.gif', IMAGE_CONFIRM, 'name="new" class="fixedWidth me-2"').'
            <a href="'.tep_href_link(FILENAME_RECRUITER_CONTROL_PANEL).'" class="btn btn-outline-secondary me-2">'.IMAGE_CANCEL.'
            </a>';
 if($edit)
 {
  $preview_job_form=tep_draw_form('preview_job', FILENAME_RECRUITER_POST_JOB, 'jobID='.$_GET['jobID'], 'post', 'onsubmit="return ValidateForm(this)"');
 }
 else
 {
  $preview_job_form=tep_draw_form('preview_job', FILENAME_RECRUITER_POST_JOB, '', 'post', 'onsubmit="return ValidateForm(this)"');
 }
 $hidden_fields.=tep_draw_input_field('action', '','',false,'hidden',false);
 $hidden_fields.=tep_draw_input_field('TR_job_title', $TR_job_title,'',false,'hidden');
 $hidden_fields.=tep_draw_input_field('job_reference', $job_reference,'',false,'hidden');
 $hidden_fields.=tep_draw_input_field('country1', $country1,'',false,'hidden');
 $hidden_fields.=tep_draw_input_field('state', $state_value,'',false,'hidden');
 $hidden_fields.=tep_draw_input_field('location', $location,'',false,'hidden');
 $hidden_fields.=tep_draw_input_field('salary', $salary,'',false,'hidden');
 $hidden_fields.=tep_draw_input_field('skills', $skills,'',false,'hidden');
 $hidden_fields.=tep_draw_input_field('post_url', $post_url,'',false,'hidden');
 $hidden_fields.=tep_draw_input_field('url', $url,'',false,'hidden');
 $hidden_fields.=tep_draw_input_field('job_auto_renew', $job_auto_renew,'',false,'hidden');

 $job_category1=explode(',',$post_job_category);
	if($job_category1[0]=="0")
	{
		$post_job_category="All job categories";
  $hidden_fields.=tep_draw_input_field('TR_post_job_category[]', $job_category1[0],'',false,'hidden');
	}
	else
	{
  for($i=0;$i<count($job_category1);$i++)
		{
   $hidden_fields.=tep_draw_input_field('TR_post_job_category[]', $job_category1[$i],'',false,'hidden');
		}
  $post_job_category=$job_category;//get_name_from_table(JOB_CATEGORY_TABLE,'category_name', 'id', $job_category);
	}

/*###### #### *JOBFAIR CODING BEGIN* ######## #####*/

		$jobfair1=explode(',',$post_jobfair);
		if($jobfair1[0]=="0")
		{
			$post_jobfair="None";
	  $hidden_fields.=tep_draw_input_field('post_jobfair[]', $jobfair1[0],'',false,'hidden');
		}
		else
		{
	  for($i=0;$i<count($jobfair1);$i++)
			{
	   $hidden_fields.=tep_draw_input_field('post_jobfair[]', $jobfair1[$i],'',false,'hidden');
			}
	  $post_jobfair=$jobfair;//get_name_from_table(JOB_CATEGORY_TABLE,'category_name', 'id', $job_category);
		}

/*###### #### *JOBFAIR CODING BEGIN* ######## #####*/

 $hidden_fields.=tep_draw_input_field('TR_job_summary', $TR_job_summary,'',false,'hidden');
 $hidden_fields.=tep_draw_input_field('description', $description,'',false,'hidden');
 $hidden_fields.=tep_draw_input_field('TR_qualification', $TR_qualification,'',false,'hidden');
 $hidden_fields.=tep_draw_input_field('benefits', $benefits,'',false,'hidden');
 $hidden_fields.=tep_draw_input_field('schedule', $schedule,'',false,'hidden');
 $hidden_fields.=tep_draw_input_field('responsibility', $responsibility,'',false,'hidden');
 $hidden_fields.=tep_draw_input_field('TREF_email_address', $TREF_email_address,'',false,'hidden');
 $job_type1=explode(',',$job_type);
	if($job_type1[0]=="0")
	{
		$job_type="All Job types";
  $hidden_fields.=tep_draw_input_field('job_type1[]', $job_type1[0],'',false,'hidden');
	}
	else
	{
		for($i=0;$i<count($job_type1);$i++)
		{
   $hidden_fields.=tep_draw_input_field('job_type1[]', $job_type1[$i],'',false,'hidden');
		}
  $job_type=get_name_from_table(JOB_TYPE_TABLE,TEXT_LANGUAGE.'type_name', 'id', $job_type);
	}
 $hidden_fields.=tep_draw_input_field('relocate', $relocate,'',false,'hidden');
 $hidden_fields.=tep_draw_input_field('TR_experience', $TR_experience,'',false,'hidden');
 $TR_experience=explode('-',$TR_experience);
 $experience_string=calculate_experience($TR_experience['0'],$TR_experience['1']);
 $hidden_fields.=tep_draw_input_field('TR_vacancy_period', $TR_vacancy_period,'',false,'hidden');
 $hidden_fields.=tep_draw_input_field('TR_year', substr($adv_date,0,4),'',false,'hidden');
 $hidden_fields.=tep_draw_input_field('TR_month', substr($adv_date,5,2),'',false,'hidden');
 $hidden_fields.=tep_draw_input_field('TR_date', substr($adv_date,8,2),'',false,'hidden');
 $hidden_fields.=tep_draw_input_field('TR_Year', substr($expired,0,4),'',false,'hidden');
 $hidden_fields.=tep_draw_input_field('TR_Month', substr($expired,5,2),'',false,'hidden');
 $hidden_fields.=tep_draw_input_field('TR_Date', substr($expired,8,2),'',false,'hidden');
	$hidden_fields.=tep_draw_input_field('post_url', $post_url,'',false,'hidden');
	$hidden_fields.=tep_draw_input_field('url', $url,'',false,'hidden');
 //////////////////////
}
else
{
 if($edit)
 {
  //$buttons=tep_image_submit(PATH_TO_BUTTON.'button_preview.gif', IMAGE_PREVIEW, 'name="Preview"').'&nbsp;&nbsp;<a href="'.tep_href_link(FILENAME_RECRUITER_CONTROL_PANEL).'">'.tep_image_button(PATH_TO_BUTTON.'button_cancel.gif', IMAGE_CANCEL).'</a>';
  $buttons=tep_draw_submit_button_field('Preview',INFO_BUTTON_PREVIEW,'class="btn btn-primary"').'&nbsp;&nbsp;<a href="'.tep_href_link(FILENAME_RECRUITER_CONTROL_PANEL).'"><button class="btn btn-outline-secondary">'.INFO_BUTTON_CANCEL.'</button></a>';
  if(check_login('admin'))
   $post_job_form=tep_draw_form('defineForm', FILENAME_RECRUITER_POST_JOB, 'rID='.$_SESSION['sess_recruiterid'].'&jobID='.$_GET['jobID'], 'post', 'enctype="multipart/form-data" onsubmit="return ValidateForm(this)"').tep_draw_hidden_field('action','edit');
  else
   $post_job_form=tep_draw_form('defineForm', FILENAME_RECRUITER_POST_JOB, 'jobID='.$_GET['jobID'], 'post', 'enctype="multipart/form-data" onsubmit="return ValidateForm(this)"').tep_draw_hidden_field('action','edit');
 }
 else
 {
  //$buttons=tep_image_submit(PATH_TO_BUTTON.'button_preview.gif', IMAGE_PREVIEW, 'name="Preview"').'&nbsp;&nbsp;<a href="'.tep_href_link(FILENAME_RECRUITER_CONTROL_PANEL).'">'.tep_image_button(PATH_TO_BUTTON.'button_cancel.gif', IMAGE_CANCEL).'</a>';
   $buttons=tep_draw_submit_button_field('Preview','Preview','class="btn btn-primary"');//tep_image_submit(PATH_TO_BUTTON.'button_preview.gif', IMAGE_PREVIEW, 'name="Preview"');
 $post_job_form=tep_draw_form('defineForm', FILENAME_RECRUITER_POST_JOB, '', 'post', 'enctype="multipart/form-data" onsubmit="return ValidateForm(this)"').tep_draw_hidden_field('action','new');
 }
 if($state_error)
 {
  $zones_array=tep_get_country_zones($country1);
  if(sizeof($zones_array) > 1)
  {
   define('INFO_TEXT_STATE1',LIST_SET_DATA(ZONES_TABLE,"",TEXT_LANGUAGE.'zone_name','zone_id',TEXT_LANGUAGE."zone_name",'name="state" class="form-select"',INFO_TEXT_STATE,'',$state_value));
  }
  else
  {
   define('INFO_TEXT_STATE1',LIST_SET_DATA(ZONES_TABLE,"",TEXT_LANGUAGE.'zone_name','zone_id',TEXT_LANGUAGE."zone_name",'name="state" class="form-select"',INFO_TEXT_STATE,'',$state_value));
  }
 }
 else
 {
  define('INFO_TEXT_STATE1',LIST_SET_DATA(ZONES_TABLE,"",TEXT_LANGUAGE.'zone_name','zone_id',TEXT_LANGUAGE."zone_name",'name="state" class="form-select"',INFO_TEXT_STATE,'',$state_value));
 }
}
$startYear="2007";
$endYear=date("Y")+7;

////*** curency display coding ***********/
$row_cur=getAnyTableWhereData(CURRENCY_TABLE,"code ='".DEFAULT_CURRENCY."'",'symbol_left,symbol_right');
$sym_left=(tep_not_null($row_cur['symbol_left'])?'<div class="input-group-prepend d-inline-block mr-1">
    <span class="input-group-text" id="basic-addon1">'.$row_cur['symbol_left'].'</span>
  </div> ':'');
$sym_rt=(tep_not_null($row_cur['symbol_right'])?'<div class="input-group-prepend"><span class="input-group-text" id="basic-addon1"> '.$row_cur['symbol_right'].'</span></div>':'');
//////**********currency display ***************************/

/******************display jobfair only if applied***********************/
if(getAnyTableWhereData(RECRUITER_JOBFAIR_TABLE,"recruiter_id ='".$_SESSION['sess_recruiterid']."' and approved='Yes'",'recruiter_id'))
	$jobfair_text='<div class="form-group row align-items-center">
    <label for="inputPassword" class="col-sm-3 col-form-label text-right">'.INFO_TEXT_JOBFAIR.'</label>
    <div class="col-sm-9">
      '.LIST_SET_DATA(RECRUITER_JOBFAIR_TABLE." as rjf left join ".JOBFAIR_TABLE." as jf on rjf.jobfair_id=jf.id"," where recruiter_id='".$_SESSION['sess_recruiterid']."' and approved='Yes'",'jobfair_title','jobfair_id',"jobfair_title",'name="post_jobfair[]" class="form-select"',"Select Jobfair",'',$post_jobfair).'
    </div>
  </div>';
else
$jobfair_text='';
/***********************************************************************/

//////////////////////////////////////
$email_list=email_list("name='TREF_email_address' class='form-select'","","",$job_email_address);
$show_text_box=tep_draw_input_field('url', $url,'class="form-control" placeholder="Enter URL"',false);
////////////////////////////////////////////////////////
if($action=="preview" && !$error)
{
 $full_location =	trim((($location!='')?$location.",":"").(tep_not_null($state_value)?(is_numeric($state_value)?get_name_from_table(ZONES_TABLE,TEXT_LANGUAGE.'zone_name', 'zone_id',$state_value): $state_value):''));
 $full_location =   (($full_location!='')?$full_location.",":"").get_name_from_table(COUNTRIES_TABLE,TEXT_LANGUAGE.'country_name', 'id', $country1);
 $full_location = preg_replace(" [,+]",",",$full_location);
 $hidden_fields.=tep_draw_input_field('full_location', $full_location,'',false,'hidden');
 $template->assign_vars(array(
  'HEADING_TITLE'=>HEADING_TITLE,
  'update_message'=>$messageStack->output(),
  'preview_job_form'=>$preview_job_form,
  'buttons'=>$buttons,
  'INFO_TEXT_JOB_TITLE'=>INFO_TEXT_JOB_TITLE,
  'INFO_TEXT_JOB_TITLE1'=>$TR_job_title,
  'INFO_TEXT_JOB_TITLE'=>INFO_TEXT_JOB_TITLE,
  'INFO_TEXT_JOB_REF'=>INFO_TEXT_JOB_REF,
  'INFO_TEXT_JOB_REF1'=>(tep_not_null($job_reference)?tep_db_output($job_reference):INFO_TEXT_NOT_MENTIONED),
  'INFO_TEXT_COUNTRY'=>INFO_TEXT_COUNTRY,
  'INFO_TEXT_COUNTRY1'=>get_name_from_table(COUNTRIES_TABLE,TEXT_LANGUAGE.'country_name', 'id', $country1),
  'INFO_TEXT_STATE'=>INFO_TEXT_STATE,
  'INFO_TEXT_STATE1'=>(tep_not_null($state_value)?(is_numeric($state_value)?get_name_from_table(ZONES_TABLE,TEXT_LANGUAGE.'zone_name', 'zone_id',$state_value): $state_value):INFO_TEXT_NOT_MENTIONED),
  'INFO_TEXT_LOCATION'=>INFO_TEXT_LOCATION,
  'INFO_TEXT_LOCATION1'=>(tep_not_null($location)?tep_db_output($location):INFO_TEXT_NOT_MENTIONED),
  'INFO_TEXT_SALARY'=>INFO_TEXT_SALARY,
  'INFO_TEXT_SALARY1'=>(tep_not_null($salary)?$sym_left.tep_db_output($salary).$sym_rt:INFO_TEXT_NOT_MENTIONED),
  'INFO_TEXT_SKILLS'=>INFO_TEXT_SKILLS,
  'INFO_TEXT_SKILLS1'=>(tep_not_null($skills)?tep_db_output($skills):INFO_TEXT_NOT_MENTIONED),
  'INFO_TEXT_INDUSTRY_SECTOR'=>INFO_TEXT_INDUSTRY_SECTOR,
  'INFO_TEXT_INDUSTRY_SECTOR1'=>get_name_from_table(JOB_CATEGORY_TABLE,TEXT_LANGUAGE.'category_name','id',$post_job_category),
  //'INFO_TEXT_INDUSTRY_SECTOR1'=>job_category_checkbox($post_job_category ,'post_job_category[]',true),
  'INFO_TEXT_VACANCY_SUMMARY'=>INFO_TEXT_VACANCY_SUMMARY,
  'INFO_TEXT_VACANCY_SUMMARY1'=>nl2br($TR_job_summary),
  'INFO_TEXT_DESCRIPTION'=>INFO_TEXT_DESCRIPTION,

  'INFO_TEXT_JOB_RESP'=>INFO_TEXT_JOB_RESP,
  'INFO_TEXT_JOB_RESP1'=>nl2br($responsibility),
  'INFO_TEXT_JOB_QUAL'=>INFO_TEXT_JOB_QUAL,
  'INFO_TEXT_JOB_QUAL1'=>nl2br($qualification),
  'INFO_TEXT_JOB_BENEFIT'=>INFO_TEXT_JOB_BENEFIT,
  'INFO_TEXT_JOB_BENEFIT1'=>nl2br($benefits),
  'INFO_TEXT_JOB_SCHEDULE'=>INFO_TEXT_JOB_SCHEDULE,
  'INFO_TEXT_JOB_SCHEDULE1'=>$schedule,

  'INFO_TEXT_DESCRIPTION1'=>nl2br($description),
  'INFO_TEXT_APPLICATION_GOTO'=>((($post_url=='Yes') && (strlen($url)>6))? INFO_TEXT_APPLICATION_GOTO_URL :INFO_TEXT_APPLICATION_GOTO),
  'INFO_TEXT_APPLICATION_GOTO1'=>((($post_url=='Yes') && (strlen($url)>6))? '<span style="color:blue;">'.$url.'</span>' :$TREF_email_address),
  'INFO_TEXT_JOB_TYPE'=>INFO_TEXT_JOB_TYPE,
  'INFO_TEXT_JOB_TYPE1'=>$job_type,
  'INFO_TEXT_RELOCATE'=>INFO_TEXT_RELOCATE,
  'INFO_TEXT_RELOCATE1'=>$relocate,
  'INFO_TEXT_EXPERIENCE'=>INFO_TEXT_EXPERIENCE,
  'INFO_TEXT_EXPERIENCE1'=>$experience_string,
  'INFO_TEXT_ADVERTISE_WEEKS'=>(($adminedit==true || $edit==false)?INFO_TEXT_ADVERTISE_WEEKS:''),
  'INFO_TEXT_ADVERTISE_WEEKS1'=>(($adminedit==true || $edit==false)?$expired." ( yyyy-mm-dd ) ":''),
  'INFO_TEXT_ADVERTISE_DATE'=>(($adminedit==true || $edit==false)?INFO_TEXT_ADVERTISE_DATE:''),
  'INFO_TEXT_ADVERTISE_DATE1'=>(($adminedit==true || $edit==false)?$adv_date." ( yyyy-mm-dd ) ":''),
  'INFO_TEXT_JOB_AUTO_RENEW'=>INFO_TEXT_JOB_AUTO_RENEW,
  'INFO_TEXT_JOB_AUTO_RENEW1'=>get_auto_renew_name($job_auto_renew),
  'INFO_TEXT_JOBFAIR'=>INFO_TEXT_JOBFAIR,
  'INFO_TEXT_JOBFAIR1'=>($jobfair>0?'<div class="form-group row align-items-center">
	<label for="staticEmail" class="col-sm-3 col-form-label font-weight-bold">'.INFO_TEXT_JOBFAIR.' </label>
    <div class="col-sm-9">'.get_name_from_table(JOBFAIR_TABLE,'jobfair_title','id',$post_jobfair).'</div>
	</div>':''),

  'hidden_fields'=>$hidden_fields,
  'LEFT_BOX_WIDTH'=>LEFT_BOX_WIDTH1,
  'RIGHT_BOX_WIDTH'=>RIGHT_BOX_WIDTH1,
  'LEFT_HTML'=>'',
  'RIGHT_HTML'=>RIGHT_HTML));
 $template->pparse('preview_job');
}
else
{
 $template->assign_vars(array(
  'HEADING_TITLE'=>HEADING_TITLE,
  'REQUIRED_INFO'=>REQUIRED_INFO,
  'buttons'=>$buttons,
  'post_job_form'=>$post_job_form,
  'preview_job_form'=>$preview_job_form,
  'INFO_TEXT_JOB_TITLE'=>INFO_TEXT_JOB_TITLE,
  'INFO_TEXT_JOB_TITLE1'=>tep_draw_input_field('TR_job_title', $TR_job_title,'class="form-control required"'),
  'INFO_TEXT_JOB_REF'=>INFO_TEXT_JOB_REF,
  'INFO_TEXT_JOB_REF1'=>tep_draw_input_field('job_reference', $job_reference,'class="form-control"'),
  'INFO_TEXT_COUNTRY'=>INFO_TEXT_COUNTRY,
  'INFO_TEXT_COUNTRY1'=>tep_get_country_list('country1',$country1,'class="form-select"'),
  'INFO_TEXT_STATE'=>INFO_TEXT_STATE,
  'INFO_TEXT_STATE1'=>INFO_TEXT_STATE1,
  'INFO_TEXT_LOCATION'=>INFO_TEXT_LOCATION,
  'INFO_TEXT_LOCATION1'=>tep_draw_input_field('location', $location,'class="form-control"',false),
  'INFO_TEXT_SALARY'=>INFO_TEXT_SALARY,
  'INFO_TEXT_SALARY1'=>$sym_left.tep_draw_input_field('salary', $salary,'class="form-control"',false).$sym_rt,
  'INFO_TEXT_SKILLS'=>INFO_TEXT_SKILLS,
  'INFO_TEXT_SKILLS1'=>tep_draw_input_field('skills', $skills,'class="form-control" "',false),
  'INFO_TEXT_SEPARATED'=> INFO_TEXT_SEPARATED,
  'INFO_TEXT_INDUSTRY_SECTOR'=>INFO_TEXT_INDUSTRY_SECTOR,
  'INFO_TEXT_INDUSTRY_SECTOR1'=>get_drop_down_list(JOB_CATEGORY_TABLE,"name='TR_post_job_category[]' class='form-control required h-100' multiple ","","0",$post_job_category),
  //'INFO_TEXT_INDUSTRY_SECTOR1'=>job_category_checkbox($post_job_category ,'post_job_category[]'),//get_drop_down_list(DIVING_CATEGORY_TABLE,"name='TR_job_category[]' size='6' multiple","All Job Categorys","0",$TR_job_category),
  'INFO_TEXT_VACANCY_SUMMARY'=>INFO_TEXT_VACANCY_SUMMARY,
  'INFO_TEXT_VACANCY_SUMMARY1'=>tep_draw_textarea_field('TR_job_summary', 'soft', '68', '4', $TR_job_summary, 'class="form-control required h-100"', true),
  'INFO_TEXT_DESCRIPTION'=>INFO_TEXT_DESCRIPTION,
  'INFO_TEXT_DESCRIPTION1'=>tep_draw_textarea_field('description', 'soft', '190', '10', $description, 'id="description" class="form-control7"', '', true),

  'INFO_TEXT_JOB_RESP'=>INFO_TEXT_JOB_RESP,
  'INFO_TEXT_JOB_RESP1'=>tep_draw_textarea_field('responsibility', 'soft', '50', '2', $responsibility, 'class="form-control h-50"', false),
  'INFO_TEXT_JOB_QUAL'=>INFO_TEXT_JOB_QUAL,
  'INFO_TEXT_JOB_QUAL1'=>tep_draw_textarea_field('TR_qualification', 'soft', '50', '2', $TR_qualification, 'class="form-control required h-50"', true),
  'INFO_TEXT_JOB_BENEFIT'=>INFO_TEXT_JOB_BENEFIT,
  'INFO_TEXT_JOB_BENEFIT1'=>tep_draw_textarea_field('benefits', 'soft', '50', '2', $benefits, 'class="form-control h-50"', false),
  'INFO_TEXT_JOB_SCHEDULE'=>INFO_TEXT_JOB_SCHEDULE,
  'INFO_TEXT_JOB_SCHEDULE1'=>tep_draw_input_field('schedule', $schedule,'class="form-control" "',false),

  'INFO_TEXT_JOBFAIR'=>INFO_TEXT_JOBFAIR,
  'INFO_TEXT_JOBFAIR1'=>LIST_SET_DATA(RECRUITER_JOBFAIR_TABLE." as rjf left join ".JOBFAIR_TABLE." as jf on rjf.jobfair_id=jf.id"," where recruiter_id='".$_SESSION['sess_recruiterid']."' and approved='Yes'",'jobfair_title','jobfair_id',"jobfair_title",'name="post_jobfair[]" class="form-control"',"Select Jobfair",'',$post_jobfair),
'JOBFAIR_TEXT'=>$jobfair_text,

  'INFO_TEXT_APPLICATION_GOTO'=>INFO_TEXT_APPLICATION_GOTO,
  'INFO_TEXT_APPLICATION_GOTO2'=>INFO_TEXT_APPLICATION_GOTO2,
  'INFO_TEXT_APPLICATION_GOTO1'=>$email_list,
  'INFO_TEXT_JOB_TYPE'=>INFO_TEXT_JOB_TYPE,
  'INFO_TEXT_JOB_TYPE1'=>LIST_TABLE(JOB_TYPE_TABLE,TEXT_LANGUAGE."type_name","","name='job_type1[]' class='form-select h-100' multiple",INFO_TEXT_ALL_JOB_TYPE,"0",$job_type),
  'INFO_TEXT_RELOCATE'=>INFO_TEXT_RELOCATE,
  'INFO_TEXT_RELOCATE1'=>tep_draw_radio_field('relocate', 'Yes', '', $relocate, 'id="radio_relocate1"').'&nbsp;<label for="radio_relocate1">'.INFO_TEXT_YES.'</label>&nbsp;'.tep_draw_radio_field('relocate', 'No', '', $relocate, 'id="radio_relocate2"').'&nbsp;<label for="radio_relocate2">'.INFO_TEXT_NO.'</label>',
  'INFO_TEXT_EXPERIENCE'=>INFO_TEXT_EXPERIENCE,
  'INFO_TEXT_EXPERIENCE1'=>experience_drop_down('name="TR_experience" class="form-select"', INFO_TEXT_ANY_EXPERIENCE, '0', $TR_experience),
  'INFO_TEXT_ADVERTISE_WEEKS'=>(($adminedit==true || $edit==false)?INFO_TEXT_ADVERTISE_WEEKS:''),
  'INFO_TEXT_ADVERTISE_WEEKS1'=>(($adminedit==true || $edit==false)?datelisting($expired,"name='TR_Date' class='form-select required'","name='TR_Month' class='form-select required'","name='TR_Year' class='form-select required'",$startYear,$endYear,false):''),
  //(($adminedit==true || $edit==false)?tep_draw_pull_down_menu('TR_vacancy_period', $vacancy_period_array, $TR_vacancy_period).'&nbsp;<span class="inputRequirement">*</span>':''),
  'INFO_TEXT_ADVERTISE_DATE'=>(($adminedit==true || $edit==false)?INFO_TEXT_ADVERTISE_DATE:''),
  'INFO_TEXT_ADVERTISE_DATE1'=>(($adminedit==true || $edit==false)?datelisting($adv_date,"name='TR_date' class='form-select required'","name='TR_month' class='form-select required'","name='TR_year' class='form-select required'",$startYear,$endYear,false):''),
  'INFO_TEXT_POST_URL'      => INFO_TEXT_POST_URL,
  'INFO_TEXT_POST_URL1'     => '<div class="form-check">
                              '.tep_draw_checkbox_field('post_url', 'Yes', '',$post_url,'onclick="show_hide()" class="form-check-input" id="checkbox_url"').'
                              <label class="form-check-label" for="checkbox_url"></label>
                            </div>',
  'INFO_TEXT_JOB_AUTO_RENEW'=>INFO_TEXT_JOB_AUTO_RENEW,
  'INFO_TEXT_JOB_AUTO_RENEW1'=>list_job_auto_renew("name='job_auto_renew' class='form-select'",'','',$job_auto_renew),
  'SHOW_TEXT_BOX'           => $show_text_box,
  'SCRIPT'                  => country_state($c_name='country1',$c_d_value=INFO_TEXT_PLEASE_SELECT,$s_name='state',$s_d_value='state','zone_id',$state_value),
  'INFO_TEXT_JSCRIPT_FILE'  =>$jscript_file,
  'HOST_NAME'            => HOST_NAME,
  'LEFT_BOX_WIDTH'=>LEFT_BOX_WIDTH1,
  'RIGHT_BOX_WIDTH'=>RIGHT_BOX_WIDTH1,
  'LEFT_HTML'=>'',
  'RIGHT_HTML'=>RIGHT_HTML,
  'update_message'=>$messageStack->output()));
  $template->pparse('post_job');
}
?>