<?php
if($_SESSION['language']=='english')
  include_once(dirname(__FILE__).'/language/home_english.php');

#################FEATURED EMPLOYER############################
$featured_emp=banner_display("7",6,140,'class="img-fluid img-thumbnail theme9-featured img-hover mr-2 mb-2"','class="img-fluid img-thumbnail theme9-featured img-hover mr-2 mb-2"');
for($i=0;$i<count($featured_emp);$i=$i+25)
{
		$template->assign_block_vars('featured',array(
															'emp1'=>$featured_emp[$i],
															'emp2'=>$featured_emp[$i+1],
															'emp3'=>$featured_emp[$i+2],
															'emp4'=>$featured_emp[$i+3],
															'emp5'=>$featured_emp[$i+4],
															'emp6'=>$featured_emp[$i+5],
															'emp7'=>$featured_emp[$i+6],
															'emp8'=>$featured_emp[$i+7],
															'emp9'=>$featured_emp[$i+8],
															'emp10'=>$featured_emp[$i+9],
															'emp11'=>$featured_emp[$i+10],
															'emp12'=>$featured_emp[$i+11],
															'emp13'=>$featured_emp[$i+12],
															'emp14'=>$featured_emp[$i+13],
															'emp15'=>$featured_emp[$i+14],
															'emp16'=>$featured_emp[$i+15],
															'emp17'=>$featured_emp[$i+16],
															'emp18'=>$featured_emp[$i+17],
															'emp19'=>$featured_emp[$i+18],

															'emp20'=>$featured_emp[$i+19],
															'emp21'=>$featured_emp[$i+20],
															'emp22'=>$featured_emp[$i+21],
															'emp23'=>$featured_emp[$i+22],
															'emp24'=>$featured_emp[$i+23],
															'emp25'=>$featured_emp[$i+24],


															));
}
/////////// FEATURED EMPLOYER END///////////////

#################JOB CATEGORY############################
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

$field_names="id,".TEXT_LANGUAGE."category_name";
$whereClause=" where sub_cat_id is null";
$query11 = "select $field_names from ".JOB_CATEGORY_TABLE." $whereClause  order by ".TEXT_LANGUAGE."category_name  asc limit 0,10";//.(int) MODULE_THEME_SAMPLE4_MAX_JOB_CATEORY;
$result11=tep_db_query($query11);
$i=1;
$job_category="";
while($row11 = tep_db_fetch_array($result11))
{
 $ide=$row11["id"];
 $total_jobs=show_category_total_job($ide);
 if($total_jobs>0)
 $total_jobs = ' ('.$total_jobs.')';
 else
 $total_jobs = '';
 $row11[TEXT_LANGUAGE.'category_name'];
 $key=((strlen($row11[TEXT_LANGUAGE.'category_name'])<30)?$row11[TEXT_LANGUAGE.'category_name']:substr($row11[TEXT_LANGUAGE.'category_name'],0,28)."..");
	$key1=$row11[TEXT_LANGUAGE.'category_name'];
	$job_category.="<div class='mb-2'> <a href='".$ide.'/'.encode_category($key1)."-jobs.html"."'  title='".tep_db_output($key1)."'> ".tep_db_output($key)."</a>&nbsp;".$total_jobs."&nbsp;</div>";
$i++;
}
$job_category.="";

/****************end of JOB CATEGORY******************/


/****************JOBS BY COMPANY*****************************/
$whereClause1="where rl.recruiter_status='Yes'";
$fields_c="recruiter_company_name,recruiter_email_address";
$query_c = "select $fields_c  from ".RECRUITER_TABLE." as r left join ".RECRUITER_LOGIN_TABLE." as rl on ( r.recruiter_id = rl.recruiter_id) $whereClause1 limit 0,13";
$result_c=tep_db_query($query_c);//echo "<br>$query";//exit;
$x=tep_db_num_rows($result_c);//echo $x;exit;
$k=1;
$company_name1_old="";
$company_form=tep_draw_form('company_search', FILENAME_JOBSEEKER_COMPANY_PROFILE,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('company_name','');
$job_company="".$company_form."";
while($row_c=tep_db_fetch_array($result_c))
{
	$company_name1=strtoupper(substr($row_c["recruiter_company_name"],0,1));
	$company_name="";
 if($company_name1!=$company_name1_old || $company_name1_old=='')
 {
  $title="<div class='mb-2'><a id='".tep_db_output($company_name1)."'>".tep_db_output($company_name1)."</a></div>";
  $link_array[]=$company_name1;
 }
 $email_id=$row_c["recruiter_email_address"];
 $query_string1=encode_string("recruiter_email=".$email_id."=mail");
 $company_name="<a href='#' onclick='search_company(\"".$query_string1."\")'>".tep_db_output($row_c['recruiter_company_name'])."</a> (".count_jobs_for_company($row_c['recruiter_company_name']).")";
	$job_company.="<div class='mb-2'>".$company_name."</div>";
	$company_name1_old=$company_name1;
 $k++;
}
$job_company.="</form>";
/***************end of JOBS BY COMPANY************************/

/***********************  CALCULATE TOTAL NUMBER OF JOBS *******************************/
$jobs_query = tep_db_query("select distinct job_id from " . JOB_TABLE );
$no_of_jobs= tep_db_num_rows($jobs_query);
///////////*********************************************************/////////////////////////////////////

//////////////////// LATEST JOBS STARTS ///////////////////
$now=date('Y-m-d H:i:s');
$table_names=JOB_TABLE." as j,".RECRUITER_LOGIN_TABLE.' as rl,'.RECRUITER_TABLE.' as r';
$whereClause="j.recruiter_id=rl.recruiter_id and rl.recruiter_id=r.recruiter_id and rl.recruiter_status='Yes'and j.expired >='$now' and j.re_adv <='$now' and j.job_status='Yes' and ( j.deleted is NULL or j.deleted='0000-00-00 00:00:00') ";//
$field_names="j.job_id, j.job_title, j.job_type, j.job_salary,j.job_featured,j.min_experience,j.max_experience,j.re_adv, j.job_location,j.job_short_description,j.inserted, r.recruiter_company_name,job_country_id,r.recruiter_logo";
$order_by_field_name = "j.inserted";
$query = "select $field_names from $table_names where $whereClause order by $order_by_field_name limit 0,11";

$result=tep_db_query($query);
$x=tep_db_num_rows($result);
//echo $x;exit;
$count=1;
while($row = tep_db_fetch_array($result))
{
 $ide=$row["job_id"];
 $title_format=encode_category($row['job_title']);
 $query_string=encode_string("job_id=".$ide."=job_id");

  if(strlen($row['recruiter_company_name']) > 20)
  $company_name_short=	substr($row['recruiter_company_name'],0,15).'..';
 else
  $company_name_short=	substr($row['recruiter_company_name'],0,20);
	$company=$row['recruiter_company_name'];
/////logo
 $recruiter_logo='';
 $company_logo=$row['recruiter_logo'];
 if(tep_not_null($company_logo) && is_file(PATH_TO_MAIN_PHYSICAL.PATH_TO_LOGO.$company_logo))
     $recruiter_logo=tep_image(FILENAME_IMAGE."?image_name=".PATH_TO_LOGO.$company_logo."&size=75",'','','','class="img-fluid extra-mini-profile-img theme9-profile-img img-hover mr-3 mt-1"');



   ///job type
   $row_type=getAnyTableWhereData(JOB_TYPE_TABLE,"id='".$row['job_type']."'",'type_name');
   if ($row_type['type_name']) {
     $jobtype='<span class="'.$row_type['type_name'].'">'.$row_type['type_name'].'</span>';
   }
   else {
     $jobtype='<span class="Full-time">Full time</span>';
   }

  // salary with currency
  $row_cur = getAnyTableWhereData(CURRENCY_TABLE, "code ='" . DEFAULT_CURRENCY . "'", 'symbol_left,symbol_right');
  $sym_left = (tep_not_null($row_cur['symbol_left']) ? $row_cur['symbol_left'] . '' : '');
  $salary = (tep_not_null($row['job_salary']) ? $sym_left . tep_db_output($row['job_salary']) . $sym_rt : '• Negotiable');
  $job_posted = tep_date_long(tep_db_output($row['re_adv']));

	 ///////////////
$description=(strlen($row['job_short_description'])>100?substr($row['job_short_description'],0,100).'..':$row['job_short_description']);
if(strlen($row['job_title']) > 20)
  $name_short=	substr($row['job_title'],0,15).'..';
 else
  $name_short=	substr($row['job_title'],0,20);
 $title=' <a style="color:#000;font-size:17px;" href="'.tep_href_link($ide.'/'.$title_format.'.html').'" target="_blank">'.$row['job_title'].'</a>';
///location
 $location=tep_db_output($row['job_location']);
$country=get_name_from_table(COUNTRIES_TABLE, 'country_name', 'id',tep_db_output($row['job_country_id'])); $company_address=tep_not_null($location)?"$location, $country":"$country";
/////
//echo $company_address;
 $template->assign_block_vars('latest_jobs', array(
                              'title'    	=> $title,
                              'location' 	=> $company_address ? "• $company_address" : '',
						      'logo'	 	=>$recruiter_logo,
                              'summary'  	=> $description,
							  'company'	 	=>$company,
							  'jobtype'  	=> $jobtype ? "" .$jobtype : '',
							  'salary'    	=> $salary  ? "• " .$salary : '',
							  'job_posted' 	=> $job_posted ? "• " .$job_posted : '',
							  'job_featured'=> (tep_db_output($row['job_featured']) == 'Yes') ? 'featured-job-tag' : '',
							  'experience'  => "• " . tep_db_output(calculate_experience($row['min_experience'],$row['max_experience'])),
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
$articles1.='';
while($article = tep_db_fetch_array($result1))
{
 $ide=$article['id'];
 $seo_name = $article["seo_name"];
 $article_url=tep_href_link(get_display_link($ide,$seo_name));

 $title='<a href="'.$article_url.'"  target="_blank">'.$article['title'].'</a>';
  $article_image='';
  if(tep_not_null($article["article_photo"]) && is_file(PATH_TO_MAIN_PHYSICAL.PATH_TO_ARTICLE_PHOTO.$article["article_photo"]))
    $article_image='<a href="'.$article_url.'"  target="_blank">'.tep_image(FILENAME_IMAGE."?image_name=".PATH_TO_ARTICLE_PHOTO.$article["article_photo"].'&size=450','','','','class="img-fluid img-hover mb-2 gray-img"').'</a>';
  else
    $article_image='<a href="'.$article_url.'" target="_blank">'.tep_image(FILENAME_IMAGE."?image_name=".PATH_TO_ARTICLE_PHOTO."blank_com.gif".'&size=450','','','','class="img-fluid img-hover mb-2 gray-img"').'</a>';
 	$description=((strlen($article['short_description'])<120)?$article['short_description']:substr($article['short_description'],0,50)."..");
	$article_date='<p class="card-text small m-0 mb-4"><i class="bi bi-calendar2-week"></i> '.tep_date_long(tep_db_output($article['show_date'])).'</p>';
$MORE='<a href="article_'.$ide.'.html"  target="_blank">'.tep_db_output($description).'</a>';
$articles1.=$article_image.'<p class="m-0">'.$MORE.'</p>'.$article_date;
$count++;
}
//// CAREER TOOLS ENDS ////

$social_login_button='';
if(!check_login("jobseeker"))
{
 if(MODULE_FACEBOOK_PLUGIN=='enable' && MODULE_FACEBOOK_PLUGIN_JOBSEEKER=='enable')
 $social_login_button.=' <a href="'.FILENAME_FACEBOOK_APPLICATION.'" title="Sign in with Facebook" class="btn btn-default border btn-facebook"><i class="bi bi-facebook"></i></a>';

 if(MODULE_GOOGLE_PLUGIN=='enable' && MODULE_GOOGLE_PLUGIN_JOBSEEKER=='enable')
 $social_login_button.=' <a href="'.FILENAME_GOOGLE_APPLICATION.'" title="Sign in with Google" class="btn btn-default border btn-google-plus"><i class="bi bi-google"></i></a>';

 if(MODULE_LINKEDIN_PLUGIN=='enable' && MODULE_LINKEDIN_PLUGIN_JOBSEEKER=='enable')
 $social_login_button.=' <a href="'.FILENAME_LINKEDIN_APPLICATION.'" title="Sign in with Linkedin" class="btn btn-default border btn-linkedin"><i class="bi bi-linkedin"></i></a>';

 if(MODULE_TWITTER_PLUGIN_JOBSEEKER=='enable' && MODULE_TWITTER_SUBMITTER_OAUTH_CONSUMER_KEY!='')
 $social_login_button.=' <a href="'.FILENAME_TWITTER_APPLICATION.'" title="Sign in with Twitter" class="btn btn-default border btn-twitter"><i class="bi bi-twitter"></i></a>';
}

/*************************codeing to display different form and save search link for login and non login users *********************/
if(check_login("jobseeker"))
{
	$save_search= tep_draw_form('save_search', FILENAME_JOB_ALERT_AGENT,($edit?'sID='.$save_search_id:''),'post','onsubmit="return ValidateForm(this)"').tep_draw_hidden_field('action1','save_search');
    $INFO_TEXT_ALERT_TEXT=$save_search.(($action1=='save_search')?'':"<a href='#' onclick='document.save_search.submit();' class='btn btn-sm outline-secondary-theme9 btn-full py-2 mt-2'>Create Job Alert</a></form>");
}
else
{
	 $save_search= tep_draw_form('save_search', FILENAME_JOB_ALERT_AGENT_DIRECT,'','post','onsubmit="return ValidateForm(this)"').tep_draw_hidden_field('action','new');
	 $INFO_TEXT_ALERT_TEXT=$save_search.''.tep_draw_input_field('TREF_job_alert_email', $TREF_job_alert_email,'class="form-control" placeholder="Email Address" ',false).''."<button type='submit' class='btn btn-sm outline-secondary-theme9 btn-full py-2 mt-2'>Create Job Alert</button></form>";
}

/**********************************************************************************************************************************/

function count_jobs_for_company($company_name='')
{
    //   SELECT recruiter.recruiter_id,recruiter.recruiter_company_name, COUNT(jobs.recruiter_id) as total_jobs
    // FROM recruiter
    // LEFT JOIN jobs ON jobs.recruiter_id = recruiter.recruiter_id
    // WHERE recruiter.recruiter_company_name = 'BTC Times'
    // GROUP BY jobs.recruiter_id;
  $now=date('Y-m-d H:i:s');
  $total_job=0;
  $where ="j.expired >='$now' and j.re_adv <='$now' and j.job_status='Yes' and ( j.deleted is NULL or j.deleted='0000-00-00 00:00:00') and r.recruiter_company_name='$company_name'";
  if ($row=getAnyTableWhereData(RECRUITER_TABLE." as r left join ".JOB_TABLE." as j on j.recruiter_id = r.recruiter_id",$where,'r.recruiter_id,r.recruiter_company_name, COUNT(j.recruiter_id) as total_jobs')) {
    if($row['total_jobs']>0){
      $total_job=$row['total_jobs'];
    }
  }
  return $total_job;
}

/////////////////////////////////////////////////////////////////

$cat_array=tep_get_categories(JOB_CATEGORY_TABLE);
array_unshift($cat_array,array("id"=>0,"text"=>INFO_TEXT_ALL_CATEGORIES));
$default_country = DEFAULT_COUNTRY_ID;
$template->assign_vars(array(
'JOB_CATEGORY'=> $job_category,
'ARTICLE_HOME'=>$articles1,
 'searchform'=>tep_draw_form('search_select_form',FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').'<select name="days" onchange="this.form.submit()" class="form-select form-select-sm">
                                    <option value="0">Posted</option>
                                    <option value="7">Last 1 weeks</option>
                                    <option value="14">Last 2 weeks</option>
                                    <option value="21">Last 3 weeks</option>
                                    <option value="30">Last 30 days</option>
									<option>30+ days</option>
                                </select>'.tep_draw_hidden_field('job_post_day',$_POST["days"]).'</form>',
'INFO_JOBSEEKER_LOGIN_BOX'=>(check_login("jobseeker") || check_login("recruiter")?'':'

<div class="card card-custom mb-3 mt-3 theme9-card">
  <h5 class="card-header theme9-card-header">Job Seeker Login</h5>
  <div class="card-body card-body-custom">
    '.tep_draw_form('login', FILENAME_EMP_JOBSEEKER_LOGIN,'','post', 'onsubmit="return ValidateForm(this)"').tep_draw_hidden_field('action','check').'
	<div class="mb-3">
		'.tep_draw_input_field('TREF_email_address1', $TREF_email_address1,'class="form-control" placeholder="Email address" aria-describedby="basic-addon2" required',false,'email').'
	</div>
	<div class="mb-3">
		'.tep_draw_password_field('TR_password1', $TR_password1,false, 'class="form-control" placeholder="Password" aria-describedby="basic-addon2" required').'
	</div>

	<div class="form-check">
	'.tep_draw_checkbox_field('auto_login1','on', $checked1,'','id="auto_login1" class="form-check-input"').' <label class="text-muted form-check-label" for="auto_login1">Remember me</label>
	</div>

	<div class="form-group"><button type="submit" class="btn theme9-btn-warning2 btn-full my-3">Login</button></div>

	<div class="text-center"><a href="'.tep_href_link(FILENAME_JOBSEEKER_FORGOT_PASSWORD).'">Forget Password?</a></div>
	<div class="text-center">or</div>
	<div class="text-center">Sign in with</div>

	<div class="text-center mt-1">'.$social_login_button.'</div>

	<a type="button" class="btn btn-sm btn-full outline-secondary-theme9 mt-3" href="' . tep_href_link(FILENAME_JOBSEEKER_REGISTER1).'">Sign up</a>

</form>

</div>



</div>'),




'CONTACT_US'=>'<a href="'.tep_href_link(FILENAME_JOBSEEKER_CONTROL_PANEL).'" class="btn outline-secondary-theme9 btn-sm btn-block" type="button">Job alert</a>',
'JOBSEEKER_REGISTER'=>(check_login("jobseeker")?'<a href="'.tep_href_link(FILENAME_JOBSEEKER_CONTROL_PANEL).'" class="btn outline-secondary-theme9 btn-sm btn-block" type="button">Dashboard</a>':'<a href="'.tep_href_link(FILENAME_JOBSEEKER_REGISTER1).'" class="btn outline-secondary-theme9 btn-sm btn-block" type="button">Register</a>'),
'EMPLOYER_REGISTER'=>(check_login("recruiter")?'<a href="'.tep_href_link(FILENAME_RECRUITER_CONTROL_PANEL).'" class="btn outline-secondary-theme9 btn-sm btn-block" type="button">Dashboard</a>':'<a href="'.tep_href_link(FILENAME_RECRUITER_REGISTRATION).'" class="btn outline-secondary-theme9 btn-sm btn-block" type="button">Register</a>'),
 'jobseeker_login_form'=>tep_draw_form('login', FILENAME_JOBSEEKER_LOGIN,'','post', 'onsubmit="return ValidateForm(this)"').tep_draw_hidden_field('action','check'),
 'INFO_TEXT_EMAIL_ADDRESS'=>tep_draw_input_field('TREF_email_address1', $TREF_email_address1,'class="form-control2" placeholder="Email address" aria-describedby="basic-addon2"',false).' <span class="input-group-addon" id="basic-addon2"><i class="fa fa-user" aria-hidden="true"></i></span>',
 'INFO_TEXT_PASSWORD'=>tep_draw_password_field('TR_password1', $TR_password1,false, 'class="form-control2" placeholder="Password" aria-describedby="basic-addon2"').'<span class="input-group-addon" id="basic-addon2"><i class="fa fa-lock" aria-hidden="true"></i></span>',
 'button'=>'<button type="submit" class="btn btn-secondary">Login</button>',
 'FORGOT_PASSWORD'=>'<a href="'.tep_href_link(FILENAME_JOBSEEKER_FORGOT_PASSWORD).'">Forget Password?</a>',
 'AUTO_LOGIN1'=>tep_draw_checkbox_field('auto_login1','on', $checked1,'','id="auto_login1"').'<label for="auto_login1">Remember me</label>',
'INFO_TEXT_SOCIAL_LOGIN_BUTTON'=>$social_login_button,
 'NEW_USER_REGISTER_NOW'=>'<a type="button" class="btn btn-info" href="' . tep_href_link(FILENAME_JOBSEEKER_REGISTER1).'">New user Register Now</a>',
'JOB_CATEGORY'=>$job_category,
'JOB_COMPANY'=>$job_company,
'ARTICLE'=>$articles1,
 //'JOB_ALERT' => tep_draw_form('alert_job', FILENAME_JOB_ALERT_AGENT,'','post').tep_draw_input_field('TREF_job_alert_address2', $TREF_job_alert_address2,'class="form-control4" placeholder="Email Address"',false).'<button type="submit" class="btn btn-primary">Submit </button></form>',
 'JOB_ALERT' => $INFO_TEXT_ALERT_TEXT,
'TOTAL_RECENT_JOBS'=>$no_of_jobs,
'JOB_SEARCH'=>'<a href="'.tep_href_link(FILENAME_JOB_SEARCH_BY_INDUSTRY).'" class="badge badge-light border p-2 me-2">Category</a><a class="badge badge-light border p-2 me-2 mt-1" href="'.tep_href_link(FILENAME_JOBSEEKER_COMPANY_PROFILE).'">Company</a><a class="badge badge-light border p-2 me-2" href="'.tep_href_link(FILENAME_JOB_SEARCH_BY_LOCATION).'">Location</a><a class="badge badge-light border p-2 me-2" href="'.tep_href_link(FILENAME_JOB_BY_MAP).'">Map</a>',
'ADVANCE_SEARCH'=>'<a href="'.tep_href_link(FILENAME_JOB_SEARCH).'">Advanced Search</a>',
 'LEFT_BOX_WIDTH'=> '',
 'ALL_JOBS'=>tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').'<button class="btn btn-sm outline-secondary-theme9" type="submit">View all jobs</button></form>',
 'ALL_CATEGORY'=>'<button class="btn btn-lg btn-primary" onclick="location.href=\''.tep_href_link(FILENAME_JOB_SEARCH_BY_INDUSTRY).'\'" type="submit">All Categories</button>',
 'ALL_ARTICLES'=> '<button class="btn btn-lg btn-primary" onclick="location.href=\''.tep_href_link(FILENAME_ARTICLE).'\'" type="submit">All Articles</button>',
'jobseeker_pic'=>'<img src="'.HOST_NAME.'themes/theme9/images/jobseeker.jpg" alt="Job Seeker" class="card-img-top img-hover gray-img" />',
'employer_pic'=>'<img src="'.HOST_NAME.'themes/theme9/images/employer.jpg" alt="Employer" class="card-img-top img-hover gray-img" />',
'advertiser_pic'=>'<img src="'.HOST_NAME.'themes/theme9/images/advertise.jpg" alt="Advertise" class="card-img-top img-hover gray-img" />',
 'RIGHT_BOX_WIDTH'=> RIGHT_BOX_WIDTH1,
 'RIGHT_HTML'=> RIGHT_HTML,
 'LEFT_HTML'=> '',
 'update_message'=> $messageStack->output(),
		));
	?>