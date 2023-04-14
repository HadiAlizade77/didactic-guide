<?
/*-----------------------SEARCH CODE---------------------------------------------------------*/
$job_search_form=tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search');
$key=tep_draw_input_field('keyword','','class="form-control form-control-lg margin-bottom-theme" placeholder="Enter keywords"',false);
$locat= LIST_TABLE(COUNTRIES_TABLE,TEXT_LANGUAGE."country_name","priority","name='country' class='form-select form-select-lg margin-bottom-theme'","All Locations","",DEFAULT_COUNTRY_ID);
$experience_1=experience_drop_down('name="experience" class="form-control form-control-lg"', 'Experience', '', $experience);

$button= '<button class="btn btn-lg btn-success btn-full-theme py-3" type="submit" id="button-addon2"><i class="bi bi-search"></i></button>';
/********************************  SEARCH CODE ENDS********************************************* */

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$jobs_query = tep_db_query("select distinct job_id from " . JOB_TABLE );
$no_of_jobs= tep_db_num_rows($jobs_query);

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if(strtolower($_SERVER['PHP_SELF'])=="/".PATH_TO_MAIN.FILENAME_INDEX)
{
 define('HEADER_MIDDLE_HTML','

 <div class="container-fluid px-0 mb-5">
 <header>
 <div class="overlay"></div>
 <video playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">
   <source src="https://bpojobsite.com/themes/theme2/jobboard3.mp4" type="video/mp4">
 </video>
 <div class="container">
 <div class="row align-items-center">
   <div class="text-center">
	 <div class="text-white">
	   <h1 class="display-4 text-dark fw-bold mb-1">Find the perfect job for you</h1>
	   <p class="text-dark mb-0" style="font-size:20px;">We have '.$no_of_jobs.' job offers for you!</p>
	 </div>
   </div>
   <div class="col-md-8 mx-auto mt-4">
				<!-- Job Section -->
                    '.$job_search_form.'
				<div class="row g-3">
					<div class="col-md-6 mpr-0">'.$key.'</div>
					<div class="col-md-4 mpr-0">'.$locat.'</div>
					<div class="col-md-2">'.$button.'</div>
				</div>
	<div>
	<a href="'.tep_href_link(FILENAME_JOB_SEARCH).'" class="d-block mt-3 text-center">Advanced Search</a>
	</form>
 </div>
 </div>
</header>
</div>

 ');
}
else
{
 define('HEADER_MIDDLE_HTML','
    ');
}
?>
