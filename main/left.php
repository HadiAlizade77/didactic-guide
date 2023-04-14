<?php
if(!((strtolower($_SERVER['PHP_SELF'])==("/".PATH_TO_MAIN.FILENAME_INDEX)) || (strtolower($_SERVER['PHP_SELF'])==("/".PATH_TO_MAIN.FILENAME_ABOUT_US)) || (strtolower($_SERVER['PHP_SELF'])==("/".PATH_TO_MAIN.FILENAME_PRIVACY)) || (strtolower($_SERVER['PHP_SELF'])==("/".PATH_TO_MAIN.FILENAME_TERMS)) || (strtolower($_SERVER['PHP_SELF'])==("/".PATH_TO_MAIN.FILENAME_ARTICLE)) || (strtolower($_SERVER['PHP_SELF'])==("/".PATH_TO_MAIN.FILENAME_SITE_MAP)) || (strtolower($_SERVER['PHP_SELF'])==("/".PATH_TO_MAIN.FILENAME_CONTACT_US)) || (strtolower($_SERVER['PHP_SELF'])==("/".PATH_TO_MAIN.FILENAME_INDUSTRY_RSS)) || (strtolower($_SERVER['PHP_SELF'])==("/".PATH_TO_MAIN.FILENAME_FAQ))))
{
  ////// Recruiter starts///////
  if(check_login('recruiter'))
  {
    $today=date("Y-m-d H:i:s");
    $no_of_save_resume=no_of_records(SAVE_RESUME_TABLE," recruiter_id ='".$_SESSION['sess_recruiterid']."'",'id');
    $no_of_save_search=no_of_records(SEARCH_RESUME_RESULT_TABLE," recruiter_id ='".$_SESSION['sess_recruiterid']."'",'id');
    $no_of_news_letters=no_of_records(NEWSLETTERS_HISTORY_TABLE," send_to ='recruiter'",'id');
    $no_of_active_job=no_of_records(JOB_TABLE," recruiter_id ='".$_SESSION['sess_recruiterid']."' and re_adv <= '".$today."' and expired >= '".$today."' and deleted is NULL",'job_id');
    $no_of_expired_job=no_of_records(JOB_TABLE," recruiter_id ='".$_SESSION['sess_recruiterid']."' and re_adv <= '".$today."' and expired <= '".$today."' and deleted is NULL",'job_id');
    $no_of_job=(int)no_of_records(JOB_TABLE," recruiter_id ='".$_SESSION['sess_recruiterid']."'",'job_id');
    $no_of_applicant=(int)no_of_records(APPLICATION_TABLE." as a  left outer join ".JOB_TABLE." as jb on (a.job_id=jb.job_id)"," jb.recruiter_id ='".$_SESSION['sess_recruiterid']."' ",'a.id');
    $no_of_selectd_applicant=(int)no_of_records(APPLICATION_TABLE." as a  left outer join ".JOB_TABLE." as jb on (a.job_id=jb.job_id)","a.applicant_select='Yes' and  jb.recruiter_id ='".$_SESSION['sess_recruiterid']."' ",'a.id');
    $no_of_contact=no_of_records(USER_CONTACT_TABLE,"user_id='".$_SESSION['sess_recruiterid']."' and user_type='recruiter'",'id');
    $no_of_user=no_of_records(RECRUITER_USERS_TABLE,"recruiter_id='".$_SESSION['sess_recruiterid']."' ",'id');
    /////////////////////
    $row_contact=getAnyTableWhereData(RECRUITER_LOGIN_TABLE." as rl left join  ".RECRUITER_TABLE." as r on (rl.recruiter_id=r.recruiter_id) left join  ".COUNTRIES_TABLE." as c on (r.recruiter_country_id=c.id) left join ".ZONES_TABLE." as z on(r.recruiter_state_id=z.zone_id or z.zone_id is NULL)"," rl.recruiter_id ='".$_SESSION['sess_recruiterid']."'","r.recruiter_first_name,r.recruiter_last_name,r.recruiter_logo,r.recruiter_company_name,r.recruiter_address1,r.recruiter_address2,c.country_name,if(r.recruiter_state_id,z.zone_name,r.recruiter_state) as location,r.recruiter_state_id,r.recruiter_state,r.recruiter_city,r.recruiter_zip,r.recruiter_telephone,r.fax,r.recruiter_url,rl.recruiter_email_address");

    $post_a_job     = '<a class="small text-muted" href="'.tep_href_link(FILENAME_RECRUITER_POST_JOB).'" title="'.INFO_TEXT_POST_A_JOB.'">'.INFO_TEXT_POST_A_JOB.'</a>';
    $list_of_jos    = '<a class="small text-muted" href="'.tep_href_link(FILENAME_RECRUITER_LIST_OF_JOBS).'" title="'.INFO_TEXT_L_LIST_OF_JOBS.'" >'.INFO_TEXT_L_LIST_OF_JOBS.'</a>';
    $active_jobs    = '<a class="small text-muted" href="'.tep_href_link(FILENAME_RECRUITER_LIST_OF_JOBS).'" title="'.INFO_TEXT_L_ACTIVE_JOBS.'" >'.INFO_TEXT_L_ACTIVE_JOBS.'</a> '.(($no_of_active_job>0)?'('.$no_of_active_job.')':'');
    $expired_jobs   = '<a class="small text-muted" href="'.tep_href_link(FILENAME_RECRUITER_LIST_OF_JOBS,'j_status=expired').'" title="'.INFO_TEXT_L_EXPIRED_JOBS.'" >'.INFO_TEXT_L_EXPIRED_JOBS.'</a> '.(($no_of_expired_job>0)?'('.$no_of_expired_job.')':'');
    $import_multiple_jobs = '<a class="small text-muted" href="'.tep_href_link(FILENAME_RECRUITER_IMPORT_JOBS).'" title="'.INFO_TEXT_L_IMPORT_MULTIPLE_JOBS.'" >'.INFO_TEXT_L_IMPORT_MULTIPLE_JOBS.'</a> ';

    $search_resumes = '<a class="small text-muted" href="'.tep_href_link(FILENAME_RECRUITER_SEARCH_RESUME).'" title="'.INFO_TEXT_L_SEARCH_RESUMES.'" >'.INFO_TEXT_L_SEARCH_RESUMES.'</a>';
    $search_applicant = '<a class="small text-muted" href="'.tep_href_link(FILENAME_RECRUITER_SEARCH_APPLICANT).'" title="'.INFO_TEXT_L_SEARCH_APPLICANT.'" >'.INFO_TEXT_L_SEARCH_APPLICANT.'</a>';
    $resume_search_agents = '<a class="small text-muted" href="'.tep_href_link(FILENAME_RECRUITER_LIST_OF_RESUME_SEARCH_AGENTS).'" title="'.INFO_TEXT_L_RESUME_SEARCH_AGENTS.'" >'.INFO_TEXT_L_RESUME_SEARCH_AGENTS.'</a> '.(($no_of_save_search>0)?'('.$no_of_save_search.')':'');
    $my_saved_resumes     = '<a class="small text-muted" href="'.tep_href_link(FILENAME_RECRUITER_SAVE_RESUME).'" title="'.INFO_TEXT_MY_SAVED_RESUMES.'" >'.INFO_TEXT_MY_SAVED_RESUMES.'</a> '.(($no_of_save_resume>0)?'('.$no_of_save_resume .')':'');
    $edit_profile  = '<a class="small text-muted" href="'.tep_href_link(FILENAME_RECRUITER_REGISTRATION).'" title="'.INFO_TEXT_L_EDIT_PROFILE.'" >'.INFO_TEXT_L_EDIT_PROFILE.'</a>';
    $company_description = '<a class="small text-muted" href="'.tep_href_link(FILENAME_RECRUITER_COMPANY_DESCRIPTION).'" title="'.INFO_TEXT_L_COMPANY_DESCRIPTION.'" >'.INFO_TEXT_L_COMPANY_DESCRIPTION.'</a>';
    $order_history   = '<a class="small text-muted" href="'.tep_href_link(FILENAME_RECRUITER_ACCOUNT_HISTORY_INFO).'" title="'.INFO_TEXT_L_ORDER_HISTORY.'" >'.INFO_TEXT_L_ORDER_HISTORY.'</a>';
    $manage_users    = '<a class="small text-muted" href="'.tep_href_link(FILENAME_RECRUITER_LIST_OF_USERS).'" title="'.INFO_TEXT_MANAGE_USERS.'" >'.INFO_TEXT_MANAGE_USERS.'</a> '.(($no_of_user>0)?'('.$no_of_user.')':'');
    $contact_list    = '<a class="small text-muted" href="'.tep_href_link(FILENAME_RECRUITER_CONTACT_LIST).'" title="'.INFO_TEXT_L_CONTACT_LIST.'" >'.INFO_TEXT_L_CONTACT_LIST.'</a>'.(($no_of_contact>0)?'('.$no_of_contact.')':'');
    $news_letter     = '<a class="small text-muted" href="'.tep_href_link(FILENAME_LIST_OF_NEWSLETTERS).'" title="'.INFO_TEXT_L_NEWS_LETTER.'" >'.INFO_TEXT_L_NEWS_LETTER.'</a> '.(($no_of_news_letters>0)?'('.$no_of_news_letters.')':'');
    $change_password = '<a class="small text-muted" href="'.tep_href_link(FILENAME_RECRUITER_CHANGE_PASSWORD).'" title="'.INFO_TEXT_L_CHANGE_PASSWORD.'" >'.INFO_TEXT_L_CHANGE_PASSWORD.'</a>';
    $log_out         = '<a class="small text-muted" href="'.tep_href_link(FILENAME_LOGOUT).'" title="'.INFO_TEXT_L_LOG_OUT.'" >'.INFO_TEXT_L_LOG_OUT.'</a>';
    $rate_card       = '<a class="small text-muted" href="'.tep_href_link(FILENAME_RECRUITER_RATES).'" title="'.INFO_TEXT_RATE_CARD.'" >'.INFO_TEXT_RATE_CARD.'</a>';

    $total_jobs_posted = '<div class="small text-muted">'.INFO_TEXT_TOTAL_JOBS_POSTED." : ".$no_of_job.'</div>';
    $total_applicants  = '<div class="small text-muted">'.INFO_TEXT_TOTAL_APPLICANTS." : ".$no_of_applicant.'</div>';
    $selected_applicant = '<div class="small text-muted">'.INFO_TEXT_L_SELECTED_APPLICANT." : ".$no_of_selectd_applicant.'</div>';
  }
  ////// Recruiter ends///////


  if(check_login('recruiter'))
  define('LEFT_HTML','

  <div class="card card-custom mb-3 for-mobile">
  <div class="card-body card-body-custom">
  <div class="fw-bold mb-0">'.INFO_TEXT_L_JOB_POSTING.'</div>
  <div >'.$post_a_job.'</div>
  <div>'.$list_of_jos.'</div>
  <div>'.$active_jobs.'</div>
  <div>'.$expired_jobs.'</div>
  <div>'.$import_multiple_jobs.'</div>

  <div class="fw-bold mt-3">'.INFO_TEXT_L_MY_ACCOUNT.'</div>
  <div>'.$edit_profile.'</div>
  <div>'.$company_description.'</div>
  <div>'.$order_history.'</div>
  <div>'.$manage_users.'</div>
  <div>'.$contact_list.'</div>
  <div>'.$news_letter.'</div>
  <div>'.$change_password.'</div>
  <div>'.$log_out.'</div>

  <div class="fw-bold mt-3">'.INFO_TEXT_SEARCH_RESUME.'</div>
  <div>'.$search_resumes.'</div>
  <div>'.$search_applicant.'</div>
  <div>'.$resume_search_agents.'</div>
  <div>'.$my_saved_resumes.'</div>
  <div>'.$rate_card.'</div>

  <div class="fw-bold mt-3">'.INFO_TEXT_L_CURRENT_STATUS.'</div>
  <div>'.$total_jobs_posted.'</div>
  <div>'.$total_applicants.'</div>
  <div>'.$selected_applicant.'</div>
  </div>
  </div>



  ');
  else
  define('LEFT_HTML','');

  //////Jobseeker starts///////
  if(check_login('jobseeker'))
  {
    $no_of_applications=no_of_records(APPLY_TABLE.' as a, '.JOB_TABLE." as j","a.job_id=j.job_id and a.jobseeker_id='".$_SESSION['sess_jobseekerid']."' and jobseeker_apply_status='active'",'a.id');
    $no_of_cover_letters=no_of_records(JOBSEEKER_LOGIN_TABLE . " as jl, ".COVER_LETTER_TABLE." as c","jl.jobseeker_id='".$_SESSION['sess_jobseekerid']."' and jl.jobseeker_id=c.jobseeker_id",'c.cover_letter_id');
    $no_of_saved_searches=no_of_records(SEARCH_JOB_RESULT_TABLE . " as sr ","sr.jobseeker_id='".$_SESSION['sess_jobseekerid']."'",'sr.id');
    $no_of_saved_jobs=no_of_records(SAVE_JOB_TABLE . " as s, ".JOB_TABLE." as j, ".RECRUITER_TABLE." as r, ".RECRUITER_LOGIN_TABLE." as rl","s.jobseeker_id='".$_SESSION['sess_jobseekerid']."' and s.job_id=j.job_id and j.recruiter_id=rl.recruiter_id and j.recruiter_id=r.recruiter_id and rl.recruiter_status='Yes'",'s.id');
    $no_of_resumes=no_of_records(JOBSEEKER_RESUME1_TABLE . " as j1","j1.jobseeker_id='".$_SESSION['sess_jobseekerid']."'",'j1.jobseeker_id');
    $no_of_unread_mail=no_of_records(APPLICANT_INTERACTION_TABLE." as ai left join ".APPLICATION_TABLE."  as a on (a.id=ai.application_id) ","a.jobseeker_id='".$_SESSION['sess_jobseekerid']."' and ai.receiver_mail_status='active'  and ai.user_see ='No' and  sender_user='recruiter'",'ai.id');
    $no_of_contact=no_of_records(USER_CONTACT_TABLE,"user_id='".$_SESSION['sess_jobseekerid']."' and user_type='jobseeker'",'id');
    $no_of_companies=no_of_records(RECRUITER_LOGIN_TABLE . " as r1","r1.recruiter_id");


    $table_names1=JOBSEEKER_RESUME1_TABLE." as jr1 ";
    $whereClause1.="jr1.jobseeker_id='".$_SESSION['sess_jobseekerid']."' order by jr1.inserted desc";
    $field_names1="jr1.resume_id,jr1.resume_title,jr1.inserted,jr1.updated,jr1.availability_date,jr1.search_status ";//;,sum(rs.viewed) as viewed";

    $resume_query_raw="select $field_names1 from $table_names1 where $whereClause1";
    $resume_query = tep_db_query($resume_query_raw);
    $resume_query_numrows=tep_db_num_rows($resume_query);
    $available_status='';
    if($resume_query_numrows > 0)
    {
      while ($resume = tep_db_fetch_array($resume_query))
      {
        if(tep_not_null($resume['availability_date']))
        {
          $available_status='<a href="' . tep_href_link(FILENAME_JOBSEEKER_CONTROL_PANEL, 'action=available_inactive') . '">' . tep_image(PATH_TO_IMAGE.'icon_status_red_light.gif', INFO_TEXT_L_STATUS_NOT_AVAILABLE, 15, 9) . '</a>&nbsp;' . tep_image(PATH_TO_IMAGE.'icon_status_green.gif', INFO_TEXT_L_STATUS_AVAILABLE, 15, 9);
        }
        else
        {
          $available_status=tep_image(PATH_TO_IMAGE.'icon_status_red.gif', INFO_TEXT_L_STATUS_NOT_AVAILABLITY, 15, 9) . '&nbsp;<a href="' . tep_href_link(FILENAME_JOBSEEKER_CONTROL_PANEL, 'action=available_active') . '">' . tep_image(PATH_TO_IMAGE.'icon_status_green_light.gif', INFO_TEXT_L_STATUS_AVAILABLITY, 15, 9) . '</a>';
        break;
      }
    }
  }

  $action=((isset($_GET['action']) && ($_GET['action']=='available_active' || $_GET['action']=='available_inactive'))?$_GET['action']:'');
  {
    $action = $_GET['action'] ;
  }
  if(tep_not_null($action))
  {
    switch($action)
    {
      case 'available_active':
        case 'available_inactive':
          if($action=='available_active')
          {
            tep_db_query("update ".JOBSEEKER_RESUME1_TABLE." set availability_date=now() where jobseeker_id='".$_SESSION['sess_jobseekerid']."'");
            $messageStack->add_session(MESSAGE_SUCCESS_UPDATED_AVAILABLE, 'success');
          }
          else
          {
            tep_db_query("update ".JOBSEEKER_RESUME1_TABLE." set availability_date=NULL where jobseeker_id='".$_SESSION['sess_jobseekerid']."'");
            $messageStack->add_session(MESSAGE_SUCCESS_UPDATED_NOT_AVAILABLE, 'success');
          }
          tep_redirect(FILENAME_JOBSEEKER_CONTROL_PANEL);
        break;
      }
    }
    $row_contact1=getAnyTableWhereData(JOBSEEKER_LOGIN_TABLE." as jl left join  ".JOBSEEKER_TABLE." as j on (jl.jobseeker_id=j.jobseeker_id) left join  ".COUNTRIES_TABLE." as c on (j.jobseeker_country_id=c.id) left join ".ZONES_TABLE." as z on(j.jobseeker_state_id=z.zone_id or z.zone_id is NULL)"," jl.jobseeker_id ='".$_SESSION['sess_jobseekerid']."'","j.jobseeker_first_name,j.jobseeker_last_name,j.jobseeker_address1,j.jobseeker_address2,c.country_name,if(j.jobseeker_state_id,z.zone_name,j.jobseeker_state) as location,j.jobseeker_city,j.jobseeker_zip,j.jobseeker_phone,j.jobseeker_mobile,jl.jobseeker_email_address");

    $add_resume= '<a class="small text-muted" href="'.tep_href_link(FILENAME_JOBSEEKER_RESUME1).'" title="'.INFO_TEXT_L_ADD_RESUMES.'">'.INFO_TEXT_L_ADD_RESUMES.'</a>';
    $my_resumes= '<a class="small text-muted" href="'.tep_href_link(FILENAME_JOBSEEKER_LIST_OF_RESUMES).'" title="'.INFO_TEXT_MY_RESUMES.'">'.INFO_TEXT_MY_RESUMES.'</a>'.(($no_of_resumes>0)?"(".$no_of_resumes.")":" ");
    $set_status= '<a class="small text-muted" href="'.tep_href_link(FILENAME_JOBSEEKER_CONTROL_PANEL, 'action=available_active').'" title="'.INFO_TEXT_SET_STATUS_AS_AVAILALE_NOW.'">'.INFO_TEXT_SET_STATUS_AS_AVAILALE_NOW.'</a> '.$available_status.'';

    $my_saved_jobs = '<a class="small text-muted" href="'.tep_href_link(FILENAME_JOBSEEKER_LIST_OF_SAVED_JOBS).'" title="'.INFO_TEXT_MY_SAVED_JOBS.'">'.INFO_TEXT_MY_SAVED_JOBS.'</a>'.(($no_of_saved_jobs>0)?'('.$no_of_saved_jobs.')':'');
    $my_applications= '<a class="small text-muted" href="'.tep_href_link(FILENAME_JOBSEEKER_LIST_OF_APPLICATIONS).'" title="'.INFO_TEXT_MY_APPLICATIONS.'">'.INFO_TEXT_MY_APPLICATIONS.'</a>'.(($no_of_applications>0)?"(".$no_of_applications.")":"");
    $response_from_employer = '<a class="small text-muted" href="'.tep_href_link(FILENAME_JOBSEEKER_MAILS).'" title="'.INFO_TEXT_RESPONSE_FROM_EMPLOYER.'">'.INFO_TEXT_RESPONSE_FROM_EMPLOYER.'</a>'.(($no_of_unread_mail>0)?"(".$no_of_unread_mail.")":"");

    $edit_personal_details  = '<a class="small text-muted" href="'.tep_href_link(FILENAME_JOBSEEKER_REGISTER1).'" title="'.INFO_TEXT_L_EDIT_PERSONAL_DETAILS.'">'.INFO_TEXT_L_EDIT_PERSONAL_DETAILS.' </a>';
    $my_cover_letters= '<a class="small text-muted" href="'.tep_href_link(FILENAME_JOBSEEKER_LIST_OF_COVER_LETTERS).'" title="'.INFO_TEXT_MY_COVER_LETTERS.'">'.INFO_TEXT_MY_COVER_LETTERS.'</a>'.(($no_of_cover_letters>0)?"(".$no_of_cover_letters.")":"");
    $change_password = '<a class="small text-muted" href="'.tep_href_link(FILENAME_JOBSEEKER_CHANGE_PASSWORD).'" title="'.INFO_TEXT_L_CHANGE_PASSWORD.'">'.INFO_TEXT_L_CHANGE_PASSWORD.'</a>';
    $newsletters = '<a class="small text-muted" href="'.tep_href_link(FILENAME_LIST_OF_NEWSLETTERS).'" title="'.INFO_TEXT_NEWSLETTERS.'">'.INFO_TEXT_NEWSLETTERS.'</a>';
    $contact_list = '<a class="small text-muted" href="'.tep_href_link(FILENAME_RECRUITER_CONTACT_LIST).'" title="'.INFO_TEXT_L_CONTACT_LIST.'">'.INFO_TEXT_L_CONTACT_LIST.'</a>'.(($no_of_contact>0)?"(".$no_of_contact.")":"");
    $video_resume = '<a class="small text-muted" href="'.tep_href_link(FILENAME_JOBSEEKER_LIST_OF_RESUMES).'"  title="'.INFO_TEXT_L_VIDEO_RESUME.'">'.INFO_TEXT_L_VIDEO_RESUME.'</a>';

    $search_jobs = '<a class="small text-muted" href="'.tep_href_link(FILENAME_JOB_SEARCH).'" title="'.INFO_TEXT_L_SEARCH_JOBS.'">'.INFO_TEXT_L_SEARCH_JOBS.'</a>';
    $jobs_by_location= '<a class="small text-muted" href="'.tep_href_link(FILENAME_JOB_SEARCH_BY_LOCATION).'"  title="'.INFO_TEXT_JOBS_BY_LOCATION.'">'.INFO_TEXT_JOBS_BY_LOCATION.'</a>';
    $jobs_by_map= (GOOGLE_MAP=='true'?'<div><i class="fa fa-angle-right" aria-hidden="true"></i> <a href="'.tep_href_link(FILENAME_JOB_BY_MAP).'" title="'.INFO_TEXT_JOBS_BY_MAP.'">'.INFO_TEXT_JOBS_BY_MAP.'</a></div>':'');
    $jobs_by_category = '<a class="small text-muted" href="'.tep_href_link(FILENAME_JOB_SEARCH_BY_INDUSTRY).'" title="'.INFO_TEXT_JOBS_BY_INDUSTRY.'">'.INFO_TEXT_JOBS_BY_INDUSTRY.'</a>';
    $jobs_by_skill = '<a class="small text-muted" href="'.tep_href_link(FILENAME_JOB_SEARCH_BY_SKILL).'" title="'.INFO_TEXT_JOBS_BY_SKILL.'">'.INFO_TEXT_JOBS_BY_SKILL.'</a>';
    $jobs_by_companies = '<a class="small text-muted" href="'.tep_href_link(FILENAME_JOBSEEKER_COMPANY_PROFILE).'" title="'.INFO_TEXT_JOBS_BY_COMPANIES.'">'.INFO_TEXT_JOBS_BY_COMPANIES.'</a>'.(($no_of_companies>0)?"(".$no_of_companies.")":'');
    $my_saved_searches = '<a class="small text-muted" href="'.tep_href_link(FILENAME_JOBSEEKER_LIST_OF_SAVED_SEARCHES).'"  title="'.INFO_TEXT_MY_SAVED_SEARCHES.'">'.INFO_TEXT_MY_SAVED_SEARCHES.'</a>'.(($no_of_saved_searches>0)?"(".$no_of_saved_searches.")":'');
    $job_alert_agent = '<a class="small text-muted" href="'.tep_href_link(FILENAME_JOBSEEKER_LIST_OF_SAVED_SEARCHES).'"  title="'.INFO_TEXT_L_JOB_ALERT_AGENT.'">'.INFO_TEXT_L_JOB_ALERT_AGENT.' </a>'.(($no_of_saved_searches>0)?"(".$no_of_saved_searches.")":'');
  }
  //////Jobseeker ends///////
  if(check_login('jobseeker'))
  define('LEFT_HTML_JOBSEEKER','
  <div class="left-sidebar  for-mobile">
  <table width="100%" border="0" align="left" cellpadding="0" cellspacing="0" id="left-panel-container">
  <tr>
  <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  <td>
  <div class="card card-custom mb-3 for-mobile">
  <div class="card-body card-body-custom">
  <div class="fw-bold">'.INFO_TEXT_L_RESUME_MANAGER.'</div>
  <div>'.$add_resume.'</div>
  <div>'.$my_resumes.'</div>
  <div>'.$set_status.'</div>

  <div class="fw-bold mt-3">'.INFO_TEXT_L_JOBS.'</div>
  <div>'.$my_saved_jobs.'</div>
  <div>'.$my_applications.'</div>
  <div>'.$response_from_employer.'</div>

  <div class="fw-bold mt-3">'.INFO_TEXT_L_MY_ACCOUNT.'</div>
  <div>'.$edit_personal_details.'</div>
  <div>'.$my_cover_letters.'</div>
  <div>'.$contact_list.'</div>
  <div>'.$newsletters.'</div>
  <div>'.$change_password.'</div>
  <div>'.$video_resume.'</div>

  <div class="fw-bold mt-3">'.INFO_TEXT_MY_JOB_SEARCH.'</div>
  <div>'.$search_jobs.'</div>
  <div>'.$jobs_by_companies.'</div>
  <div>'.$jobs_by_location.'</div>
  '.$jobs_by_map.'
  <div>'.$jobs_by_skill.'</div>
  <div>'.$jobs_by_category.'</div>
  <div>'.$my_saved_searches.'</div>
  <div>'.$job_alert_agent.'</div>
  </div>
  </div>


  <div class="left-panel-bar"></div></td>
  </tr>
  <tr>
  <td>
  <ul class="left-panel-list">

  </ul>
  <div id="leftpanelbox">&nbsp;</div>
  </td>
  </tr>
  </table></td>
  </tr>
  <tr>
  <td>&nbsp;</td>
  </tr>
  <tr>
  <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  <td><div class="left-panel-bar"></div></td>
  </tr>
  <tr>
  <td>
  <div id="leftpanelbox">&nbsp;</div>
  </td>
  </tr>
  </table></td>
  </tr>
  <tr>
  <td>&nbsp;</td>
  </tr>
  <tr>
  <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  <td><div class="left-panel-bar"></div></td>
  </tr>
  <tr>
  <td><ul class="left-panel-list">

  </ul>
  <div id="leftpanelbox">&nbsp;</div>
  </td>
  </tr>
  </table></td>
  </tr>
  <tr>
  <td>&nbsp;</td>
  </tr>
  <tr>
  <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  <td><div class="left-panel-bar"></div></td>
  </tr>
  <tr>
  <td><ul class="left-panel-list">

  </ul>

  </td>
  </tr>
  </table></td>
  </tr>
  </table>

  </div>
  ');
  else
  define('LEFT_HTML_JOBSEEKER','');


  //////// Job Search start//////////
  $jobs_by_location       = '<a class="small text-muted" href="'.tep_href_link(FILENAME_JOB_SEARCH_BY_LOCATION).'" title="'.INFO_TEXT_L_BY_LOCATION.'">'.INFO_TEXT_L_BY_LOCATION.'</a>';
  $jobs_by_map       = (GOOGLE_MAP=='true'?'<div><i class="fa fa-angle-right" aria-hidden="true"></i> <a class="small text-muted" href="'.tep_href_link(FILENAME_JOB_BY_MAP).'"  title="'.INFO_TEXT_L_BY_MAP.'">'.INFO_TEXT_L_BY_MAP.'</a></div>':'');
  $jobs_by_skill       = '<a class="small text-muted" href="'.tep_href_link(FILENAME_JOB_SEARCH_BY_SKILL).'" title="'.INFO_TEXT_L_BY_SKILL.'">'.INFO_TEXT_L_BY_SKILL.'</a>';
  $jobs_by_category       = '<a class="small text-muted" href="'.tep_href_link(FILENAME_JOB_SEARCH_BY_INDUSTRY).'" title="'.INFO_TEXT_L_BY_CATEGORY.'">'.INFO_TEXT_L_BY_CATEGORY.'</a>';
  $jobs_by_companies      = '<a class="small text-muted" href="'.tep_href_link(FILENAME_JOBSEEKER_COMPANY_PROFILE).'" title="'.INFO_TEXT_L_BY_COMPANY.'">'.INFO_TEXT_L_BY_COMPANY.'</a>'.(($no_of_companies>0)?" ( ".$no_of_companies." )":'');
  $my_saved_jobs1          = '<a class="small text-muted" href="'.tep_href_link(FILENAME_JOBSEEKER_LIST_OF_SAVED_SEARCHES).'" title="'.INFO_TEXT_MY_SAVED_JOBS.'">'.INFO_TEXT_MY_SAVED_JOBS.'</a>'.(($no_of_saved_searches>0)?" ( ".$no_of_saved_searches." )":'');
  $week_form1             = tep_draw_form('week1_form', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('job_post_day','7');
  $lastoneweek1           = '<a class="small text-muted" href="#" onclick="document.week1_form.submit()" title="'.INFO_TEXT_LAST_ONE_WEEK.'">'.INFO_TEXT_LAST_ONE_WEEK.'</a>';
  $week_form2             = tep_draw_form('week2_form', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('job_post_day','14');
  $lastoneweek2           = '<a class="small text-muted" href="#" onclick="document.week2_form.submit()" title="'.INFO_TEXT_LAST_TWO_WEEKS.'">'.INFO_TEXT_LAST_TWO_WEEKS.'</a>';
  $week_form3             = tep_draw_form('week3_form', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('job_post_day','21');
  $lastoneweek3           = '<a class="small text-muted" href="#" onclick="document.week3_form.submit()" title="'.INFO_TEXT_LAST_THREE_WEEKS.'">'.INFO_TEXT_LAST_THREE_WEEKS.'</a>';
  $week_form4             = tep_draw_form('week4_form', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('job_post_day','30');
  $lastoneweek4           = '<a class="small text-muted" href="#" onclick="document.week4_form.submit()" title="'.INFO_TEXT_LAST_ONE_MONTH.'">'.INFO_TEXT_LAST_ONE_MONTH.'</a>';

  if(check_login('jobseeker'))
  {
    $job_serach_left1 ='
    
    <div class="card-body">
	    <div class="fw-bold mt-3">'.INFO_TEXT_APP_TRACK.'</div>
      <div class="jobseeker-left">
        <div>'.$my_applications.'</div>
        <div>'.$response_from_employer.'</div>
        <div>'.$my_saved_searches.'</div>
        <div>'.$job_alert_agent.'</div>
      </div>
	  </div>
    </div>
    ';
  }
  else
  $job_serach_left1 ='';

  if(strtolower($_SERVER['PHP_SELF']) == "/".PATH_TO_MAIN.FILENAME_JOB_SEARCH && $_POST['action'] =='search' || (strtolower($_SERVER['PHP_SELF']) == "/".PATH_TO_MAIN.FILENAME_JOB_SEARCH_BY_LANGUAGE ) || (strtolower($_SERVER['PHP_SELF']) == "/".PATH_TO_MAIN.FILENAME_JOB_SEARCH_BY_INDUSTRY  ) || (strtolower($_SERVER['PHP_SELF']) == "/".PATH_TO_MAIN.FILENAME_JOB_SEARCH_BY_SKILL ) || (strtolower($_SERVER['PHP_SELF']) == "/".PATH_TO_MAIN.FILENAME_JOB_SEARCH_BY_LOCATION ) )
{// print_r($_POST);print_r($_GET);
 $country      = tep_db_input($_POST['country']);
 $keyword      = tep_db_input($_POST['keyword']);
 $job_post_day = tep_db_input($_POST['job_post_day']);
 $job_type     = tep_db_input($_POST['job_type']);
 $hidden_fields2='';
 $hidden_fields2.=tep_draw_hidden_field('job_type',tep_db_input($_POST['job_type']),'id="sf_job_type"');
 $left_query='';

 if(tep_not_null($_POST['word1']))
 $hidden_fields2.=tep_draw_hidden_field('word1',tep_db_input($_POST['word1']),'id="sf_word1"');


  if(tep_not_null($keyword)  && (($_POST['keyword']!='keyword') && ($_POST['keyword']!='job search keywords')) ) //   keyword starts //////
  {
	$l_search = array ("'[\s]+'");
    $l_replace = array (" ");
    $l_keyword = preg_replace($l_search, $l_replace, $keyword);
	$word1=tep_db_prepare_input($_POST['word1']);
    $left_query=(tep_not_null($left_query)?$left_query.' and  ':' ');
    if($word1=='Yes')
	{
     $explode_string=explode(' ',$l_keyword);
	 $total_keys = count($explode_string);
	 for($i=0;$i<$total_keys;$i++)
	 {
	  if(strlen($explode_string[$i])< 3 or strtolower($explode_string[$i])=='and')
	  {
       unset($explode_string[$i]);
	  }
	 }
	 sort($explode_string);
 	 $total_keys = count($explode_string);
     for($i=0;$i<$total_keys;$i++)
	 {
	  if($i>0)
      $whereClause1_l.='or ( ';
	  else
      $whereClause1_l.=' ( ';
      $whereClause1_l.=" j.job_title like '%".tep_db_input($explode_string[$i])."%' or ";
      $whereClause1_l.=" j.job_state like '%".tep_db_input($explode_string[$i])."%' or ";
      $whereClause1_l.=" j.job_location like '%".tep_db_input($explode_string[$i])."%' or ";
      $whereClause1_l.=" j.job_short_description like '%".tep_db_input($explode_string[$i])."%' or ";
      $whereClause1_l.=" j.job_description like '%".tep_db_input($explode_string[$i])."%'   ";
       $whereClause1_l.=" ) ";
     }

	 if($total_keys<=0)
	  $whereClause1_l='';
     if(tep_not_null($whereClause1_l))
	 {
     $left_query=(tep_not_null($left_query)?$left_query.' and  ':' ');
     $left_query.=" (";
     $left_query.=$whereClause1_l;
	 $left_query.=" )";
	 }

	}
	else
	{
     $left_query.=" (";
     $left_query.=" j.job_title like '%".tep_db_input($l_keyword)."%' ";
     $left_query.=" or j.job_state like '%".tep_db_input($l_keyword)."%' ";
     $left_query.=" or j.job_location like '%".tep_db_input($l_keyword)."%' ";
     $left_query.=" or j.job_short_description like '%".tep_db_input($l_keyword)."%'";
     $left_query.=" or j.job_description like '%".tep_db_input($l_keyword)."%'";
      $left_query.=" )";
	}
  }


 if(tep_not_null($_POST['country']))
 {
  $hidden_fields2.=tep_draw_hidden_field('country',tep_db_input($_POST['country']),'id="sf_country"');
  $left_query=(tep_not_null($left_query)?$left_query.' and  ':' ');
  $left_query.=" j.job_country_id ='".tep_db_input($country)."'";
 }
 if(tep_not_null($_POST['location']))
 {
  $hidden_fields2.=tep_draw_hidden_field('location',tep_db_input($_POST['location']),'id="sf_location"');
  $left_query=(tep_not_null($left_query)?$left_query.' and  ':' ');
  $left_query.=" j.job_location  ='".tep_db_input($_POST['location'])."'";
 }
 if(tep_not_null($_POST['experience']))
 {
  $experience_l=$_POST['experience'];
  $explode_string=explode("-",$experience_l);
  $left_query=(tep_not_null($left_query)?$left_query.' and  ':' ');
  $left_query.=" ( j.min_experience='".tep_db_input(trim($explode_string['0']))."' and  j.max_experience='".tep_db_input(trim($explode_string['1']))."' ) ";
  $hidden_fields2.=tep_draw_hidden_field('experience',tep_db_input($_POST['experience']),'id="sf_experience"');
 }
  if(tep_not_null($_POST['job_language']))
 {
  $hidden_fields2.=tep_draw_hidden_field('job_language',tep_db_input($_POST['job_language']),'id="sf_job_language"');
  $job_language=$_POST['job_language'];
  $left_query=(tep_not_null($left_query)?$left_query.' and  ':' ');
  $left_query.=" ( j.job_language='".tep_db_input($job_language)."'  ";
  $left_query.=" or j.job_language like '".tep_db_input($job_language).",%'  ";
  $left_query.=" or j.job_language like '%,".tep_db_input($job_language)."'  ) ";
 }
 elseif(tep_not_null($_GET['language']))
 {
  $jl_language   = tep_db_prepare_input($_GET['language']);
  if($row_lan=getAnyTableWhereData(JOBSEEKER_LANGUAGE_TABLE," name='".tep_db_input($jl_language)."'",'languages_id'))
  {
   $hidden_fields2.=tep_draw_hidden_field('job_language',tep_db_input($row_lan['languages_id']),'id="sf_job_language"');
   $left_query=(tep_not_null($left_query)?$left_query.' and  ':' ');
   $left_query.=" ( j.job_language='".tep_db_input($row_lan['languages_id'])."'  ";
   $left_query.=" or j.job_language like '".tep_db_input($row_lan['languages_id']).",%'  ";
   $left_query.=" or j.job_language like '%,".tep_db_input($row_lan['languages_id'])."'  ) ";
  }
 }
 if(tep_not_null($_GET['state']) && isset($_GET['action']) )
 {
  $jl_state   = tep_db_prepare_input($_GET['state']);
  $hidden_fields2.=tep_draw_hidden_field('state',tep_db_input($jl_state),'id="sf_job_state"');
  $left_query=(tep_not_null($left_query)?$left_query.' and  ':' ');
  if($row_lan=getAnyTableWhereData(ZONES_TABLE," zone_name='".tep_db_input($jl_state)."'",'zone_id'))
  {
   $left_query.=" ( j.job_state_id='".tep_db_input($row_lan['zone_id'])."' ) ";
  }
  else
   $left_query.=" ( j.job_state='".tep_db_input($jl_state)."' ) ";
 }
 if(tep_not_null($_POST['job_skill']))
 {
  $left_job_skill   = tep_db_prepare_input($_GET['job_skill']);
  $hidden_fields2.=tep_draw_hidden_field('job_skill',tep_db_input($left_job_skill),'id="sf_job_skill"');
  $left_query=(tep_not_null($left_query)?$left_query.' and  ':' ');
  $left_query.="( j.job_skills = '".tep_db_input($left_job_skill)."'";
  $left_query.=" or j.job_skills like '".tep_db_input($left_job_skill).",%'";
  $left_query.=" or j.job_skills like '%,".tep_db_input($left_job_skill)."'";
  $left_query.=" or j.job_skills like '%,".tep_db_input($left_job_skill).",%'";
  $left_query.="  )";
 }
 elseif(tep_not_null($_GET['skill']))
 {
  $left_job_skill   = tep_db_prepare_input($_GET['skill']);
  $hidden_fields2.=tep_draw_hidden_field('job_skill',tep_db_input($left_job_skill),'id="sf_job_skill"');
  $left_query=(tep_not_null($left_query)?$left_query.' and  ':' ');
  $left_query.="( j.job_skills = '".tep_db_input($left_job_skill)."'";
  $left_query.=" or j.job_skills like '".tep_db_input($left_job_skill).",%'";
  $left_query.=" or j.job_skills like '%,".tep_db_input($left_job_skill)."'";
  $left_query.=" or j.job_skills like '%,".tep_db_input($left_job_skill).",%'";
  $left_query.="  )";
 }
 if(tep_not_null($_POST['job_category']))
 {
  $job_category_l=tep_db_prepare_input($_POST['job_category']);
  if(is_array($job_category_l) && $job_category_l[0]!='')
  {
     $job_category_2= implode(',',$job_category_l);
	 $now=date('Y-m-d H:i:s');
	 if($job_category_2 !='0')
	 {
       $whereClause_job_category=" select distinct (j.job_id) from ".JOB_TABLE."  as j  left join ".JOB_JOB_CATEGORY_TABLE." as jc on(j.job_id=jc.job_id ) where j.expired >='$now' and j.re_adv <='$now' and j.job_status='Yes' and ( j.deleted is NULL or j.deleted='0000-00-00 00:00:00') and jc.job_category_id in (".$job_category_2.")";
       $left_query=(tep_not_null($left_query)?$left_query.' and job_id in ( ':' job_id in ( ');
       $left_query.=$whereClause_job_category;
       $left_query.=" ) ";
	  }
     foreach($job_category_l as $val )
    {
     if($val!='')
    $hidden_fields2.=tep_draw_hidden_field('job_category[]',tep_db_input($val),'id="sf_job_category_'.$val.'"');
    }
  }
 }
 if(tep_not_null($_GET['search_category']))
 {
  $search_category=(int) tep_db_prepare_input($_GET['search_category']);
  if($search_category>0)
  {
	$now=date('Y-m-d H:i:s');
    $hidden_fields2.=tep_draw_hidden_field('job_category[]',tep_db_input($search_category),'id="sf_job_category_'.$search_category.'"');
	$whereClause_job_category=" select distinct (j.job_id) from ".JOB_TABLE."  as j  left join ".JOB_JOB_CATEGORY_TABLE." as jc on(j.job_id=jc.job_id ) where j.expired >='$now' and j.re_adv <='$now' and j.job_status='Yes' and ( j.deleted is NULL or j.deleted='0000-00-00 00:00:00') and jc.job_category_id in (".$search_category.")";
    $left_query=(tep_not_null($left_query)?$left_query.' and job_id in ( ':' job_id in ( ');
    $left_query.=$whereClause_job_category;
    $left_query.=" ) ";
  }

 }
 $post_day_array =array('1'=>'Past 24 Hours','7'=>'Past Week','14'=>'Past Quarter','30'=>'Past Month');
 $left_query_jposted='';
 if(tep_not_null($job_post_day))
 {
   $left_query_jposted =" ( j.re_adv >'".date("Y-m-d", mktime(0, 0, 0, date("m")  , (date("d")- (int)$job_post_day), date("Y")))."' ) ";
 }
 $left_query_jt='';
 if(tep_not_null($job_type))
 {
   $left_query_jt =" ( j.job_type ='". tep_db_input($job_type)."' ) ";
 }
 $salary_array=getSalaryRangelist();
 $sal_query_array=array();
 foreach($salary_array as $key )
 {
  $sal_query_array[$key]  =getSalaryQuery($key);
 }

  if(tep_not_null($_POST['salary_range']))
 {
  if(is_array($_POST['salary_range']))
  {
   //$left_query=(tep_not_null($left_query)?$left_query.' and  ':' ');
   $salary_range=tep_db_prepare_input($_POST['salary_range']);
   $job_salary_l=implode(',',$salary_range);
   foreach($salary_range as  $key)
   {
    $s_query[]  =getSalaryQuery($key);
   }
    $s_query =trim( implode(' or ',$s_query));
	if(tep_not_null($s_query))
    $s_query = ' ( '.$s_query.' ) ';
   }
 }
 $left_query1 = $left_query2 = $left_query3=$left_query;

  if(tep_not_null($left_query_jposted))
 {
   $left_query2=(tep_not_null($left_query2)?$left_query2.' and  ':' ');
   $left_query2 .= $left_query_jposted;
   $left_query3=(tep_not_null($left_query3)?$left_query3.' and  ':' ');
   $left_query3 .= $left_query_jposted;
 }
 if(tep_not_null($left_query_jt))
 {
   $left_query1=(tep_not_null($left_query1)?$left_query1.' and  ':' ');
   $left_query1 .= $left_query_jt;
   $left_query3=(tep_not_null($left_query3)?$left_query3.' and  ':' ');
   $left_query3 .= $left_query_jt;
 }
 if(tep_not_null($s_query))
 {
   $left_query1=(tep_not_null($left_query1)?$left_query1.' and  ':' ');
   $left_query1 .= $s_query;
   $left_query2=(tep_not_null($left_query2)?$left_query2.' and  ':' ');
   $left_query2 .= $s_query;
 }
 if($left_query1=='')
 $left_query1=1;

 if($left_query2=='')
 $left_query2=1;

 if($left_query3=='')
 $left_query3=1;


 $no_of_all_time=no_of_records(JOB_TABLE.' as j',$left_query1);
 $no_of_all_types=no_of_records(JOB_TABLE.' as j',$left_query2);

 $job_serach_left1 ='<div>
                        '.tep_draw_form('left_search_job', FILENAME_JOB_SEARCH,'','post')
                        .tep_draw_hidden_field('action','search').
							        '</div>'
                      .$hidden_fields2.'
                    <div class="dropdown me-2">
                    <button class="btn btn-job-filter dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                      Any Time
                    </button>
                    <ul class="dropdown-menu py-3 px-3 btn-filter-shadow">';
foreach($post_day_array as $k => $val)
{
  $no_of_records=no_of_records(JOB_TABLE.' as j',$left_query1." and  ( j.re_adv >'".date("Y-m-d", mktime(0, 0, 0, date("m")  , (date("d")- (int)$k), date("Y")))."' )");

    $job_serach_left1 .='
                          <div class="form-check posted-box">
                            '.tep_draw_radio_field('job_post_day', $k, '', $job_post_day, 'id="sf_job_post_day_'.$k.'" class="form-check-input custom-control-input-posted-box"').'
                            <label class="form-check-label" for="sf_job_post_day_'.$k.'">'.tep_db_output($val).'</label>
                            <div class="total-numbers text-muted mr-3">'.$no_of_records.'</div>
                          </div>
              ';
}

$job_serach_left1 .='<div class="form-check posted-box">
                        '.tep_draw_radio_field('job_post_day',' ', ($job_post_day==''?true:false), $job_post_day, 'id="sf_job_post_day_" class="form-check-input custom-control-input-posted-box"').'
                          <label class="form-check-label" for="sf_job_post_day_">Any Time</label>
                        <div class="total-numbers text-muted mr-3">'.$no_of_all_time.'</div>
                      </div>
                    </ul></div>';


$job_serach_left1 .= '<div class="dropdown me-2">
                        <button class="btn btn-job-filter dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                          Job Type
                        </button>
                        <ul class="dropdown-menu py-3 px-3 btn-filter-shadow">
                      ';

		$query_l = "select * from ".JOB_TYPE_TABLE." order by  type_name asc";
	    $result_l=tep_db_query($query_l);
		$color_array=array('#0170c1','#33ff00','#01b0f1','#00af50','#ec8b5e','#ffc001','#cc0033','#ff6600');

		$c=0;
	    while($row_l = tep_db_fetch_array($result_l))
	    {
		  $no_of_records=no_of_records(JOB_TABLE.' as j',$left_query2." and j.job_type ='".$row_l['id']."'");

		 $job_serach_left1 .='
              <a href="#" class="box_job_type_l" id="box_job_type_'.$row_l['id'].'" >
                <div class="orm-check d-flex mb-1">
                        <div class="box_job_type"  style="background-color:'.$color_array[$c].';">&nbsp; </div>
                        <span class="job-type text-dark">'.tep_db_output($row_l['type_name']).'</span>
                        <span class="ms-auto d-flex small text-muted"> '.$no_of_records.'</span>
                      
                  </div>
              </a>';
						$c++;
		}

		$job_serach_left1 .='<a style="width: 100%;display: block;" href="#" class="box_job_type_l" id="box_job_type_" ><div class="box_job_type"  style="background-color:'.$color_array[$c].';">&nbsp; </div>
                            <div class="orm-check d-flex mb-1">
                                <span class="job-type text-dark">Any Type</span>
                                <span class="ms-auto d-flex small text-muted">'.$no_of_all_types.' </span>
                            </div>
                          </a>';
		tep_db_free_result($result_l);
        
    
  $job_serach_left1 .='</ul></div>';
        //////////
		$job_serach_left1 .='
                       <div class="dropdown me-2">
                          <button class="btn btn-job-filter dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Salary
                          </button>
                          <ul class="dropdown-menu py-3 px-3 btn-filter-shadow" style="width: 260px;">';

		 $salary_array=getSalaryRangelist();
         foreach($salary_array as $key=>$val)
	     {
          $s_query   =getSalaryQuery($key);
 	      if(tep_not_null($s_query))
          $s_query = ' ( '.$s_query.' ) ';

		  $no_of_records=no_of_records(JOB_TABLE.' as j',$left_query3." and ".$s_query);

		  $job_serach_left1 .='
                <div class="form-check d-flex mb-1">
                    '.tep_draw_checkbox_field('salary_range[]', $key,is_array($salary_range)? (in_array($key,$salary_range)?true:false):'','',' class="form-check-input custom-control-input-salary_range me-2" id="sf_job_salary_'.$key.'"').'
                    <label class="form-check-label" for="sf_job_salary_'.$key.'"> '.($val).'</label>
                    <span class="ms-auto d-flex small text-muted"> '.$no_of_records.'</span>
                </div>
           ';
          }


      $job_serach_left1 .='</ul></div>';
}

  define('JOB_SEARCH_LEFT','
  <div class=" text-dark d-flex align-items-center">
      <!--
        <small id="advSearch" class="form-text text-muted"><a href="'.tep_href_link(FILENAME_JOB_SEARCH).'">'.INFO_TEXT_ADV_SEARCH.'</a></small>
      -->

      <!--
        <div class="fw-bold mb-2">'.INFO_TEXT_REFINE_SEARCH.'</div>
        '.tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').
        tep_draw_input_field('keyword','','placeholder="'.INFO_TEXT_EG_SEARCH.'" type="text" class="form-control mb-2"',false).
        LIST_TABLE(COUNTRIES_TABLE,TEXT_LANGUAGE."country_name","priority","name='country' class='form-control mb-2'",INFO_TEXT_ALL_LOCATION,"",DEFAULT_COUNTRY_ID).
        '<input type="submit" name="login2" value="'.INFO_TEXT_SEARCH_NOW.'" class="btn btn-primary btn-block" /></form>
        <div class=" mt-2"><a href="'.tep_href_link(FILENAME_JOB_SEARCH).'">'.INFO_TEXT_ADV_SEARCH.'</a></div>
        <div class="fw-bold mt-4 mb-2">'.INFO_TEXT_SEARCH_JOBS.'</div>
        <div>'.$jobs_by_category.'</div>
        <div class="my-1">'.$jobs_by_companies.'</div>
        <div class="my-1">'.$jobs_by_location.'</div>
        <div class="my-1">'.$jobs_by_skill.'</div>
        '.$jobs_by_map.'
        </div> 
      -->
  
  
    '. $job_serach_left1.'

    <div class="1card-body 1card-boy-custom for-mobile">
    '.tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').'
        <div class="input-group">
          '.tep_draw_input_field('keyword','','placeholder="'.INFO_TEXT_EG_SEARCH.'" type="text" class="form-control form-control-result-page-search"',false).'
          <div class="input-group-append">
            <button class="btn btn-result-page-search" type="submit" id="button-addon2"><i class="bi bi-search"></i></button>
          </div>
        </div>
    </form>
</div>

      <!-- 
        <div class="left-sidebar">
        <div class="fw-bold mb-2 mt-3">'.INFO_TEXT_DATE_POSTED.'</div>

        '.$week_form1.'<div class="my-1">'.$lastoneweek1.'</div></form>
        '.$week_form2.'<div class="my-1">'.$lastoneweek2.'</div></form>
        '.$week_form3.'<div class="my-1">'.$lastoneweek3.'</div></form>
        '.$week_form4.'<div class="my-1">'.$lastoneweek4.'</div></form> 
        -->
    </div>
');

}

/////// Job Search ends//////////

?>