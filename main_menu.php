<?php
      $uri_items = explode("/",$_SERVER[REQUEST_URI]);
      if($uri_items[0]) $module = $uri_items[0];
        else $module = $uri_items[1];

       if(($objme) and ($objme->popup))
       {
            $target = "target='popup'";
            $popup_t = "on";  
       }
       else
       {
            $target = "";
            $popup_t = ""; 
       }
        
      // here was old const php
      
      $nummenu = 1;
      $numtheme = 0;
      $numsubtheme = 0;
      $numfrontclass = 4;
      
      $theme[$numtheme] = "إدارة البيانات";
      $subtheme_class[$numtheme][$numsubtheme] = "front";
      $subtheme_title_class[$numtheme][$numsubtheme] = "database"; 
      $subtheme[$numtheme][$numsubtheme] = "إدارة بياناتي الشخصية";
      
      if($mySemplObj)
      {
              $sempl_me = $mySemplObj->getId();   
              $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"main.php?Main_Page=afw_mode_edit.php&cl=Sempl&id=$sempl_me&currmod=sdd", "png"=>"../images/emprofile.png", "titre"=>"إدارة سيرتي الذاتية", "id"=>"", "class"=>"front_$numfrontclass", "subtheme"=>0);
              $numfrontclass = ($numfrontclass + 1) % 15;  
      }
      $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"afw_my_files.php?popup=$popup_t", "target"=>"$target", "png"=>"../images/attachements.png", "titre"=>"إدارة المرفقات", "id"=>"", "class"=>"front_$numfrontclass", "subtheme"=>0);
      $numfrontclass = ($numfrontclass + 1) % 15;

      $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"main.php?Main_Page=afw_mode_search.php&cl=Sempl&currmod=sdd", "png"=>"../images/emsearch.png", "titre"=>"البحث في الموظفين", "id"=>"", "class"=>"front_$numfrontclass", "subtheme"=>0, 
                                                         "afw"=>"Sempl", "mod"=>"sdd", "operation"=>"search");
      $numfrontclass = ($numfrontclass + 1) % 15;
      
      include "../pag/menu_constructor.php";
      
       
      
      


?>

