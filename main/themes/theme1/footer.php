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


define('FOOTER_HTML',
    '<div class="container-fluid white-bg dark-bg-theme1 border-top border-bottom py-3">
<div class="container text-left">
	<div class="py-4 row row-cols-1 row-cols-sm-2 row-cols-md-4 g-3">
		<div class="col">
            <img src="'.tep_href_link('img/'.DEFAULT_SITE_LOGO).'" alt="Logo" class="footer-logo">
            <p class="copyright">&copy; '.date("Y").'<a href="'.tep_href_link('').'"> '.SITE_TITLE.' </a></p>
			
			<ul class="social-network social-circle">
                    '.$social_footer_button.'
                    <li><a href="'.tep_href_link(FILENAME_INDUSTRY_RSS).'" class="icoRss" title="Rss"><i class="bi bi-rss-fill"></i></a></li>
            </ul>
        </div>

		<div class="col">
        <p><strong>'.JOB_SEEKER.'</strong></p>
        <div>
                '.(check_login("jobseeker")?'<a href="'.tep_href_link(FILENAME_JOBSEEKER_CONTROL_PANEL).'">'.INFO_TEXT_MENU_CONTROL_PANEL.'</a>':'<a href="'.tep_href_link(FILENAME_JOBSEEKER_REGISTER1).'">'.INFO_TEXT_MENU_REGISTER_NOW.'</a>').'
        </div>

            <div>
                <a href="'.tep_href_link(FILENAME_JOB_SEARCH).'">'.INFO_TEXT_MENU_SEARCH_JOBS.'</a>
            </div>

            <div>
                '.(check_login("jobseeker")?'<a href="'.tep_href_link(FILENAME_LOGOUT).'">'.INFO_TEXT_MENU_LOGOUT.'</a>':'<a href="'.tep_href_link(FILENAME_JOBSEEKER_LOGIN).'">'.INFO_TEXT_MENU_LOGIN.'</a>').'
            </div>

            <div>
                '.(check_login("jobseeker")?'<a href="'.tep_href_link(FILENAME_JOBSEEKER_LIST_OF_APPLICATIONS).'">'.INFO_TEXT_MENU_VIEW_APPLICATIONS.'</a>':'<a href="'.tep_href_link(FILENAME_JOBSEEKER_LOGIN).'">'.INFO_TEXT_MENU_VIEW_APPLICATIONS.'</a>').'
            </div>

            <div>
                '.(check_login("jobseeker")?'<a href="'.tep_href_link(FILENAME_JOB_ALERT_AGENT).'">'.JOB_ALERTS.'</a>':'<a href="'.tep_href_link(FILENAME_JOBSEEKER_LOGIN).'">'.JOB_ALERTS.'</a>').'
            </div>


            <div>
            <a href="'.tep_href_link(FILENAME_JOBSEEKER_RESUME1).'">'.INFO_TEXT_MENU_POST_RESUME.'</a>
            </div>

            <div>
                <a href="'.tep_href_link(PATH_TO_LMS.LMS_MY_COURSES_FILENAME).'">My Courses</a>
            </div>

        </div>

        <div class="col">

            <p>
            <strong>'.EMPLOYER.'</strong>
            </p>

            <div>
            <a href="'.tep_href_link(FILENAME_RECRUITER_POST_JOB).'">'.INFO_POST_JOB.'</a>
            </div>

            <div>
            <a href="'.tep_href_link(FILENAME_RECRUITER_SEARCH_RESUME).'">'.INFO_TEXT_MENU_SEARCH_RESUME.'</a>
            </div>

            <div>'.(check_login("recruiter")?'<a href="'.tep_href_link(FILENAME_LOGOUT).'">'.INFO_TEXT_MENU_LOGOUT.'</a>':'<a href="'.tep_href_link(FILENAME_RECRUITER_LOGIN).'">'.INFO_TEXT_MENU_LOGIN.'</a>').'
            </div>

            <div>'.(check_login("recruiter")?'<a href="'.tep_href_link(FILENAME_RECRUITER_CONTROL_PANEL).'">'.INFO_TEXT_MENU_CONTROL_PANEL.'</a>':'<a href="'.tep_href_link(FILENAME_RECRUITER_REGISTRATION).'">'.INFO_TEXT_MENU_REGISTER_NOW.'</a>').'
            </div>

            <div>'.(check_login("recruiter")?'<a href="'.tep_href_link(FILENAME_RECRUITER_SAVE_RESUME).'">'.RESUME_ALERTS.'</a>':'<a href="'.tep_href_link(FILENAME_RECRUITER_LOGIN).'">'.RESUME_ALERTS.'</a>').'
            </div>

            <div>'.(check_login("recruiter")?'<a href="'.tep_href_link(FILENAME_RECRUITER_SEARCH_APPLICANT).'">'.INFO_TEXT_MENU_APP_TRACK.'</a>':'<a href="'.tep_href_link(FILENAME_RECRUITER_LOGIN).'">'.INFO_TEXT_MENU_APP_TRACK.'</a>').'
            </div>

            <div>
                <a href="'.tep_href_link(PATH_TO_LMS.LMS_COURSES_FILENAME).'">LMS</a>
            </div>

        </div>

        <div class="col">
            <p>
            <strong>'.INFORMATION.'</strong>
            </p>
            <div>
                <a href="'.tep_href_link(FILENAME_ABOUT_US).'">'.INFO_TEXT_F_ABOUT_US.'</a>
            </div>

            <div>
                <a href="'.tep_href_link(FILENAME_TERMS).'">'.INFO_TEXT_F_TERMS.'</a>
            </div>

            <div>
                <a href="'.tep_href_link(FILENAME_PRIVACY).'">'.INFO_TEXT_F_PRIVACY.'</a>
            </div>

            <div>
                <a href="'.tep_href_link(FILENAME_ARTICLE).'">'.INFO_TEXT_F_ARTICLE.'</a>
            </div>

            <div>
                <a href="'.tep_href_link(FILENAME_SITE_MAP).'">'.INFO_TEXT_F_SITE_MAP.'</a>
            </div>

            <div>
                <a href="'.tep_href_link(FILENAME_CONTACT_US).'">'.INFO_TEXT_F_CONTACT.'</a>
            </div>

        </div>

        
	</div>
	

</div>
</div>


	<script src="'.HOST_NAME.'jscript/jquery-3.5.1.min.js"></script>
    <script src="'.HOST_NAME.'jscript/bootstrap.bundle.min.js"></script>
    <!--THis page.js is used for ajax or jquery delete operation-->
    <script src="'.tep_href_link(PATH_TO_LANGUAGE.$language."/jscript/page.js").'"></script>
    <!--THis timout js is used for timout the error or success message-->
    <script src="'.HOST_NAME.'jscript/error_success_message_timeout.js"></script>
    <script src="'.HOST_NAME.'jscript/cookiealert.js"></script>
	<!--<script src="'.HOST_NAME.'jscript/fix.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    '.tep_get_google_analytics_code().'

</body>

</html>'
);

?>