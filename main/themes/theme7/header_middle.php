<?
/*-----------------------SEARCH CODE---------------------------------------------------------*/
$job_search_form=tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search');
$all_job_form=tep_draw_form('all_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search');
$key=tep_draw_input_field('keyword','','class="form-control form-control-lg form-control-outline" placeholder="Keyword"',false);
$locat= LIST_TABLE(COUNTRIES_TABLE,TEXT_LANGUAGE."country_name","priority","name='country' class='form-select form-select-lg'","All Location","",DEFAULT_COUNTRY_ID);
$experience_1=experience_drop_down('name="experience" class="form-control form-control-lg"', 'Experience', '', $experience);
$cat_array=tep_get_categories(JOB_CATEGORY_TABLE);
array_unshift($cat_array,array("id"=>0,"text"=>"All Category"));
$industry_sector_1=tep_draw_pull_down_menu('job_category[]', $cat_array, '', 'class="form-select form-select-lg form-control-outline"');

$button= '<button type="submit" class="btn brn-lg theme7-btn-warning btn-block px-4 py-2 m-w-100"> Search </button>';
/********************************  SEARCH CODE ENDS********************************************* */

if(strtolower($_SERVER['PHP_SELF'])=="/".PATH_TO_MAIN.FILENAME_INDEX)
{
 define('HEADER_MIDDLE_HTML','
 
 
<div class="container-fluid home-slider">&nbsp;</div>

            <div class="container">
                <div class="">
				<div class="col-md-12 theme7-bg-white px-4 pb-4">
				<div class="row pt-4 mobile-padding-zero">
				<div class="col-md-9 pr-4">
				
				'.$job_search_form.'
				<div class="row g-3">
					<div class="col-md-12 mm-theme7"><h1 class="display-6 fw-bold">Find A Job at India\'s No.1 Job Site</h1></div>
					<div class="col-md-6 mpr-0">'.$key.'</div>
					<div class="col-md-4 mpr-0">'.$industry_sector_1.'</div>
					<div class="col-md-2 pr-0">'.$button.'</div>
				</div>
				</form>
				
				<div class="mt-3 textcenter for-mobile">
				<a class="badge badge-pill theme7-badge-light mr-2" href="'.tep_href_link(FILENAME_JOB_SEARCH).'">Advanced Search</a>
				<!--<button class="badge badge-pill theme7-badge-light mr-2" type="submit">All Jobs</button>--></form>
				<a class="badge badge-pill theme7-badge-light mr-2 display-none" href="'.tep_href_link(FILENAME_JOBSEEKER_COMPANY_PROFILE).'">Jobs by Company</a>
				<a class="badge badge-pill theme7-badge-light mr-2 display-none" href="'.tep_href_link(FILENAME_JOB_SEARCH_BY_INDUSTRY).'">Jobs by Category</a>
				<a class="badge badge-pill theme7-badge-light display-none" href="'.tep_href_link(FILENAME_JOB_SEARCH_BY_LOCATION).'">Jobs by Location</a>
				</div>
				
				</div>
				
				<div class="col-md-3 pr-2 pr-0 border-left display-none for-mobile">
					<div class="ms-auto">
					<h4 style="color: #091e42;font-size: 16px;font-weight:bold;">Job Seeker</h4>
					<p class="mt-2 mb-3" style="font-size: 14px;line-height: 20px;">Search job openings in jobboard and post your resume.</p>
					<a class="upload" href="">
					
					</a>
					<div>
					<a href="'.tep_href_link(FILENAME_JOBSEEKER_REGISTER1).'" class="btn btn-sm theme7-btn-outline-warning btn-lg" role="button">Register with us</a>
					<a href="'.tep_href_link(FILENAME_JOBSEEKER_RESUME5).'" class="btn btn-sm theme7-btn-outline-warning1 btn-lg" role="button">Upload CV</a>
					</div>
					</div>
				'.$all_job_form.'
				  </div>
				</div>
				
				
				
				</div>

                </div>
            </div>



 ');
}
else
{
 define('HEADER_MIDDLE_HTML','');
}
?>
