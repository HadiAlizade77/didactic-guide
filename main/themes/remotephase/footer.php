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

// remote articles start
$now=date("Y-m-d H:i:s");
$query = "select a.id,a.title,a.short_description,a.article_photo,a.show_date  from
            ".ARTICLE_TABLE." as a
            where a.show_date <='$now' and a.is_show='Yes' order by rand() limit 0,6";

$result1=tep_db_query($query);
$x=tep_db_num_rows($result1);
$count=1;
$remote_articles='';
while($article = tep_db_fetch_array($result1))
{
    $ide=$article['id'];
    if(strlen($article['title']) > 30){
        $article_name_short = substr($article['title'],0,33).'...';
    }else{
        $article_name_short = substr($article['title'],0,33);
    }
    $title='<div class="footer-link"><a href="article_'.$ide.'.html">'.$article_name_short.'</a></div>';
$remote_articles.=$title;
$count++;
}
// remote article end
define('FOOTER_HTML','<div class="container">'.(check_login("admin")?'<a href="'.tep_href_link(FILENAME_ADMIN1_CONTROL_PANEL).'">Admin Dashboard</a>':'').'</div><div class="container-fluid white-bg dark-bg-theme1 py-3">
<div class="container text-left">
	<div class="py-4 row row-cols-1 row-cols-sm-2 row-cols-md-4 g-5">
		<div class="col">
            <img src="'.tep_href_link('img/'.DEFAULT_SITE_LOGO).'" alt="Logo" class="footer-logo">
            <div class="footer-link"><a href="'.tep_href_link(FILENAME_ABOUT_US).'">About</a></div>
            <div class="footer-link"><a href="'.tep_href_link(FILENAME_CONTACT_US).'">Contact</a></div>
            <p class="copyright">&copy; '.date("Y").'<a href="'.tep_href_link('').'"> '.SITE_TITLE.' </a></p>

			<ul class="social-network social-circle">
                    '.$social_footer_button.'
                    <li><a href="'.tep_href_link(FILENAME_INDUSTRY_RSS).'" class="icoRss" title="Rss"><i class="bi bi-rss-fill"></i></a></li>
            </ul>
        </div>

        <div>
        <div class="footer-title">Remote Work Articles</div>
		'.$remote_articles.'
        </div>

        <div class="col">
            <div class="footer-title">Remote Jobs</div>
            <div class="footer-link"><a href="#">Remote Consulting Services</a></div>
            <div class="footer-link"><a href="#">Remote Courier/Distribution/Logistics</a></div>
            <div class="footer-link"><a href="#">Remote Customer Support/Relemarketing</a></div>
            <div class="footer-link"><a href="#">Remote Education/Training</a></div>
            <div class="footer-link"><a href="#">Remote engineering/Manufacturing</a></div>
            <div class="footer-link"><a href="#">Remote Entertainment/Media</a></div>
            <div class="footer-link"><a href="#">Remote Environmental Remote Export/Import</a></div>
            <div class="footer-link"><a href="#">Remote Fashion/Garments</a></div>
        </div>

        <div class="col">
            <div class="footer-title">More Remote Jobs</div>
            <div class="footer-link"><a href="#">Remote Accounting/Finance/Banking</a></div>
            <div class="footer-link"><a href="#">Remote Administration/HR/Legal</a></div>
            <div class="footer-link"><a href="#">Remote Administration_GJ</a></div>
            <div class="footer-link"><a href="#">Remote Advertising/Marketing/PR</a></div>
            <div class="footer-link"><a href="#">Remote Arts & Design</a></div>
            <div class="footer-link"><a href="#">Remote Automotive</a></div>
            <div class="footer-link"><a href="#">Remote Aviation/Airlines</a></div>
            <div class="footer-link"><a href="#">Remote Call Centre/BPO</a></div>
            <div class="footer-link"><a href="#">Remote Cancer Research</a></div>
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