<?php
      $file_dir_name = dirname(__FILE__); 
      
      
      //require_once("$file_dir_name/../rfw/rfw_factory.php");      
      //require_once("$file_dir_name/../rfw/rfw.php");
      
      //$rfwFactoryObj = new RFWFactory();

      $module_id = 16;
      $module_obj = new Module();
      $module_obj->load($module_id);


      $numfrontclass = 0;
      $nummenu = 1;
      $numtheme = 0;
      $numsubtheme = 0;
      
      $theme[$numtheme] = "";// $module_obj->getMainsh()->getDisplay();
      $subtheme[$numtheme][$numsubtheme] = "لوحة التحكم";//$module_obj->getDisplay();

      $menu_tit = "تفعيل حساب موظف على الحافظة";
      $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"main.php?Main_Page=activate_account.php", "png"=>"../images/profile.png", "titre"=>"$menu_tit", "id"=>"", "class"=>"front_$numfrontclass", "subtheme"=>0);
      $numfrontclass = ($numfrontclass + 1) % 15;

      $menu_tit = "صلاحيات موظف على الحافظة";
      $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"main.php?Main_Page=account_roles.php", "png"=>"../images/profile.png", "titre"=>"$menu_tit", "id"=>"", "class"=>"front_$numfrontclass", "subtheme"=>0);
      $numfrontclass = ($numfrontclass + 1) % 15;

      $menu_tit = "أحدث الإجراءات";
      $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"main.php?Main_Page=afw_lv_menu.php", "png"=>"../images/profile.png", "titre"=>"$menu_tit", "id"=>"", "class"=>"front_$numfrontclass", "subtheme"=>0);
      $numfrontclass = ($numfrontclass + 1) % 15;

      $menu_tit = "مراقبة تحديث البيانات";
      $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"main.php?Main_Page=data_refresh.php", "png"=>"../images/profile.png", "titre"=>"$menu_tit", "id"=>"", "class"=>"front_$numfrontclass", "subtheme"=>0);
      $numfrontclass = ($numfrontclass + 1) % 15;

      
      include "../pag/menu_constructor.php";
      
       
      
      


?>

