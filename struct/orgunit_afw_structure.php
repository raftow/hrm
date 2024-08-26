<?php 
        class HrmOrgunitAfwStructure
        {

			public static function initInstance(&$obj)
			{
				if($obj instanceof Orgunit)
				{
					$obj->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 10;
					$obj->DISPLAY_FIELD = "titre_short";
					$obj->ORDER_BY_FIELDS = "titre_short, titre, id_sh_type, id_sh_org, id_domain";
					//$obj->UNIQUE_KEY = array('id_sh_type','id_sh_org','titre_short','titre','id_domain');
					
					$obj->UNIQUE_KEY = array('hrm_code');
					$obj->editByStep = true;
					$obj->editNbSteps = 7;
					$obj->showQeditErrors = true;
					$obj->showRetrieveErrors = true;
					$obj->public_display = true;
				}        
			}

                public static $DB_STRUCTURE = array(

                        
			'id' => array('SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  
				'TYPE' => 'PK',  
				'CSS' => 'width_pct_25',  'SEARCH-BY-ONE' => '',  'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', ),

			'gender_id' => array('IMPORTANT' => 'IN',  'SEARCH' => false,  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => false,  'SIZE' => 40,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'genre',  'ANSMODULE' => 'pag',  'DEFAUT' => 1,  
				'CSS' => 'width_pct_25',  'SEARCH-BY-ONE' => '',  'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', ),

			'city_id' => array('STEP' => 1,  'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => false,  
				'CSS' => 'width_pct_25',  'SIZE' => 40,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'city',  'ANSMODULE' => 'pag',  'AUTOCOMPLETE' => true,  
				'RELATION' => 'ManyToOne',  'READONLY' => false,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', ),

			'active' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'DEFAUT' => 'Y',  
				'TYPE' => 'YN',  
				'CSS' => 'width_pct_25',  'SEARCH-BY-ONE' => '',  'DISPLAY' => '',  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', ),

			'id_sh_type' => array('SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  
				'TYPE' => 'FK',  'ANSWER' => 'orgunit_type',  'ANSMODULE' => 'hrm',  'SEARCH' => true,  'QSEARCH' => true,  'SHORTNAME' => 'orgtype',  
				'CSS' => 'width_pct_25',  'SEARCH-BY-ONE' => true,  'DISPLAY' => true,  'STEP' => 1,  'NO-COTE'=>true,
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', ),

			'id_sh_org' => array('SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  
				'TYPE' => 'FK',  'ANSWER' => 'orgunit',  'ANSMODULE' => 'hrm',  
				'RELATION' => 'OneToMany',  
				'WHERE' => "id_sh_type in (5,6,7,8)", 
				 'SEARCH-BY-ONE' => true,  'QSEARCH' => false,  
				'CSS' => 'width_pct_25',  'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', ),

			'titre_short' => array('SHOW' => true,  'QSEARCH' => true,  'SEARCH' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  
				'TYPE' => 'TEXT',  'UTF8' => true,  'SHORTNAME' => 'title',  'SIZE' => 32,  'SEARCH-BY-ONE' => true,  'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),

			'titre' => array('SHOW' => true,  'QSEARCH' => true,  'SEARCH' => true,  'RETRIEVE' => false,  'EDIT' => true,  
				'TYPE' => 'TEXT',  'UTF8' => true,  'SIZE' => 64,  'SEARCH-BY-ONE' => true,  'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),

			'id_domain' => array(
				'TYPE' => 'FK',  'ANSWER' => 'domain',  'ANSMODULE' => 'pag',  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  
				'WHERE' => "", 
				 'SHORTNAME' => 'domain',  'SEARCH-BY-ONE' => true,  'SEARCH' => true,  'QSEARCH' => true,  'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),

			'sh_code' => array('SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  
				'TYPE' => 'TEXT',  'SIZE' => 16,  'QSEARCH' => true,  'UTF8' => true,  'SEARCH-BY-ONE' => true,  'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),

			'hrm_code' => array('SEARCH' => false,  'QSEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 16,  'MIN-SIZE' => 3,  'CHAR_TEMPLATE' => 'ALPHABETIC,NUMERIC,UNDERSCORE',  'UTF8' => true,  
				'TYPE' => 'TEXT',  'READONLY' => false,  'SEARCH-BY-ONE' => true,  'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),

			'crm_code' => array('SEARCH' => false,  'QSEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 16,  'MIN-SIZE' => 3,  'CHAR_TEMPLATE' => 'ALPHABETIC,NUMERIC,UNDERSCORE',  'UTF8' => true,  
				'TYPE' => 'TEXT',  'READONLY' => false,  'SEARCH-BY-ONE' => true,  'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),


			'id_sh_parent' => array('SHOW' => true,  'SHORTNAME' => 'parent',  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => false,  'SEARCH-BY-ONE' => true,  'SEARCH' => true,  
				'TYPE' => 'FK',  'ANSWER' => 'orgunit',  'ANSMODULE' => 'hrm',  
				'RELATION' => 'OneToMany',  
				'WHERE' => "id != '§id§' 
								and (id_sh_type in (5,6,7,8)) or (§id_sh_type§ in (3,4,9,10,15) and  id_sh_type in (11,13,14,16))", 
			// and (id_sh_org = §id_sh_org§ or (§id_sh_type§ in ('5','6','7','8') and id_sh_type in ('5','6','7','8'))) 				
			// and id_domain = §id_domain§
			'STEP' => 2,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),

			'id_responsible' => array('SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => false,  
				'TYPE' => 'FK',  'SHORTNAME' => 'resp',  'ANSWER' => 'employee',  'ANSMODULE' => 'hrm',  
				'WHERE' => "(id_sh_org in (§id_sh_org§, §id_sh_parent§, §id§) 
                                                   and (id_sh_div = 0 or id_sh_div is null or id_sh_div=§id§ or id_sh_div=§id_sh_parent§) 
                                                   and (jobrole_mfk like '%,1,%' or jobrole_mfk like '%,51,%' or jobrole_mfk like '%,123,%'))", 
				 
				'RELATION' => 'OneToMany',  'STEP' => 2,  'SEARCH-BY-ONE' => '',  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),

		'subOrgList' => array(
				'TYPE' => 'FK',  'ANSWER' => 'orgunit',  'ANSMODULE' => 'hrm',  
				'CATEGORY' => 'ITEMS',  'ITEM' => 'id_sh_parent',  
				'WHERE' => "", 
				 'SHOW' => true,  'FORMAT' => 'retrieve',  'LINK_COL' => 'id_sh_parent',  'ITEMS_COL' => 'subOrgList',  'FEUILLE_COL' => 'employeeList',  'FEUILLE_COND_METHOD' => '',  'ALL_ITEMS' => true,  'EDIT' => false,  'ICONS' => true,  'DELETE-ICON' => false,  'BUTTONS' => true,  'NO-LABEL' => false,  'STEP' => 2,  'SEARCH-BY-ONE' => '',  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),

			'addresse' => array('SHOW' => true,  'SEARCH' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => false,  
				'TYPE' => 'TEXT',  'UTF8' => true,  'STEP' => 3,  'QSEARCH' => true,  'SEARCH-BY-ONE' => true,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),

			'home_latitude' => array(
				'TYPE' => 'FLOAT',  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => false,  'STEP' => 3,  
				'TITLE_AFTER' => '(in DEG)',  'HELP' => "latitude احصل عليه من  <a target='_gps' href='http://www.whatsmygps.com/'>هنا</a> أو من <a target='_gps' href='http://www.coordonnees-gps.fr/'>هنا</a>",  
				'SEARCH-BY-ONE' => '',  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),

			'home_longitude' => array(
				'TYPE' => 'FLOAT',  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => false,  'STEP' => 3,  'TITLE_AFTER' => '(in DEG)',  
				'HELP' => "longitude احصل عليه من  <a target='_gps' href='http://www.whatsmygps.com/'>هنا</a> أو من <a target='_gps' href='http://www.coordonnees-gps.fr/'>هنا</a>", 
				'SEARCH-BY-ONE' => '',  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),

			'city_name' => array('SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => false,  
				'TYPE' => 'TEXT',  'UTF8' => true,  'STEP' => 3,  'SEARCH-BY-ONE' => '',  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),

			'cp' => array('SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => false,  
				'TYPE' => 'TEXT',  'STEP' => 3,  'SEARCH-BY-ONE' => '',  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),

			'quarter' => array('SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => false,  
				'TYPE' => 'TEXT',  'UTF8' => true,  'STEP' => 3,  'SEARCH-BY-ONE' => '',  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),

			'country_code' => array('SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => false,  
				'TYPE' => 'TEXT',  'UTF8' => true,  'DEFAUT' => 'sa',  'STEP' => 3,  'SEARCH-BY-ONE' => '',  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),

		'map' => array(
				'TYPE' => 'TEXT',  
				'CATEGORY' => 'FORMULA',  'SHOW' => true,  'RETRIEVE' => false,  'STEP' => 3,  'SEARCH-BY-ONE' => '',  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),

		'route' => array(
				'TYPE' => 'TEXT',  
				'CATEGORY' => 'FORMULA',  'SHOW' => true,  'RETRIEVE' => false,  'STEP' => 3,  'SEARCH-BY-ONE' => '',  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),

			'phone' => array('SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => false,  
				'TYPE' => 'TEXT',  'QSEARCH' => true,  'UTF8' => true,  'STEP' => 4,  'SEARCH-BY-ONE' => true,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),

			'web' => array('SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => false,  
				'TYPE' => 'TEXT',  'FORMAT' => 'WEB',  'QSEARCH' => true,  'UTF8' => true,  'STEP' => 4,  'SEARCH-BY-ONE' => true,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),

			'email' => array('SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => false,  
				'TYPE' => 'TEXT',  'QSEARCH' => true,  'UTF8' => true,  'STEP' => 4,  'SEARCH-BY-ONE' => true,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),

		'goalConcernList' => array(
				'TYPE' => 'FK',  'ANSWER' => 'goal_concern',  'ANSMODULE' => 'bau',  
				'CATEGORY' => 'ITEMS',  'ITEM' => 'orgunit_id',  
				'WHERE' => "", 
				 'SHOW' => false,  'FORMAT' => 'retrieve',  'EDIT' => false,  'ICONS' => true,  'DELETE-ICON' => false,  'BUTTONS' => true,  'NO-LABEL' => false,  'STEP' => 5,  'SEARCH-BY-ONE' => '',  'DISPLAY' => false,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),

		'goalList' => array(
				'TYPE' => 'MFK',  'ANSWER' => 'goal',  'ANSMODULE' => 'bau',  
				'CATEGORY' => 'FORMULA',  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => false,  'READONLY' => true,  'PHP_FORMULA' => 'list_extract.goalConcernList.goal_id.',  'STEP' => 5,  'SEARCH-BY-ONE' => '',  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),

		'allEmployeeList' => array(
				'TYPE' => 'FK',  'ANSWER' => 'employee',  'ANSMODULE' => 'hrm',  
				'CATEGORY' => 'ITEMS',  'ITEM' => 'id_sh_org',  
				'WHERE' => "", 
				 'SHOW' => true,  'FORMAT' => 'retrieve',  'EDIT' => true,  'ICONS' => true,  'DELETE-ICON' => true,  'BUTTONS' => true,  'NO-LABEL' => false,  'READONLY' => true,  'STEP' => 6,  'SEARCH-BY-ONE' => '',  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),

		'employeeList' => array(
				'TYPE' => 'FK',  'ANSWER' => 'employee',  'ANSMODULE' => 'hrm',  
				'CATEGORY' => 'ITEMS',  'ITEM' => 'id_sh_div',  
				'WHERE' => "", 
				 'SHOW' => true,  'FORMAT' => 'retrieve',  'EDIT' => true,  
				 'ICONS' => true,  'DELETE-ICON' => true,  'BUTTONS' => true,  'NO-LABEL' => false,  'READONLY' => true,  
				 'STEP' => 6,  'SEARCH-BY-ONE' => '',  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),

		'moduleOrgunitList' => array(
				'TYPE' => 'FK',  'ANSWER' => 'module_orgunit',  'ANSMODULE' => 'ums',  
				'CATEGORY' => 'ITEMS',  'ITEM' => 'id_orgunit',  
				'WHERE' => "", 
				 'SHOW' => true,  'FORMAT' => 'retrieve',  'EDIT' => true,  'READONLY' => true,  'ICONS' => true,  'DELETE-ICON' => true,  'BUTTONS' => true,  'NO-LABEL' => false,  'STEP' => 7,  'SEARCH-BY-ONE' => '',  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),

                        'created_by'         => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'TECH_FIELDS-RETRIEVE' => true, 'RETRIEVE' => false, 
                                                                'QEDIT' => false, 'TYPE' => 'FK', 'ANSWER' => 'auser', 'ANSMODULE' => 'ums', 'FGROUP' => 'tech_fields'),

                        'created_at'            => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'TECH_FIELDS-RETRIEVE' => true, 'RETRIEVE' => false, 
                                                                'QEDIT' => false, 'TYPE' => 'GDAT', 'FGROUP' => 'tech_fields'),

                        'updated_by'           => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'TECH_FIELDS-RETRIEVE' => true, 'RETRIEVE' => false, 
                                                                'QEDIT' => false, 'TYPE' => 'FK', 'ANSWER' => 'auser', 'ANSMODULE' => 'ums', 'FGROUP' => 'tech_fields'),

                        'updated_at'              => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'TECH_FIELDS-RETRIEVE' => true, 'RETRIEVE' => false, 
                                                                'QEDIT' => false, 'TYPE' => 'GDAT', 'FGROUP' => 'tech_fields'),

                        'validated_by'       => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'RETRIEVE' => false, 
                                                                'QEDIT' => false, 'TYPE' => 'FK', 'ANSWER' => 'auser', 'ANSMODULE' => 'ums', 'FGROUP' => 'tech_fields'),

                        'validated_at'          => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'RETRIEVE' => false, 'QEDIT' => false, 
                                                                'TYPE' => 'GDAT', 'FGROUP' => 'tech_fields'),

                        /* 'active'                   => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'RETRIEVE' => false, 'EDIT' => false, 
                                                                'QEDIT' => false, "DEFAULT" => 'Y', 'TYPE' => 'YN', 'FGROUP' => 'tech_fields'),*/

                        'version'                  => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'RETRIEVE' => false, 
                                                                'QEDIT' => false, 'TYPE' => 'INT', 'FGROUP' => 'tech_fields'),

                        // 'draft'                         => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'RETRIEVE' => false, 'EDIT' => false, 
                        //                                        'QEDIT' => false, "DEFAULT" => 'Y', 'TYPE' => 'YN', 'FGROUP' => 'tech_fields'),

                        'update_groups_mfk'             => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'RETRIEVE' => false, 
                                                                'QEDIT' => false, 'ANSWER' => 'ugroup', 'ANSMODULE' => 'ums', 'TYPE' => 'MFK', 'FGROUP' => 'tech_fields'),

                        'delete_groups_mfk'             => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'RETRIEVE' => false, 
                                                                'QEDIT' => false, 'ANSWER' => 'ugroup', 'ANSMODULE' => 'ums', 'TYPE' => 'MFK', 'FGROUP' => 'tech_fields'),

                        'display_groups_mfk'            => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'RETRIEVE' => false, 
                                                                'QEDIT' => false, 'ANSWER' => 'ugroup', 'ANSMODULE' => 'ums', 'TYPE' => 'MFK', 'FGROUP' => 'tech_fields'),

                        'sci_id'                        => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'RETRIEVE' => false, 'QEDIT' => false, 
                                                                'TYPE' => 'FK', 'ANSWER' => 'scenario_item', 'ANSMODULE' => 'pag', 'FGROUP' => 'tech_fields'),

                        'tech_notes' 	                => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'TYPE' => 'TEXT', 'CATEGORY' => 'FORMULA', "SHOW-ADMIN" => true, 
                                                                'TOKEN_SEP'=>"§", 'READONLY'=>true, "NO-ERROR-CHECK"=>true, 'FGROUP' => 'tech_fields'),
                ); 
        } 
?>