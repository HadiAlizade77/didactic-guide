<?
/*
***********************************************************
***********************************************************
**********# Name          : Kamal Kumar Sahoo   #**********
**********# Company       : Aynsoft             #**********
**********# Date Created  : 11/02/04            #**********
**********# Date Modified : 11/02/04            #**********
**********# Copyright (c) www.aynsoft.com 2004  #**********
***********************************************************
***********************************************************
*/
$heading = array();
$contents = array();
$heading[] = array('link'  =>FILENAME_ADMIN1_RECRUITER_JOBS.'?selected_box=jobs',
                   'text'  =>BOX_HEADING_JOBS,
                   'default_row'=>(($_SESSION['selected_box'] == 'jobs') ?'1':''),
                   'text_image'=>'<ion-icon name="briefcase-outline" style="color: #9ca2a7;margin: 1px 5px 0 10px;font-size: 18px;position: absolute;"></ion-icon>',
                  );

if ($_SESSION['selected_box'] == 'jobs')
{
 $blank_space='<i class="far fa-circle" style="margin: 3px 5px 3px 10px;font-size: 10px;color:#fff;"></i>';
 $content=tep_admin_files_boxes(FILENAME_ADMIN1_RECRUITER_JOBS, BOX_JOBS);
 if(tep_not_null($content))
 {
	 $contents[] = array('text'=>$blank_space.$content);
 }
 $content=tep_admin_files_boxes(FILENAME_ADMIN1_RECRUITER_INDEED_JOBS, BOX_INDEED_JOBS);
 if(tep_not_null($content))
 {
	 $contents[] = array('text'=>$blank_space.$content);
 }
}
$box = new left_box;
$LEFT_HTML.=$box->menuBox($heading, $contents);
?>