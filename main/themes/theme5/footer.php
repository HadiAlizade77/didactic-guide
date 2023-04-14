<?
$social_footer_button='';
if(tep_not_null(MODULE_FACEBOOK_FOOTER_LINK))
$social_footer_button.='<li>
                       <a href="'.MODULE_FACEBOOK_FOOTER_LINK.'
                       " class="icoFacebook" title="Facebook"><i class="bi bi-facebook"></i>
                       </a>
                       </li>';

if(tep_not_null(MODULE_LINKEDIN_FOOTER_LINK))
$social_footer_button.='<li>
                       <a href="'.MODULE_LINKEDIN_FOOTER_LINK.'
                       " class="icoLinkedin" title="Linkedin"><i class="bi bi-linkedin"></i>
                       </a>
                       </li>';

if(tep_not_null(MODULE_TWITTER_FOOTER_LINK))
$social_footer_button.='<li>
                       <a href="'.MODULE_TWITTER_FOOTER_LINK.'
                       " class="icoTwitter" title="Twitter"><i class="bi bi-twitter"></i>
                       </a>
                       </li>';

if(tep_not_null(MODULE_GOOGLEPLUS_FOOTER_LINK))
$social_footer_button.='<li>
                       <a href="'.MODULE_GOOGLEPLUS_FOOTER_LINK.'
                       " class="icoGoogle-plus" title="Google-plus"><i class="bi bi-google"></i>
                       </a>
                       </li>';
//////////////////////

define('FOOTER_HTML','	<div class="container-fluid bg-white theme6-mobile-mt p-0 pt-4">
<div class="container">
            <!-- Footer -->
            <footer>
                <div class="container">
                    <div class="row">
                        <div class="col-md-3">
                            <img src="'.tep_href_link('img/'.DEFAULT_SITE_LOGO).'" class="footer-logo">
                            <p class="copyright my-3">&copy; '.date("Y").'<a href="'.tep_href_link('').'"> '.SITE_TITLE.' </a></p>
                            <ul class="social-network social-circle">
                            '.$social_footer_button.'
                            <li><a href="'.tep_href_link(FILENAME_INDUSTRY_RSS).'" class="icoRss" title="Rss"><i class="bi bi-rss-fill"></i></a></li>
                            </ul>
                            
                        </div>
                        <div class="col-md-3 mobile-margin-top">
                            <ul class="list-unstyled text-small">
                                <li class="fw-bold mb-2">JOB SEEKER</li>
								<li>'.(check_login("jobseeker")?'<a href="'.tep_href_link(FILENAME_JOBSEEKER_CONTROL_PANEL).'">Control Panel</a>':'<a href="'.tep_href_link(FILENAME_JOBSEEKER_REGISTER1).'">Register Now</a>').'</li>
								<li><a href="'.tep_href_link(FILENAME_JOB_SEARCH).'">Search Jobs</a></li>
								<li>'.(check_login("jobseeker")?'<a href="'.tep_href_link(FILENAME_LOGOUT).'">Logout</a>':'<a href="'.tep_href_link(FILENAME_JOBSEEKER_LOGIN).'">Login</a>').'</li>
								<li><a href="'.tep_href_link(FILENAME_JOBSEEKER_LIST_OF_APPLICATIONS).'">View Applications</a></li>
								<li><a href="'.tep_href_link(FILENAME_JOB_ALERT_AGENT).'">Job Alerts</a></li>
								<li><a href="'.tep_href_link(FILENAME_JOBSEEKER_RESUME1).'">Post Resume</a></li>                            </ul>
                        </div>
                        <div class="col-md-3 mobile-margin-top">
                            <ul class="list-unstyled text-small">
                                <li class="fw-bold mb-2">EMPLOYER</li>
								<li><a href="'.tep_href_link(FILENAME_RECRUITER_POST_JOB).'">Post a Job</a></li>
								<li><a href="'.tep_href_link(FILENAME_RECRUITER_SEARCH_RESUME).'">Search Resume</a></li>
								<li>'.(check_login("recruiter")?'<a href="'.tep_href_link(FILENAME_LOGOUT).'">Logout</a>':'<a href="'.tep_href_link(FILENAME_RECRUITER_LOGIN).'">Login</a>').'</li>
								<li>'.(check_login("recruiter")?'<a href="'.tep_href_link(FILENAME_RECRUITER_CONTROL_PANEL).'">Control Panel</a>':'<a href="'.tep_href_link(FILENAME_RECRUITER_REGISTRATION).'">Register Now</a>').'</li>
								<li><a href="'.tep_href_link(FILENAME_RECRUITER_SAVE_RESUME).'">Resume Alerts</a></li>
								<li><a href="'.tep_href_link(FILENAME_RECRUITER_SEARCH_APPLICANT).'">Applicant Tracking</a></li>
                            </ul>
                        </div>
                        <div class="col-md-3 mobile-margin-top">
                            <ul class="list-unstyled text-small">
                                <li class="fw-bold mb-2">INFORMATION</li>
								<li><a href="'.tep_href_link(FILENAME_ABOUT_US).'">About Us</a></li>
								<li><a href="'.tep_href_link(FILENAME_TERMS).'">Terms & Conditions</a></li>
								<li><a href="'.tep_href_link(FILENAME_PRIVACY).'">Privacy Policy</a></li>
								<li><a href="'.tep_href_link(FILENAME_ARTICLE).'">Resources</a></li>
								<li><a href="'.tep_href_link(FILENAME_SITE_MAP).'">Sitemap</a></li>
								<li><a href="'.tep_href_link(FILENAME_CONTACT_US).'">Contact Us</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </footer>
        </div></div>

<div align="center">'.(check_login("admin")?'<a href="'.tep_href_link(PATH_TO_ADMIN.FILENAME_ADMIN1_CONTROL_PANEL).'" class="style39">Admin Control Panel</a> | <a href="'.tep_href_link(FILENAME_LOGOUT).'" class="style39">Logout</a>':'').'</div>

        <!-- jQuery -->
        <script src="'.HOST_NAME.'jscript/jquery-3.5.1.min.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="'.HOST_NAME.'jscript/bootstrap.bundle.min.js"></script>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn\'t work if you view the page via file:// -->
        <!--[if lt IE 9]>
                <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
                <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>-->
        <script src="'.HOST_NAME.'themes/theme3/js/main.js"></script>
        <script src="'.HOST_NAME.'jscript/cookiealert.js"></script>
		<script src="'.HOST_NAME.'jscript/fix.js"></script>
        '.tep_get_google_analytics_code().'
        <script src="'.tep_href_link(PATH_TO_LANGUAGE.$language."/jscript/page.js").'"></script>
        <!--THis timout js is used for timout the error or success message-->
        <script src="'.HOST_NAME.'jscript/error_success_message_timeout.js"></script>
</body>
</html>
');
?>