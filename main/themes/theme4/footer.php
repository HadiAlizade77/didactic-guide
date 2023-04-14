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
        <div class="container-fluid heme4-bg-dark">
            <!-- Footer -->
            <footer class="container">
                <div class="py-5 row row-cols-1 row-cols-sm-2 row-cols-md-5 g-3 text-left">
                    <div class="col">
                        <p class="fw-bold">JOB SEEKER</p>
                        <p class="text-muted">'.(check_login("jobseeker")?'<a href="'.tep_href_link(FILENAME_JOBSEEKER_CONTROL_PANEL).'">Control Panel</a>':'<a href="'.tep_href_link(FILENAME_JOBSEEKER_REGISTER1).'">Register Now</a>').'</p>
                        <p class="text-muted"><a href="'.tep_href_link(FILENAME_JOB_SEARCH).'">Search Jobs</a></p>
                        <p class="text-muted">'.(check_login("jobseeker")?'<a href="'.tep_href_link(FILENAME_LOGOUT).'">Logout</a>':'<a href="'.tep_href_link(FILENAME_JOBSEEKER_LOGIN).'">Login</a>').'</p>
                        <p class="text-muted"><a href="'.tep_href_link(FILENAME_JOBSEEKER_RESUME1).'">Upload Resume</a></p>
                    </div>
                    <div class="col">
                        <p class="fw-bold">EMPLOYER</p>
                        <p class="text-muted"><a href="'.tep_href_link(FILENAME_RECRUITER_POST_JOB).'">Post a Job</a></p>
                        <p class="text-muted"><a href="'.tep_href_link(FILENAME_RECRUITER_SEARCH_RESUME).'">Find the Right Talent</a></p>
                        <p class="text-muted">'.(check_login("recruiter")?'<a href="'.tep_href_link(FILENAME_LOGOUT).'">Logout</a>':'<a href="'.tep_href_link(FILENAME_RECRUITER_LOGIN).'">Login</a>').'</p>
                        <p class="text-muted"><a href="'.tep_href_link(FILENAME_RECRUITER_SEARCH_APPLICANT).'">Applicant Tracking</a></p>
                    </div>
                    <div class="col">
                        <p class="fw-bold">GENERAL LINKS</p>
                        <p class="text-muted"><a href="'.tep_href_link("").'">Home</a></p>
                        <p class="text-muted"><a href="'.tep_href_link(FILENAME_TERMS).'">Terms & Conditions</a></p>
                        <p class="text-muted"><a href="'.tep_href_link(FILENAME_PRIVACY).'">Privacy Policy</a></p>
                        <p class="text-muted"><a href="'.tep_href_link(FILENAME_ARTICLE).'">Resources</a></p>
                    </div>
                    <div class="col">
                        <p class="fw-bold">ABOUT US</p>
                        <p class="text-muted"><a href="'.tep_href_link(FILENAME_ABOUT_US).'">About Us</a></p>
                        <p class="text-muted"><a href="'.tep_href_link('blog/').'">Blog</a></p>
                        <p class="text-muted"><a href="'.tep_href_link(FILENAME_CONTACT_US).'">Contact Us</a></p>
                        <p class="text-muted"><a href="'.tep_href_link(FILENAME_SITE_MAP).'">Site map</a></p>
                    </div>
                    
					<div class="col">
						<img src="'.tep_href_link('img/'.DEFAULT_SITE_LOGO).'" alt="Logo" class="img-fluid footer-logo">
                        <div class="copyright mt-3">&copy; '.date("Y").'<a class="copyright-theme4 text-dark" href="'.tep_href_link('').'"> '.SITE_TITLE.' </a></div>
                        <ul class="social-network social-circle">'.$social_footer_button.'</ul>
                    </div>
                </div>
            </footer>
        </div>
        <!-- jQuery -->
        <script src="'.HOST_NAME.'jscript/jquery-3.5.1.min.js"></script>
        <!-- Bootstrap Core JavaScript -->
        <script src="'.HOST_NAME.'jscript/bootstrap.bundle.min.js"></script>
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