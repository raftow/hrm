<?php
                
                $TECH_FIELDS = array();
                $MODULE = "hrm";
                $THIS_MODULE_ID = 1072;
                $MODULE_FRAMEWORK[$THIS_MODULE_ID] = 1;

        	$TECH_FIELDS[$MODULE]["CREATION_USER_ID_FIELD"]  ="created_by";
        	$TECH_FIELDS[$MODULE]["CREATION_DATE_FIELD"]     ="created_at";
        	$TECH_FIELDS[$MODULE]["UPDATE_USER_ID_FIELD"]    ="updated_by";
        	$TECH_FIELDS[$MODULE]["UPDATE_DATE_FIELD"]       ="updated_at";
        	$TECH_FIELDS[$MODULE]["VALIDATION_USER_ID_FIELD"]="validated_by";
        	$TECH_FIELDS[$MODULE]["VALIDATION_DATE_FIELD"]   ="validated_at";
        	$TECH_FIELDS[$MODULE]["VERSION_FIELD"]           ="version";
        	$TECH_FIELDS[$MODULE]["ACTIVE_FIELD"]            ="active";
                
                $TECH_FIELDS_TYPE = array("created_by"=>"FK", 
                                          "created_at"=>"DATE", 
                                          "updated_by"=>"FK", 
                                          "updated_at"=>"DATE", 
                                          "validated_by"=>"FK", 
                                          "validated_at"=>"DATE", 
                                          "version"=>"INT");
                                          
                $LANGS_MODULE = array("ar"=>true,"fr"=>false,"en"=>true);
                
                
                $MENU_ICONS[1] = "cogs";
                $MENU_ICONS[9] = "sitemap";
                $MENU_ICONS[3] = "building";
                $MENU_ICONS[5] = "pie-chart";
                
                $custom_header = true;
                
                $date_pos_left = "40%";
                $date_pos_top = "29px";
                $date_color = "#000";
                $date_bgcolor = "transparent";
                $header_bg_color = "rgb(230, 242, 255)";
                //$date_font_weight = "bold";
                //$date_color = "#1e620b";
                $date_font_size = "14px";
                $date_font_family = "maghreb";
                $welcome_pos_left = "42%";
                $welcome_pos_top = "5px";
                $module_config_token["file_types"] = "1,2,3,4,5,6";
                
                $auto_generate_employeenum = true;

                $front_header = true;
                $front_application = true;
                // $config["img-path"] = "../exte-rnal/pic/"; // "../$MODULE/pic/";
                // $config["img-company-path"] = "../exte-rnal/pic/";
                // $config["sms-crm"] = true;
                // $config["sms-captcha"] = true;
                // $config["default-logged-out-page"] = "customer_login.php";
                
                
                $header_style = "header_thin";
                $my_theme = "simple";
                
                


                $jstree_activate = true;
                $display_in_edit_mode["*"] = true;
?>