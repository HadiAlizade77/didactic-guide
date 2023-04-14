<?
/*-----------------------SEARCH CODE---------------------------------------------------------*/
$job_search_form=tep_draw_form('search_job', FILENAME_JOB_SEARCH,'','post').tep_draw_hidden_field('action','search');
$key=tep_draw_input_field('keyword','','class="form-control form-control-lg theme10-form-control" type="search" placeholder="Enter Keywords"',false);
$locat= LIST_TABLE(COUNTRIES_TABLE,TEXT_LANGUAGE."country_name","priority","name='country' class='form-select form-select-lg  theme10-form-control'","All Location","",DEFAULT_COUNTRY_ID);
$experience_1=experience_drop_down('name="experience" class="form-select form-select-lg theme10-form-control"', 'Experience', '', $experience);

$button= '<button type="submit" class="btn btn-lg theme10-btn-danger theme10-btn btn-customs btn-full"><i class="bi bi-search"></i></button>';
/********************************  SEARCH CODE ENDS********************************************* */





if(strtolower($_SERVER['PHP_SELF'])=="/".PATH_TO_MAIN.FILENAME_INDEX)
{
 define('HEADER_MIDDLE_HTML','


<div class="container-fluid home-banner-theme10">
<div class="container">
'.$job_search_form.'

<div class="row">
<div class="col-md-12">
<h2 class="display-5 text-white">Find the career you deserve</h2>
<p class=" text-white">Your job search starts and ends with us.</p>
</div>
<div class="col-md-12">
<div class="row g-3 align-items-center">
					<div class="col-md-4 mpr-0">'.$key.'</div>
					<div class="col-md-3 mpr-0">'.$locat.' '.$industry_sector.'</div>
					<div class="col-md-1">'.$button.'</div>
          			<div class="col-md-2 mt-3 m-text-center"><a href="'.tep_href_link(FILENAME_JOB_SEARCH).'" class="text-white">Advanced Search</a></div>
					</div>
					</div>
					</form>
				</div>
				
				</div>
			</div>
			</div>
			</div>');
}
else
{
 define('HEADER_MIDDLE_HTML','');
}
?>