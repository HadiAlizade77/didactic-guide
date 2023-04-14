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
define('FOOTER_HTML','
<div class="container-fluid footer-container theme7-bg-footer border-top">
    <!-- Footer -->
    <footer class="container py-5">
        <div class="row">
            <div class="col-lg-3 mobile-col-md-3">
                    <h4 class="mb-3 text-white">Job Seeker</h4>
                    <div class="mb-2">'.(check_login("jobseeker")?'<a href="'.tep_href_link(FILENAME_JOBSEEKER_CONTROL_PANEL).'">Control Panel</a>':'<a href="'.tep_href_link(FILENAME_JOBSEEKER_REGISTER1).'">Register Now</a>').'</div>
                    <div class="mb-2"><a href="'.tep_href_link(FILENAME_JOB_SEARCH).'">Search Jobs</a></div>
                    <div class="mb-2">'.(check_login("jobseeker")?'<a href="'.tep_href_link(FILENAME_LOGOUT).'">Logout</a>':'<a href="'.tep_href_link(FILENAME_JOBSEEKER_LOGIN).'">Login</a>').'</div>
                    <div class="mb-2"><a href="'.tep_href_link(" blog/").'">Blog </a> </div>
					<div class="mb-2"><a href="'.tep_href_link(FILENAME_JOB_ALERT_AGENT).'">Job Alerts</a></div>
					<div class="mb-2"><a href="'.tep_href_link(FILENAME_JOBSEEKER_CONTROL_PANEL).'">View Applications</a></div>
			</div>
            <div class="col-lg-3 mobile-col-md-3">
                    <h4 class="mb-3 text-white">Employer</h4>
                    <div class="mb-2"><a href="'.tep_href_link(FILENAME_RECRUITER_POST_JOB).'">Post a Job</a></div>
                    <div class="mb-2"><a href="'.tep_href_link(FILENAME_RECRUITER_SEARCH_RESUME).'">Search Resume</a></div>
                    <div class="mb-2">'.(check_login("recruiter")?'<a href="'.tep_href_link(FILENAME_LOGOUT).'">Logout</a>':'<a href="'.tep_href_link(FILENAME_RECRUITER_LOGIN).'">Login</a>').'</div>
                    <div class="mb-2">'.(check_login("recruiter")?'<a href="'.tep_href_link(FILENAME_RECRUITER_CONTROL_PANEL).'">Control Panel</a>':'<a href="'.tep_href_link(FILENAME_RECRUITER_REGISTRATION).'">Register Now</a>').'</div>
                    <div class="mb-2"><a href="'.tep_href_link(FILENAME_RECRUITER_SAVE_RESUME).'">Resume Alerts</a></div>
                    <div class="mb-2"><a href="'.tep_href_link(FILENAME_RECRUITER_SEARCH_APPLICANT).'">Applicant Tracking</a></div>

            </div>

            <div class="col-lg-3 mobile-col-md-3 mobile-margin-top">
                    <h4 class="mb-3 text-white">Information</h4>
                    <div class="mb-2"><a href="'.tep_href_link(FILENAME_ABOUT_US).'">About Us</a></div>
                    <div class="mb-2"><a href="'.tep_href_link(FILENAME_TERMS).'">Terms & Conditions</a></div>
                    <div class="mb-2"><a href="'.tep_href_link(FILENAME_PRIVACY).'">Privacy Policy</a></div>
                    <div class="mb-2"><a href="'.tep_href_link(FILENAME_ARTICLE).'">Resources</a></div>
                    <div class="mb-2"><a href="'.tep_href_link(FILENAME_SITE_MAP).'">Sitemap</a></div>
                    <div class="mb-2"><a href="'.tep_href_link(FILENAME_CONTACT_US).'">Contact Us</a></div>

            </div>

            <div class="col-lg-3 mobile-col-md-3 mobile-margin-top">
                <h4 class="mb-3 text-white">Follow Us</h4>
                <ul class="social-network social-circle">
                '.$social_footer_button.'
                <li><a href="'.tep_href_link(FILENAME_INDUSTRY_RSS).'" class="icoRss" title="Rss"><i class="bi bi-rss-fill"></i></a></li>
                </ul>
            </div>
        </div>
    </footer>
</div>
<div class="container-fluid footer-sub-container theme7-bg-subfooter">
    <!-- Footer -->
	<footer class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="py-2">&copy; Copyright '.date("Y").' - <a class="footer-link" href="'.tep_href_link('').'"> '.SITE_TITLE.' </a></div>
            </div>
        </div>
    </footer>
</div>

<!-- jQuery -->
<script src="'.HOST_NAME.'jscript/jquery-3.5.1.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="'.HOST_NAME.'jscript/bootstrap.bundle.min.js"></script>
<script src="'.HOST_NAME.'jscript/cookiealert.js"></script>
<script src="'.HOST_NAME.'jscript/fix.js"></script>
'.tep_get_google_analytics_code().'

<script src="'.tep_href_link(PATH_TO_LANGUAGE.$language."/jscript/page.js").'"></script>
<!--THis timout js is used for timout the error or success message-->
<script src="'.HOST_NAME.'jscript/error_success_message_timeout.js"></script>
</body>

</html>');

?>