<?php
if($_SESSION['language']=="arabic")
{
 include_once(dirname(__FILE__).'/language/home_arabic.php');
}
else
{
 include_once(dirname(__FILE__).'/language/home_english.php');
}
#################FEATURED EMPLOYER############################
$featured_emp=banner_display("7",18,110,"class='theme3-featured-logo'");
for($i=0;$i<count($featured_emp);$i=$i+6)
{
  $template->assign_block_vars('banner', array(
                                'emp1'=>$featured_emp[$i],
								'emp2'=>$featured_emp[$i+1],
								'emp3'=>$featured_emp[$i+2],
								'emp4'=>$featured_emp[$i+3],
								'emp5'=>$featured_emp[$i+4],
								'emp6'=>$featured_emp[$i+5],
								));
}
/////////// FEATURED EMPLOYER END///////////////
//////FOOTER BANNER DISPLAY
$footer_banner=banner_display("1",2,728);
$home_footer_banner=$footer_banner[0];
//////////////////////////////////////////////////////////////////

//////HOMEPAGE LOGGED IN BANNER DISPLAY
$loggedin_banner=banner_display("2",1,250);
$home_loggedin_jobseeker_banner=$loggedin_banner[0];
$home_loggedin_recruiter_banner=$loggedin_banner[1];
//////////////////////////////////////////////////////////////////

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

function count_jobs_for_location($location='')
{
  $now=date('Y-m-d H:i:s');
  $total_job=0;
  $where ="j.expired >='$now' and j.re_adv <='$now' and j.job_status='Yes' and ( j.deleted is NULL or j.deleted='0000-00-00 00:00:00') and j.job_location='$location'";
  if ($row=getAnyTableWhereData(JOB_TABLE." as j ",$where,'count(j.job_location) as total')) {
    if($row['total']>0){
      $total_job=$row['total'];
    }
  }
  return $total_job;
}

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




$field_names="id,".TEXT_LANGUAGE."category_name";
$whereClause=" where sub_cat_id is null";
$query11 = "select $field_names from ".JOB_CATEGORY_TABLE." $whereClause  order by ".TEXT_LANGUAGE."category_name  asc limit 0,30";
$result11=tep_db_query($query11);
$i=1;
$job_category='<div class="row"><div class="col-md-4 col-sm-4 col-xs-12">';
while($row11 = tep_db_fetch_array($result11))
{
 $ide=$row11["id"];
  /*------------------*/
  $total_jobs=show_category_total_job($ide);
  if($total_jobs>0) {
    $total_jobs = ' ('.$total_jobs.')';
  } else {
    $total_jobs = '';
  }
 /*------------------*/
 $row11[TEXT_LANGUAGE.'category_name'];
 $job_category_form=tep_draw_form('job_category'.$i, FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search');
	$key=((strlen($row11[TEXT_LANGUAGE.'category_name'])<10)?$row11[TEXT_LANGUAGE.'category_name']:substr($row11[TEXT_LANGUAGE.'category_name'],0,26)."");
	$key1=$row11[TEXT_LANGUAGE.'category_name'];
	$job_category.="<p class='py-1 m-0'><a href='".$ide.'/'.encode_category($key1)."-jobs.html"."'  title='".tep_db_output($key1)."'>".tep_db_output($key1)."</a>".$total_jobs."</p>";
	if($i%10 == 0)
	{
     $job_category.='</div><div class="col-md-4 col-sm-4 col-xs-12 for-mobile">';
	}
$i++;
}
$job_category.="</div></div>";
/****************end of JOB CATEGORY******************/

#################JOB LOCATION############################
$default_country_value=DEFAULT_COUNTRY_ID;
$field_name12="zone_id,".TEXT_LANGUAGE."zone_name";
$whereclause12="where zone_country_id=$default_country_value";
$query12="select $field_name12 from ".ZONES_TABLE." $whereclause12 order by ".TEXT_LANGUAGE."zone_name asc limit 0,30";//.(int) MODULE_THEME_DEFAULT_MAX_JOB_LOCATION;
$result12=tep_db_query($query12);
$j=1;
$job_location='<div class="row"><div class="col-md-4 col-sm-4 col-xs-12">';
while($row12=tep_db_fetch_array($result12))
{
	$id12=$row12['zone_id'];
  $zone_name= $row12['zone_name'];
 $location_form=tep_draw_form('job_location'.$j, FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search');
	$key12=((strlen($row12[TEXT_LANGUAGE.'zone_name'])<23)
			?$row12[TEXT_LANGUAGE.'zone_name']
			:substr($row12[TEXT_LANGUAGE.'zone_name'],0,20)."...");
	$job_location.="".$location_form."
		<p class='py-1 m-0'>
			<a href='#' title='".tep_db_output($key12)."' onclick='document.job_location".$j.".submit()'>".tep_db_output($key12)."</a> (".count_jobs_for_location($zone_name).")
		</p>
		</form>";
	if($j%10 == 0)
	{
  $job_location.='
							</div>

							<div class="col-md-4 col-sm-4 col-xs-12">';
	}
$j++;
}
//$job_location.="<td align='right' colspan='6'><a href='".tep_href_link(FILENAME_JOB_SEARCH_BY_LOCATION)."' class='home_4'>".INFO_TEXT_HOME_MORE."&gt;&gt;</a></td></tr>";
$job_location.="</div></div>";
/****************end of JOB LOCATION******************/

/****************JOBS BY COMPANY*****************************/
$whereClause1="where rl.recruiter_status='Yes'";
$fields_c="recruiter_company_name,recruiter_email_address";
$query_c = "select $fields_c  from ".RECRUITER_TABLE." as r left join ".RECRUITER_LOGIN_TABLE." as rl on ( r.recruiter_id = rl.recruiter_id) $whereClause1 limit 0,30";//.(int) MODULE_THEME_DEFAULT_MAX_RECRUITER_COMPANY;
$result_c=tep_db_query($query_c);//echo "<br>$query";//exit;
$x=tep_db_num_rows($result_c);//echo $x;exit;
$k=1;
$company_name1_old="";
$company_form=tep_draw_form('company_search', FILENAME_JOBSEEKER_COMPANY_PROFILE,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('company_name','');
$job_company='<div class="row"><div class="col-md-4 col-sm-4 col-xs-12">
							'.$company_form.'';
while($row_c=tep_db_fetch_array($result_c))
{
	$company_name1=strtoupper(substr($row_c["recruiter_company_name"],0,1));
	$company_name="";
 if($company_name1!=$company_name1_old || $company_name1_old=='')
 {
  $title="<tr><td colspan='2' class='header_bold' ><b><a id='".tep_db_output($company_name1)."'>".tep_db_output($company_name1)."</a></b></td></tr>";
  $link_array[]=$company_name1;
 }
 $email_id=$row_c["recruiter_email_address"];
 $query_string1=encode_string("recruiter_email=".$email_id."=mail");
 $company_name="<p class='py-1 m-0'><a href='#' onclick='search_company(\"".$query_string1."\")' class='home_4'>".tep_db_output($row_c['recruiter_company_name'])."</a> (
 ".count_jobs_for_company(tep_db_output($row_c['recruiter_company_name'])).")</p>";
	$job_company.="".$company_name."";
	if($k%10 == 0)
	{
		$job_company.='</form>
							</div>

							<div class="col-md-4 col-sm-4 col-xs-12">
							';
	}
	$company_name1_old=$company_name1;
 $k++;
}
$job_company.="</form></div></div>";
/***************end of JOBS BY COMPANY************************/

//////////////////// LATEST JOBS STARTS ///////////////////
$now=date('Y-m-d H:i:s');
$table_names=JOB_TABLE." as j,".RECRUITER_LOGIN_TABLE.' as rl,'.RECRUITER_TABLE.' as r';
$whereClause="j.recruiter_id=rl.recruiter_id and rl.recruiter_id=r.recruiter_id and rl.recruiter_status='Yes'and j.expired >='$now' and j.re_adv <='$now' and j.job_status='Yes' and ( j.deleted is NULL or j.deleted='0000-00-00 00:00:00') ";//
$field_names="j.job_id, j.job_title,j.job_location,j.job_type, j.job_salary,j.job_featured,j.min_experience,j.max_experience,j.re_adv,r.recruiter_company_name,r.recruiter_logo,j.job_country_id";
$order_by_field_name = "j.inserted";
$query = "select $field_names from $table_names where $whereClause order by $order_by_field_name limit 0,5";
// $query = "select $field_names from $table_names where $whereClause order by rand() limit 0,6";// " . (int) MODULE_THEME_JOBSITE12_MAX_LATEST_JOB;

//echo "<br>$query";exit;
$result=tep_db_query($query);
$x=tep_db_num_rows($result);
//echo $x;exit;
$count=1;
while($row = tep_db_fetch_array($result))
{
 $ide=$row["job_id"];
 $title_format=encode_category($row['job_title']);
 $query_string=encode_string("job_id=".$ide."=job_id");

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

 ///logo
$comp_logo='';
if(tep_not_null($row["recruiter_logo"]) && is_file(PATH_TO_MAIN_PHYSICAL.PATH_TO_LOGO.$row["recruiter_logo"]))
    $comp_logo=tep_image(FILENAME_IMAGE."?image_name=".PATH_TO_LOGO.$row["recruiter_logo"],'','','','','class="featured-logo2 thumbnail img-hover"');
  else
    $comp_logo=tep_image(FILENAME_IMAGE."?image_name=".PATH_TO_IMG."nologo.jpg",'','','','','class="featured-logo2 thumbnail img-hover"');
  ///////////

$company=$row['recruiter_company_name'];

$title=' <a href="'.tep_href_link($ide.'/'.$title_format.'.html').'" target="_blank">'.$row['job_title'].'</a>';
$country=get_name_from_table(COUNTRIES_TABLE, 'country_name', 'id',tep_db_output($row['job_country_id']));
 $location=tep_db_output($row['job_location']);
 $company_address=tep_not_null($location)?"$location, $country":"$country";

 $template->assign_block_vars('latest_jobs', array(
                                'title'    => $title,
                                'location'    =>$company_address,
                                'company'=> $company,
                                'clogo'=>$comp_logo,
                                'jobtype'   => $jobtype,
                                'salary'    => $salary,
                                'job_posted' => $job_posted,
                                'job_featured'=> (tep_db_output($row['job_featured']) == 'Yes') ? 'featured-job-tag' : '',
                                'experience'  => tep_db_output(calculate_experience($row['min_experience'],$row['max_experience'])),
                              ));
 $count++;
}
//// LATEST JOB ENDS ////


///////////////////////////////////////
if($_SESSION['language']=="arabic")
{
	$home_email_address= tep_draw_input_field('TREF_email_address', $TREF_email_address,'class="form-control form-control-theme3" placeholder="عنوان البريد الإلكتروني"',false);
	$home_password=tep_draw_password_field('TR_password','',false,'class="form-control form-control-theme3" placeholder="كلمه السر"');
	$home_confirm_password=tep_draw_password_field('TR_confirm_password','',false,'class="form-control form-control-theme3" placeholder="تأكید كلمة السر"');
	$home_keyword=tep_draw_input_field('keyword','','class="form-control form-control-theme3" placeholder="كلمة البحث" ',false);
}
else
{
	$home_email_address= tep_draw_input_field('TREF_email_address', $TREF_email_address,'class="form-control form-control-theme3 mb-3" placeholder="E-mail Address"',false);
	$home_password=tep_draw_password_field('TR_password','',false,'class="form-control form-control-theme3  fc-border  form-control form-control-theme3 mb-3" placeholder="Password"');
	$home_confirm_password=tep_draw_password_field('TR_confirm_password','',false,'class="form-control form-control-theme3 mb-3" placeholder="Verify Password"');
	$home_keyword=tep_draw_input_field('keyword','','class="form-control form-control-theme3" placeholder="Keyword" ',false);
}
////////////////////////////////////////////////////////////////////

/*************************codeing to display different form and save search link for login and non login users *********************/
if(check_login("jobseeker"))
{
	$save_search= tep_draw_form('save_search', FILENAME_JOB_ALERT_AGENT,($edit?'sID='.$save_search_id:'class="d-flex"'),'post','onsubmit="return ValidateForm(this)"').tep_draw_hidden_field('action1','save_search');
    $INFO_TEXT_ALERT_TEXT=$save_search.(($action1=='save_search')?'':"<a href='#' onclick='document.save_search.submit();' class='btn btn-white'><i class='bi bi-bell-fill'></i></a></form>");
}
else
{
	 $save_search= tep_draw_form('save_search', FILENAME_JOB_ALERT_AGENT_DIRECT,'','post','onsubmit="return ValidateForm(this)"').tep_draw_hidden_field('action','new');
	 $INFO_TEXT_ALERT_TEXT=$save_search.''.tep_draw_input_field('TREF_job_alert_email', $TREF_job_alert_email,'class="form-control form-control-theme3 border-0" style="width:100%;" placeholder="Email Address" ',false).''."<button type='submit' class='btn btn-white'><i class='bi bi-bell-fill'></i></button></form>";
}

/**********************************************************************************************************************************/

//////////////////////////////////////////////////////////////////////////

// Latest Articles
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

	if(strlen($article['title']) > 20)
  $article_name_short=	substr($article['title'],0,15).'..';
 else
  $article_name_short=	substr($article['title'],0,20);
 $title='<a href="'.$article_url.'"  target="_blank">'.$article_name_short.'</a>';
  $article_image='';
  if(tep_not_null($article["article_photo"]) && is_file(PATH_TO_MAIN_PHYSICAL.PATH_TO_ARTICLE_PHOTO.$article["article_photo"]))
    $article_image='<a href="'.$article_url.'"  target="_blank">'.tep_image(FILENAME_IMAGE."?image_name=".PATH_TO_ARTICLE_PHOTO.$article["article_photo"]."&size=400",'','','','class="img-fluid img-hover article-img"').'</a>';
  else
    $article_image='<a href="'.$article_url.'" target="_blank">'.tep_image(FILENAME_IMAGE."?image_name=".PATH_TO_ARTICLE_PHOTO."blank_com.gif",'','','','class="img-fluid img-hover article-img"').'</a>';
 	$description=((strlen($article['short_description'])<90)?$article['short_description']:substr($article['short_description'],0,75)."..");
//$MORE='<a href="article_'.$ide.'.html"  target="_blank"><span class="new_style13">...<u>more&gt;&gt;</u></span></a>';
$articles1.='


<div class="col-md-4 mb-3">
<div class="card card-animation">
  '.$article_image.'
  <div class="card-body">
	<h3 class="card-title">'. $title.'</h3>
    <p class="card-text">'.tep_db_output($description).''.$MORE.'</p>
    <p class="card-text small"><i class="bi bi-calendar2-week"></i> '.tep_date_long(tep_db_output($article['show_date'])).'</p>
   </div>
</div>
</div>
';

$count++;
}



$cat_array=tep_get_categories(JOB_CATEGORY_TABLE);
array_unshift($cat_array,array("id"=>0,"text"=>INFO_TEXT_ALL_CATEGORIES));
$default_country = DEFAULT_COUNTRY_ID;
$template->assign_vars(array(
//'INFO_TEXT_HEADER_LINK' => $header_link,
'JOBSEEKER_LOGIN'=>'<button class="btn btn-success btn-success-theme3" onclick="location.href=\''.tep_href_link(FILENAME_JOBSEEKER_LOGIN).'\'"" type="submit">'.INFO_TEXT_H_J_LOGIN.'</button>',
'RECRUITER_LOGIN'=>'<button class="btn btn-primary btn-primary-theme3" onclick="location.href=\''.tep_href_link(FILENAME_RECRUITER_LOGIN).'\'"" type="submit">'.INFO_TEXT_R_EMPLOYER_LOGIN.'</button>',
'INFO_TEXT_WELCOME_HEADING'=>INFO_TEXT_WELCOME_HEADING,
'INFO_TEXT_M_QUICK_SEARCH'=>INFO_TEXT_M_QUICK_SEARCH,
'INFO_TEXT_MESSAGE'=>INFO_TEXT_MESSAGE,
'INFO_TEXT_JOBSEEKER'=>INFO_TEXT_JOBSEEKER,
'INFO_TEXT_RECRUITER'=>INFO_TEXT_RECRUITER,
'INFO_TEXT_RESUME_SERVICES'=>INFO_TEXT_RESUME_SERVICES,
'INFO_TEXT_RESUME_DISPLAY'=>INFO_TEXT_RESUME_DISPLAY,
'INFO_TEXT_LATEST_JOBS'=>INFO_TEXT_LATEST_JOBS,
'INFO_TEXT_ARTICLES'=>INFO_TEXT_ARTICLES,
'INFO_TEXT_JOBS_BY_CATEGORY'=>INFO_TEXT_JOBS_BY_CATEGORY,
'INFO_TEXT_JOBS_BY_COMPANY'=>INFO_TEXT_JOBS_BY_COMPANY,
'INFO_TEXT_JOBS_BY_LOCATION'=>INFO_TEXT_JOBS_BY_LOCATION,
'INFO_TEXT_EMPLOYER_SPOTLIGHT'=>INFO_TEXT_EMPLOYER_SPOTLIGHT,
'INFO_ADVANCED_SEARCH' => '<a href="'.tep_href_link(FILENAME_JOB_SEARCH).'" class="d-block mt-2 text-white">Advanced Search</a>',
 'job_search_form' => tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search'),
 'INFO_TEXT_KEYWORD' => $home_keyword,
 'location'=> LIST_TABLE(COUNTRIES_TABLE,TEXT_LANGUAGE."country_name","priority","name='country' class='form-select form-select-theme3'",INFO_TEXT_COUNTRY,"",DEFAULT_COUNTRY_ID),
 'industry_sector' => tep_draw_pull_down_menu('job_category[]', $cat_array, '', 'class="form-select form-select-theme3"'),
 'SEARCH_BUTTON'=>'<button type="submit" class="btn btn-success btn-full btn-success-theme3">
                                    <span class="glyphicon glyphicon-search"></span>'.INFO_HOME_SEARCH_BUTTON.'
                                    </button>',
  'ARTICLE_HOME'=>$articles1,
  'LATEST_ARTICLES'=>LATEST_ARTICLES,
  'ALL_ARTICLES' => '<button class="btn btn-outline-secondary-home" onclick="location.href=\''.tep_href_link(FILENAME_ARTICLE).'\'" type="submit">'.ALL_ARTICLES.'</button>',
// 'jobseeker_registration_form'=>tep_draw_form('registration', FILENAME_JOBSEEKER_REGISTER1, '', 'post').tep_draw_hidden_field('action','new'),

'INFO_TEXT_H_CREATE_ACCOUNT'=>INFO_TEXT_H_CREATE_ACCOUNT,
'CREATE_ACCOUNT_HOME'=>(check_login("jobseeker")?''.$home_loggedin_jobseeker_banner.'': ( check_login("recruiter")?''.$home_loggedin_recruiter_banner.'':('	<!--<h5 class="card-header">'.INFO_TEXT_H_CREATE_ACCOUNT.'</h5>-->
<!-- Tabs -->
<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
      <button class="nav-link active nav-link-theme3" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">'.INFO_TEXT_JOBSEEKER.'</button>
      <button class="nav-link nav-link-theme3" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">'.INFO_TEXT_RECRUITER.'</button>
    </div>
  </nav>
  <div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
        <!-- Jobseeker Tab -->
        <div class="card card-theme3">
            <div class="card-body"> '.tep_draw_form('registration', FILENAME_JOBSEEKER_REGISTER1, '',
                'post').tep_draw_hidden_field('action','new').'
                '.$home_email_address
                .$home_password
                .$home_confirm_password.'
                <button class="btn btn-primary-theme3 btn-full" data-loading-text="Loading ..."
                    type="submit">'.INFO_HOME_REGISTER_NOW.'</button>
                <a class="btn btn-outline-theme3 btn-full mt-3" href="'.tep_href_link(FILENAME_JOBSEEKER_LOGIN).'">'.INFO_HOME_SIGN_IN.'</a>
                </form>
            </div>
        </div>
        <!-- End Jobseeker Tab -->
    </div>
    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
        <!-- Employer Tab -->
        <div class="card card-theme3">
            <div class="card-body"> '.tep_draw_form('registration', FILENAME_RECRUITER_REGISTRATION, '',
                'post').tep_draw_hidden_field('action','new').'
                '.$home_email_address
                .$home_password
                .$home_confirm_password
                .'<button class="btn btn-primary-theme3 btn-full" data-loading-text="Loading ..."
                    type="submit">Register Now </button>
                <a class="btn btn-outline-theme3 btn-full mt-3" href="'.tep_href_link(FILENAME_RECRUITER_LOGIN).'">Sign in</a>
            </div>
        </div>
        </form>
        <!-- End Employer Tab -->
    </div>
  </div>
<!-- Tabs -->


'))),
'CREATE_ALERT'=>$INFO_TEXT_ALERT_TEXT,

'FOOTER_BANNER'=>$home_footer_banner,
 'JOB_CATEGORY'              => $job_category,
 'JOB_LOCATION'              => $job_location,
 'JOB_COMPANY'               => $job_company,
 'LEFT_BOX_WIDTH'     =>'',
 'RIGHT_BOX_WIDTH'    =>RIGHT_BOX_WIDTH1,
 'RIGHT_HTML'         =>RIGHT_HTML,
 'LEFT_HTML'=>'',
 'update_message'=>$messageStack->output()));
?>