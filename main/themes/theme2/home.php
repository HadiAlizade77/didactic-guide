<?php
if($_SESSION['language']=='english')
  include_once(dirname(__FILE__).'/language/home_english.php');

	#################FEATURED EMPLOYER############################
$home_right=banner_display("7",6,250,"class='featured-logo-theme2 mb-3'");
for($i=0;$i<=count($home_right);$i++)
{
  $template->assign_block_vars('banner', array(
                                'banner'=>$home_right[$i],
                              ));
}
/////////// FEATURED EMPLOYER END///////////////



#################JOB CATEGORY############################
$now=date('Y-m-d H:i:s');
$field_names="id,".TEXT_LANGUAGE."category_name";
$whereClause=" where sub_cat_id is null";
$query11 = "select $field_names from ".JOB_CATEGORY_TABLE." $whereClause  order by ".TEXT_LANGUAGE."category_name  asc limit 0,8";
$result11=tep_db_query($query11);
$i=1;
$job_category="";
while($row11 = tep_db_fetch_array($result11))
{
 $ide=$row11["id"];
// $category=$row11[TEXT_LANGUAGE.'category_name'];
 $job_category_form=tep_draw_form('job_category'.$i, FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search');
 $key1=$row11[TEXT_LANGUAGE.'category_name'];
 $job_category.="<div class='col-lg-3 jobcategory'><center>
                        <i class='fa fa-pie-chart' aria-hidden='true'></i><br>
                        <h2><a href='".$ide.'/'.encode_category($key1)."-jobs.html"."'  title='".tep_db_output($key1)."'>".tep_db_output($key1)."</a></h2>
                    </center>
                </div>";
$i++;
}

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
$field_names="j.job_id, j.job_title,j.job_type,j.job_salary,j.min_experience,j.job_featured,j.max_experience,j.re_adv,j.job_location,r.recruiter_company_name,r.recruiter_logo,j.job_country_id";
$order_by_field_name = "j.inserted";
$query = "select $field_names from $table_names where $whereClause order by $order_by_field_name limit 0,5";
// $query = "select $field_names from $table_names where $whereClause order by rand() limit 0,5";// " . (int) MODULE_THEME_JOBSITE12_MAX_LATEST_JOB;


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

 ///logo
$comp_logo='';
if(tep_not_null($row["recruiter_logo"]) && is_file(PATH_TO_MAIN_PHYSICAL.PATH_TO_LOGO.$row["recruiter_logo"]))
    $comp_logo=tep_image(FILENAME_IMAGE."?image_name=".PATH_TO_LOGO.$row["recruiter_logo"]."&size=60",'','','','');
  else
    $comp_logo=tep_image(FILENAME_IMAGE."?image_name=".PATH_TO_IMG."nologo.jpg&size=60",'','','','');
  ///////////

$company=$row['recruiter_company_name'];

 // salary with currency
 $row_cur = getAnyTableWhereData(CURRENCY_TABLE, "code ='" . DEFAULT_CURRENCY . "'", 'symbol_left,symbol_right');
 $sym_left = (tep_not_null($row_cur['symbol_left']) ? $row_cur['symbol_left'] . '' : '');
 $salary = (tep_not_null($row['job_salary']) ? $sym_left . tep_db_output($row['job_salary']) . $sym_rt : '• Negotiable');
 $job_posted = tep_date_long(tep_db_output($row['re_adv']));

  ///job type
  $row_type=getAnyTableWhereData(JOB_TYPE_TABLE,"id='".$row['job_type']."'",'type_name');
  if ($row_type['type_name']) {
    $jobtype='<span class="'.$row_type['type_name'].'">'.$row_type['type_name'].'</span>';
  }
  else {
    $jobtype='<span class="Full-time">Full time</span>';
  }

$title=' <a href="'.tep_href_link($ide.'/'.$title_format.'.html').'" class="text-dark" target="_blank">'.$row['job_title'].'</a>';
$country=get_name_from_table(COUNTRIES_TABLE, 'country_name', 'id',tep_db_output($row['job_country_id']));
 $location=tep_db_output($row['job_location']);
 $company_address=tep_not_null($location)? "• "."$location, $country" : "$country";

 $template->assign_block_vars('latest_jobs', array(
                                'title'    => $title,
                                'location'    =>$company_address,
                                'company'=> $company,
                                'clogo'=>$comp_logo,
                                'jobtype'   => $jobtype,
                                'salary' => $salary,
                                'job_posted' => $job_posted ? "• ".$job_posted : "",
                                'job_featured'=> (tep_db_output($row['job_featured']) == 'Yes') ? 'featured-job-tag' : '',
                                'experience'  => '• '.tep_db_output(calculate_experience($row['min_experience'],$row['max_experience'])),
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

	if(strlen($article['title']) > 20)
  $article_name_short=	substr($article['title'],0,15).'..';
 else
  $article_name_short= substr($article['title'],0,20);
 $title='<a href="'.$article_url.'"  target="_blank">'.$article_name_short.'</a>';
  $article_image='';
  if(tep_not_null($article["article_photo"]) && is_file(PATH_TO_MAIN_PHYSICAL.PATH_TO_ARTICLE_PHOTO.$article["article_photo"]))
    $article_image='<a href="'.$article_url.'"  target="_blank">'.tep_image(FILENAME_IMAGE."?image_name=".PATH_TO_ARTICLE_PHOTO.$article["article_photo"]."",'','','','class="card-img-top-theme2"').'</a>';
  else
    $article_image='<a href="'.$article_url.'" target="_blank">'.tep_image(FILENAME_IMAGE."?image_name=".PATH_TO_ARTICLE_PHOTO."blank_com.gif",'','','','class="card-img-top-theme2"').'</a>';
 	$description=((strlen($article['short_description'])<100)?$article['short_description']:substr($article['short_description'],0,100)."..");
//$MORE='<a href="article_'.$ide.'.html"  target="_blank" class="btn btn-primary" type="button">Read More</a>';
$articles1.='

<div class="col-md-4">
<div class="card card-theme2" style="height:370px;">
'.$article_image.'
  <div class="card-body">
    <h5 class="theme2 mb-2"><a href="article_'.$ide.'.html"  target="_blank" class="text-dark" type="button">'.$article['title'].'</a></h5>
    <p class="card-text">'.tep_db_output($description).'</p>
    <p class="card-text small"><i class="bi bi-calendar2-week"></i> '.tep_date_long(tep_db_output($article['show_date'])).'</p>
    <!--'.$MORE.'-->
  </div>
</div>
</div>
';
/*  if($count%2 == 0)
		{
    $articles1.='</tr><tr><td><img src="themes/sample5/img/spacer.gif" width="5" height="2"></td></tr><tr>';
		}
*/
		$count++;
}
//// CAREER TOOLS ENDS ////

/*************************codeing to display different form and save search link for login and non login users *********************/
if(check_login("jobseeker"))
{
	$save_search= tep_draw_form('save_search', FILENAME_JOB_ALERT_AGENT,($edit?'sID='.$save_search_id:''),'post','onsubmit="return ValidateForm(this)"').tep_draw_hidden_field('action1','save_search');
    $INFO_TEXT_ALERT_TEXT=$save_search.(($action1=='save_search')?'':"<a href='#' onclick='document.save_search.submit();' class='btn btn-danger btn-full-theme2'>Create Job Alert</a></form>");
}
else
{
	 $save_search= tep_draw_form('save_search', FILENAME_JOB_ALERT_AGENT_DIRECT,'','post','onsubmit="return ValidateForm(this)"').tep_draw_hidden_field('action','new');
	 $INFO_TEXT_ALERT_TEXT=$save_search.''.tep_draw_input_field('TREF_job_alert_email', $TREF_job_alert_email,'class="form-control" placeholder="Email Address" ',false).''."<button type='submit' class='btn btn-danger btn-full-theme2 mt-2'><i class='bi bi-bell-fill'></i> Create Alert</button></form>";
}

/**********************************************************************************************************************************/
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

$cat_array=tep_get_categories(JOB_CATEGORY_TABLE);
array_unshift($cat_array,array("id"=>0,"text"=>INFO_TEXT_ALL_CATEGORIES));
$default_country = DEFAULT_COUNTRY_ID;
$template->assign_vars(array(
'JOB_CATEGORY'=>$job_category,
'ARTICLE_HOME'=>$articles1,
 'ALL_JOBS'=>tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').'<button class="btn-all-theme2" type="submit">All jobs <i class="bi bi-arrow-right"></i></button>',
 'ALL_RECRUITERS'=>'<button class="btn-all-theme2" onclick="location.href=\''.tep_href_link(FILENAME_JOBSEEKER_COMPANY_PROFILE).'\'" type="button">All Recruiters <i class="bi bi-arrow-right"></i></button>',
 'ALL_CATEGORIES'=>'<a class="btn-all-theme2" href="'.tep_href_link(FILENAME_JOB_SEARCH_BY_INDUSTRY).'" type="button">All Categories <i class="bi bi-arrow-right"></i></a>',
 'ALL_ARTICLES'=> '<a class="btn-all-theme2" href="'.tep_href_link(FILENAME_ARTICLE).'" type="button">All Articles <i class="bi bi-arrow-right"></i></a>',
 'LEFT_BOX_WIDTH'=> '',
 'ALL_ARTICLES'=> '<a class="btn-all-theme2" href="'.tep_href_link(FILENAME_ARTICLE).'" type="button">All Articles</a>',
"CATEGORY1"=>tep_draw_form('search_job1', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('job_category[]','16')."<button type='submit' class='category-theme2'>Export/Import (".show_category_total_job(16).")</button></form>",
"CATEGORY2"=>tep_draw_form('search_job2', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('job_category[]','33')."<button type='submit' class='category-theme2'>IT Software (".show_category_total_job(33).")</button></form>",
"CATEGORY3"=>tep_draw_form('search_job3', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('job_category[]','3')."<button type='submit' class='category-theme2'>Arts & Design (".show_category_total_job(3).")</button></form>",
"CATEGORY4"=>tep_draw_form('search_job4', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('job_category[]','25')."<button type='submit' class='category-theme2'>Education/Training (".show_category_total_job(25).")</button></form>",
"CATEGORY5"=>tep_draw_form('search_job5', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('job_category[]','29')."<button type='submit' class='category-theme2'>Government Services (".show_category_total_job(29).")</button></form>",
"CATEGORY6"=>tep_draw_form('search_job6', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('job_category[]','24')."<button type='submit' class='category-theme2'>CustomerSupport (".show_category_total_job(24).")</button></form>",
"CATEGORY7"=>tep_draw_form('search_job7', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('job_category[]','22')."<button type='submit' class='category-theme2'>Advertising/Marketing/PR (".show_category_total_job(22).")</button></form>",
"CATEGORY8"=>tep_draw_form('search_job8', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search').tep_draw_hidden_field('job_category[]','21')."<button type='submit' class='category-theme2'>Accounting/Finance/Banking (".show_category_total_job(21).")</button></form>",
'CREATE_ALERT'=>$INFO_TEXT_ALERT_TEXT,

 'RIGHT_BOX_WIDTH'=> RIGHT_BOX_WIDTH1,
 'RIGHT_HTML'=> RIGHT_HTML,
 'LEFT_HTML'=> '',
 'update_message'=> $messageStack->output(),
		));
	?>