<?php
include_once("include_files.php");
include_once(PATH_TO_MAIN_PHYSICAL_LANGUAGE.$language.'/'.FILENAME_ARTICLE);
$template->set_filenames(array('article' => 'article.htm', // it shows main article through article.php // article view page
                               'article1' => 'article1.htm', // article category page
                               'article2' => 'article2.htm', //page displays through article.php
							                'article3' => 'article3.htm'//print
							   ));
include_once(FILENAME_BODY);

$action      = tep_db_prepare_input($_GET['action']);
$article_seo = tep_db_prepare_input($_GET['article_seo']);
//print_r($_GET);
$hidden_fields='';
$article_id = tep_db_prepare_input($_GET['article_id']);
$cat_Path   = tep_db_prepare_input($_GET['cat_path']);
$page_no    = tep_db_prepare_input($_GET['page']);
$now=date("Y-m-d H:i:s");
if(tep_not_null($article_seo))
{
 if($row_check=getAnyTableWhereData(ARTICLE_TABLE,"seo_name='".tep_db_input($article_seo)."'","id"))
 {
  $article_id =$row_check['id'];
 }
 else
  tep_redirect(tep_href_link(FILENAME_ARTICLE));
}
////////////////////////////////////////////////////////////////////////////////////////////////////
if($article_id=="" && $cat_Path=="")
{
 $article_query_raw = "select ac.".TEXT_LANGUAGE."category_name,ac.id as acid,a.id,a.title,a.seo_name,a.short_description,a.show_date,a.article_photo,a.viewed  from ".ARTICLE_CATEGORY_TABLE." as ac, ".ARTICLE_TABLE." as a where ac.id=a.category_id and a.show_date <='$now' and a.is_show='Yes' order by ac.".TEXT_LANGUAGE."category_name,a.show_date desc,a.inserted";
 //echo $article_query_raw."<br>";
 $article_split = new splitPageResults($page_no, MAX_DISPLAY_ARTICLES, $article_query_raw, $article_query_numrows);
 $article_query = tep_db_query($article_query_raw);
 if(tep_db_num_rows($article_query) > 0)
 {
  $alternate=1;
  while ($article = tep_db_fetch_array($article_query))
  {
   $ide      = $article["id"];
   $seo_name = $article["seo_name"];
   $short_desp=nl2br(stripslashes(strip_tags($article['short_description'])));
   $short_description = (strlen($short_desp)>100?substr($short_desp,0,100).'..':$short_desp);
   $short_title = (strlen(tep_db_output($article['title'])) > 55) ?  substr(tep_db_output($article['title']), 0, 55) . '...' : tep_db_output($article['title']);

   $article_image='';
   if(tep_not_null($article["article_photo"]) && is_file(PATH_TO_MAIN_PHYSICAL.PATH_TO_ARTICLE_PHOTO.$article["article_photo"]))
   $article_image=tep_image(FILENAME_IMAGE."?image_name=".PATH_TO_ARTICLE_PHOTO.$article["article_photo"]."&size=400",'','','','align="center" class="card-img-top"');
			$article_url=tep_href_link(get_display_link($ide,$seo_name));



   $template->assign_block_vars('article', array(

    'article_name' =>'<h5 class="mt-0 mb-0"><a href="'.$article_url.' " class="article_title">'. $short_title .'</a></h5>

                      <span class="text-muted mb-2 block"><a  href="'.tep_href_link('article_cat_'.$article['acid'].'.html').'" title="'.$article[TEXT_LANGUAGE.'category_name'].'" class=green-badge>'
                        .tep_db_output($article[TEXT_LANGUAGE.'category_name']).'
                      </span></a>

                      <p class="card-text">'
                      .$short_description.
                      '</p>',

    'article_image' =>$article_image,
	'showdate'=>date("m-d-Y", strtotime($article['show_date'])),
  //  'more_article'  =>'<a href="'.$article_url.'"class="style20" >'.INFO_TEXT_MORE.'&gt;&gt;</a>',
    ));
  }
  @tep_db_free_result($result);
 }
}
else if($article_id!="")
{
 if(!$row_check=getAnyTableWhereData(ARTICLE_TABLE,"id='".tep_db_input($article_id)."' and show_date <='$now' and is_show='Yes'","*"))
 {
  $title='';
  $description=INFO_TEXT_SORRY_NO_ARTICLE;
  $author='';
  $article_img='';
  $art_posted_date = '';
 }
 else
 {
		$hidden_field=tep_draw_hidden_field('article_id',$row_check['id']);
		$email_form=tep_draw_form("email_form",FILENAME_EMAIL_ARTICLE,'','post').$hidden_field;
  $email="<a href='#' onclick=\"document.email_form.submit();\" class='email'>".INFO_TEXT_EMAIL."</a>";
  $email_button="<a href='#' onclick=\"document.email_form.submit();\" class='email'><img src='img/send_to_friend.gif'/></a>";
  $title=tep_db_output($row_check['title']);
  $viewed=tep_db_output($row_check['viewed'])+1;
   tep_db_query("UPDATE `article` SET `viewed` = ".$viewed." WHERE `article`.`id` = ".$article_id);

  $article_img=tep_image(FILENAME_IMAGE."?image_name=".PATH_TO_ARTICLE_PHOTO.$row_check["article_photo"]."&size=400",'','','','class="card-img-top2"');
  $description=stripslashes($row_check['description']);
  $author=tep_db_output($row_check['author']);
  $art_posted_date=date("m-d-Y", strtotime($row_check['show_date']));
 }
}
/////////////////////////// List of articles for a particular category //////////////////////////
else if($cat_Path!="")
{
 if(!$row_check=getAnyTableWhereData(ARTICLE_CATEGORY_TABLE,"id='".tep_db_input($cat_Path)."'","id,sub_cat_id"))
 {
  $messageStack->add_session(ERROR_CATEGORY_NOT_EXIST, 'error');
  tep_redirect(tep_href_link(FILENAME_ERROR));
 }
 $category_name='';
 if($row_check['sub_cat_id']!='')
 {
  $sub_cat_id=$row_check['sub_cat_id'];
  $sub_category_name_array=array();
  while($sub_cat_id!="")
  {
   $row_sub_cat=getAnyTableWhereData(ARTICLE_CATEGORY_TABLE,"id='".$sub_cat_id."'","id,".TEXT_LANGUAGE."category_name,sub_cat_id");
   $sub_cat_id=$row_sub_cat['sub_cat_id'];
   $sub_category_name_array[]='<a href="'.tep_href_link('article_cat_'.$row_sub_cat['id'].'.html').'">'.tep_db_output($row_sub_cat[TEXT_LANGUAGE.'category_name']).'</a>';
  }
  if(count($sub_category_name_array)>0)
  {
   $sub_category_name_array=array_reverse($sub_category_name_array);
   $category_name=implode(" -> ",$sub_category_name_array);
  }
  $category_name=$category_name.' -&gt; '.'<a href="'.tep_href_link('article_cat_'.$row_check['id'].'.html').'">'.get_name_from_table(ARTICLE_CATEGORY_TABLE,TEXT_LANGUAGE.'category_name','id',$row_check['id']).'</a>';
 }
 else
 {
  $category_name=get_name_from_table(ARTICLE_CATEGORY_TABLE,TEXT_LANGUAGE.'category_name','id',$cat_Path);
 }
 $whereClause="category_id='$cat_Path' and show_date <='$now' and is_show='Yes'";
 $article_query_raw = "select a.category_id,a.id,a.title,a.seo_name,a.short_description,a.show_date,a.article_photo from ".ARTICLE_TABLE." as a where $whereClause order by show_date desc, inserted desc";
 //echo $article_query_raw."<br>";
 $article_split = new splitPageResults($page_no, MAX_DISPLAY_ARTICLES, $article_query_raw, $article_query_numrows);
 $article_query = tep_db_query($article_query_raw);
 if(tep_db_num_rows($article_query) > 0)
 {
  $alternate=1;
  while ($article = tep_db_fetch_array($article_query))
  {
   $ide=$article["id"];
   $viewed=$article["viewed"]++;
   $article_image='';
   if(tep_not_null($article["article_photo"]) && is_file(PATH_TO_MAIN_PHYSICAL.PATH_TO_ARTICLE_PHOTO.$article["article_photo"]))
   $article_image=tep_image(FILENAME_IMAGE."?image_name=".PATH_TO_ARTICLE_PHOTO.$article["article_photo"]."&size=400",'','','','class="card-img-top"');
   $seo_name    = $article["seo_name"];
   $article_url = tep_href_link(get_display_link($ide,$seo_name));

   $row_selected=' class="dataTableRow'.($alternate%2==1?'1':'2').'" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . $article_url. '\'"';

   $short_title = (strlen(tep_db_output($article['title'])) > 55) ?  substr(tep_db_output($article['title']), 0, 55) . '...' : tep_db_output($article['title']);
   $short_description = (strlen(nl2br(stripslashes(strip_tags($article['short_description']))))>100 ? substr(nl2br(stripslashes(strip_tags($article['short_description']))),0,100).'..' : $short_desp);

   $template->assign_block_vars('article', array( 'row_selected' => $row_selected,
//    'article_name' => '&#8226&nbsp;<a href="'.tep_href_link('article_'.$ide.'.html').'">'.tep_db_output($article['title']).'</a></li>',
     'article_name' =>'<h5 class="mt-0 mb-0"><a href="'.$article_url.' ">'
                      .$short_title.'</h5>
                      </a>
                      <div class="for-mobile" align="justify">'
                          .$short_description.
                      '</div>',
    'article_image' =>$article_image,
    'showdate'=>date("m-d-Y", strtotime($article['show_date'])),
    ));
  }
  @tep_db_free_result($result);
 }
}
////// List of Categories start///////
$article_categories='';
$categories=tep_db_query("select * from " .ARTICLE_CATEGORY_TABLE ." where sub_cat_id='' ORDER BY ".TEXT_LANGUAGE."category_name");
$article_categories.='';
while($category = tep_db_fetch_array($categories))
{
  $article_categories.="
  <a class='me-3' href='".tep_href_link('article_cat_'.$category['id'].'.html')."' title='".$category[TEXT_LANGUAGE.'category_name']."' class='style3'>".ucfirst($category[TEXT_LANGUAGE."category_name"])."</a>
    <!--<tr ><td bgcolor='#ffffff' background='img/dotted_line.gif'><img src='img/dotted_line.gif' height='1' width='4'/></td></tr>-->
    ";
	$query_sub_category="select * from ".ARTICLE_CATEGORY_TABLE."  where sub_cat_id='".$category['id']."' ";
	$sub_category_result=tep_db_query($query_sub_category);
	$rows=tep_db_num_rows($sub_category_result);
	$article_categories.='
																									 ';
	$row=1;
	while($sub_cate=tep_db_fetch_array($sub_category_result))
	{
		$article_categories.="<a href='".tep_href_link('article_cat_'.$sub_cate['id'].'.html')."' title='".$sub_cate[TEXT_LANGUAGE.'category_name']."' class=\"style3\">".ucfirst($sub_cate[TEXT_LANGUAGE.'category_name'])."</a>";
		$query_sub_sub_category="select * from ".ARTICLE_CATEGORY_TABLE."  where sub_cat_id='".$sub_cate['id']."' ";
		$sub_sub_category_result=tep_db_query($query_sub_sub_category);
		$rows=tep_db_num_rows($sub_sub_category_result);
		$article_categories.='
																											';
		$row=1;
	while($sub_sub_cate=tep_db_fetch_array($sub_sub_category_result))
	{
		$article_categories.="



		<a href='".tep_href_link('article_cat_'.$sub_sub_cate['id'].'.html')."' title='".$sub_sub_cate[TEXT_LANGUAGE.'category_name']."' class=\"style3\">".ucfirst($sub_sub_cate[TEXT_LANGUAGE.'category_name'])."</a>";
  $row++;
	}
 $article_categories.="";
	}
 $article_categories.="";
}
$article_categories.="";
///////List of categories End //////
///////////////////////////
$template->assign_vars(array(
 'HEADING_TITLE'=>HEADING_TITLE,
 'category_name'=>$category_name,
 'tabletext'=>$article_categories,
 'hidden_fields'=>$hidden_fields,
	'email_article' =>$email_form,
	'email_button' =>$email_button,
	'email'        =>$email,
	'print_image'        =>'<a href="#" class="email" onclick="popUp(\''.tep_href_link(FILENAME_ARTICLE,tep_get_all_get_params().'action=print&article_id='.$article_id).'\')"><img src="img/print.gif"/></a>',
	'print'              =>'<a href="#" class="email" onclick="popUp(\''.tep_href_link(FILENAME_ARTICLE,tep_get_all_get_params().'action=print&article_id='.$article_id).'\')" class="article_sub_title">'.INFO_TEXT_PRINT.'</a>',
 'title'=>$title,
 'article_img' => $article_img,
 'description'=>$description,
 'author'=>$author,
 'article_posted_date' => $art_posted_date,
 'article_viewed'=>$viewed,
 'INFO_TEXT_BY' => INFO_TEXT_BY,
 'INFO_TEXT_ARTICLE_BY_CATEGORY'=>INFO_TEXT_ARTICLE_BY_CATEGORY,
 'INFO_TEXT_LATEST_ARTICLE'     => INFO_TEXT_LATEST_ARTICLE,
	'INFO_TEXT_CATEGORY'=>INFO_TEXT_CATEGORY,
 'INFO_TEXT_NEWSLETTER'=>'<a href="'.tep_href_link(FILENAME_ARTICLE).'" class="article_home">'.INFO_TEXT_NEWSLETTER.'</a>',
 'LEFT_BOX_WIDTH'=>LEFT_BOX_WIDTH1,
 'INFO_TEXT_PRINT_ARTICLE'=>INFO_TEXT_PRINT_ARTICLE,
 'RIGHT_BOX_WIDTH'=>RIGHT_BOX_WIDTH1,
 'LEFT_HTML'=>LEFT_HTML,
 'RIGHT_HTML'=>RIGHT_HTML,
 'update_message'=>$messageStack->output()));

if($article_id=="" && $cat_Path=="")
{
 $template->assign_vars(array(
  'count_rows'=>$article_split->display_count($article_query_numrows, MAX_DISPLAY_ARTICLES, $page_no, TEXT_DISPLAY_NUMBER_OF_ARTICLES),
  'no_of_pages'=>$article_split->display_links($article_query_numrows, MAX_DISPLAY_ARTICLES, MAX_DISPLAY_PAGE_LINKS, $page_no),
  ));
 $template->pparse('article2');
}
elseif($action=='print')
{
 $template->pparse('article3');
}
else if($article_id!="")
{
 $template->pparse('article');
}
else
{
 $template->assign_vars(array(
  'count_rows'=>$article_split->display_count($article_query_numrows, MAX_DISPLAY_ARTICLES, $page_no, TEXT_DISPLAY_NUMBER_OF_ARTICLES),
  'no_of_pages'=>$article_split->display_links($article_query_numrows, MAX_DISPLAY_ARTICLES, MAX_DISPLAY_PAGE_LINKS, $page_no,'cat_path='.$_GET['cat_path']),
  ));
 $template->pparse('article1');
}
?>
