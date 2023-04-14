<?
if((strtolower($_SERVER['PHP_SELF'])=="/".PATH_TO_MAIN.PATH_TO_ADMIN.FILENAME_ADMIN1_RATE_RESUMES) || (strtolower($_SERVER['PHP_SELF'])=="/".PATH_TO_MAIN.PATH_TO_ADMIN.FILENAME_ADMIN1_JOBSEEKER_REPORTS) || (strtolower($_SERVER['PHP_SELF'])=="/".PATH_TO_MAIN.PATH_TO_ADMIN.FILENAME_ADMIN1_RECRUITER_REPORTS) )
$body_load="onLoad=\"initOptionLists()\"";
elseif((strtolower($_SERVER['PHP_SELF'])=="/".PATH_TO_MAIN.PATH_TO_ADMIN.FILENAME_ADMIN1_BANNER_MANAGEMENT))
$body_load="onLoad='set_type();'";
$_SESSION['language']='english';
$_SESSION['languages_id']='1';
if($_SESSION['languages_id']!=1)
tep_redirect(tep_href(PATH_TO_ADMIN.FILENAME_ADMIN1_CONTROL_PANEL));
$ADMIN_HEADER_HTML='
<!DOCTYPE html>
<html lang="en">

<head>
<title>'.SITE_TITLE.'</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
 <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
<link rel="stylesheet" type="text/css" href="'.tep_href_link("css/bootstrap.min.css").'">
<link rel="stylesheet" type="text/css" href="'.tep_href_link("fonts/font-awesome.min.css").'">
<link rel="stylesheet" type="text/css" href="'.tep_href_link("css/perfect-scrollbar.css").'"/>
<link rel="stylesheet" type="text/css" href="'.tep_href_link("css/material-design-iconic-font.min.css").'"/>
<link rel="stylesheet" type="text/css" href="'.tep_href_link("css/jquery-jvectormap-1.2.2.css").'"/>
<link rel="stylesheet" type="text/css" href="'.tep_href_link("css/jqvmap.min.css").'"/>
<link rel="stylesheet" type="text/css" href="'.tep_href_link("css/bootstrap-datetimepicker.min.css").'"/>
<link rel="stylesheet" href="'.tep_href_link("css/app.css").'" type="text/css"/>
<link rel="stylesheet" href="'.tep_href_link("css/admin.css").'" type="text/css"/>
<link rel="preconnect" href="https://fonts.gstatic.com">
<script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600;700;800;900&family=Suez+One&display=swap" rel="stylesheet">

<link rel="stylesheet"
        href="https://use.fontawesome.com/releases/v5.2.0/css/all.css"
        integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
</head>

<body>';
if (strtolower($_SERVER['PHP_SELF'])!="/".PATH_TO_MAIN.PATH_TO_ADMIN.FILENAME_INDEX)
{
$ADMIN_HEADER_HTML.='
<div class="be-wrapper be-fixed-sidebar">
<nav class="navbar navbar-expand fixed-top be-top-header">
      <div class="container-fluid">
        <div class="be-navbar-header"><a class="navbar-brand display-1" href="'.tep_href_link(PATH_TO_ADMIN).'">'.SITE_TITLE.'</a>

        </div>
        <div class="page-title"><span>
        '.((check_login("admin"))?'
        <a href="'.tep_href_link().'" target="_blank">
            Visit Site
        </a>':'').'
        </span></div>



        '.((check_login("admin"))?'
            <div class="be-right-navbar">
            <ul class="nav navbar-nav float-right be-user-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown"
                        role="button" aria-expanded="false">
                        <i class="fa fa-user-circle fa-2x text-muted" aria-hidden="true"></i>
                    </a>
                    <div class="dropdown-menu" role="menu">
                            <div class="user-info">
                                <div class="user-name">Administrator</div>
                                <div class="user-position online">Available</div>
                            </div>
                            <a class="dropdown-item" href="' . tep_href_link(PATH_TO_ADMIN.FILENAME_ADMIN1_ACCOUNT) . '">
                            <i class="fa fa-user" aria-hidden="true"></i>
                                My Account
                            </a>
                            <a class="dropdown-item" href="' . tep_href_link(PATH_TO_ADMIN.FILENAME_LOGOUT) . '">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                                Logout
                            </a>
                    </div>
                </li>
            </ul>
            </div>
        ':'').'

      </div>
    </nav>
';
}

 $ADMIN_HEADER_HTML.='';
?>