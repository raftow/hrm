<?php
require_once ("ini.php");
require_once ("../external/db.php");
// here old require of common.php
$only_members = false;
include("../pag/check_member.php");


include("../lib/hzm/web/hzm_header.php");

$id_sh = $_REQUEST["sh"];
if(!$id_sh)
   include("main_menu.php");
else
   include("sh_menu.php");
include("../lib/hzm/web/hzm_footer.php");
?>