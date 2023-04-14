<?
$social_footer_button='';
 if(tep_not_null(MODULE_FACEBOOK_FOOTER_LINK))
 $social_footer_button.=' <li><a href="'.MODULE_FACEBOOK_FOOTER_LINK.'" class="icoFacebook" title="Facebook"><i class="bi bi-facebook"></i></a></li>';

 if(tep_not_null(MODULE_LINKEDIN_FOOTER_LINK))
 $social_footer_button.='<li><a href="'.MODULE_LINKEDIN_FOOTER_LINK.'" class="icoLinkedin" title="Linkedin"><i class="bi bi-linkedin"></i></a></li>';

 if(tep_not_null(MODULE_TWITTER_FOOTER_LINK))
 $social_footer_button.='<li><a href="'.MODULE_TWITTER_FOOTER_LINK.'" class="icoTwitter" title="Twitter"><i class="bi bi-twitter"></i></a></li>';

 if(tep_not_null(MODULE_GOOGLEPLUS_FOOTER_LINK))
 $social_footer_button.='<li><a href="'.MODULE_GOOGLEPLUS_FOOTER_LINK.'" class="icoGoogle-plus" title="Google-plus"><i class="bi bi-google"></i></a></li>';
//////////////////////
define('FOOTER_HTML','
<div class="container-fluid footer-bg-theme3 py-5">
		<!-- Footer -->
        <footer class="container">
            <div class="row">

                <div class="col-lg-3">
				<img src="'.tep_href_link('img/'.DEFAULT_SITE_LOGO).'" alt="Logo" class="footer-logo">
				<p class="text-white mt-3">&copy; '.date("Y").'<a href="'.tep_href_link('').'"> '.SITE_TITLE.' </a></p>
                </div>

				<div class="col-md-2 mobile-col-md-3">
				<div class="mb-2"><strong>'.INFO_TEXT_F_JOBSEEKER.'</strong></div>
				<div class="mb-2">'.(check_login("jobseeker")?'<a href="'.tep_href_link(FILENAME_JOBSEEKER_CONTROL_PANEL).'">'.INFO_TEXT_MENU_CONTROL_PANEL.'</a>':'<a href="'.tep_href_link(FILENAME_JOBSEEKER_REGISTER1).'">'.INFO_TEXT_F_REGISTER_NOW.'</a>').'</div>
				<div class="mb-2"><a href="'.tep_href_link(FILENAME_JOB_SEARCH).'">'.INFO_TEXT_MENU_J_SEARCH_JOBS.'</a></div>
				<div class="mb-2">'.(check_login("jobseeker")?'<a href="'.tep_href_link(FILENAME_LOGOUT).'">'.INFO_TEXT_MENU_LOGOUT.'</a>':'<a href="'.tep_href_link(FILENAME_JOBSEEKER_LOGIN).'">'.INFO_TEXT_MENU_LOGIN.'</a>').'</div>
				<div class="mb-2"><a href="'.tep_href_link(FILENAME_RECRUITER_LIST_OF_APPLICATIONS).'">'.INFO_TEXT_MENU_J_VIEW_APPLICATIONS.'</a></div>
				<div class="mb-2"><a href="'.tep_href_link(FILENAME_JOB_ALERT_AGENT).'">'.INFO_TEXT_F_JOB_ALERT.'</a></div>
				<div class="mb-2"><a href="'.tep_href_link(FILENAME_JOBSEEKER_RESUME1).'">'.INFO_TEXT_MENU_J_POST_RESUME.'</a></div>
                </div>

				<div class="col-md-2 mobile-col-md-3">
				<div class="mb-2"><strong>'.INFO_TEXT_F_EMPLOYER.'</strong></div>
				<div class="mb-2"><a href="'.tep_href_link(FILENAME_RECRUITER_POST_JOB).'">'.INFO_TEXT_F_POST_JOB.'</a></div>
				<div class="mb-2"><a href="'.tep_href_link(FILENAME_RECRUITER_SEARCH_RESUME).'">'.INFO_TEXT_HM_SEARCH_RESUMES.'</a></div>
				<div class="mb-2">'.(check_login("recruiter")?'<a href="'.tep_href_link(FILENAME_LOGOUT).'">'.INFO_TEXT_F_SIGNOUT.'</a>':'<a href="'.tep_href_link(FILENAME_RECRUITER_LOGIN).'">'.INFO_TEXT_F_SIGNIN.'</a>').'</div>
				<div class="mb-2">'.(check_login("recruiter")?'<a href="'.tep_href_link(FILENAME_RECRUITER_CONTROL_PANEL).'">'.INFO_TEXT_MENU_CONTROL_PANEL.'</a>':'<a href="'.tep_href_link(FILENAME_RECRUITER_REGISTRATION).'">'.INFO_TEXT_F_REGISTER_NOW.'</a>').'</div>
				<div class="mb-2"><a href="'.tep_href_link(FILENAME_RECRUITER_SAVE_RESUME).'">'.INFO_TEXT_F_RESUME_ALERT.'</a></div>
				<div class="mb-2"><a href="'.tep_href_link(FILENAME_RECRUITER_SEARCH_APPLICANT).'">'.INFO_TEXT_MENU_EMPLOYER_APP_TRACKING.'</a></div>
                </div>

				<div class="col-md-2 mobile-col-md-3 mobile-margin-top">
				<div class="mb-2"><strong>'.INFO_TEXT_F_INFORMATION.'</strong></div>
				<div class="mb-2"><a href="'.tep_href_link(FILENAME_ABOUT_US).'">'.INFO_TEXT_F_ABOUT_US.'</a></div>
				<div class="mb-2"><a href="'.tep_href_link(FILENAME_TERMS).'">'.INFO_TEXT_F_TERMS.'</a></div>
				<div class="mb-2"><a href="'.tep_href_link(FILENAME_PRIVACY).'">'.INFO_TEXT_F_PRIVACY.'</a></div>
				<div class="mb-2"><a href="'.tep_href_link(FILENAME_ARTICLE).'">'.INFO_TEXT_F_ARTICLE.'</a></div>
				<div class="mb-2"><a href="'.tep_href_link(FILENAME_SITE_MAP).'">'.INFO_TEXT_F_SITE_MAP.'</a></div>
				<div class="mb-2"><a href="'.tep_href_link(FILENAME_CONTACT_US).'">'.INFO_TEXT_F_CONTACT.'</a></div>
                </div>

				<div class="col-md-3">
				<div class="mb-2"><strong>'.INFO_TEXT_F_CONVERSATION.'</strong></div>
				 <ul class="social-network social-circle">
'.$social_footer_button.'
						<li><a href="'.tep_href_link(FILENAME_INDUSTRY_RSS).'" class="icoRss" title="Rss"><i class="bi bi-rss-fill"></i></a></li>
                  </ul>

                </div>

            </div>
        </footer>
	</div>


  <!-- jQuery -->
  <script src="'.HOST_NAME.'jscript/jquery-3.5.1.min.js"></script>

  <!-- Bootstrap Core JavaScript -->
  <script src="'.HOST_NAME.'jscript/bootstrap.bundle.min.js"></script>
  <!-- Script to Activate the Carousel -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  <script src="'.HOST_NAME.'themes/theme3/js/main.js"></script>
  <script src="'.HOST_NAME.'jscript/cookiealert.js"></script>
  <script src="'.HOST_NAME.'jscript/fix.js"></script>
  '.tep_get_google_analytics_code().'
	<script src="'.tep_href_link(PATH_TO_LANGUAGE.$language."/jscript/page.js").'"></script>
	<!--THis timout js is used for timout the error or success message-->
	<script src="'.HOST_NAME.'jscript/error_success_message_timeout.js"></script>
</body>

</html>');


?>