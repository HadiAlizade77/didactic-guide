<?php
if($_SESSION['language']=='english')
  include_once(dirname(__FILE__).'/language/home_english.php');


#################FEATURED EMPLOYER############################
$featured_emp=banner_display("7",8,130,"class='theme8-featured-img'","class='theme8-featured-img'");
//print_r($featured_emp);
for($i=0;$i<count($featured_emp);$i=$i+18)
{
  $template->assign_block_vars('banner', array(
                                'banner1'=>$featured_emp[$i],
								'banner2'=>$featured_emp[$i+1],
								'banner3'=>$featured_emp[$i+2],
								'banner4'=>$featured_emp[$i+3],
								'banner5'=>$featured_emp[$i+4],
								'banner6'=>$featured_emp[$i+5],
                                'banner7'=>$featured_emp[$i+6],
								'banner8'=>$featured_emp[$i+7],
								'banner9'=>$featured_emp[$i+8],
								'banner10'=>$featured_emp[$i+9],
								'banner11'=>$featured_emp[$i+10],
								'banner12'=>$featured_emp[$i+11],
                                'banner13'=>$featured_emp[$i+12],
								'banner14'=>$featured_emp[$i+13],
								'banner15'=>$featured_emp[$i+14],
								'banner16'=>$featured_emp[$i+15],
								'banner17'=>$featured_emp[$i+16],
								'banner18'=>$featured_emp[$i+17],

								));
}
/////////// FEATURED EMPLOYER END///////////////
#################JOB SKILLS############################
$field_names="id,tag";
$whereClause=" where status ='active'";
$querysk = "select $field_names from ".SKILL_TAGS_TABLE." $whereClause  order by rand() limit 0,15";//.(int) MODULE_THEME_DEFAULT_MAX_JOB_CATEORY;
$resultsk=tep_db_query($querysk);
$i=1;
$job_skill="<div class='row'><div class='col-md-4'>";
while($rowsk = tep_db_fetch_array($resultsk))
{
 $ide=$rowsk["id"];
$tag=getSkillTagLink (ucfirst($rowsk['tag']),' Jobs');
 $job_skill_form=tep_draw_form('job_skill'.$i, FILENAME_JOB_SEARCH_BY_SKILL,'','post').	tep_draw_hidden_field('skill',$tag);
 //$key=((strlen($row11['category_name'])<30)?$row11['category_name']:substr($row11['category_name'],0,27)."...");
	$key=((strlen($rowsk['tag'])<30)?$rowsk['tag']:substr($rowsk['tag'],0,28)."..");
	$key1=$rowsk['tag'];

$job_skill.=$job_skill_form."<p>".$tag."</p></form>";
	//$job_skill.=$job_skill_form."<p><a href='".$ide.'/'.encode_category($key1)."-jobs.html"."'  title='".tep_db_output($key1)."'>".tep_db_output($key)."</a></p></form>";
	if($i%5 == 0)
	{
     $job_skill.="</div><div class='col-md-4 for-mobile'>";
	}
$i++;
}
$job_skill.="</div></div>";
/****************end of JOB SKILLS ******************/

#################JOB CATEGORY############################
// count jobs for job category
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
$query11 = "select $field_names from ".JOB_CATEGORY_TABLE." $whereClause  order by ".TEXT_LANGUAGE."category_name  asc limit 0,15"; // .(int) MODULE_THEME_DEFAULT_MAX_JOB_CATEORY;
$result11=tep_db_query($query11);
$i=1;
$job_category="<div class='row'><div class='col-md-4'>";
while($row11 = tep_db_fetch_array($result11))
{
 $ide=$row11["id"];
 $total_jobs=show_category_total_job($ide);
 $row11[TEXT_LANGUAGE.'category_name'];
 $job_category_form=tep_draw_form('job_category'.$i, FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search');
 //$key=((strlen($row11['category_name'])<30)?$row11['category_name']:substr($row11['category_name'],0,27)."...");
 $key=((strlen($row11[TEXT_LANGUAGE.'category_name'])<30)?$row11[TEXT_LANGUAGE.'category_name']:substr($row11[TEXT_LANGUAGE.'category_name'],0,28)."..");
 $key1=$row11[TEXT_LANGUAGE.'category_name'];
 $job_category.=$job_category_form."<p><a href='".$ide.'/'.encode_category($key1)."-jobs.html"."'  title='".tep_db_output($key1)."'>".tep_db_output($key)."</a> $total_job</p></form>";
if($i%5 == 0)
{
	$job_category.="</div><div class='col-md-4 for-mobile'>";
}
$i++;
}
//$job_category.="<td align='right' colspan='4'><a href='".tep_href_link(FILENAME_JOB_SEARCH_BY_INDUSTRY)."' class='home_4'>".INFO_TEXT_HOME_MORE."&gt;&gt;</a></td></tr>";
$job_category.="</div></div>";
/****************end of JOB CATEGORY******************/

#################JOB LOCATION############################
$default_country_value=DEFAULT_COUNTRY_ID;
$field_name12="zone_id,".TEXT_LANGUAGE."zone_name";
$whereclause12="where zone_country_id=$default_country_value";
$query12="select $field_name12 from ".ZONES_TABLE." $whereclause12 order by ".TEXT_LANGUAGE."zone_name asc limit 0,15";//.(int) MODULE_THEME_DEFAULT_MAX_JOB_LOCATION;
$result12=tep_db_query($query12);
$j=1;
$job_location="<div class='row'><div class='col-md-4'>";
while($row12=tep_db_fetch_array($result12))
{
	$id12=$row12['zone_id'];
 $location_form=tep_draw_form('job_location'.$j, FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search');
	$key12=((strlen($row12[TEXT_LANGUAGE.'zone_name'])<23)?$row12[TEXT_LANGUAGE.'zone_name']:substr($row12[TEXT_LANGUAGE.'zone_name'],0,20)."...");
	$job_location.=$location_form."<p><input type='hidden' name='state[]' value='".$row12['zone_name']."'><a href='#' title='".tep_db_output($key12)."' onclick='document.job_location".$j.".submit()'>".tep_db_output($key12)."</a></p></form>";
	if($j%5 == 0)
	{
  $job_location.="</div><div class='col-md-4'>";
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
$job_company="<div class='col'>".$company_form."";
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
 $company_name="<a href='#' onclick='search_company(\"".$query_string1."\")'>".tep_db_output($row_c['recruiter_company_name'])."</a> ";
	$job_company.="<p>".$company_name."</p>";
	if($k%10 == 0)
	{
  $job_company.="</div><div class='col-md-4'>";
	}
	$company_name1_old=$company_name1;
 $k++;
}
//$job_company.="<td align='right' colspan='4'><a href='".tep_href_link(FILENAME_JOBSEEKER_COMPANY_PROFILE)."' class='home_4'>".INFO_TEXT_HOME_MORE."&gt;&gt;</a></td></tr></form>";
$job_company.="</form></div>";
/***************end of JOBS BY COMPANY************************/

//////////////////// LATEST JOBS STARTS ///////////////////
$now=date('Y-m-d H:i:s');
$table_names=JOB_TABLE." as j,".RECRUITER_LOGIN_TABLE.' as rl,'.RECRUITER_TABLE.' as r';
$whereClause="j.recruiter_id=rl.recruiter_id and rl.recruiter_id=r.recruiter_id and rl.recruiter_status='Yes'and j.expired >='$now' and j.re_adv <='$now' and j.job_status='Yes' and ( j.deleted is NULL or j.deleted='0000-00-00 00:00:00') ";//
$field_names="j.job_id, j.job_title,j.job_type, j.job_salary,j.job_featured,j.min_experience,j.max_experience,j.re_adv, j.job_location,j.job_short_description,j.inserted, r.recruiter_company_name,job_country_id,r.recruiter_logo";
$order_by_field_name = "j.inserted";
$query = "select $field_names from $table_names where $whereClause order by $order_by_field_name limit 0,5";

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

  if(strlen($row['recruiter_company_name']) > 50)
  $company_name_short=	substr($row['recruiter_company_name'],0,15).'..';
 else
  $company_name_short=	substr($row['recruiter_company_name'],0,20);
	$company=$company_name_short;
/////logo
 $recruiter_logo='';
 $company_logo=$row['recruiter_logo'];
 if(tep_not_null($company_logo) && is_file(PATH_TO_MAIN_PHYSICAL.PATH_TO_LOGO.$company_logo))
    $recruiter_logo=tep_image(FILENAME_IMAGE."?image_name=".PATH_TO_LOGO.$company_logo,'','','','class="img-fluid img-thumbnail theme8-profile-img mr-3"');
 else
    $recruiter_logo=tep_image(FILENAME_IMAGE."?image_name=".PATH_TO_IMG."nologo.jpg",'','','','class="img-fluid img-thumbnail theme8-profile-img mr-3"');

///////////////
//$description=(tep_not_null(strlen($row['job_short_description']))>10?substr($row['job_short_description'],0,28).'..':$row['job_short_description']);
if(strlen($row['job_title']) > 20)
  $name_short=	substr($row['job_title'],0,15).'..';
 else
  $name_short=	substr($row['job_title'],0,20);
 //$title=' <a href="'.tep_href_link($ide.'/'.$title_format.'.html').'" target="_blank">'.$name_short.'</a>';

 $title=' <a href="'.tep_href_link($ide.'/'.$title_format.'.html').'" target="_blank">'.$row['job_title'].'</a>';
 $location=tep_db_output($row['job_location']);
 $company_address=tep_not_null($location)? '<i class="bi bi-geo-alt me-1"></i>'." $location, $country" : "$country";

 $template->assign_block_vars('latest_jobs', array(
                              	'title'    => $title,
                                'location'    =>$company_address,
                                'company'=> $company,
                                'clogo'=>$recruiter_logo,
                                'jobtype'   => $jobtype,
                                'salary'    => $salary,
                                'job_posted' => $job_posted,
                                'job_featured'=> (tep_db_output($row['job_featured']) == 'Yes') ? 'featured-job-tag' : '',
                                'experience'  => tep_db_output(calculate_experience($row['min_experience'],$row['max_experience'])),
                              ));
 $count++;
}
//// LATEST JOB ENDS ////

//////////////////// CAREER TOOLS STARTS ///////////////////
$now=date("Y-m-d H:i:s");
$query = "select a.id,a.title,a.seo_name, a.short_description,a.article_photo,a.show_date  from ".ARTICLE_TABLE." as a  where a.show_date <='$now' and a.is_show='Yes'  order by rand() limit 0,5";
//echo "<br>$query";//exit;
$result1=tep_db_query($query);
$x=tep_db_num_rows($result1);
$count=1;
while($article = tep_db_fetch_array($result1))
{
 $ide=$article['id'];
 $seo_name = $article["seo_name"];
 $article_url=tep_href_link(get_display_link($ide,$seo_name));

 $title='<a href="'.$article_url.'" class="jobsite8" target="_blank">'.tep_db_output(ucfirst($article['title'])).'</a>';
  $article_image='';
  if(tep_not_null($article["article_photo"]) && is_file(PATH_TO_MAIN_PHYSICAL.PATH_TO_ARTICLE_PHOTO.$article["article_photo"]))
    $article_image='<a href="'.$article_url.'"  target="_blank">'.tep_image(FILENAME_IMAGE."?image_name=".PATH_TO_ARTICLE_PHOTO.$article["article_photo"]."&size=58",'','','','class="img-thumbnail align-self-start mr-3 theme8-profile-img2"').'</a>';
  else
    $article_image='';
 	$description=((strlen($article['short_description'])<80)?$article['short_description']:substr($article['short_description'],0,70)."..");

 $template->assign_block_vars('career_tools', array(
                              'title'      => $title,
                              'image'      => $article_image,
							  'date'	   	=> tep_date_long(tep_db_output($article['show_date'])),
                              'description'=> tep_db_output($description),
                              ));
 $count++;
}
tep_db_free_result($result1);

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
	 $INFO_TEXT_ALERT_TEXT=$save_search.''.tep_draw_input_field('TREF_job_alert_email', $TREF_job_alert_email,'class="form-control" placeholder="Email Address" ',false).''."<button type='submit' class='btn btn-sm btn-info btn-block py-2 mt-2'>Create Job Alert</button></form>";
}

/**********************************************************************************************************************************/
/////////////////////////////////////////////////////////////////

$cat_array=tep_get_categories(JOB_CATEGORY_TABLE);
array_unshift($cat_array,array("id"=>0,"text"=>INFO_TEXT_ALL_CATEGORIES));
$default_country = DEFAULT_COUNTRY_ID;
$template->assign_vars(array(
'JOB_SKILL'=> $job_skill,
'ARTICLE_HOME'=>$articles1,
'CONTACT_US'=>'<a href="'.tep_href_link(FILENAME_CONTACT_US).'" class="btn theme8-btn-outline" role="button">Job alert</a>',
'CAND_REG'=>(check_login('jobseeker')?'<a href="'.tep_href_link(FILENAME_JOBSEEKER_REGISTER1).'" class="btn btn-block theme8-btn-outline" role="button">Candidate registration</a>':'<a href="'.tep_href_link(FILENAME_JOBSEEKER_CONTROL_PANEL).'" class="btn theme8-btn-outline" role="button">Jobs by category</a>'),
'EMP_REG'=>(check_login('recruiter')?'<a href="'.tep_href_link(FILENAME_RECRUITER_REGISTRATION).'" class="btn btn-block theme8-btn-outline" role="button">Employer registration</a>':'<a href="'.tep_href_link(FILENAME_RECRUITER_CONTROL_PANEL).'" class="btn theme8-btn-outline" role="button">Jobs by location</a>'),
 'JOB_CATEGORY' => $job_category,
 'JOB_LOCATION' => $job_location,
'JOB_COMPANY' => $job_company,
 'job_search_form' => tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search'),
 'INFO_TEXT_KEYWORD' => tep_draw_input_field('keyword', INFO_TEXT_JOB_SEARCH_KEYWORD,'class="form-control mb-2 theme8-border" placeholder="Keyword or phrase"',false),
 'industry_sector'  => tep_draw_pull_down_menu('job_category[]', $cat_array, '', 'class="form-control mb-2 theme8-border"'),
 'location'  => LIST_TABLE(COUNTRIES_TABLE,TEXT_LANGUAGE."country_name","priority","name='country' class='form-control theme8-border mb-2'",INFO_TEXT_HOME_ANY_COUNTRY,"",DEFAULT_COUNTRY_ID),
'search_button'=> '<button type="submit" class="btn btn-block mt-2 theme8-btn-dark">Search </button>',
'CREATE_ALERT'=>$INFO_TEXT_ALERT_TEXT,

'HOME_RIGHT_BANNER'=>$home_right_banner,
'APPLICANT_TRACKING'=>'<button class="btn btn-lg btn-primary" onclick="location.href=\''.tep_href_link(FILENAME_RECRUITER_SEARCH_APPLICANT).'\'" type="submit">Sign Up</button>',
 'LEFT_BOX_WIDTH'=> '',
 'ALL_JOBS'=>'<button class="btn btn-lg btn-primary" onclick="location.href=\''.tep_href_link(FILENAME_JOB_SEARCH).'\'" type="submit">All Jobs</button>',
 'ALL_CATEGORY'=>'<button class="btn btn-lg btn-primary" onclick="location.href=\''.tep_href_link(FILENAME_JOB_SEARCH_BY_INDUSTRY).'\'" type="submit">All Categories</button>',
 'ALL_ARTICLES'=> '<button class="btn btn-lg btn-primary" onclick="location.href=\''.tep_href_link(FILENAME_ARTICLE).'\'" type="submit">All Articles</button>',
'CREATE_ALERT'=>'<a class="btn btn-block theme8-btn-outline" href="'.tep_href_link(FILENAME_JOB_ALERT_AGENT).'">Create a Job Alert</a>',
 'RIGHT_BOX_WIDTH'=> RIGHT_BOX_WIDTH1,
 'RIGHT_HTML'=> RIGHT_HTML,
 'LEFT_HTML'=> '',
 'update_message'=> $messageStack->output(),
		));
	?>