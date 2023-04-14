<?
class theme_theme3
{
 var $theme_id;
 function __construct()
 {
  $this->theme_id = 'theme3';
 }
 function install_theme()
 {
  tep_db_query("insert into " . CONFIGURATION_TABLE . " (configuration_title, configuration_name, configuration_value, configuration_description, configuration_group_id, priority, set_function, inserted) values ('Maximum latest jobs  display', 'MODULE_THEME_THEME3_MAX_LATEST_JOB', '6', 'Maximum latest jobs display', '9', '1', '', now())");
  tep_db_query("insert into " . CONFIGURATION_TABLE . " (configuration_title, configuration_name, configuration_value, configuration_description, configuration_group_id, priority, set_function, inserted) values ('Maximum category  display', 'MODULE_THEME_THEME3_MAX_JOB_CATEORY', '10', 'Maximum category display', '9', '2', '', now())");
  tep_db_query("insert into " . CONFIGURATION_TABLE . " (configuration_title, configuration_name, configuration_value, configuration_description, configuration_group_id, priority, set_function, inserted) values ('Maximum career tools display', 'MODULE_THEME_THEME3_MAX_FEATURED_ARTICLE', '3', 'Maximum  article display', '9', '3', '', now())");

	}
 function remove_theme()
 {
  tep_db_query("delete from " . CONFIGURATION_TABLE . " where configuration_name in ('".implode("', '",$this->keys())."')");
 }
 function keys()
 {
  return array('MODULE_THEME_THEME3_MAX_LATEST_JOB', 'MODULE_THEME_THEME3_MAX_JOB_CATEORY','MODULE_THEME_THEME3_MAX_FEATURED_ARTICLE');
 } 
}
?>