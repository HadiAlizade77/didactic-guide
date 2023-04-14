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
<div class="container-fluid footer-container theme8-bg-dark border-top py-4">

<div class="footer">
	<div class="container text-center">
		<div class="row mx-auto">
		<div class="col-md-12 mb-3 mx-auto">
		    <a class="btn btn-text text-white" href="'.tep_href_link('').'">Home</a>
            <a class="btn btn-text text-white" href="'.tep_href_link(FILENAME_ABOUT_US).'">About Us</a>
            <a class="btn btn-text text-white" href="'.tep_href_link(FILENAME_PRIVACY).'">Privacy Policy</a>
            <a class="btn btn-text text-white" href="'.tep_href_link(FILENAME_TERMS).'">Terms of Use</a>
            <a class="btn btn-text text-white" href="'.tep_href_link(FILENAME_ARTICLE).'">Resources</a>
            <a class="btn btn-text text-white" href="'.tep_href_link(FILENAME_SITE_MAP).'">Site Map</a>
            <a class="btn btn-text text-white" href="'.tep_href_link(FILENAME_CONTACT_US).'">Contact</a>
            <a class="btn btn-text text-white" href="'.tep_href_link(FILENAME_INDUSTRY_RSS).'"><i class="bi bi-rss-fill"></i> RSS</a>
		</div>
		<div class="col-md-12 mb-3"><font color="#fff">&copy; '.date("Y").' - <a class="text-white" href="'.tep_href_link('').'">'.SITE_TITLE.'</a> - <span class="theme8-break-line">All rights Reserved</span></font></div>
		<ul class="social-network social-circle">'.$social_footer_button.'
        <li><a href="'.tep_href_link(FILENAME_INDUSTRY_RSS).'" class="icoRss" title="Rss"><i class="bi bi-rss-fill"></i></a></li>
        </ul>
		</div>
	</div>
</div></div>

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