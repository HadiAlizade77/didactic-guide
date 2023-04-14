<?
if(check_login("admin"))
{
 if(isset($_GET['jID']))
 {
  $session_array=array("sess_recruiterid"=>$_GET['rID'],"sess_recruiterlogin"=>"y");
  unset_session_value($session_array);
  if($row=getAnyTableWhereData(JOBSEEKER_LOGIN_TABLE,"jobseeker_id='".(int)tep_db_input($_GET['jID'])."'",'jobseeker_id'))
  {
   $session_array=array("sess_jobseekerid"=>$_GET['jID'],"sess_jobseekerlogin"=>"y");
   set_session_value($session_array);
  }
  else
  {
   $messageStack->add_session(MESSAGE_JOBSEEKER_ERROR, 'error');
   tep_redirect(tep_href_link(PATH_TO_ADMIN.FILENAME_ADMIN1_JOBSEEKERS,'selected_box=jobseekers'));
  }
 }
 else if($_GET['add']=='jobseeker')
 {
  $session_array=array("sess_jobseekerid"=>$_GET['jID'],"sess_jobseekerlogin"=>"y");
  unset_session_value($session_array);
 }
 else if(isset($_GET['rID']))
 {
  $session_array=array("sess_jobseekerid"=>$_GET['jID'],"sess_jobseekerlogin"=>"y");
  unset_session_value($session_array);
  if($row=getAnyTableWhereData(RECRUITER_TABLE,"recruiter_id='".(int)tep_db_input($_GET['rID'])."'",'recruiter_id'))
  {
   $session_array=array("sess_recruiterid"=>$_GET['rID'],"sess_recruiterlogin"=>"y");
   set_session_value($session_array);
  }
  else
  {
   $messageStack->add_session(MESSAGE_RECRUITER_ERROR, 'error');
   tep_redirect(tep_href_link(PATH_TO_ADMIN.FILENAME_ADMIN1_RECRUITERS,'selected_box=recruiters'));
  }
 }
 else if($_GET['add']=='recruiter')
 {
  $session_array=array("sess_recruiterid"=>$_GET['rID'],"sess_recruiterlogin"=>"y");
  unset_session_value($session_array);
 }
}
//////////////////////////////
$welcome_text='';
if(check_login('jobseeker'))
{
 if($row_11=getAnyTableWhereData(JOBSEEKER_TABLE," jobseeker_id ='".$_SESSION['sess_jobseekerid']."'","jobseeker_first_name,jobseeker_middle_name,jobseeker_last_name"))
 {
  $welcome_text=tep_db_output('Welcome,'.$row_11['jobseeker_first_name'].' '.$row_11['jobseeker_last_name']);
 }
}
else if(check_login('recruiter'))
{
 if($row_11=getAnyTableWhereData(RECRUITER_TABLE," recruiter_id ='".$_SESSION['sess_recruiterid']."'","recruiter_first_name,recruiter_last_name"))
 {
  $welcome_text=tep_db_output('Welcome,'.$row_11['recruiter_first_name'].' '.$row_11['recruiter_last_name']);
 }
}
else
{
 $welcome_text='Welcome,Guest';
}
if(strtolower($_SERVER['PHP_SELF'])=="/".PATH_TO_MAIN.FILENAME_JOB_DETAILS)
{
$job_name=getAnyTableWhereData(JOB_TABLE," job_id ='".$_GET['query_string']."'","job_title,job_short_description");
$meta_title=$job_name['job_title']."/".SITE_TITLE;
$meta_description="<META NAME='Keywords' CONTENT='".$job_name['job_title']."'>
<META NAME='Description' CONTENT='".strip_tags($job_name['job_short_description'], '<a><b><i><u><>')."'>";
$meta_description.=$obj_title_metakeyword->metakeywords;
}
else
{
//print_r($obj_title_metakeyword);
$meta_title   = $obj_title_metakeyword->title;
$meta_description = $obj_title_metakeyword->metakeywords;
}
///////////////////////////////
 include_once(dirname(__FILE__).'/language/english.php');
////////////////////////////////////////////////////////////

$add_script='';
//autologin(); ///auto login
if(strtolower($_SERVER['PHP_SELF'])=="/".PATH_TO_MAIN.FILENAME_JOBSEEKER_RESUME2)
{
 $add_script=' set_current_emp();';
}
$add_script_file='';
if(strtolower($_SERVER['PHP_SELF'])=="/".PATH_TO_MAIN.FILENAME_INDEX)
{
	$add_script_file.='<script language="JavaScript">
<!--
function search_company(company_name)
{
 document.company_search.company_name.value=company_name;
 document.company_search.submit();
}
//-->
</script>';
}
else
{
 $add_script_file='<script src="'.tep_href_link(PATH_TO_LANGUAGE.$language."/jscript/optionlist.js").'"></script>';
 $add_script.='initOptionLists();';
}
$abs=strstr($_SERVER['REQUEST_URI'],'?');
$path1=(($abs)?(stristr($_SERVER['REQUEST_URI'],'language=')?substr($_SERVER['REQUEST_URI'],0,-2):$_SERVER['REQUEST_URI'].'&language='):$_SERVER['REQUEST_URI'].'?language=');
if(strtolower($_SERVER['PHP_SELF'])=="/".PATH_TO_MAIN.FILENAME_RECRUITER_POST_JOB)
{
 $add_script.='show_hide();';
}
$header_banner=banner_display("3",1,380);

///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////CALL JOBSEEKER PIC, name & email ///////////////////////////////////////////////////////
///////////////////////////////////////////////

if(check_login("jobseeker"))
{
	 if($row=getAnyTableWhereData(JOBSEEKER_LOGIN_TABLE.' as jl, '.JOBSEEKER_TABLE.' as j',"jl.jobseeker_id='".$_SESSION['sess_jobseekerid']."' && jl.jobseeker_id=j.jobseeker_id","j.jobseeker_first_name,j.jobseeker_last_name"))
	{
		///////name and email
		$name=$row['jobseeker_first_name'].' '.$row['jobseeker_last_name'];
		///no of resumes
		$resume_query = tep_db_query("select distinct resume_id from " . JOBSEEKER_RESUME1_TABLE.' where jobseeker_id='.$_SESSION['sess_jobseekerid'] );
		$no_of_resumes= tep_db_num_rows($resume_query);
	}

		///find pic
		$resume_photo_check=getAnyTableWhereData(JOBSEEKER_RESUME1_TABLE,"jobseeker_id='".$_SESSION['sess_jobseekerid']."' and jobseeker_photo!='' ","jobseeker_photo,resume_id");

		$photo='';
		if(tep_not_null($resume_photo_check['jobseeker_photo']) && is_file(PATH_TO_MAIN_PHYSICAL_PHOTO.$resume_photo_check['jobseeker_photo']))
		{
			$photo = tep_image(FILENAME_IMAGE.'?image_name='.PATH_TO_PHOTO.$resume_photo_check['jobseeker_photo'],'','','','class="jobseeker-profile2 img-responsive"');
		}
		else
			$photo='<img src="'.HOST_NAME.'image/no_pic.gif" class="jobseeker-profile2 img-responsive">';

//call of premium membership
 $row=getAnyTableWhereData(JOBSEEKER_ACCOUNT_HISTORY_TABLE.' as jah',"jah.jobseeker_id='".$_SESSION['sess_jobseekerid']."' and jah.start_date<=CURDATE() and jah.end_date >=CURDATE()",'jah.id,jah.order_id');
  $membership=(!tep_not_null($row['id'])?'<li><a href="'.tep_href_link(FILENAME_JOBSEEKER_RATES).'">'.INFO_TEXT_HEADER_MIDDLE_PREMIUM_MEMBERSHIP.'<i class="fa fa-shopping-cart pull-right" aria-hidden="true"></i></a></li>':'<li class="divider"></li><li><a href="'.tep_href_link(FILENAME_JOBSEEKER_ACCOUNT_HISTORY_INFO).'">'.INFO_TEXT_HEADER_MIDDLE_PREMIUM_MEMBERSHIP.'<i class="fa fa-shopping-cart pull-right" aria-hidden="true"></i></a></li>');
}
elseif(check_login("recruiter"))
{
	 if($row=getAnyTableWhereData(RECRUITER_LOGIN_TABLE.' as rl, '.RECRUITER_TABLE.' as r',"rl.recruiter_id='".$_SESSION['sess_recruiterid']."' && rl.recruiter_id=r.recruiter_id","r.recruiter_first_name,r.recruiter_last_name,r.recruiter_logo"))
	{
		///////name and email
		$name=$row['recruiter_first_name'].' '.$row['recruiter_last_name'];
		$photo='';
		if(tep_not_null($row['recruiter_logo']) && is_file(PATH_TO_MAIN_PHYSICAL_LOGO.$row['recruiter_logo']))
			$photo =tep_image(FILENAME_IMAGE.'?image_name='.PATH_TO_LOGO.$row['recruiter_logo'],'','','','class="jobseeker-profile2 img-responsive"');
		else
			$photo='<img src="'.HOST_NAME.'image/no_pic.gif" class="jobseeker-profile2 img-responsive">';
		///no. of jobs posted
		$resume_query = tep_db_query("select distinct job_id from " . JOB_TABLE.' where recruiter_id='.$_SESSION['sess_recruiterid'] );
		$no_of_resumes= tep_db_num_rows($resume_query);
	}
	else
		$photo='<img src="'.HOST_NAME.'image/no_pic.gif" class="jobseeker-profile2 img-responsive">';
}
else//if neither recruiter nor jobseeker
{
$photo='';
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////JObseeker or recruiter cpanel display//////////////////////////////////////////////////////////////////
if(check_login("jobseeker"))
{	$jobrec_profilemenu='
			<!-- Top Profile Pictures-->
                    <ul class="nav navbar-nav navbar-right navbar-profile-open">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            '.$photo.'
                            </a>
                            <ul class="dropdown-menu dropdown-menu-custom">
                                <li>
                                    <div class="navbar-login">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <p class="text-center profile-inner">
                                                    '.$photo.'
                                                </p>
                                            </div>
                                            <div class="col-lg-8">
                                                <p class="text-left profile-name"><strong>'.$name.'</strong></p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="divider navbar-login-session-bg"></li>
                                <li><a href="'.tep_href_link(FILENAME_JOBSEEKER_CONTROL_PANEL).'">'.INFO_TEXT_HM_JOBSEEKER_CONTROL_PANEL.' <span class="glyphicon glyphicon-dashboard pull-right"></span></a></li>
                                <li class="divider"></li>
                                <li><a href="'.tep_href_link(FILENAME_JOB_SEARCH).'">'.INFO_TEXT_HM_JOB_SEARCH.' <span class="glyphicon glyphicon-search pull-right"></span></a></li>
                                <li class="divider"></li>
                                <li><a href="'.tep_href_link(FILENAME_JOBSEEKER_LIST_OF_RESUMES).'">'.INFO_TEXT_HM_MY_RESUMES.' <span class="badge-custom pull-right"> '.$no_of_resumes.' </span></a></li>
                                <li class="divider"></li>
                                <li><a href="'.tep_href_link(FILENAME_JOBSEEKER_REGISTER1).'">'.INFO_TEXT_EDIT_PROFILE.' <i class="fa fa-pencil pull-right" aria-hidden="true"></i></a></li>
								<li class="divider"></li>
								'.$membership.'
                                <li class="divider"></li>
                                <li><a href="'.tep_href_link(FILENAME_LOGOUT).'">'.INFO_TEXT_HM_LOGOUT.' <span class="glyphicon glyphicon-log-out pull-right"></span></a></li>
                            </ul>
                        </li>
                    </ul>
                    <!-- Top Profile Pictures End-->


';
}
elseif(check_login("recruiter"))
{ $jobrec_profilemenu='
			<!-- Top Profile Pictures recruiter-->
                    <ul class="nav navbar-nav navbar-right navbar-profile-open">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            '.$photo.'
                            </a>
                            <ul class="dropdown-menu dropdown-menu-custom">
                                <li>
                                    <div class="navbar-login">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <p class="text-center profile-inner">
                                                    '.$photo.'
                                                </p>
                                            </div>
                                            <div class="col-lg-8">
                                                <p class="text-left profile-name"><strong>'.$name.'</strong></p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="divider navbar-login-session-bg"></li>
                                <li><a href="'.tep_href_link(FILENAME_RECRUITER_CONTROL_PANEL).'">'.INFO_TEXT_HM_RECRUITER_CONTROL_PANEL.' <span class="glyphicon glyphicon-dashboard pull-right"></span></a></li>
                                <li class="divider"></li>
                                <li><a href="'.tep_href_link(FILENAME_RECRUITER_SEARCH_RESUME).'">'.INFO_TEXT_HM_RESUME_SEARCH.' <span class="glyphicon glyphicon-search pull-right"></span></a></li>
                                <li class="divider"></li>
                                <li><a href="'.tep_href_link(FILENAME_RECRUITER_POST_JOB).'">'.INFO_TEXT_HM_POST_JOB.' <span class="badge-custom pull-right"> 9 </span></a></li>
                                <li class="divider"></li>
                                <li><a href="'.tep_href_link(FILENAME_RECRUITER_REGISTRATION).'">'.INFO_TEXT_EDIT_PROFILE.' <i class="fa fa-pencil pull-right" aria-hidden="true"></i></a></li>
                                <li class="divider"></li>
                                <li><a href="'.tep_href_link(FILENAME_LOGOUT).'">'.INFO_TEXT_HM_LOGOUT.' <span class="glyphicon glyphicon-log-out pull-right"></span></a></li>
                            </ul>
                        </li>
                    </ul>
                    <!-- Top Profile Pictures End-->


';
}
else {$jobrec_profilemenu='';}

///////////////////////////////////////////////////////////////////
////profile menu for internal pages
//////////////////////////////////////////////////////////////

if(check_login("jobseeker"))
{
//call of premium membership
 $row=getAnyTableWhereData(JOBSEEKER_ACCOUNT_HISTORY_TABLE.' as jah',"jah.jobseeker_id='".$_SESSION['sess_jobseekerid']."' and jah.start_date<=CURDATE() and jah.end_date >=CURDATE()",'jah.id,jah.order_id');
  $membership=(!tep_not_null($row['id'])?'<a href="'.tep_href_link(FILENAME_JOBSEEKER_RATES).'">'.INFO_TEXT_HEADER_MIDDLE_PREMIUM_MEMBERSHIP.'<i class="fa fa-shopping-cart pull-right" aria-hidden="true"></i></a>':'<a href="'.tep_href_link(FILENAME_JOBSEEKER_ACCOUNT_HISTORY_INFO).'">'.INFO_TEXT_HEADER_MIDDLE_PREMIUM_MEMBERSHIP.'<i class="fa fa-shopping-cart pull-right" aria-hidden="true"></i></a>');

$jobrec_int_profilemenu='
					<!-- Profile Menu -->
					<div class="dropdown">
                      <button class="dropbtn">'.$photo.'</button>
                      <div class="dropdown-content dropdown-content-custom">
								<span class="jprofile profile-inner">'.$photo.'
								<p class="jname">'.$name.'</P>
								</span>
                                <a href="'.tep_href_link(FILENAME_JOBSEEKER_CONTROL_PANEL).'">'.INFO_TEXT_HM_JOBSEEKER_CONTROL_PANEL.' <i class="fa fa-tachometer pull-right" aria-hidden="true"></i></a>
                                <a href="'.tep_href_link(FILENAME_JOB_SEARCH).'">'.INFO_TEXT_HM_JOB_SEARCH.' <i class="fa fa-search pull-right" aria-hidden="true"></i></a>
								<a href="'.tep_href_link(FILENAME_JOBSEEKER_LIST_OF_RESUMES).'">'.INFO_TEXT_HM_MY_RESUMES.' <span class="resume-badge">'.$no_of_resumes.'</span></a>
								<a href="'.tep_href_link(FILENAME_JOBSEEKER_REGISTER1).'">'.INFO_TEXT_EDIT_PROFILE.' <i class="fa fa-pencil pull-right" aria-hidden="true"></i></a>
'.$membership.'
								<a href="'.tep_href_link(FILENAME_LOGOUT).'">'.INFO_TEXT_HM_LOGOUT.' <i class="fa fa-sign-out pull-right" aria-hidden="true"></i></a>
                       </div>
                    </div>
					<!-- Profile End -->
';
}
elseif(check_login("recruiter"))
{
	$jobrec_int_profilemenu='
					<!-- Profile Menu recruiter -->
					<div class="dropdown">
                      <button class="dropbtn">'.$photo.'</button>
                      <div class="dropdown-content dropdown-content-custom">
						<span class="jprofile profile-inner">'.$photo.'
								<p class="jname">'.$name.'</P>
								</span>
                                <a href="'.tep_href_link(FILENAME_RECRUITER_CONTROL_PANEL).'">'.INFO_TEXT_HM_RECRUITER_CONTROL_PANEL.' <i class="fa fa-tachometer pull-right" aria-hidden="true"></i></a>
                                <a href="'.tep_href_link(FILENAME_RECRUITER_SEARCH_RESUME).'">'.INFO_TEXT_HM_RESUME_SEARCH.' <i class="fa fa-search pull-right" aria-hidden="true"></i></a>
								<a href="'.tep_href_link(FILENAME_RECRUITER_POST_JOB).'">'.INFO_TEXT_HM_POST_JOB.' <span class="resume-badge">'.$no_of_resumes.'</span></a>
								<a href="'.tep_href_link(FILENAME_RECRUITER_REGISTRATION).'">'.INFO_TEXT_EDIT_PROFILE.' <i class="fa fa-pencil pull-right" aria-hidden="true"></i></a>
								<a href="'.tep_href_link(FILENAME_LOGOUT).'">'.INFO_TEXT_HM_LOGOUT.' <i class="fa fa-sign-out pull-right" aria-hidden="true"></i></a>
                       </div>
                    </div>
					<!-- Profile End -->
';
}
else { $jobrec_int_profilemenu='';}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////END PROFILE MENU CODING--------------------------------------------//////////////////////
//***************COOKIE ALERT POPUP CODE *****************************************************/
if(COOKIE_ALERT_POPUP=='true')
	$cookie_alert_popup='
    <!-- /.container -->
<div class="alert alert-dismissible text-center cookiealert" role="alert">
  <div class="cookiealert-container">
      This website uses cookies to improve your experience. We will assume you\'re ok with this, but you can opt-out if you wish. <a href="'.tep_href_link(FILENAME_PRIVACY).'" target="_blank">Learn more</a>

      <button type="button" class="btn btn-primary btn-sm acceptcookies" aria-label="Close">
          I agree
      </button>
  </div>
</div>
';
else
$cookie_alert_popup='';
///////////////////////////////////////////////////////////////////////////////////////////////////

//---------------------------------------------------------------------------------------------------------//
//------------------------different header for internal page and for home page  begins-------------------

if(strtolower($_SERVER['PHP_SELF'])=="/".PATH_TO_MAIN.FILENAME_INDEX)
{
define('HEADER_HTML','<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>'.$meta_title.'</title>
<meta http-equiv="Content-Type" >
'.$meta_description.'
<link rel="stylesheet" type="text/css" href="'.tep_href_link("css/bootstrap.min.css").'">
<link rel="stylesheet" type="text/css" href="'.tep_href_link("css/modern-business.css").'">
<link rel="stylesheet" type="text/css" href="'.tep_href_link("fonts/font-awesome.min.css").'">
<link rel="icon" href="'.HOST_NAME.'img/'.DEFAULT_SITE_FAVICON.'" type="ico" sizes="16x16">
<!-- Gem style -->
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn\'t work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
<link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600,700,800,900" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="'.tep_href_link("themes/theme10/css/theme10.css").'">
<!-- cookiealert.css -->
<link rel="stylesheet" href="'.tep_href_link("css/cookiealert.css").'">
'.$add_script_file.'

</head>

<body>
'.$cookie_alert_popup.'

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="'.tep_href_link("").'"><img src="'.tep_href_link('img/'.DEFAULT_SITE_LOGO).'" alt="Jobboard Logo"  class="navbar-brand"></a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
'.$jobrec_profilemenu.'
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Job Seeker <i class="fa fa-angle-down" aria-hidden="true"></i></a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="'.tep_href_link(FILENAME_JOBSEEKER_RESUME1).'">'.INFO_TEXT_MENU_POST_RESUME.'</a>
                            </li>
                            <li>
                                <a href="'.tep_href_link(FILENAME_JOB_SEARCH).'">'.INFO_TEXT_MENU_SEARCH_JOBS.'</a>
                            </li>
                            <li>
                                '.(check_login("jobseeker")?'<a href="'.tep_href_link(FILENAME_LOGOUT).'">Logout</a>':'<a href="'.tep_href_link(FILENAME_JOBSEEKER_LOGIN).'">Login</a>').'
                            </li>
                            <li>
                                '.(check_login("jobseeker")?'<a href="'.tep_href_link(FILENAME_JOBSEEKER_CONTROL_PANEL).'">Control Panel</a>':'<a href="'.tep_href_link(FILENAME_JOBSEEKER_REGISTER1).'">Register</a>').'
                            </li>
							<li>
                                <a href="'.tep_href_link(FILENAME_JOBSEEKER_LIST_OF_APPLICATIONS).'">View Applications</a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Employer <i class="fa fa-angle-down" aria-hidden="true"></i></a>
                        <ul class="dropdown-menu">
                             <li>
                                <a href="'.tep_href_link(FILENAME_RECRUITER_POST_JOB).'">'.INFO_TEXT_MENU_EMPLOYER_POST_JOB.'</a>
                            </li>
                            <li>
                                <a href="'.tep_href_link(FILENAME_RECRUITER_SEARCH_RESUME).'">'.INFO_TEXT_MENU_EMPLOYER_SEARCH_RESUME.'</a>
                            </li>
                            <li>'.(check_login("recruiter")?'<a href="'.tep_href_link(FILENAME_LOGOUT).'">'.INFO_TEXT_S_MENU_EMPLOYER_LOGOUT.'</a>':'<a href="'.tep_href_link(FILENAME_RECRUITER_LOGIN).'">'.INFO_TEXT_MENU_LOGIN.'</a>').'
                            </li>
                            <li>'.(check_login("recruiter")?'<a href="'.tep_href_link(FILENAME_RECRUITER_CONTROL_PANEL).'">'.INFO_TEXT_MENU_CONTROL_PANEL.'</a>':'<a href="'.tep_href_link(FILENAME_RECRUITER_REGISTRATION).'">'.INFO_TEXT_MENU_REGISTER.'</a>').'
                            </li>
                            <li>
                                <a href="'.tep_href_link(FILENAME_RECRUITER_SEARCH_APPLICANT).'">'.INFO_TEXT_MENU_EMPLOYER_APP_TRACKING.'</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="'.tep_href_link(FILENAME_RECRUITER_RATES).'">Pricing</a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Resources <i class="fa fa-angle-down" aria-hidden="true"></i></a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="'.tep_href_link('forum/').'">'.INFO_TEXT_MENU_FORUM.'</a>
                            </li>
                            <li>
                                <a href="'.tep_href_link(FILENAME_ARTICLE).'">'.INFO_TEXT_MENU_ARTICLES.'</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
');
}
else
{
	define('HEADER_HTML','<html lang="en">
    <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>'.$meta_title.'</title>
	<meta http-equiv="Content-Type" >
'.$meta_description.'
    <meta name="author" content="">

<link rel="icon" href="'.HOST_NAME.'img/'.DEFAULT_SITE_FAVICON.'" type="ico" sizes="16x16">

<link href="'.tep_href_link("css/stylesheet.css").'" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="'.tep_href_link("css/custom.css").'" />
<link rel="stylesheet" type="text/css" href="'.tep_href_link("css/new.css").'" />
<link href="'.tep_href_link("fonts/font-awesome.css").'" rel="stylesheet" type="text/css">
<link href="'.tep_href_link("fonts/font-awesome.min.css").'" rel="stylesheet" type="text/css">
<link href="'.tep_href_link("themes/theme10/css/theme10-internal.css").'" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600,700,800,900" rel="stylesheet">
<script src="'.tep_href_link(PATH_TO_LANGUAGE.$language."/jscript/page.js").'"></script>
'.$add_script_file.'
<script language="JavaScript" type="text/JavaScript">
<!--
function body_load()
{
 '.$add_script.'
}
//-->
</script>
<script src="'.tep_href_link("bower_components/jquery/dist/jquery.min.js").'"></script>

<!--***************  CSS AND FONT FILES FOR NEW iNTERNAL PAGES*****************************-->
<script language="javascript" type="text/javascript" src="'.tep_href_link("themes/theme10/tab.js").'"></script>
<!--********************************************************************************-->
<!-- cookiealert.css -->
<link rel="stylesheet" href="'.tep_href_link("css/cookiealert.css").'">

</head>

<body onLoad="body_load();">
'.$cookie_alert_popup.'
<table width="1140" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="header"><table width="1160" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td><a href="'.tep_href_link("").'"><img src="'.tep_href_link('img/'.DEFAULT_SITE_LOGO).'" alt="Logo" class="internal-logo"></a></td>
            <td align="right" style="display: flex;float: right;margin: 0px 0 0 0;">
					<div class="dropdown">
                      <button class="dropbtn">Job Seeker <img class="down" src="'.HOST_NAME.'themes/theme10/images/icon-down.png"></button>
                      <div class="dropdown-content down"> <a href="'.tep_href_link(FILENAME_JOBSEEKER_RESUME1).'">Post Resume</a>
						<a href="'.tep_href_link(FILENAME_JOB_SEARCH).'">Search Jobs</a>
                        '.(check_login("jobseeker")?'<a href="'.tep_href_link(FILENAME_LOGOUT).'">Logout</a>':'<a href="'.tep_href_link(FILENAME_JOBSEEKER_LOGIN).'">Login</a>').'
                        '.(check_login("jobseeker")?'<a href="'.tep_href_link(FILENAME_JOBSEEKER_CONTROL_PANEL).'">Control Panel</a>':'<a href="'.tep_href_link(FILENAME_JOBSEEKER_REGISTER1).'">Register</a>').'</div>
                    </div>

				 <div class="dropdown">
					  <button class="dropbtn">Employers <img class="down" src="'.HOST_NAME.'themes/theme10/images/icon-down.png"></button>
					  <div class="dropdown-content">
                               <a href="'.tep_href_link(FILENAME_RECRUITER_POST_JOB).'">Post Jobs</a>
								<a href="'.tep_href_link(FILENAME_RECRUITER_SEARCH_RESUME).'">Search Resume</a>
								'.(check_login("recruiter")?'<a href="'.tep_href_link(FILENAME_LOGOUT).'">Logout</a>':'<a href="'.tep_href_link(FILENAME_RECRUITER_LOGIN).'">Login</a>').'
								'.(check_login("recruiter")?'<a href="'.tep_href_link(FILENAME_RECRUITER_CONTROL_PANEL).'">Control Panel</a>':'<a href="'.tep_href_link(FILENAME_RECRUITER_REGISTRATION).'">Register</a>').'
</div>
		</button>		</div>

				<div class="dropdown">
                      <a href="'.tep_href_link(FILENAME_RECRUITER_RATES).'" type="button" class="dropbtn">Pricing</a>
                  </div>

                    <div class="dropdown">
                      <button class="dropbtn">Resources <img class="down" src="'.HOST_NAME.'themes/theme10/images/icon-down.png"></button>
                      <div class="dropdown-content"> <a href="'.tep_href_link('forum/').'">Forum</a>
                                <a href="'.tep_href_link(FILENAME_ARTICLE).'">Articles</a></div>
                    </div>
'.$jobrec_int_profilemenu.'
                </td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>');
}
?>