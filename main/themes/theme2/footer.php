<?

$social_footer_button='';
if(tep_not_null(MODULE_FACEBOOK_FOOTER_LINK))
$social_footer_button.='<li>
                       <a href=".MODULE_FACEBOOK_FOOTER_LINK." class="icoFacebook" title="Facebook"><i class="bi bi-facebook"></i></a>
                       </li>';

if(tep_not_null(MODULE_LINKEDIN_FOOTER_LINK))
$social_footer_button.='<li>
                       <a href=".MODULE_LINKEDIN_FOOTER_LINK." class="icoLinkedin" title="Linkedin"><i class="bi bi-linkedin"></i></a>
                       </li>';

if(tep_not_null(MODULE_TWITTER_FOOTER_LINK))
$social_footer_button.='<li>
                       <a href=".MODULE_TWITTER_FOOTER_LINK." class="icoTwitter" title="Twitter"><i class="bi bi-twitter"></i></a>
                       </li>';

if(tep_not_null(MODULE_GOOGLEPLUS_FOOTER_LINK))
$social_footer_button.='<li>
<a href=".MODULE_GOOGLEPLUS_FOOTER_LINK." class="icoGoogle-plus" title="Google-plus"><i class="bi bi-google"></i></a>
</li>';


///////////****************************************************///////////

define('FOOTER_HTML','
    <div class="container-fluid bg-footer-theme2">
	<div class="container text-left">
    <footer>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-3">
            <div class="col">
                <h5 class="fw-bold mb-3">About</h5>
                <p style="line-height:24px;">We the fastest growing recruitment and career advancement resources website in the Job sector for employers, recruiters, freelancers and job seekers.
                <button class="btn-all-theme2" onclick="location.href=\''.tep_href_link(FILENAME_JOBSEEKER_LOGIN).'\'" type="submit">Get Started <i class="bi bi-arrow-right"></i></button>
                </p>
                
                <div class="my-3">&copy; '.date("Y").'<a href="'.tep_href_link('').'"> '.SITE_TITLE.' </a></div>
                <div>
                <ul class="social-network social-circle">
                '.$social_footer_button.'
                <li><a href="'.tep_href_link(FILENAME_INDUSTRY_RSS).'" class="icoRss" title="Rss"><i class="bi bi-rss-fill"></i></a></li>
                </ul>
                </div>
                <!--<div><a href="mailto:"><i class="bi bi-envelope text-muted" style="font-size:32px;"></i></a></div>-->
                
            </div>
            <div class="col">
                <h5 class="fw-bold mb-3">Job Seeker</h5>
                <p>'
                    .(check_login("jobseeker")?'<a href="'.tep_href_link(FILENAME_JOBSEEKER_CONTROL_PANEL).'">Control Panel</a>':
                    '<a href="'.tep_href_link(FILENAME_JOBSEEKER_REGISTER1).'">Register Now</a>').'</p>

                <p><a href="'.tep_href_link(FILENAME_JOB_SEARCH).'">Search Jobs</a></p>

                <p>'
                    .(check_login("jobseeker")?'<a href="'.tep_href_link(FILENAME_LOGOUT).'">Logout</a>':
                    '<a href="'.tep_href_link(FILENAME_JOBSEEKER_LOGIN).'">Login</a>').'
                    <p> '.(check_login("jobseeker")?'<a href="'.tep_href_link(FILENAME_JOBSEEKER_LIST_OF_APPLICATIONS).'">View Applications</a>':
                        '<a href="'.tep_href_link(FILENAME_JOBSEEKER_LOGIN).'">View Applications</a>').'</p>
                    <p> '.(check_login("jobseeker")?'<a href="'.tep_href_link(FILENAME_JOB_ALERT_AGENT).'">Job Alerts</a>':
                        '<a href="'.tep_href_link(FILENAME_JOBSEEKER_LOGIN).'">Job Alerts</a>').'</p>
            </div>
            <div class="col">
            <h5 class="fw-bold mb-3">Employer</h5>
                <p><a href="'.tep_href_link(FILENAME_RECRUITER_POST_JOB).'">Post a Job</a></p>
                <p><a href="'.tep_href_link(FILENAME_RECRUITER_SEARCH_RESUME).'">Search Resume</a></p>
                <p>'.(check_login("recruiter")?'<a href="'.tep_href_link(FILENAME_LOGOUT).'">Logout</a>':
                    '<a href="'.tep_href_link(FILENAME_RECRUITER_LOGIN).'">Login</a>').'</p>
                <p>'.(check_login("recruiter")?'<a href="'.tep_href_link(FILENAME_RECRUITER_CONTROL_PANEL).'">Control Panel</a>':
                    '<a href="'.tep_href_link(FILENAME_RECRUITER_REGISTRATION).'">Register Now</a>').'</p>
                <p>'.(check_login("recruiter")?'<a href="'.tep_href_link(FILENAME_RECRUITER_SAVE_RESUME).'">Resume Alerts</a>':
                    '<a href="'.tep_href_link(FILENAME_RECRUITER_LOGIN).'">Resume Alerts</a>').'</p>
            </div>
            <div class="col">
            <h5 class="fw-bold mb-3">Information</h5>
                <p><a href="'.tep_href_link(FILENAME_ABOUT_US).'">About Us</a></p>
                <p><a href="'.tep_href_link(FILENAME_TERMS).'">Terms & Conditions</a></p>
                <p><a href="'.tep_href_link(FILENAME_PRIVACY).'">Privacy Policy</a></p>
                <!--<p><a href="'.tep_href_link(FILENAME_ARTICLE).'">Resources</a></p>-->
                <p><a href="'.tep_href_link(FILENAME_SITE_MAP).'">Sitemap</a></p>
                <p><a href="'.tep_href_link(FILENAME_CONTACT_US).'">Contact Us</a></p>
            </div>
        </div>
    </footer>
</div>
</div>

	<!-- /container -->
    <!-- jQuery -->
    <script src="'.HOST_NAME.'jscript/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="'.HOST_NAME.'jscript/bootstrap.bundle.min.js"></script>
    <script src="'.HOST_NAME.'jscript/cookiealert.js"></script>
    <script src="'.HOST_NAME.'jscript/video.js"></script>
    '.tep_get_google_analytics_code().'
    <script src="'.tep_href_link(PATH_TO_LANGUAGE.$language."/jscript/page.js").'"></script>
    <!--THis timout js is used for timout the error or success message-->
    <script src="'.HOST_NAME.'jscript/error_success_message_timeout.js"></script>
    </body>
</html>');
?>