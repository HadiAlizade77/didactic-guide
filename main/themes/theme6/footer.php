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
    '<div class="container-fluid bg-footer">
<div class="container text-left">
	<div class="row py-4">
		<div class="col-md-3 mobile-col-md-3">
            <img src="'.tep_href_link('img/'.DEFAULT_SITE_LOGO).'" alt="Logo" class="footer-logo">
            <p class="copyright text-white">&copy; '.date("Y").'<a class="text-white" href="'.tep_href_link('').'"> '.SITE_TITLE.' </a></p>
			
			<ul class="social-network social-circle">
                    '.$social_footer_button.'
                    <li><a href="'.tep_href_link(FILENAME_INDUSTRY_RSS).'" class="icoRss" title="Rss"><i class="bi bi-rss-fill text-white"></i></a></li>
            </ul>
        </div>

		<div class="col-md-3 mobile-col-md-3 footer-links">
        <p class="footer-title">'.JOB_SEEKER.'</p>
        <p>
                '.(check_login("jobseeker")?'<a href="'.tep_href_link(FILENAME_JOBSEEKER_CONTROL_PANEL).'">'.INFO_TEXT_MENU_CONTROL_PANEL.'</a>':'<a href="'.tep_href_link(FILENAME_JOBSEEKER_REGISTER1).'">'.INFO_TEXT_MENU_REGISTER_NOW.'</a>').'
        </p>

            <p>
                <a href="'.tep_href_link(FILENAME_JOB_SEARCH).'">'.INFO_TEXT_MENU_SEARCH_JOBS.'</a>
            </p>

            <p>
                '.(check_login("jobseeker")?'<a href="'.tep_href_link(FILENAME_LOGOUT).'">'.INFO_TEXT_MENU_LOGOUT.'</a>':'<a href="'.tep_href_link(FILENAME_JOBSEEKER_LOGIN).'">'.INFO_TEXT_MENU_LOGIN.'</a>').'
            </p>

            <p>
                '.(check_login("jobseeker")?'<a href="'.tep_href_link(FILENAME_JOBSEEKER_LIST_OF_APPLICATIONS).'">'.INFO_TEXT_MENU_VIEW_APPLICATIONS.'</a>':'<a href="'.tep_href_link(FILENAME_JOBSEEKER_LOGIN).'">'.INFO_TEXT_MENU_VIEW_APPLICATIONS.'</a>').'
            </p>

            <p>
                '.(check_login("jobseeker")?'<a href="'.tep_href_link(FILENAME_JOB_ALERT_AGENT).'">'.JOB_ALERTS.'</a>':'<a href="'.tep_href_link(FILENAME_JOBSEEKER_LOGIN).'">'.JOB_ALERTS.'</a>').'
            </p>


            <p>
            <a href="'.tep_href_link(FILENAME_JOBSEEKER_RESUME1).'">'.INFO_TEXT_MENU_POST_RESUME.'</a>
            </p>

        </div>

        <div class="col-md-3 mobile-col-md-3 footer-links">

            <p class="footer-title">'.EMPLOYER.'</p>

            <p>
            <a href="'.tep_href_link(FILENAME_RECRUITER_POST_JOB).'">'.INFO_POST_JOB.'</a>
            </p>

            <p>
            <a href="'.tep_href_link(FILENAME_RECRUITER_SEARCH_RESUME).'">'.INFO_TEXT_MENU_SEARCH_RESUME.'</a>
            </p>

            <p>'.(check_login("recruiter")?'<a href="'.tep_href_link(FILENAME_LOGOUT).'">'.INFO_TEXT_MENU_LOGOUT.'</a>':'<a href="'.tep_href_link(FILENAME_RECRUITER_LOGIN).'">'.INFO_TEXT_MENU_LOGIN.'</a>').'
            </p>

            <p>'.(check_login("recruiter")?'<a href="'.tep_href_link(FILENAME_RECRUITER_CONTROL_PANEL).'">'.INFO_TEXT_MENU_CONTROL_PANEL.'</a>':'<a href="'.tep_href_link(FILENAME_RECRUITER_REGISTRATION).'">'.INFO_TEXT_MENU_REGISTER_NOW.'</a>').'
            </p>

            <p>'.(check_login("recruiter")?'<a href="'.tep_href_link(FILENAME_RECRUITER_SAVE_RESUME).'">'.RESUME_ALERTS.'</a>':'<a href="'.tep_href_link(FILENAME_RECRUITER_LOGIN).'">'.RESUME_ALERTS.'</a>').'
            </p>

            <p>'.(check_login("recruiter")?'<a href="'.tep_href_link(FILENAME_RECRUITER_SEARCH_APPLICANT).'">'.INFO_TEXT_MENU_APP_TRACK.'</a>':'<a href="'.tep_href_link(FILENAME_RECRUITER_LOGIN).'">'.INFO_TEXT_MENU_APP_TRACK.'</a>').'
            </p>

        </div>

        <div class="col-md-3 mobile-col-md-3 mobile-margin-top footer-links">
            <p class="footer-title">'.INFORMATION.'</p>
            <p>
                <a href="'.tep_href_link(FILENAME_ABOUT_US).'">'.INFO_TEXT_F_ABOUT_US.'</a>
            </p>

            <p>
                <a href="'.tep_href_link(FILENAME_TERMS).'">'.INFO_TEXT_F_TERMS.'</a>
            </p>

            <p>
                <a href="'.tep_href_link(FILENAME_PRIVACY).'">'.INFO_TEXT_F_PRIVACY.'</a>
            </p>

            <p>
                <a href="'.tep_href_link(FILENAME_ARTICLE).'">'.INFO_TEXT_F_ARTICLE.'</a>
            </p>

            <p>
                <a href="'.tep_href_link(FILENAME_SITE_MAP).'">'.INFO_TEXT_F_SITE_MAP.'</a>
            </p>

            <p>
                <a href="'.tep_href_link(FILENAME_CONTACT_US).'">'.INFO_TEXT_F_CONTACT.'</a>
            </p>

        </div>

        
	</div>
	

    </div>
</div>
</div>


    <script src="'.HOST_NAME.'jscript/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="'.HOST_NAME.'jscript/bootstrap.bundle.min.js"></script>

    <!--THis page.js is used for ajax or jquery delete operation-->
    <script src="'.tep_href_link(PATH_TO_LANGUAGE.$language."/jscript/page.js").'"></script>

    <!--THis timout js is used for timout the error or success message-->
    <script src="'.HOST_NAME.'jscript/error_success_message_timeout.js"></script>

    <script src="'.HOST_NAME.'jscript/cookiealert.js"></script>
    '.tep_get_google_analytics_code().'

</body>

</html>'
);

?>