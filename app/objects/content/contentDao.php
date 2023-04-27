<?php
include_once($GLOBALS['configuration']['path_app_admin_objects']."content/content.php");
interface  ContentDao{
   public function editar (Content $content);
   public function get ($id);
   public function getList();
}
?>
