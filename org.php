<?php
    $out_scr .= "<div class='hzm3-row-padding hzm3-center hzm3-small hzm_home_bloc' style='margin:0 -16px'>";
    if(!$objme) $objme = AfwSession::getUserConnected();
    if(!$objme) die("no opened session for you. please contact administartor!!");
    $iamAdmin = $objme->isAdmin();
    if(!$org) 
    {
        $org = $objme->getMyOrganizationId();
        $dep = $objme->getMyDepartmentId();
    }
    
    if(!$org)
    {
        die("session aborted, check your connection or do logout and login again.");
        //die("your organization id is not (no more) allowed to access the system. please contact administrator!!<br>مؤسستك لا أو لم تعد تملك الصلاحية للدخول على هذا النظام")
    }    
    
    $orgObj = Orgunit::loadById($org);
    //if($dep) $depObj = Orgunit::loadById($dep);         
    if($orgObj)
    {
            $menu_folder = $orgObj->getOrgunitMenu($extended_orgunit_class,$extended_orgunit_module);
            //die("Orgunit::loadById($org) ->getOrgunitMenu() => menu_folder = ".var_export($menu_folder));
            foreach($menu_folder["items"] as $menu_folder_item_id => $menu_folder_item)
            {
                $menu_item_id = $menu_folder_item["id"];
                $menu_item_icon = $menu_folder_item["icon"];
                if(!$menu_item_icon) $menu_item_icon = $menu_icons_arr[$menu_item_id];
                if($menu_item_icon=="org") $menu_item_icon = $org_icon;
                if($menu_item_icon=="sub-org") $menu_item_icon = $sub_org_icon;
                if(!$menu_item_icon) $menu_item_icon = $org_icon;
                
                if(!$menu_item_icon) $menu_item_icon = "building";
                 
                $menu_item_title = $menu_folder_item["menu_name"];
                $menu_item_page = $menu_folder_item["page"];
                $menu_item_css = $menu_folder_item["css"];
                $menu_item_color = $menu_folder_item["color"];

                $out_scr .= "<div id='menu-item-$menu_item_id' class='$menu_item_css hzm-menu-item hzm3-col l3 m3 s12'>
                                <a class='action_lourde hzm3-button hzm3-light-grey hzm3-block' href='$menu_item_page' style='white-space:nowrap;text-decoration:none;margin-top:1px;margin-bottom:1px'>
                                    <div class=\"hzm-width-100 hzm-text-center hzm_margin_bottom \">
                                      <div class=\"hzm-menu hzm-vertical-align hzm-container-center hzm-custom hzm-custom-$menu_item_color hzm-custom-icon-container only-border border-primary\">
                                        <i class=\"hzm-container-center hzm-vertical-align-middle hzm-icon-$menu_item_icon\"></i>
                                      </div>
                                    </div>
                                    $menu_item_title
                                </a>
                             </div>";

            }
            foreach($menu_folder["sub-folders"] as $menu_sub_folder_id => $menu_sub_folder)
            {
                if(($iamAdmin) or (!$menu_sub_folder["need_admin"]))
                {
                        $menu_folder_title = $menu_sub_folder["menu_name"];
                        $menu_folder_id = $menu_sub_folder["id"];
                        $menu_folder_page = $menu_sub_folder["page"];
                        $menu_folder_css = $menu_sub_folder["css"];                        
                        
                        $out_scr .= "<div id='menu-folder-$menu_sub_folder_id' class='$menu_folder_css hzm-menu-folder hzm3-col l3 m3 s12'>
                                         <a class='hzm3-button hzm3-light-grey hzm3-block' href='$menu_folder_page' style='white-space:nowrap;text-decoration:none;margin-top:1px;margin-bottom:1px'>
                                            <div class=\"hzm-width-100 hzm-text-center hzm_margin_bottom \">
                                              <div class=\"hzm-vertical-align hzm-container-center hzm-custom hzm-custom-icon-container only-border border-primary\">
                                                <i class=\"hzm-container-center hzm-vertical-align-middle hzm-icon-globe\"></i>
                                              </div>
                                            </div>
                                            $menu_folder_title
                                          </a>
                                     </div>";
                }         
            }
    }

    
        
        
        
            
    $out_scr .= "</div>";    
?>