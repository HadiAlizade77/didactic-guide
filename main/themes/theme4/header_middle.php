<?
/*-----------------------SEARCH CODE---------------------------------------------------------*/
$job_search_form=tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search');
$key=tep_draw_input_field('keyword','','class="form-control form-control-lg theme4-form-lg" placeholder="Keyword"',false);
$locat= LIST_TABLE(COUNTRIES_TABLE,TEXT_LANGUAGE."country_name","priority","name='country' class='form-select form-select-lg theme4-form-lg'","All Locations","",DEFAULT_COUNTRY_ID);
$experience_1=experience_drop_down('name="experience" class="form-control theme4-form-lg"', 'Experience', '', $experience);
$cat_array=tep_get_categories(JOB_CATEGORY_TABLE);
array_unshift($cat_array,array("id"=>0,"text"=>"All Category"));
$industry_sector= tep_draw_pull_down_menu('job_category[]', $cat_array, '', 'class="form-select form-select-lg theme4-form-lg"');
$button= '<button type="submit" class="btn btn-lg btn-success-theme4 btn-full"><i class="bi bi-search"></i></button>';
/********************************  SEARCH CODE ENDS********************************************* */

//////////////////////////////////////////////////CALCULATE NO. of COMPANIES AND JOBS/////////////////////////////////////////////////////////////////
$jobs_query = tep_db_query("select distinct job_id from " . JOB_TABLE );
$no_of_jobs= tep_db_num_rows($jobs_query);
$recruiter_query = tep_db_query("select distinct recruiter_company_name from " . RECRUITER_TABLE );
$no_of_companies= tep_db_num_rows($recruiter_query);

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


if(strtolower($_SERVER['PHP_SELF'])=="/".PATH_TO_MAIN.FILENAME_INDEX)
{
 define('HEADER_MIDDLE_HTML','
 <div class="container-fluid home-banner-bg theme4-home-banner-bg py-5 mb-4">

 <div class="">

                <div class="row">
                    <div class="col-md-12 text-center">
                        <h1 class="fw-bold text-green-theme4">Find your dream job now</h1>
                        <p class="mb-4 fw-bold">Search '.$no_of_jobs.' jobs from '.$no_of_companies.' companies</p>
                    </div>
                </div>
</div>

			<div class="row px-3 mb-4">
			<div class="col-md-7 mx-auto">
			'.$job_search_form.'
			<div class="row g-3">
				<div class="col-md-4 mpr-0">'.$key.'</div>
				<div class="col-md-3 mpr-0">'.$locat.'</div>
				<div class="col-md-3 mpr-0">'.$industry_sector.'</div>
				<div class="col-md-2">'.$button.'</div>
			</div>
			</form>

			</div>
			</div>




                <div class="row">
				<div class="col-md-7 mx-auto">
					<div class="col-md-12 text-center">
					<a href="'.tep_href_link(FILENAME_JOB_SEARCH).'" class="btn btn-sm theme4-btn-outline2 mr-2 mobile-padding ">Advanced Search</a>
					<button class="btn btn-sm theme4-btn-outline2 mr-2 mobile-padding" onclick="location.href=\''.tep_href_link(FILENAME_JOBSEEKER_RESUME1).'\'" type="submit">Add CV</button>
					<button class="btn btn-sm theme4-btn-outline2 mobile-padding" onclick="location.href=\''.tep_href_link(FILENAME_RECRUITER_POST_JOB).'\'" type="submit">Post Job</button>
					</div>
                </div>
				</div>

</div>
</div></div>
 ');
}
else
{
 define('HEADER_MIDDLE_HTML','');
}
?>
