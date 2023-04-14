<?
/*-----------------------SEARCH CODE---------------------------------------------------------*/
$job_search_form=tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search');
$key=tep_draw_input_field('keyword','','class="form-control form-control-lg" placeholder="Keywords"',false);
$locat= LIST_TABLE(COUNTRIES_TABLE,TEXT_LANGUAGE."country_name","priority","name='country' class='form-select form-select-lg'","All Locations","",DEFAULT_COUNTRY_ID);
$experience_1=experience_drop_down('name="experience" class="form-control form-control-lg form-lg-theme6"', 'Experience', '', $experience);
//////////////////////////////////////////////////CALCULATE NO. of COMPANIES AND JOBS/////////////////////////////////////////////////////////////////
$jobs_query = tep_db_query("select distinct job_id from " . JOB_TABLE );
$no_of_jobs= tep_db_num_rows($jobs_query);
$button= '<button type="submit" class="btn btn-lg btn-danger-theme6 btn-full px-4"><i class="bi bi-search text-white"></i></button>';
/********************************  SEARCH CODE ENDS********************************************* */

if(strtolower($_SERVER['PHP_SELF'])=="/".PATH_TO_MAIN.FILENAME_INDEX)
{
 define('HEADER_MIDDLE_HTML','<!-- Main jumbotron for a primary marketing message or call to action -->

                <div class="container-fluid bg-banner">
					<div class="container">
					<div class="col-md-10">
						<!-- Job Section -->
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <h class="fw-bold display-4 theme4-display ">Search From '.$no_of_jobs.' Jobs!</h3>
                            </div>

                        </div>
					'.$job_search_form.'
					<div class="row g-3">
					<div class="col-md-5">'.$key.' </div>
					<div class="col-md-5">'.$locat.' </div>
					<div class="col-md-2">'.$button.'</div>
					<div class="col-md-12"><a href="'.tep_href_link(FILENAME_JOB_SEARCH).'" class="d-block text-white">Advanced Search</a></div>
					</form>
					</div>
					</div>
					</div>
                    <!-- /.row -->

            </div>
');
}
else
{
 define('HEADER_MIDDLE_HTML','');
}
?>
