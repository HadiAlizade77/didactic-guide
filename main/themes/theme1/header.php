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
    /*$welcome_text='';
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
    */
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
    if($_SESSION['language']=='english')
    include_once(dirname(__FILE__).'/language/english.php');

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
    //<!--
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
                $photo = tep_image(FILENAME_IMAGE.'?image_name='.PATH_TO_PHOTO.$resume_photo_check['jobseeker_photo'],'','','','class=" "');
            }
            else
                // $photo='<img src="'.HOST_NAME.'image/no_pic.gif" class="jobseeker-profile2 img-fluid">';
                $photo=defaultProfilePhotoUrl($name,false,112,'class="img-responsive" id=""');;

    //call of premium membership
    $row=getAnyTableWhereData(JOBSEEKER_ACCOUNT_HISTORY_TABLE.' as jah',"jah.jobseeker_id='".$_SESSION['sess_jobseekerid']."' and jah.start_date<=CURDATE() and jah.end_date >=CURDATE()",'jah.id,jah.order_id');
    $membership=(!tep_not_null($row['id'])?'<a class="dropdown-item" href="'.tep_href_link(FILENAME_JOBSEEKER_RATES).'">'.INFO_TEXT_HEADER_MIDDLE_PREMIUM_MEMBERSHIP.'</a>':'<a class="dropdown-item" href="'.tep_href_link(FILENAME_JOBSEEKER_ACCOUNT_HISTORY_INFO).'">'.INFO_TEXT_HEADER_MIDDLE_PREMIUM_MEMBERSHIP.'</a>');
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
                // $photo='<img src="'.HOST_NAME.'image/no_pic.gif" class="jobseeker-profile2 img-responsive">';
                $photo=defaultProfilePhotoUrl($name,false,112,'class="img-responsive" id=""');
            ///no. of jobs posted
            $resume_query = tep_db_query("select distinct job_id from " . JOB_TABLE.' where recruiter_id='.$_SESSION['sess_recruiterid'] );
            $no_of_resumes= tep_db_num_rows($resume_query);
        }
        else
            // $photo='<img src="'.HOST_NAME.'image/no_pic.gif" class="jobseeker-profile3 img-responsive">';
            $photo=defaultProfilePhotoUrl($name,false,112,'class="img-responsive" id=""');
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

		<li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$photo.'</a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown01">
		<h4 class="p-3 pb-0">'.$name.'</h4>
          <a class="dropdown-item" href="'.tep_href_link(FILENAME_JOBSEEKER_CONTROL_PANEL).'">'.INFO_TEXT_HM_JOBSEEKER_CONTROL_PANEL.'</a>
		  <a class="dropdown-item" href="'.tep_href_link(FILENAME_JOB_SEARCH).'">'.INFO_TEXT_HM_JOB_SEARCH.'</a>
		  <a class="dropdown-item" href="'.tep_href_link(FILENAME_JOBSEEKER_LIST_OF_RESUMES).'">'.INFO_TEXT_HM_MY_RESUMES.'</a>
		  <a class="dropdown-item" href="'.tep_href_link(FILENAME_JOBSEEKER_REGISTER1).'">'.INFO_TEXT_EDIT_PROFILE.'</a>
	  '.(JOBSEEKER_MEMBERSHIP=='false'?'':$membership).'
		  <a class="dropdown-item" href="'.tep_href_link(FILENAME_LOGOUT).'">'.INFO_TEXT_HM_LOGOUT.'</a>
        </div>
      </li>
	   <!-- Top Profile Pictures End-->


    ';
    }
    elseif(check_login("recruiter"))
    { $jobrec_profilemenu='
                <!-- Top Profile Pictures recruiter-->

	<li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$photo.'</a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown01">
            <h4 class="p-3 pb-0">'.$name.'</h4>
			<a class="dropdown-item" href="'.tep_href_link(FILENAME_RECRUITER_CONTROL_PANEL).'">'.INFO_TEXT_HM_RECRUITER_CONTROL_PANEL.'</a>
			<a class="dropdown-item" href="'.tep_href_link(FILENAME_RECRUITER_SEARCH_RESUME).'">'.INFO_TEXT_HM_RESUME_SEARCH.'</a>
			<a class="dropdown-item" href="'.tep_href_link(FILENAME_RECRUITER_POST_JOB).'">'.INFO_TEXT_HM_POST_JOB.'</a>
			<a class="dropdown-item" href="'.tep_href_link(FILENAME_RECRUITER_REGISTRATION).'">'.INFO_TEXT_EDIT_PROFILE.'</a>
			<a class="dropdown-item" href="'.tep_href_link(FILENAME_LOGOUT).'">'.INFO_TEXT_HM_LOGOUT.'</a>
        </div>
      </li>
    <!-- Top Profile Pictures End-->


    ';
    }
    else {
        $jobrec_profilemenu='';
    }
//////////////END PROFILE MENU CODING------------------//////////////////////
//***************COOKIE ALERT POPUP CODE *****************************************************/

if(COOKIE_ALERT_POPUP=='true')

$cookie_alert_popup='

<!-- /.container -->

<div class="alert-warning alert-dismissible fade text-center cookiealert" role="alert">
This website uses cookies to improve your experience. We will assume you\'re ok with this, but you can opt-out if you wish. <a class="btn btn-sm acceptcookies-btn" href="'.tep_href_link(FILENAME_PRIVACY).'" target="_blank">Learn more</a>
<button type="button" class="btn btn-sm acceptcookies" aria-label="Close">OK, Got it</button>
</div>

';

else

$cookie_alert_popup='';

///////////////////////////////////////////////////////////////////////////////////////////////////

    //---------------------------------------------------------------------------------------------------------//
    //------------------------different header for internal page and for home page  begins-------------------

    define('HEADER_HTML','<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta>
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>'.$meta_title.'</title>
            <meta http-equiv="Content-Type" >

            '.$meta_description.'
			<link rel="icon" href="'.HOST_NAME.'img/'.DEFAULT_SITE_FAVICON.'" type="ico" sizes="16x16">
			<link rel="stylesheet" type="text/css" href="'.tep_href_link("css/bootstrap.min.css").'">
            <link rel="stylesheet" type="text/css" href="'.tep_href_link("css/custom.css").'">

            <!--<link rel="stylesheet" type="text/css" href="'.tep_href_link("fonts/font-awesome.min.css").'">
            <link rel="preconnect" href="https://fonts.gstatic.com">
			<link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">-->

            <link rel="stylesheet" type="text/css" href="'.tep_href_link("themes/theme1/css/theme1.css").'">
            <link rel="stylesheet" href="'.tep_href_link("css/cookiealert.css").'">
            '.$add_script_file.'
            <script language="JavaScript" type="text/JavaScript">
            <!--
            function body_load()
            {
                '.$add_script.'
            }
            //-->
            </script>
        </head>
        <body onLoad="body_load();">
        '.$cookie_alert_popup.'
            <!-- Navigation -->

            <nav class="navbar navbar-expand-lg 1navbar-dark bg-dark fixed-top">
			<div class="container">
                <a class="navbar-brand" href="'.tep_href_link("").'"><img src="'.tep_href_link('img/'.DEFAULT_SITE_LOGO).'" alt="Jobboard Logo"></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list text-white"></i>
                </button>


                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item active">
                        <a class="nav-link" href="'.tep_href_link("").'">'.INFO_TEXT_MENU_HOME.'</a>
                    </li>

                    <!--
                    <li class="nav-item">
                        <a class="nav-link" href="'.tep_href_link(FILENAME_JOB_SEARCH).'">'.INFO_TEXT_MENU_JOBS.'</a>
                    </li>
                    -->

                    <li class="nav-item dropdown">
                        <a
                            class="nav-link dropdown-toggle"
                            id="navbarDropdown"
                            href="#"
                            data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            '.INFO_TEXT_MENU_JOBS.' <b class="caret"></b>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="'.tep_href_link(FILENAME_JOB_SEARCH_BY_INDUSTRY).'" title="'.INFO_TEXT_JOBS_BY_INDUSTRY.'">'.INFO_TEXT_JOBS_BY_INDUSTRY.'</a>
                            <a class="dropdown-item" href="'.tep_href_link(FILENAME_JOB_SEARCH_BY_SKILL).'" title="'.INFO_TEXT_JOBS_BY_SKILL.'">'.INFO_TEXT_JOBS_BY_SKILL.'</a>
                            <a class="dropdown-item" href="'.tep_href_link(FILENAME_JOBSEEKER_COMPANY_PROFILE).'" title="'.INFO_TEXT_JOBS_BY_COMPANIES.'">'.INFO_TEXT_JOBS_BY_COMPANIES.'</a>
                            <a class="dropdown-item" href="'.tep_href_link(FILENAME_JOB_SEARCH_BY_LOCATION).'" title="'.INFO_TEXT_JOBS_BY_LOCATION.'">'.INFO_TEXT_JOBS_BY_LOCATION.'</a>
                            <a class="dropdown-item" href="'.tep_href_link(FILENAME_JOB_SEARCH).'" title="'.INFO_TEXT_ADV_SEARCH.'">'.INFO_TEXT_ADV_SEARCH.'</a>
                        </div>
                    </li>

                '.(check_login("recruiter")?'':'

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            '.INFO_JOBSEEKERS.' <b class="caret"></b>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            '.(check_login("jobseeker")?'
                                <a class="dropdown-item" href="'.tep_href_link(FILENAME_JOBSEEKER_RESUME1).'">
                                    '.INFO_TEXT_MENU_POST_RESUME.'
                                </a>':
                                '<a class="dropdown-item" href="'.tep_href_link(FILENAME_JOBSEEKER_LOGIN).'">'.INFO_TEXT_MENU_POST_RESUME.'
                                </a>').'

                                <a class="dropdown-item" href="'.tep_href_link(FILENAME_JOB_SEARCH).'">'.INFO_TEXT_MENU_SEARCH_JOBS.'</a>

                                <a class="dropdown-item" href="'.tep_href_link(PATH_TO_LMS.LMS_MY_COURSES_FILENAME).'">My Courses</a>

                            '.(check_login("jobseeker")?'
                                <a class="dropdown-item" href="'.tep_href_link(FILENAME_LOGOUT).'">
                                    '.INFO_TEXT_MENU_LOGOUT.'</a>':
                                '<a class="dropdown-item" href="'.tep_href_link(FILENAME_JOBSEEKER_LOGIN).'">'.INFO_TEXT_MENU_LOGIN.'</a>').'

                            '.(check_login("jobseeker")?'
                                <a class="dropdown-item" href="'.tep_href_link(FILENAME_JOBSEEKER_CONTROL_PANEL).'">
                                    '.INFO_TEXT_MENU_CONTROL_PANEL.'</a>':
                                '<a class="dropdown-item" href="'.tep_href_link(FILENAME_JOBSEEKER_REGISTER1).'">'.INFO_TEXT_MENU_REGISTER.'</a>').'

                            '.(check_login("jobseeker")?'
                                <a class="dropdown-item" href="'.tep_href_link(FILENAME_JOBSEEKER_LIST_OF_APPLICATIONS).'">
                                    '.INFO_TEXT_MENU_VIEW_APPLICATIONS.'</a>':
                                '<a class="dropdown-item" href="'.tep_href_link(FILENAME_JOBSEEKER_LOGIN).'">'.INFO_TEXT_MENU_VIEW_APPLICATIONS.'</a>').'
								<a class="dropdown-item" href="'.tep_href_link(FILENAME_JOBSEEKER_RATES).'"  type="button" class="dropbtn">Pricing</a>
                        </div>
                    </li>').'


                '.(check_login("jobseeker")?'':'

                    <li class="nav-item dropdown">
                        <a
                            class="nav-link dropdown-toggle"
                            id="navbarDropdown"
                            href="#"
                            data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            '.INFO_EMPLOYER.' <b class="caret"></b>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">

                        '.(check_login("recruiter")?
                        '<a class="dropdown-item" href="'.tep_href_link(FILENAME_RECRUITER_POST_JOB).'">
                            '.INFO_TEXT_MENU_POST_JOB.'</a>':
                        '<a class="dropdown-item" href="'.tep_href_link(FILENAME_RECRUITER_LOGIN).'">'.INFO_TEXT_MENU_POST_JOB.'
                        </a>').'

                        <a class="dropdown-item" href="'.tep_href_link(FILENAME_RECRUITER_SEARCH_RESUME).'">
                            '.INFO_TEXT_MENU_SEARCH_RESUME.'
                        </a>

                        <a class="dropdown-item" href="'.tep_href_link(PATH_TO_LMS.LMS_COURSES_FILENAME).'">LMS</a>

                        '.(check_login("recruiter")?
                        '<a class="dropdown-item" href="'.tep_href_link(FILENAME_LOGOUT).'">
                            '.INFO_TEXT_MENU_LOGOUT.'</a>':
                        '<a class="dropdown-item" href="'.tep_href_link(FILENAME_RECRUITER_LOGIN).'">'.INFO_TEXT_MENU_LOGIN.'
                        </a>').'

                        '.(check_login("recruiter")?
                        '<a class="dropdown-item" href="'.tep_href_link(FILENAME_RECRUITER_CONTROL_PANEL).'">
                            '.INFO_TEXT_MENU_CONTROL_PANEL.'</a>':
                        '<a class="dropdown-item" href="'.tep_href_link(FILENAME_RECRUITER_REGISTRATION).'">'.INFO_TEXT_MENU_REGISTER.'
                        </a>').'

                        '.(check_login("recruiter")?
                        '<a class="dropdown-item" href="'.tep_href_link(FILENAME_RECRUITER_SEARCH_APPLICANT).'">
                            '.INFO_TEXT_MENU_APP_TRACK.'</a>':
                        '<a class="dropdown-item" href="'.tep_href_link(FILENAME_RECRUITER_LOGIN).'">'.INFO_TEXT_MENU_APP_TRACK.'
                        </a>').'
						<a class="dropdown-item" href="'.tep_href_link(FILENAME_RECRUITER_RATES).'"  type="button" class="dropbtn">Pricing</a>

                        </div>
                    </li>').'

                    <li class="nav-item dropdown">
                        <a
                            class="nav-link dropdown-toggle"
                            id="navbarDropdown"
                            href="#"
                            data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            '.INFO_TEXT_MENU_RESOURCES.' <b class="caret"></b>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="'.tep_href_link('forum/').'">'.INFO_TEXT_MENU_RESOURCES_FORUM.'</a>
                            <a class="dropdown-item" href="'.tep_href_link(FILENAME_ARTICLE).'">'.INFO_TEXT_HOME_ARTICLES.'</a>
                        </div>
                    </li>

                    <li class="nav-item active">
                        '.(check_login("jobseeker")?'
                            <a class="nav-link 1active-tab" href="'.tep_href_link(FILENAME_JOBSEEKER_RESUME1).'">
                                '.INFO_POST_RESUME.'
                            </a>':'
                            <a class="nav-link 1active-tab" href="'.tep_href_link(FILENAME_RECRUITER_POST_JOB).'">
                            '.INFO_POST_JOB.'
                            </a>').'
                    </li>

                    <li>'.$language_button.'</li>

                    '.$jobrec_profilemenu.'

                    </ul>
                </div>
				</div>
            </nav>

    ');

?>