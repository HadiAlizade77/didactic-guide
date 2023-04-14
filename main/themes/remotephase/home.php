<?php
if($_SESSION['language']=='english')
  include_once(dirname(__FILE__).'/language/home_english.php');


$preview_box_jscript_file=PATH_TO_LANGUAGE.$language."/jscript/".'previewbox_home_page.js';
#################FEATURED EMPLOYER############################
$feat_emp=banner_display("7",12,130,'class="card-custom  mb-3 theme1-featured-logo"');
for($i=0;$i<count($feat_emp);$i++)
{
  $template->assign_block_vars('featured', array(
                                'employer'=>$feat_emp[$i],
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
$query11 = "select $field_names from ".JOB_CATEGORY_TABLE." $whereClause  order by ".TEXT_LANGUAGE."category_name  asc limit 0,15";// . (int)MODULE_THEME_JOBSITE8_MAX_JOB_CATEORY;";
$result11=tep_db_query($query11);
$i=1;
$job_category="<div class='col-md-4'>
			<div class='categories'>";
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
	$key=((strlen($row11[TEXT_LANGUAGE.'category_name'])<20)?$row11[TEXT_LANGUAGE.'category_name']:substr($row11[TEXT_LANGUAGE.'category_name'],0,15)."..");
	$key1=$row11[TEXT_LANGUAGE.'category_name'];
	$job_category.="<div class='my-2'><a href='".$ide.'/'.encode_category($key1)."-jobs.html"."'  title='".tep_db_output($key1)."' class=''>".tep_db_output($key1)."</a>".$total_jobs."</div>";
	if($i%5 == 0)
	{
   $job_category.="</div></div><div class='col-md-4'><div class='categories'>";
	}
$i++;
}
 $job_category.="</div></div>";
/****************end of JOB CATEGORY******************/

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
$field_names="j.job_id, j.job_title, j.job_type, j.job_salary,j.job_featured, j.job_location,j.job_short_description,j.inserted,j.min_experience,j.max_experience, j.re_adv, r.recruiter_company_name,job_country_id,r.recruiter_logo";
$order_by_field_name = "j.inserted";
// $query = "select $field_names from $table_names where $whereClause order by rand() DESC limit 0,6" ;// " . (int) MODULE_THEME_JOBSITE12_MAX_LATEST_JOB;
$query = "select $field_names from $table_names where $whereClause order by $order_by_field_name DESC limit 0,3" ;// " . (int) MODULE_THEME_JOBSITE12_MAX_LATEST_JOB;

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

  if(strlen($row['recruiter_company_name']) > 20)
  $company_name_short=	substr($row['recruiter_company_name'],0,15).'..';
 else
  $company_name_short=	substr($row['recruiter_company_name'],0,20);
	$company=$company_name_short;

  /////logo
 $recruiter_logo='';
 $company_logo=$row['recruiter_logo'];
 if(tep_not_null($company_logo) && is_file(PATH_TO_MAIN_PHYSICAL.PATH_TO_LOGO.$company_logo))
     $recruiter_logo=tep_image(FILENAME_IMAGE."?image_name=".PATH_TO_LOGO.$company_logo."&size=150",'','','','class="featured-logo thumbnail img-responsive img-hover"');
else
     $recruiter_logo=defaultProfilePhotoUrl($row['job_title'],false,55, 'class="featured-logo" ');


///////////////
$description=(strlen($row['job_short_description'])>80?substr($row['job_short_description'],0,75).'..':$row['job_short_description']);
if(strlen($row['job_title']) > 30)
  $name_short=	substr($row['job_title'],0,25).'..';
 else
  $name_short=	substr($row['job_title'],0,30);
 $title=' <a href="'.tep_href_link($ide.'/'.$title_format.'.html').'">'.$name_short.'</a>';
//  $title= $name_short;

///job type
$row_type=getAnyTableWhereData(JOB_TYPE_TABLE,"id='".$row['job_type']."'",'type_name');

//echo $row_type['type_name'];
if ($row_type['type_name']) {
  $jobtype='<span class="'.$row_type['type_name'].'">'.$row_type['type_name'].'</span>';
}
else {
  $jobtype='<span class="Full-time">Full time</span>';
}

// salary with currency
$row_cur = getAnyTableWhereData(CURRENCY_TABLE, "code ='" . DEFAULT_CURRENCY . "'", 'symbol_left,symbol_right');
$sym_left = (tep_not_null($row_cur['symbol_left']) ? $row_cur['symbol_left'] . '' : '');
$salary = (tep_not_null($row['job_salary']) ? $sym_left . tep_db_output($row['job_salary']) . $sym_rt : 'Negotiable');
$job_posted =  date("m-d-Y", strtotime($row['re_adv']));//tep_db_output($row['re_adv']);

$save_job    ='
                <div class="d-flex ms-auto">
                    <a id="'.$query_string.'" href="'.tep_href_link(FILENAME_JOBSEEKER_LOGIN).'">
                        <i class="bi bi-heart" style="color: #E4E4ED;font-size: 22px;"></i>
                    </a>
                </div>
              ';

if(check_login("jobseeker")){
  if($row_check=getAnyTableWhereData(SAVE_JOB_TABLE,"jobseeker_id='".$_SESSION['sess_jobseekerid']."' and job_id='".$ide."'")){
    $save_job = '
    <div class="d-flex ms-auto">
      <i class="bi bi-heart" style="color: #e91111;font-size: 22px;"></i>
    </div>
    ';
  } else {
    $save_job ='
    <div class="d-flex ms-auto">
        <a id="'.$query_string.'" href="'.tep_href_link(FILENAME_JOB_DETAILS,'query_string1='.$query_string.'&action=save').'">
            <i class="bi bi-heart" style="color: #E4E4ED;font-size: 22px;"></i>
        </a>
    </div>';
  }
}

 $template->assign_block_vars('latest_jobs', array(
                              'job_id'    => $row['job_id'],
                              'title'     => $title,
                              'location'  => tep_db_output($row['job_location']) ? ''. tep_db_output($row['job_location']): '',
						                  'logo'	    => $recruiter_logo,
                              'summary'   => $description,
                              'jobtype'   => $jobtype,
                              'salary'    => $salary,
                              'job_posted' => $job_posted,
                              'job_featured'=> (tep_db_output($row['job_featured']) == 'Yes') ? 'featured-job-tag' : '',
							                'company'   =>$row['recruiter_company_name'],
                              'experience'  => ''.tep_db_output(calculate_experience($row['min_experience'],$row['max_experience'])),
                              'save_job'    => $save_job
                              ));
 $count++;
}
//// LATEST JOB ENDS ////

//////////////////// CAREER TOOLS STARTS ///////////////////
$now=date("Y-m-d H:i:s");
$query = "select a.id,a.title,a.short_description,a.article_photo,a.show_date  from ".ARTICLE_TABLE." as a  where a.show_date <='$now' and a.is_show='Yes'  order by rand() limit 0,3";
//echo "<br>$query";//exit;
$result1=tep_db_query($query);
$x=tep_db_num_rows($result1);
$count=1;
$articles1='';
$articles1.='';
while($article = tep_db_fetch_array($result1))
{
 $ide=$article['id'];
	if(strlen($article['title']) > 20)
  $article_name_short=	substr($article['title'],0,55).'..';
 else
  $article_name_short=	substr($article['title'],0,55);
//  $title='<a href="article_'.$ide.'.html"  target="_blank">'.$article_name_short.'</a>';
 $title='<p>'.$article_name_short.'</p>';
  $article_image='';
  if(tep_not_null($article["article_photo"]) && is_file(PATH_TO_MAIN_PHYSICAL.PATH_TO_ARTICLE_PHOTO.$article["article_photo"]))
    $article_image='<a href="article_'.$ide.'.html"  target="_blank">'.tep_image(FILENAME_IMAGE."?image_name=".PATH_TO_ARTICLE_PHOTO.$article["article_photo"]."&size=400",'','','','class="card-img-top"').'</a>';
  else
    $article_image='<a href="article_'.$ide.'.html" target="_blank">'.tep_image(FILENAME_IMAGE."?image_name=".PATH_TO_ARTICLE_PHOTO."blank_com.gif",'','','','class="card-img-top"').'</a>';
 	$description=((strlen($article['short_description'])<90)?$article['short_description']:substr($article['short_description'],0,105)."..");
//$MORE='<a href="article_'.$ide.'.html"  target="_blank"><span class="new_style13">...<u>more&gt;&gt;</u></span></a>';
$articles1.='


<div class="col-md-4 mb-3">
<div class="card card-custom" style="border: 1px solid #E4E4ED;border-radius: 7px;">
    <div class="card-body card-body-custom">
	<h3 class="card-title article-title">'. $title.'</h3>
    <p class="card-text jdesc">'.tep_db_output($description).'
    <div>
    <a class="btn btn-sm btn-primary btn-article" href="article_'.$ide.'.html" target="_new">
      Read Article
    </a></div>'.$MORE.'</p>
   </div>
</div>
</div>
';

$count++;
}
//// CAREER TOOLS ENDS ////

/*************************codeing to display different form and save search link for login and non login users *********************/
if(check_login("jobseeker"))
{
	$save_search= tep_draw_form('save_search', FILENAME_JOB_ALERT_AGENT,($edit?'sID='.$save_search_id:''),'post','onsubmit="return ValidateForm(this)" class="input-group"').tep_draw_hidden_field('action1','save_search');
    $INFO_TEXT_ALERT_TEXT=$save_search.(($action1=='save_search')?'':"<a href='#' onclick='document.save_search.submit();' class='btn btn-danger'>Create Job Alert</a></form>");
}
else
{
	 $save_search= tep_draw_form('save_search', FILENAME_JOB_ALERT_AGENT_DIRECT,'','post','onsubmit="return ValidateForm(this)" class="input-group"').tep_draw_hidden_field('action','new');
	 $INFO_TEXT_ALERT_TEXT=$save_search.''.tep_draw_input_field('TREF_job_alert_email', $TREF_job_alert_email,'class="form-control d-flex" placeholder="Email Address" ',false).''."<button type='submit' class='btn btn-danger d-flex ms-auto input-group-text' id='basic-addon2'>GO</button></form>";
}



/**********************************************************************************************************************************/
// more remote job btn


$more_remote_job_btn = tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search') . '
          <button type="submit" class="btn btn-primary w-100">More Remote Jobs</button></form>
        ';
$search_remote_job_btn = tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search') . '
          <button type="submit" class="btn btn-sm btn-article2 btn-primary">Search for a Remote Job</button></form>
        ';


$cat_array=tep_get_categories(JOB_CATEGORY_TABLE);
array_unshift($cat_array,array("id"=>0,"text"=>INFO_TEXT_ALL_CATEGORIES));
$default_country = DEFAULT_COUNTRY_ID;
$template->assign_vars(array(
'JOB_CATEGORY'=> $job_category,
'ARTICLE_HOME'=>$articles1,
'CONTACT_US'=>'<a href="'.tep_href_link(FILENAME_CONTACT_US).'">'.CONTACT_US.'</a>',

'HOME_RIGHT_BANNER'=>$home_right_banner,
'JOBSEEKER_SIGN_UP'=>(check_login('jobseeker')?'<button class="btn btn-outline-secondary" onclick="location.href=\''.tep_href_link(FILENAME_LOGOUT).'\'" type="submit">'.SIGN_OUT.' </i></button>':'<button class="more-link" onclick="location.href=\''.tep_href_link(FILENAME_JOBSEEKER_REGISTER1).'\'" type="submit">'.SIGN_UP.' </i></button>'),
'RECRUITER_SIGN_UP'=>(check_login('recruiter')?'<button class="btn btn-outline-secondary" onclick="location.href=\''.tep_href_link(FILENAME_LOGOUT).'\'" type="submit">'.SIGN_OUT.' </i></button>':'<button class="more-link" onclick="location.href=\''.tep_href_link(FILENAME_RECRUITER_REGISTRATION).'\'" type="submit">'.SIGN_UP.' </i></button>'),
'ADVERTISER_SIGN_UP'=>'<button class="more-link" onclick="location.href=\''.tep_href_link(FILENAME_CONTACT_US).'\'" type="submit">Contact Us </i></button>',
'INFO_MESSAGE1'=>INFO_MESSAGE1,
'LATEST_JOBS'=>LATEST_JOBS,
'JOB_CATEGORY_TEXT'=>JOB_CATEGORY_TEXT,
'FEATURED_RECRUITERS'=>FEATURED_RECRUITERS,
'FEATURED_RECRUITERS_TEXT'=>FEATURED_RECRUITERS_TEXT,
'LATEST_ARTICLES'=>LATEST_ARTICLES,
'WELCOME_MESSAGE'=>WELCOME_MESSAGE,
'WELCOME_MESSAGE_TEXT'=>WELCOME_MESSAGE_TEXT,
'INFO_JOBSEEKER'=>INFO_JOBSEEKER,
'INFO_EMPLOYER'=>INFO_EMPLOYER,
'EMPLOYER_TEXT'=>EMPLOYER_TEXT,
'INFO_ADVERTISER'=>INFO_ADVERTISER,
'ADVERTISER_TEXT'=>ADVERTISER_TEXT,
'ABOUT_US_HEADING'=>ABOUT_US_HEADING,
'ABOUT_US_HEADING2'=>ABOUT_US_HEADING2,
'ABOUT_US_TEXT'=>ABOUT_US_TEXT,
'GET_EMAIL_TEXT'=>GET_EMAIL_TEXT,
'SUBMIT_RESUME_TEXT'=>SUBMIT_RESUME_TEXT,
 'LEFT_BOX_WIDTH'=> '',
 'ALL_JOBS'=>tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').'<button class="more-link" type="submit">'.ALL_JOBS.' </i></button></form>',
 'ALL_CATEGORY'=>'<button class="more-link" onclick="location.href=\''.tep_href_link(FILENAME_JOB_SEARCH_BY_INDUSTRY).'\'" type="submit"><span class="for-mobile">'.ALL_CATEGORIES.'</span> </i></button>',
 'ALL_RECRUITERS'=>'<button class="more-link" onclick="location.href=\''.tep_href_link(FILENAME_JOBSEEKER_COMPANY_PROFILE).'\'" type="submit"><span class="for-mobile">'.ALL_RECRUITERS.'</span> </i></button>',
 'ALL_ARTICLES'=> '<button class="more-link" onclick="location.href=\''.tep_href_link(FILENAME_ARTICLE).'\'" type="submit"><span class="for-mobile">'.ALL_ARTICLES.'</span> </i></button>',
'CREATE_ALERT'=>$INFO_TEXT_ALERT_TEXT,
 'RIGHT_BOX_WIDTH'=> RIGHT_BOX_WIDTH1,
 'RIGHT_HTML'=> RIGHT_HTML,
 'LEFT_HTML'=> '',
 'update_message'=> $messageStack->output(),
 'PREVIEW_BOX_JSCRIPT_FILE' => $preview_box_jscript_file,
 'MORE_REMOTE_JOB'  => $more_remote_job_btn,
 'SEARCH_REMOTE_JOB'  => $search_remote_job_btn,
 'POST_JOB' => '<a class="btn btn-sm btn-article3 btn-primary" href="'.tep_href_link(FILENAME_RECRUITER_POST_JOB).'">Post a Remote Job</a>',
		));
	?>