<?php 
        class HrmEmployeeAfwStructure
        {
			public static function initInstance(&$obj)
			{
				if($obj instanceof Employee)
				{
					$obj->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                    $obj->DISPLAY_FIELD = "";
                    $obj->ORDER_BY_FIELDS = "firstname, f_firstname, lastname";
                    $obj->FORMULA_DISPLAY_FIELD = "concat(IF(ISNULL(firstname), '', firstname) , ' ' , IF(ISNULL(f_firstname), '', f_firstname) , ' ' , IF(ISNULL(lastname), '', lastname))"; 
                    
                    $obj->UNIQUE_KEY = array('id_sh_org','idn_type_id', 'idn');
                    $obj->editByStep = true;
                    $obj->editNbSteps = 4;
                    $obj->showQeditErrors = true;
                    $obj->showRetrieveErrors = true;
                    $obj->public_display = true;
				}        
			}
                public static $DB_STRUCTURE = array(

                        
			'id' => array('SHOW' => true,  'RETRIEVE' => false,  'EDIT' => false,  
				'TYPE' => 'PK',  'STEP' => 1,  'SEARCH-BY-ONE' => '',  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),

			'id_sh_org' => array('FGROUP' => 'employment',  'QSEARCH' => true,  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'orgunit',  'ANSMODULE' => 'hrm',  'SHORTNAME' => 'orgunit',  'NO_DDB' => true,  
				'DEPENDENT_OFME' => array (0 => 'id_sh_div',),  
				'WHERE' => "id_sh_type in (5,6,7,8)", 
				 
				'RELATION' => 'OneToMany',  'DEFAUT' => 1,  'STEP' => 1,  'SIZE' => 24,  
				'CSS' => 'width_pct_100',  'UTF8' => false,  'READONLY' => true,  
				'DISABLE-READONLY-ADMIN' => true,  'SEARCH-BY-ONE' => true,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', ),
				

			'id_sh_dep' => array('FGROUP' => 'employment',  'QSEARCH' => true,  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 24,  
				'CSS' => 'width_pct_100',  'UTF8' => false,  'AUTOCOMPLETE' => false, 'SHORTNAME' => 'department', 
				'TYPE' => 'FK',  'ANSWER' => 'orgunit',  'ANSMODULE' => 'hrm',  
				'WHERE' => "id_sh_type in (9,11,13,14,16) and (id_sh_org=§id_sh_org§ or id = §id_sh_org§ or 1 = §id_sh_org§)", 
				 
				'WHERE-SEARCH' => "id_sh_type in (11,13,14,16)", 
				 'DEPENDENCY' => 'id_sh_org',  'READONLY' => true,  'DISABLE-READONLY-ADMIN' => true,  'NO_DDB' => true,  'DEFAUT' => 0,  
				'RELATION' => 'OneToMany',  'STEP' => 1,  'SEARCH-BY-ONE' => true,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', ),				



			'id_sh_div' => array('FGROUP' => 'employment',  'QSEARCH' => true,  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 24,  
				'CSS' => 'width_pct_100',  'UTF8' => false,  'AUTOCOMPLETE' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'orgunit',  'ANSMODULE' => 'hrm', 'SHORTNAME' => 'division',  
				'WHERE' => "id_sh_type in (3,4,9,10,15) and (id_sh_org=§id_sh_dep§ or id_sh_parent=§id_sh_dep§ or id = §id_sh_dep§)", 
				 
				'WHERE-SEARCH' => "id_sh_type in (3,4,9,10,15)", 
				 'DEPENDENCY' => 'id_sh_org',  'READONLY' => true,  'DISABLE-READONLY-ADMIN' => true,  'NO_DDB' => true,  'DEFAUT' => 0,  
				'RELATION' => 'OneToMany',  'STEP' => 1,  'SEARCH-BY-ONE' => true,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', ),

			'domain_id' => array('FGROUP' => 'employment',  
				'TYPE' => 'INT',  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  
				'WHERE' => "", 
				 'SHORTNAME' => 'domain',  'SEARCH-BY-ONE' => true,  'SEARCH' => true,  'QSEARCH' => true,  
				'CSS' => 'width_pct_50',  'STEP' => 1,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', ),

			'username' => array('FGROUP' => 'employment',  
				'TYPE' => 'TEXT',  'QEDIT' => false,  'EDIT' => true,  'SHOW' => true,  'READONLY' => true,  'DISABLE-READONLY-ADMIN' => true,  'RETRIEVE' => false,  
				'CSS' => 'width_pct_50',  'ROLES' => '',  'SIZE' => 16,  
				'EDIT-ROLES' => array (
),  'SEARCH-BY-ONE' => '',  'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', ),

			'auser_id' => array('FGROUP' => 'employment',  'SEARCH' => false,  'SHOW-ADMIN' => true,  'SIZE' => 40,  'UTF8' => false,  'EDIT' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'auser',  'ANSMODULE' => 'ums',  'SHORTNAME' => 'user',  'DEFAUT' => 0,  'STEP' => 1,  'READONLY' => true,  
				'RELATION' => 'OneToOneU',  'SEARCH-BY-ONE' => '',  'DISPLAY' => '',  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),

			'emp_num' => array('FGROUP' => 'employment',  'IMPORTANT' => 'IN',  'SEARCH' => true,  'QSEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 16,  'UTF8' => false,  
				'TYPE' => 'TEXT',  
				'CSS' => 'width_pct_50',  'MB_CSS' => 'width_pct_25',  'READONLY' => true,  'DISABLE-READONLY-ADMIN' => true,  'STEP' => 1,  'SEARCH-BY-ONE' => true,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', ),

			'last_empl_date' => array('FGROUP' => 'employment',  'IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => false,  
				'CSS' => 'width_pct_50',  'MB_CSS' => 'width_pct_25',  'SIZE' => 10,  'FORMAT' => 'CONVERT_NASRANI',  'UTF8' => false,  
				'TYPE' => 'DATE',  'STEP' => 1,  'SEARCH-BY-ONE' => '',  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', ),

			'email' => array('FGROUP' => 'communication',  'IMPORTANT' => 'IN',  'SEARCH' => true,  'QSEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => false,  'SIZE' => 64,  
				'CSS' => 'width_pct_50',  'MB_CSS' => 'width_pct_50',  'FORMAT' => 'EMAIL',  'UTF8' => false,  
				'TYPE' => 'TEXT',  'REQUIRED' => true,  'STEP' => 1,  'SEARCH-BY-ONE' => true,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '',  'MANDATORY' => true,  'ERROR-CHECK' => true, ),

			'phone' => array('FGROUP' => 'communication',  'IMPORTANT' => 'IN',  'SEARCH' => false,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 16,  
				'CSS' => 'width_pct_50',  'MB_CSS' => 'width_pct_25',  'UTF8' => false,  
				'TYPE' => 'TEXT',  'STEP' => 1,  'SEARCH-BY-ONE' => '',  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', ),

			'desk' => array('FGROUP' => 'communication',  'IMPORTANT' => 'IN',  'SEARCH' => false,  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => false,  'SIZE' => 16,  
				'CSS' => 'width_pct_50',  'UTF8' => true,  
				'TYPE' => 'TEXT',  'STEP' => 1,  'SEARCH-BY-ONE' => '',  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', ),

			'active' => array('FGROUP' => 'communication',  'SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'DEFAUT' => 'Y',  
				'TYPE' => 'YN',  
				'CSS' => 'width_pct_50',  'SEARCH-BY-ONE' => '',  'DISPLAY' => '',  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', ),

			'job' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'QSEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 200,  'MB_CSS' => 'width_pct_50',  'UTF8' => true,  
				'TYPE' => 'TEXT',  'STEP' => 2,  'SEARCH-BY-ONE' => true,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),

			'jobrole_mfk' => array('IMPORTANT' => 'IN',  'SEARCH' => false,  'SHOW' => true,  'MINIBOX' => true,  'MB_CSS' => 'width_pct_100',  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => false,  'SIZE' => 32,  'LIST_SEPARATOR' => '، ',  
				'TYPE' => 'MFK',  'ANSWER' => 'jobrole',  'ANSMODULE' => 'ums',  
				'WHERE' => "id_domain in (§domain_id§,§id_domain§,§id_domain1§,1) or id_domain in (select id from §DBPREFIX§pag.domain where domain_code like '%_common')", 
				 
				'SEL_OPTIONS' => array (
					'enableFiltering' => true,
					'numberDisplayed' => 3,
					'filterPlaceholder' => 'اكتب كلمة للبحث',
					),  'STEP' => 2,  'SEARCH-BY-ONE' => '',  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),

			'gender_id' => array('IMPORTANT' => 'IN',  'SEARCH' => false,  'SHOW' => true,  'MANDATORY' => true,  'EDIT' => true,  'QEDIT' => false,  'SIZE' => 16,  'UTF8' => false,  
				'CSS' => 'width_pct_25',  
				'TYPE' => 'enum',  'ANSWER' => 'FUNCTION',   'DEFAUT' => 1,  'STEP' => 3,  'SEARCH-BY-ONE' => '',  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '',  'ERROR-CHECK' => true, ),

			'country_id' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 16,  'UTF8' => false,  'RETRIEVE' => false,  'MINIBOX' => true,  
				'CSS' => 'width_pct_25',  
				'TYPE' => 'FK',  'ANSWER' => 'country',  'ANSMODULE' => 'ums',  'DEFAUT' => 183,  'STEP' => 3,  'SEARCH-BY-ONE' => '',  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', ),

			'idn_type_id' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => false,  'SIZE' => 16,  
				'CSS' => 'width_pct_25',  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'idn_type',  'ANSMODULE' => 'ums',  'DEFAUT' => 0,  'STEP' => 3,  'SEARCH-BY-ONE' => '',  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', ),

			'idn' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => false,  'SIZE' => 16,  
				'CSS' => 'width_pct_25',  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false,  
				'TYPE' => 'TEXT',  'STEP' => 3,  'SEARCH-BY-ONE' => '',  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', ),

			'firstname' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'QSEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'MANDATORY' => true,  'QEDIT' => true,  'SIZE' => 32,  
				'CSS' => 'width_pct_25',  'UTF8' => true,  
				'TYPE' => 'TEXT',  'STEP' => 3,  'SEARCH-BY-ONE' => true,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '',  'ERROR-CHECK' => true, ),

			'f_firstname' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'QSEARCH' => true,  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 32,  
				'CSS' => 'width_pct_25',  'UTF8' => true,  
				'TYPE' => 'TEXT',  'STEP' => 3,  'SEARCH-BY-ONE' => true,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', ),

			'g_f_firstname' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'QSEARCH' => true,  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => false,  'SIZE' => 32,  'UTF8' => true,  
				'TYPE' => 'TEXT',  'STEP' => 3,  'SEARCH-BY-ONE' => true,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),

			'lastname' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'QSEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'MANDATORY' => true,  'QEDIT' => true,  'SIZE' => 32,  
				'CSS' => 'width_pct_25',  'UTF8' => true,  
				'TYPE' => 'TEXT',  'STEP' => 3,  'SEARCH-BY-ONE' => true,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '',  'ERROR-CHECK' => true, ),

		'full_name' => array(
				'TYPE' => 'TEXT',  
				'CATEGORY' => 'FORMULA',  'SHOW' => false,  'SEARCH' => true,  'RETRIEVE' => false,  'UTF8' => true,  'EDIT' => false,  
				'FIELD-FORMULA' => "concat(IF(ISNULL(firstname), '', firstname) , ' ' , IF(ISNULL(f_firstname), '', f_firstname) , ' ' , IF(ISNULL(lastname), '', lastname))",  
				'STEP' => 3,  'SEARCH-BY-ONE' => '',  'DISPLAY' => false,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),
		/*
		'id_domain' => array(
				'TYPE' => 'INT', 
				'CATEGORY' => 'SHORTCUT',  'SHORTCUT' => 'id_sh_org.id_domain',  'CAN-BE-SETTED' => false,  'SEARCH-BY-ONE' => '',  'DISPLAY' => '',  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),

		'id_domain1' => array(
				'TYPE' => 'INT',  
				'CATEGORY' => 'SHORTCUT',  'SHORTCUT' => 'id_sh_div.id_domain',  'CAN-BE-SETTED' => false,  'SEARCH-BY-ONE' => '',  'DISPLAY' => '',  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),*/

			'lastname_en' => array(
				'TYPE' => 'TEXT',  'EDIT' => true,  'QEDIT' => true,  
				'CATEGORY' => '',  'SHOW' => false,  'RETRIEVE' => false,  'UTF8' => false,  'SIZE' => 32,  'MANDATORY' => true,  'STEP' => 3,  'SEARCH-BY-ONE' => '',  'DISPLAY' => false,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '',  'ERROR-CHECK' => true, 
				),

			'g_f_firstname_en' => array(
				'TYPE' => 'TEXT',  'EDIT' => true,  'QEDIT' => false,  
				'CATEGORY' => '',  'SHOW' => false,  'RETRIEVE' => false,  'UTF8' => false,  'SIZE' => 32,  'STEP' => 3,  'SEARCH-BY-ONE' => '',  'DISPLAY' => false,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),

			'f_firstname_en' => array(
				'TYPE' => 'TEXT',  'EDIT' => true,  'QEDIT' => false,  
				'CATEGORY' => '',  'SHOW' => false,  'RETRIEVE' => false,  'UTF8' => false,  'SIZE' => 32,  'STEP' => 3,  'SEARCH-BY-ONE' => '',  'DISPLAY' => false,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),

			'firstname_en' => array(
				'TYPE' => 'TEXT',  'EDIT' => true,  'QEDIT' => true,  'SHOW' => false,  'RETRIEVE' => false,  'UTF8' => false,  'SIZE' => 32,  'MANDATORY' => true,  'STEP' => 3,  'SEARCH-BY-ONE' => '',  'DISPLAY' => false,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '',  'ERROR-CHECK' => true, 
				),

		'full_name_en' => array(
				'TYPE' => 'TEXT',  
				'CATEGORY' => 'FORMULA',  'SHOW' => false,  'SEARCH' => true,  'QSEARCH' => true,  'RETRIEVE' => false,  
				'UTF8' => false,  'EDIT' => false,  
				'FIELD-FORMULA' => "concat(IF(ISNULL(firstname_en), '', firstname_en) , ' ' , IF(ISNULL(f_firstname_en), '', f_firstname_en) , ' ' , IF(ISNULL(lastname_en), '', lastname_en))",  
				'STEP' => 3,  'SEARCH-BY-ONE' => true,  'DISPLAY' => false,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),

			'birth_date' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => false,  'SIZE' => 10,  'FORMAT' => 'CONVERT_NASRANI',  
				'CSS' => 'width_pct_25',  'UTF8' => false,  
				'TYPE' => 'DATE',  'STEP' => 3,  'SEARCH-BY-ONE' => '',  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', ),

			'address' => array('IMPORTANT' => 'IN',  'SEARCH' => false,  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => false,  'SIZE' => 255,  'TITLE_AFTER' => ' المملكة العربية السعودية',  'UTF8' => true,  
				'TYPE' => 'TEXT',  'STEP' => 4,  'SEARCH-BY-ONE' => '',  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),

			'city_id' => array('IMPORTANT' => 'IN',  'SEARCH' => false,  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => false,  'SIZE' => 40,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'city',  'ANSMODULE' => 'ums',  
				'CSS' => 'width_pct_50',  'DEFAUT' => 1,  'STEP' => 4,  'SEARCH-BY-ONE' => '',  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', ),

			'mobile' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => false,  'MINIBOX' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 16,  
				'CSS' => 'width_pct_50',  'MB_CSS' => 'width_pct_25',  'FORMAT' => 'SA-MOBILE',  'UTF8' => false,  
				'TYPE' => 'TEXT',  'STEP' => 4,  'SEARCH-BY-ONE' => '',  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', ),

			'em_name' => array('IMPORTANT' => 'IN',  'SEARCH' => false,  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => false,  'SIZE' => 64,  'UTF8' => true,  
				'TYPE' => 'TEXT',  'STEP' => 4,  'SEARCH-BY-ONE' => '',  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),

			'em_relship_id' => array('IMPORTANT' => 'IN',  'SEARCH' => false,  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => false,  'SIZE' => 40,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'relation_ship',  'ANSMODULE' => 'hrm',  'DEFAUT' => 0,  'STEP' => 4,  'SEARCH-BY-ONE' => '',  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				),

			'em_mobile' => array('IMPORTANT' => 'IN',  'SEARCH' => false,  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => false,  'SIZE' => 16,  'UTF8' => false,  
				'TYPE' => 'TEXT',  'STEP' => 4,  'SEARCH-BY-ONE' => '',  'DISPLAY' => true,  
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
                                                                'TYPE' => 'FK', 'ANSWER' => 'scenario_item', 'ANSMODULE' => 'ums', 'FGROUP' => 'tech_fields'),

                        'tech_notes' 	                => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'TYPE' => 'TEXT', 'CATEGORY' => 'FORMULA', "SHOW-ADMIN" => true, 
                                                                'TOKEN_SEP'=>"§", 'READONLY'=>true, "NO-ERROR-CHECK"=>true, 'FGROUP' => 'tech_fields'),
                ); 
        } 
?>