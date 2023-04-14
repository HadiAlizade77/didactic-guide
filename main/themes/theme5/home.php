<?php
if($_SESSION['language']=='english')
  include_once(dirname(__FILE__).'/language/home_english.php');

#######################BEGIN FEATURED BANNER################################
$home_right_banner=banner_display("7",6,300,'class="img-fluid img-thumbnail theme5-featured-img text-center mr-2 mobile-margin-bottom"');
for($i=0;$i<count($home_right_banner);$i=$i+5)
{
	$template->assign_block_vars('banner',array(
												'banner1'=>$home_right_banner[$i],
												'banner2'=>$home_right_banner[$i+1],
												'banner3'=>$home_right_banner[$i+2],
												'banner4'=>$home_right_banner[$i+3],
												'banner5'=>$home_right_banner[$i+4],
												));
}
#######################END FEATURED BANNER ################################

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$jobseeker_query = tep_db_query("select distinct jobseeker_id from " . JOBSEEKER_LOGIN_TABLE );
$no_of_jobseekers= tep_db_num_rows($jobseeker_query);
$jobs_query = tep_db_query("select distinct job_id from " . JOB_TABLE );
$no_of_jobs= tep_db_num_rows($jobs_query);
$recruiter_query = tep_db_query("select distinct recruiter_id from " . RECRUITER_LOGIN_TABLE );
$no_of_recruiters= tep_db_num_rows($recruiter_query);

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// count jobs for category
function show_category_total_job($job_category='')
{
 $now=date('Y-m-d H:i:s');
 $total_job=0;
 $where ="j.expired >='$now' and j.re_adv <='$now' and j.job_status='Yes' and rl.recruiter_status='Yes'  and ( j.deleted is NULL or j.deleted='0000-00-00 00:00:00') and jc.job_category_id = '".$job_category."'";
 if($row=getAnyTableWhereData(JOB_TABLE."  as j  left  outer join ".JOB_JOB_CATEGORY_TABLE." as jc on(j.job_id=jc.job_id ) left outer join   ".RECRUITER_LOGIN_TABLE." as rl on (j.recruiter_id = rl.recruiter_id)",$where,'count(j.job_id)  as total'))
 {
  if($row['total']>0)
  $total_job=$row['total'];
 }
 return $total_job;
}

#################JOB LOCATION############################
$field_names="z.zone_name,c.country_name,ct.continent_name ";
$whereClause=" where z.zone_country_id ='".DEFAULT_COUNTRY_ID."' ";
$query11 = "select $field_names from ".ZONES_TABLE."  as z  left outer join ".COUNTRIES_TABLE." as c on (z.zone_country_id =c.id) left outer join  ".CONTINENT_TABLE." as ct on (c.continent_id = ct.id ) $whereClause  order by zone_name  asc limit 0,15";//. (int) MODULE_THEME_SAMPLE12_MAX_JOB_LOCATION;
$result11=tep_db_query($query11);
$i=1;
while($row1 = tep_db_fetch_array($result11))
{
 $continent_name = $row1['continent_name'];
 $country_name   = $row1['country_name'];
 $zone_name      = $row1['zone_name'];
 $template->assign_block_vars('job_location1', array(
                              'job_location'    => '<a href="'.encode_forum($continent_name).'/'.encode_forum($country_name).'/'.encode_forum($zone_name).'/"   title="'.tep_db_output($zone_name).'">' .tep_db_output($zone_name).'</a>',
                              ));
 $i++;

}
tep_db_free_result($result11);
/****************end of JOB LOCATION******************/

//////////////////// LATEST JOBS STARTS ///////////////////
$now=date('Y-m-d H:i:s');
$table_names=JOB_TABLE." as j,".RECRUITER_LOGIN_TABLE.' as rl,'.RECRUITER_TABLE.' as r';
$whereClause="j.recruiter_id=rl.recruiter_id and rl.recruiter_id=r.recruiter_id and rl.recruiter_status='Yes'and j.expired >='$now' and j.re_adv <='$now' and j.job_status='Yes' and ( j.deleted is NULL or j.deleted='0000-00-00 00:00:00') ";//
$field_names="j.job_id, j.job_title, j.job_type,j.job_salary,j.job_featured,j.min_experience,j.max_experience,j.re_adv, j.job_salary, j.job_location,j.job_short_description,j.inserted, r.recruiter_company_name,r.recruiter_logo,job_country_id";
$order_by_field_name = "j.inserted";
$query = "select $field_names from $table_names where $whereClause order by $order_by_field_name limit 0,5";

//echo "<br>$query";//exit;
$result=tep_db_query($query);
$x=tep_db_num_rows($result);
//echo $x;exit;
$count=1;
while($row = tep_db_fetch_array($result))
{
 $ide=$row["job_id"];
 $title_format=encode_category($row['job_title']);
 $query_string=encode_string("job_id=".$ide."=job_id");

 ///logo
$comp_logo='';
if(tep_not_null($row["recruiter_logo"]) && is_file(PATH_TO_MAIN_PHYSICAL.PATH_TO_LOGO.$row["recruiter_logo"]))
    $comp_logo=tep_image(FILENAME_IMAGE."?image_name=".PATH_TO_LOGO.$row["recruiter_logo"]."&size=50",'','','','');
  else
    $comp_logo=tep_image(FILENAME_IMAGE."?image_name=".PATH_TO_IMG."nologo.jpg&size=50",'','','','');
  ///////////

  ///job type
	$row_type=getAnyTableWhereData(JOB_TYPE_TABLE,"id='".$row['job_type']."'",'type_name');
	//echo $row_type['type_name'];
	if ($row_type['type_name'])
		 //  $jobtype=' <a href="'.tep_href_link($ide.'/'.$title_format.'.html').'" target="_blank">'.$row_type['type_name'].'</a>';
		 $jobtype='<span class="'.$row_type['type_name'].'">'.$row_type['type_name'].'</span>';
	else
			$jobtype='<span class="Full-time">Full time</span>';//'<span class="not-mention"> NOT MENTIONED </span>';

  //////

  // salary with currency
  $row_cur = getAnyTableWhereData(CURRENCY_TABLE, "code ='" . DEFAULT_CURRENCY . "'", 'symbol_left,symbol_right');
  $sym_left = (tep_not_null($row_cur['symbol_left']) ? $row_cur['symbol_left'] . '' : '');
  $salary = (tep_not_null($row['job_salary']) ? $sym_left . tep_db_output($row['job_salary']) . $sym_rt : '• Negotiable');
  $job_posted = tep_date_long(tep_db_output($row['re_adv']));

///date
if ($row['inserted']==$now)
	$idate='TODAY';
elseif ($row['inserted']<$now)
	{
		$secs= strtotime($now) - strtotime($row['inserted']);
		$diff = floor($secs / 86400);
		$idate=$diff." DAYS AGO";

	}
	else{
		$idate=$row['inserted'];}

//////
/*  if(strlen($row['recruiter_company_name']) > 20)
  $company_name_short=	substr($row['recruiter_company_name'],0,15).'..';
 else
  $company_name_short=	substr($row['recruiter_company_name'],0,20);
*/
	$company=$row['recruiter_company_name'];

/*if(strlen($row['job_title']) > 20)
  $name_short=	substr($row['job_title'],0,15).'..';
 else
  $name_short=	substr($row['job_title'],0,20);
*/
 $title=' <a class="d-block theme5-jobtitle" href="'.tep_href_link($ide.'/'.$title_format.'.html').'" target="_blank">'.$row['job_title'].'</a>';
//echo $now."<br>".$row['inserted']."<br>".$idate;
 $template->assign_block_vars('latest_jobs', array(
                              'title'    => $title,
                              'location'    => tep_db_output($row['job_location']) ? '<i class="bi bi-geo-alt"></i> '.tep_db_output($row['job_location']) : '',
                              'company'=> $company,
							  'clogo'=>$comp_logo,
							  'jobtype'=>$jobtype,
							  'salary'    => $salary,
							  'job_posted' => $job_posted,
							  'job_featured'=> (tep_db_output($row['job_featured']) == 'Yes') ? 'featured-job-tag' : '',
							  'experience'  => tep_db_output(calculate_experience($row['min_experience'],$row['max_experience'])),
							  'jobdate'=>$idate,
                              ));
 $count++;
}
//// LATEST JOB ENDS ////

//////////////////// CAREER TOOLS STARTS ///////////////////
$now=date("Y-m-d H:i:s");
$query = "select a.id,a.title,a.seo_name, a.short_description,a.article_photo,a.show_date  from ".ARTICLE_TABLE." as a  where a.show_date <='$now' and a.is_show='Yes'  order by rand() limit 0,3";
//echo "<br>$query";//exit;
$result1=tep_db_query($query);
$x=tep_db_num_rows($result1);
$count=1;
$articles1='';
while($article = tep_db_fetch_array($result1))
{
 $ide=$article['id'];
 $seo_name = $article["seo_name"];
 $article_url=tep_href_link(get_display_link($ide,$seo_name));

	if(strlen($article['title']) > 20)
  $article_name_short=	substr($article['title'],0,15).'..';
 else
  $article_name_short=	substr($article['title'],0,20);
 $title='<a href="'.$article_url.'"  target="_blank">'.$article['title'].'</a>';
//$read_more='<button class="btn btn-sm btn-theme5" onclick="location.href=\'article_'.$ide.'.html\'" type="submit">Read more <i class="bi bi-arrow-right"></i></button>';
  $article_image='';
  if(tep_not_null($article["article_photo"]) && is_file(PATH_TO_MAIN_PHYSICAL.PATH_TO_ARTICLE_PHOTO.$article["article_photo"]))
    $article_image='<a href="'.$article_url.'"  target="_blank">'.tep_image(FILENAME_IMAGE."?image_name=".PATH_TO_ARTICLE_PHOTO.$article["article_photo"]."&size=300",'','','','class="card-img-top"').'</a>';
  else
    $article_image='<a href="'.$article_url.'" target="_blank">'.tep_image(FILENAME_IMAGE."?image_name=".PATH_TO_ARTICLE_PHOTO."blank_com.gif",'','','','class="card-img-top"').'</a>';
 	$description=((strlen($article['short_description'])<120)?$article['short_description']:substr($article['short_description'],0,100)."..");
//$MORE='<a href="article_'.$ide.'.html"  target="_blank"><span class="new_style13">...<u>more&gt;&gt;</u></span></a>';
$articles1.='<div class="col-md-4 col-sm-6"><div class="card shadow mb-3">  '.$article_image.'  <div class="card-body">    <h4 class="card-title">'. $title.'</h4>    <p class="card-text">'.tep_db_output($description).'</p>
				<p class="card-text small"><i class="bi bi-calendar2-week"></i> '.tep_date_long(tep_db_output($article['show_date'])).'</p>
				'.$read_more.'
			</div></div></div>';
$count++;
}
//// CAREER TOOLS ENDS ////

/*************************codeing to display different form and save search link for login and non login users *********************/
if(check_login("jobseeker"))
{
	$save_search= tep_draw_form('save_search', FILENAME_JOB_ALERT_AGENT,($edit?'sID='.$save_search_id:''),'post','onsubmit="return ValidateForm(this)"').tep_draw_hidden_field('action1','save_search');
    $INFO_TEXT_ALERT_TEXT=$save_search.(($action1=='save_search')?'':"<a href='#' onclick='document.save_search.submit();' class='btn btn-sm btn-info btn-block py-2 mt-2'>Create Job Alert</a></form>");
}
else
{
	 $save_search= tep_draw_form('save_search', FILENAME_JOB_ALERT_AGENT_DIRECT,'','post','onsubmit="return ValidateForm(this)"').tep_draw_hidden_field('action','new');
	 $INFO_TEXT_ALERT_TEXT=$save_search.''.tep_draw_input_field('TREF_job_alert_email', $TREF_job_alert_email,'class="form-control form-lg-theme6 " placeholder="Email Address" style="float: left;" ',false).''."<div style='float: right;margin:-46px 0 0 88px;' class='input-group-append'><button type='submit' class='btn btn-lg btn-danger-theme6 py-2 px-4 text-white' style='margin:-2px 0 0 0'><i class='bi bi-bell-fill'></i> Job alert</button></form></div>";
}

/**********************************************************************************************************************************/
///////////////////////////////////////////////////////////

$cat_array=tep_get_categories(JOB_CATEGORY_TABLE);
array_unshift($cat_array,array("id"=>0,"text"=>INFO_TEXT_ALL_CATEGORIES));
$default_country = DEFAULT_COUNTRY_ID;
$template->assign_vars(array(
'JOB_CATEGORY'=>$job_category,
'ARTICLE_HOME'=>$articles1,
 'ALL_JOBS'=>tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').'<button class="btn btn-sm btn-theme5"  type="submit">See all jobs <i class="bi bi-arrow-right"></i></button></form>',
 'ALL_ARTICLES'=> '<a class="btn btn-sm btn-theme5" href="'.tep_href_link(FILENAME_ARTICLE).'" type="button">All Articles <i class="bi bi-arrow-right"></i></a>',
 'LEFT_BOX_WIDTH'=> '',
'EMP_GET_STARTED'=>'<button class="btn btn-sm btn-theme5" onclick="location.href=\''.tep_href_link(FILENAME_RECRUITER_REGISTRATION).'\'" type="button">Get Started <i class="bi bi-arrow-right"></i></button>',

'JOBSEEKERS'=>$no_of_jobseekers,
'RECRUITERS'=>$no_of_recruiters,
'JOBS'=>$no_of_jobs,

'TOP1'=>tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('keyword','Android')."<button type='submit' class='theme5-jobs-category'>Android Developers</button></form>",
'TOP2'=>tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('keyword','AngularJS')."<button type='submit' class='theme5-jobs-category'>AngularJS Developers</button></form>",
'TOP3'=>tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('keyword','Bookkeeper')."<button type='submit' class='theme5-jobs-category'>Bookkeepers</button></form>",
'TOP4'=>tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('keyword','C#')."<button type='submit' class='theme5-jobs-category'>C# Developers</button></form>",
'TOP5'=>tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('keyword','Content Writer')."<button type='submit' class='theme5-jobs-category'>Content Writers</button></form>",
'TOP6'=>tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('keyword','Copywriter')."<button type='submit' class='theme5-jobs-category'>Copywriters</button></form>",
'TOP7'=>tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('keyword','Customer Service Representative')."<button type='submit' class='theme5-jobs-category'>Customer Service Representatives</button></form>",
'TOP8'=>tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('keyword','Data Entry')."<button type='submit' class='theme5-jobs-category'>Data Entry Specialists</button></form>",
'TOP9'=>tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('keyword','Email Marketing Consultant')."<button type='submit' class='theme5-jobs-category'>Email Marketing Consultants</button></form>",
'TOP10'=>tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('keyword','Excel')."<button type='submit' class='theme5-jobs-category'>Excel Experts</button></form>",

'TOP11'=>tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('keyword','Facebook Marketer')."<button type='submit' class='theme5-jobs-category'>Facebook Marketers</button></form>",
'TOP12'=>tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('keyword','Graphic Designer')."<button type='submit' class='theme5-jobs-category'>Graphic Designers</button></form>",
'TOP13'=>tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('keyword','iOS')."<button type='submit' class='theme5-jobs-category'>iOS Developers</button></form>",
'TOP14'=>tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('keyword','JavaScript Developer')."<button type='submit' class='theme5-jobs-category'>JavaScript Developers</button></form>",
'TOP15'=>tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('keyword','jQuery Developer')."<button type='submit' class='theme5-jobs-category'>jQuery Developers</button></form>",
'TOP16'=>tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('keyword','Mobile App Developer')."<button type='submit' class='theme5-jobs-category'>Mobile App Developers</button></form>",
'TOP17'=>tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('keyword','Objective-C Developer')."<button type='submit' class='theme5-jobs-category'>Objective-C Developers</button></form>",
'TOP18'=>tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('keyword','php Developer')."<button type='submit' class='theme5-jobs-category'>PHP Developers</button></form>",
'TOP19'=>tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('keyword','Python Developer')."<button type='submit' class='theme5-jobs-category'>Python Developers</button></form>",
'TOP20'=>tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('keyword','Sales Consultant')."<button type='submit' class='theme5-jobs-category'>Sales Consultants</button></form>",

'TOP21'=>tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('keyword','SEO')."<button type='submit' class='theme5-jobs-category'>SEO Experts</button></form>",
'TOP22'=>tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('keyword','Social Media Consultant')."<button type='submit' class='theme5-jobs-category'>Social Media Consultants</button></form>",
'TOP23'=>tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('keyword','Swift Developer')."<button type='submit' class='theme5-jobs-category'>Swift Developers</button></form>",
'TOP24'=>tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('keyword','Technical Writer')."<button type='submit' class='theme5-jobs-category'>Technical Writers</button></form>",
'TOP25'=>tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('keyword','UI Designer')."<button type='submit' class='theme5-jobs-category'>UI Designers</button></form>",
'TOP26'=>tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('keyword','UX Designer')."<button type='submit' class='theme5-jobs-category'>UX Designers</button></form>",
'TOP27'=>tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('keyword','Virtual Assistant')."<button type='submit' class='theme5-jobs-category'>Virtual Assistants</button></form>",
'TOP28'=>tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('keyword','Web Designer')."<button type='submit' class='theme5-jobs-category'>Web Designers</button></form>",
'TOP29'=>tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('keyword','Wordpress Developer')."<button type='submit' class='theme5-jobs-category'>Wordpress Developers</button></form>",
'TOP30'=>tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('keyword','Writer')."<button type='submit' class='theme5-jobs-category'>Writers</button></form>",


'ALL_CATEGORY'=>'<button class="btn btn-sm btn-theme5 text-center" onclick="location.href=\''.tep_href_link(FILENAME_JOB_SEARCH_BY_INDUSTRY).'\'" type="submit">See All Categories <i class="bi bi-arrow-right"></i></button>',
"CATEGORY1"=>tep_draw_form('search_job1', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('job_category[]','33')."<button type='submit' class='theme5-jobs-category'></i> IT/ Software (".show_category_total_job(33).")</button></form>",
"CATEGORY2"=>tep_draw_form('search_job2', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('job_category[]','19')."<button type='submit' class='theme5-jobs-category'></i> Telecom/ ISP (".show_category_total_job(19).")</button></form>",
"CATEGORY3"=>tep_draw_form('search_job3', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('job_category[]','3')."<button type='submit' class='theme5-jobs-category'></i> Arts & Design (".show_category_total_job(3).")</button></form>",
"CATEGORY4"=>tep_draw_form('search_job4', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('job_category[]','10')."<button type='submit' class='theme5-jobs-category'></i> Media/Publishing (".show_category_total_job(10).")</button></form>",
"CATEGORY5"=>tep_draw_form('search_job5', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('job_category[]','25')."<button type='submit' class='theme5-jobs-category'></i> Education &Training (".show_category_total_job(25).")</button></form>",
"CATEGORY6"=>tep_draw_form('search_job6', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('job_category[]','24')."<button type='submit' class='theme5-jobs-category'></i> Customer Support (".show_category_total_job(24).")</button></form>",
"CATEGORY7"=>tep_draw_form('search_job7', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('job_category[]','22')."<button type='submit' class='theme5-jobs-category'></i> Advertising/Marketing/PR (".show_category_total_job(22).")</button></form>",
"CATEGORY8"=>tep_draw_form('search_job8', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('job_category[]','21')."<button type='submit' class='theme5-jobs-category'></i> Accounting/Finance/Banking (".show_category_total_job(21).")</button></form>",
'CREATE_ALERT'=>$INFO_TEXT_ALERT_TEXT,

 'RIGHT_BOX_WIDTH'=> RIGHT_BOX_WIDTH1,
 'RIGHT_HTML'=> RIGHT_HTML,
 'LEFT_HTML'=> '',
 'update_message'=> $messageStack->output(),
		));
	?>