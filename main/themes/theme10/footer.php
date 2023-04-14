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
<div class="container-fluid footer-container theme10-bg-gray2 py-5">
		<!-- Footer -->
        <footer class="container">
            <div class="row">

                <div class="col-md-3">
				<img src="'.tep_href_link('img/'.DEFAULT_SITE_LOGO).'" class="footer-logo mb-3" alt="Logo">
				<p class="copyright">&copy; '.date("Y").'<a href="'.tep_href_link('').'"> '.SITE_TITLE.' </a></p>
				<ul class="social-network social-circle">
			'.$social_footer_button.'
			<li><a href="'.tep_href_link(FILENAME_INDUSTRY_RSS).'" class="icoRss" title="Rss"><i class="bi bi-rss-fill"></i></a></li>
			</ul>
                </div>

				<div class="col-md-3 mobile-margin-top">
				<p><strong>JOB SEEKER</strong></p>
				<div>'.(check_login("jobseeker")?'<a href="'.tep_href_link(FILENAME_JOBSEEKER_CONTROL_PANEL).'">Control Panel</a>':
				'<a href="'.tep_href_link(FILENAME_JOBSEEKER_REGISTER1).'">Register Now</a>').'</div>
				<p class="m-0"><a href="'.tep_href_link(FILENAME_JOB_SEARCH).'">Search Jobs</a></p>
				<p class="m-0">'.(check_login("jobseeker")?'<a href="'.tep_href_link(FILENAME_LOGOUT).'">Logout</a>':
				'<a href="'.tep_href_link(FILENAME_JOBSEEKER_LOGIN).'">Login</a>').'
				<p class="m-0"><a href="'.tep_href_link(FILENAME_JOBSEEKER_LIST_OF_APPLICATIONS).'">View Applications</a></p>
				<p class="m-0"><a href="'.tep_href_link(FILENAME_JOB_ALERT_AGENT).'">Job Alerts</a></p>
				<p class="m-0"><a href="'.tep_href_link('blog/').'">Blogs</a></p>
                </div>

				<div class="col-md-3 mobile-margin-top">
				<p><strong>EMPLOYER</strong></p>
				<p class="m-0"><a href="'.tep_href_link(FILENAME_RECRUITER_POST_JOB).'">Post a Job</a></p>
				<p class="m-0"><a href="'.tep_href_link(FILENAME_RECRUITER_SEARCH_RESUME).'">Search Resume</a></p>
				<p class="m-0">'.(check_login("recruiter")?'<a href="'.tep_href_link(FILENAME_LOGOUT).'">Logout</a>':
				'<a href="'.tep_href_link(FILENAME_RECRUITER_LOGIN).'">Login</a>').'</p>
				<p class="m-0">'.(check_login("recruiter")?'<a href="'.tep_href_link(FILENAME_RECRUITER_CONTROL_PANEL).'">Control Panel</a>':
				'<a href="'.tep_href_link(FILENAME_RECRUITER_REGISTRATION).'">Register Now</a>').'</p>
				<p class="m-0"><a href="'.tep_href_link(FILENAME_RECRUITER_SAVE_RESUME).'">Resume Alerts</a></p>
				<p class="m-0"><a href="'.tep_href_link(FILENAME_RECRUITER_SEARCH_APPLICANT).'">Applicant Tracking</a></p>
                </div>

				<div class="col-md-3 mobile-margin-top">
				<p><strong>INFORMATION</strong></p>
				<p class="m-0"><a href="'.tep_href_link(FILENAME_ABOUT_US).'">About Us</a></p>
				<p class="m-0"><a href="'.tep_href_link(FILENAME_TERMS).'">Terms & Conditions</a></p>
				<p class="m-0"><a href="'.tep_href_link(FILENAME_PRIVACY).'">Privacy Policy</a></p>
				<p class="m-0"><a href="'.tep_href_link(FILENAME_ARTICLE).'">Resources</a></p>
				<p class="m-0"><a href="'.tep_href_link(FILENAME_SITE_MAP).'">Sitemap</a></p>
				<p class="m-0"><a href="'.tep_href_link(FILENAME_CONTACT_US).'">Contact Us</a></p>
                </div>


            </div>
        </footer>
	</div>

	<div class="container-fluid theme10-bg-gray2 theme10-border-top">
	<div class="container">
		<div class="row py-3">
			<div class="col-md-12 text-center">All rights reserved &copy; '.date("Y").'<a href="'.tep_href_link('').'"> '.SITE_TITLE.' </a></div>
		</div>
	</div>
</div>
  <!-- jQuery -->
  <script src="'.HOST_NAME.'jscript/jquery-3.5.1.min.js"></script>
  <!-- Bootstrap Core JavaScript -->
  <script src="'.HOST_NAME.'jscript/bootstrap.bundle.min.js"></script>
  <script src="'.HOST_NAME.'jscript/cookiealert.js"></script>
  <script src="'.HOST_NAME.'jscript/fix.js"></script>
  '.tep_get_google_analytics_code().'

  <!--THis timout js is used for timout the error or success message-->
  <script src="'.HOST_NAME.'jscript/error_success_message_timeout.js"></script>
  <script src="'.tep_href_link(PATH_TO_LANGUAGE.$language."/jscript/page.js").'"></script>

</body>
</html>');
?>