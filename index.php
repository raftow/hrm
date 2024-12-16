<?php
$direct_dir_name = $file_dir_name = dirname(__FILE__);
include("$file_dir_name/hrm_start.php");
$objme = AfwSession::getUserConnected();
if($objme)
{
    $Main_Page = "work.php";
    $MODULE = $My_Module = "hrm";
    $options = [];
    $options["dashboard-stats"] = true;
    $options["chart-js"] = true;
    AfwMainPage::echoMainPage($My_Module, $Main_Page, $file_dir_name, $options);
}

?>