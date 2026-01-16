<?php
// SQL updates
// rafik 16/3/2022
// alter table orgunit change home_latitude home_latitude double null;
// alter table orgunit change home_longitude home_longitude double null;

$file_dir_name = dirname(__FILE__);


// old include of afw.php

class Orgunit extends AfwMomkenObject
{
    public function __construct()
    {
        parent::__construct("orgunit", "id", "hrm");
        HrmOrgunitAfwStructure::initInstance($this);
    }

    public static $HEAD_COMPANY = 3;

    // college - كلية  
    public static $orgunit_type_college = 9;

    // company - مؤسسة عامة  
    public static $orgunit_type_company = 6;

    // department - إدارة  
    public static $orgunit_type_department = 4;

    // deputy-governor - نائب محافظ  
    public static $orgunit_type_deputy_governor = 14;

    // division - قسم  
    public static $orgunit_type_division = 3;

    // firm - شركة / مؤسسة / فرع  
    public static $orgunit_type_firm = 8;

    // general_department - إدارة عـــامة  
    public static $orgunit_type_general_department = 13;

    // general_reg_department - إدارة عامة منطقة  
    public static $orgunit_type_general_reg_department = 16;

    // institute - معهد صناعي  
    public static $orgunit_type_institute = 10;

    // institute2 - معهد تدريب  
    public static $orgunit_type_institute2 = 15;

    // ministry - وزارة  
    public static $orgunit_type_ministry = 7;

    // organization - هيئة  
    public static $orgunit_type_company_group = 5;

    // أخرى 
    public static $orgunit_type_other = 11;

    // unknown - غير معروف  
    public static $orgunit_type_unknown = 12;

    // ضيوف المؤسسة virtual company to allow register of outside employees
    public static $ID_DEFAULT_EXTERNAL_ORG = 4;


    public static $MODULE            = "hrm";
    public static $TABLE            = "orgunit";
    public static $DB_STRUCTURE = null;

    public function fld_CREATION_USER_ID()
    {
        return  "created_by";
    }

    public function fld_CREATION_DATE()
    {
        return  "created_at";
    }

    public function fld_UPDATE_USER_ID()
    {
        return  "updated_by";
    }

    public function fld_UPDATE_DATE()
    {
        return  "updated_at";
    }

    public function fld_VALIDATION_USER_ID()
    {
        return  "validated_by";
    }

    public function fld_VALIDATION_DATE()
    {
        return  "validated_at";
    }

    public function fld_VERSION()
    {
        return  "version";
    }

    public function fld_ACTIVE()
    {
        return  "active";
    }



    public static function loadById($id)
    {
        $obj = new Orgunit();
        $obj->select_visibilite_horizontale();
        if ($obj->load($id)) {
            return $obj;
        } else return null;
    }

    public static function loadByTitleIndex($id_sh_org, $titre_short, $create_obj_if_not_found = false, $id_domain = 0, $from_attr = null)
    {
        $obj = new Orgunit();
        if (!$titre_short) throw new AfwRuntimeException("loadByTitleIndex : titre_short is mandatory field");

        $obj->select("id_sh_org", $id_sh_org);
        $obj->select("titre_short", $titre_short);

        if ($obj->load()) {
            if ($create_obj_if_not_found) {
                if ($id_domain) $obj->set("id_domain", $id_domain);
                $obj->activate();
            }
            return $obj;
        } elseif ($create_obj_if_not_found) {
            if ($id_domain) $obj->set("id_domain", $id_domain);
            $obj->set("id_sh_org", $id_sh_org);
            $obj->set("titre_short", $titre_short);

            if ($from_attr) $obj->$from_attr = true;

            $obj->insert();
            $obj->is_new = true;
            return $obj;
        } else return null;
    }

    public static function loadByHRMCode($hrm_code, $create_obj_if_not_found = false)
    {
        $obj = new Orgunit();
        if (!$hrm_code) throw new AfwRuntimeException("loadByHRMCode : hrm_code is mandatory field");


        $obj->select("hrm_code", $hrm_code);

        if ($obj->load()) {
            if ($create_obj_if_not_found) $obj->activate();
            return $obj;
        } elseif ($create_obj_if_not_found) {
            $obj->set("hrm_code", $hrm_code);

            $obj->insert();
            $obj->is_new = true;
            return $obj;
        } else return null;
    }


    public static function loadByCRMCode($crm_code, $create_obj_if_not_found = false)
    {
        $obj = new Orgunit();
        if (!$crm_code) throw new AfwRuntimeException("loadByCRMCode : crm_code is mandatory field");


        $obj->select("crm_code", $crm_code);

        if ($obj->load()) {
            if ($create_obj_if_not_found) $obj->activate();
            return $obj;
        } elseif ($create_obj_if_not_found) {
            $obj->set("crm_code", $crm_code);

            $obj->insert();
            $obj->is_new = true;
            return $obj;
        } else return null;
    }

    public function getRAMObjectData()
    {
        $orgTypeObj = $this->getOrgType();
        $lookup_code = $orgTypeObj->getVal("lookup_code");


        $category_id = 1;
        $typeObj = RAMObjectType::loadByMainIndex($lookup_code);
        $type_id = $typeObj->getId();
        $code = $this->getVal("sh_code");
        if (!$code) $code = "orgunit-" . $this->getId();
        $name_ar = $this->getVal("titre_short");
        $name_en = $this->getVal("sh_code");
        $specification = "";

        $childs = array();
        $childs[1] =  $this->get("subOrgList");
        $childs[12] =  $this->get("goalList");

        $domain = $this->hetDomain();
        if ($domain) {
            $childs[13][$domain->getId()] = $domain;
        }

        return array($category_id, $type_id, $code, $name_ar, $name_en, $specification, $childs);
    }


    public static function findOrgunit(
        $id_sh_type,
        $id_sh_org,
        $hrm_crm_code,
        $titre_short,
        $titre,
        $titre_short_en,
        $titre_en,
        $id_domain,
        $create_obj_if_not_found = false,
        $update_obj_if_found = true,
        $hrm_crm = "hrm"
    ) {
        $obj = new Orgunit();
        $obj->select("${hrm_crm}_code", $hrm_crm_code);

        if ($obj->load()) {
            if ($update_obj_if_found) {
                if ($id_sh_org and (!$obj->het("id_sh_org"))) $obj->set("id_sh_org", $id_sh_org);
                if ($id_sh_type) $obj->set("id_sh_type", $id_sh_type);
                $obj->set("titre_short", $titre_short);
                $obj->set("titre", $titre);
                $obj->set("titre_short_en", $titre_short_en);
                $obj->set("titre_en", $titre_en);
                if ($id_domain) $obj->set("id_domain", $id_domain);
                $obj->activate();
            }
            return $obj;
        }

        unset($obj);
        $obj = new Orgunit();

        $arrSelects = [
            "titre_short" => $titre_short,
            "titre" => $titre,
            // "titre_short_en" => $titre_short_en,
            // "titre_en" => $titre_en,
        ];

        $obj->selectOneOfListOfCritirea($arrSelects);

        if ($obj->load()) {
            if ($update_obj_if_found) {
                if ($id_sh_org and (!$obj->het("id_sh_org"))) $obj->set("id_sh_org", $id_sh_org);
                if ($id_sh_type) $obj->set("id_sh_type", $id_sh_type);
                $obj->set("titre", $titre);
                if ($id_domain) $obj->set("id_domain", $id_domain);
                $obj->set("${hrm_crm}_code", $hrm_crm_code);

                $obj->activate();
            }
            return $obj;
        } elseif ($create_obj_if_not_found) {
            $obj->set("id_sh_org", $id_sh_org);
            $obj->set("id_sh_type", $id_sh_type);
            $obj->set("titre_short", $titre_short);
            $obj->set("titre", $titre);
            $obj->set("titre_short_en", $titre_short_en);
            $obj->set("titre_en", $titre_en);
            $obj->set("id_domain", $id_domain);
            $obj->set("${hrm_crm}_code", $hrm_crm_code);

            $obj->insert();
            $obj->is_new = true;
            return $obj;
        } else return null;
    }

    public function getShortDisplay($lang = "ar")
    {
        return $this->valTitle();
    }

    public function getDisplay($lang = "ar")
    {
        return $this->valTitle();
    }

    public function getWidDisplay($lang = "ar")
    {
        return $this->getId() . "-" . $this->valTitre();
    }

    public function __toString()
    {
        return $this->valTitre() . " (" . $this->getId() . ")";
    }


    public function getFormuleResult($attribute, $what = 'value')
    {
        global $me, $URL_RACINE_SITE;

        $file_dir_name = dirname(__FILE__);

        switch ($attribute) {
            /*case "nomcomplet" :
                        $fn = trim($this->valPrefixe());
                        $fn = trim($fn." " . $this->valFirstname());
                        $fn = trim($fn." " . $this->valF_firstname());
                        $fn = trim($fn." " . $this->valLastname());
                        
			return $fn;
		    break;
                    case "age" :
                        $hdob = $this->getVal("birth_date");
                        $age = "";
                        if($hdob)
                        {
                                $gdob = AfwDateHelper::hijriToGreg($hdob);
                                $today = date("Y-m-d");
                                $diff = diff_date($today,$gdob);
                                $age = round(($diff/354.0)*10)/10;
                        }
                        return $age; 
                    break; 
                    
                    case "distance2sh" :
                        $dist = "";
                        if($this->getVal("id_sh_org")>0)
                        {
                                $lat2 = $this->getOrg()->getVal("home_latitude");
                                $lon2 = $this->getOrg()->getVal("home_longitude");
                                
                                $lat1 = $this->getVal("home_latitude");
                                $lon1 = $this->getVal("home_longitude");
                                if(($lat1 or $lon1) and ($lat2 or $lon2))
                                {
                                        require_once("common_gps.php");
                                        $dist = distance($lat1, $lon1, $lat2, $lon2); 
                                        $dist = round($dist*10)/10;
                                }
                        }
                        return $dist; 
                    break;  */

            case "map":
                $lat1 = $this->getVal("home_latitude");
                $lon1 = $this->getVal("home_longitude");
                $url = "";
                if ($lat1 or $lon1) {

                    $map_url = map($lat1, $lon1);
                    $url = "<a target='_myMap' href='" . $map_url . "'>انقر هنا</a>";
                }

                return $url;
                break;

            case "route":
                $objme = AfwSession::getUserConnected();
                $url = "";
                if (is_a($objme, 'Auser')) {


                    $lat1 = $objme->getOrg()->getVal("home_latitude");
                    $lon1 = $objme->getOrg()->getVal("home_longitude");

                    $lat2 = $this->getVal("home_latitude");
                    $lon2 = $this->getVal("home_longitude");
                    if (($lat1 or $lon1) and ($lat2 or $lon2)) {

                        $map_url = route($lat1, $lon1, $lat2, $lon2);
                        $url = "<a target=\"_myMap\" href=\"" . $map_url . "\">انقر هنا</a>";
                    }
                }

                return $url;
                break;
        }
    }

    protected function getPublicMethods()
    {
        $arrPublicMethods = array();

        if (in_array($this->getVal("id_sh_type"), array(5, 6, 7))) {
            $arrPublicMethods["xY9cvb"] = array(
                "METHOD" => "genereSubDepartments",
                "LABEL_AR" => "انشاء الإدارات الداخلية",
                "LABEL_EN" => "genere organization sub departments",
                "ADMIN-ONLY" => "true",
                "STEP" => 2
            );
        }


        if ($this->getVal("id_sh_type") >= 6) {
            $arrPublicMethods["xsdf12"] = array(
                "METHOD" => "loadSubDepartmentsFromApi",
                "LABEL_AR" => "تحميل الإدارات الداخلية من خلال  نظام الموارد البشرية",
                "LABEL_EN" => "genere organization sub departments",
                "ADMIN-ONLY" => "true",
                "STEP" => 2
            );
        }

        return $arrPublicMethods;
    }
    /* rafik : Api is not developed just simulation with php text file
        public function loadSubDepartmentsFromApi($lang="ar", $regen=false)
        {
              $id_org = $this->getId();
              $file_dir_name = dirname(__FILE__);
              
              require("$file_dir_name/list_deps_arr_$id_org.php");
              
              $error = ""; 
              $info = "";
              
              $nb_org = 0;
              $nb_empl = 0;
              $nb_assg = 0;
              $nb = count($arr_all_deps);
              
              foreach($arr_all_deps as $dep_row)
              {
                  $hrm_code = $dep_row["id"];
                  $nartaqi_code = $dep_row["nid"];
                  
                  $hrm_name = $dep_row["depname_hrm"];
                  $nartaqi_name = $dep_row["depname"];
                  
                  $manager_emp_num = $dep_row["manager_emp_num"];
                  
                  $depOrg = Orgunit::loadByHRMCode($hrm_code,$create_obj_if_not_found=true);
                  if($depOrg->is_new) 
                  {
                      $info .= "تم إضافة الإدارة : $hrm_name <br>\n";
                      $nb_org++;
                  }
                  $depOrg->set("crm_code",$nartaqi_code);
                  $depOrg->set("titre_short",$hrm_name);
                  $depOrg->set("titre",$nartaqi_name);
                  $depOrg->set("id_sh_type",self::$orgunit_type_department);
                  $depOrg->set("id_sh_org",$id_org);
                  $depOrg->set("id_domain",4);
                  
                  require_once("$file_dir_name/employee.php");
                  
                  $empl = Employee::loadByEmployeeNumber($id_org, $manager_emp_num, $create_obj_if_not_found=true);
                  list($err_msg,$info_msg) = $empl->updateMyInfosFromExternalSources($lang, $commit=true, $force=true);
                  if($info_msg) $info .= $info_msg."<br>\n";
                  if($err_msg) $error .= $err_msg."<br>\n";
                  
                  if($empl) 
                  {
                      if($empl->is_new)
                      {
                         $info .= "تم إضافة الموظف : $empl <br>\n";
                         $nb_empl++;
                      } 
                      
                      if($depOrg->getVal("id_responsible") != $empl->getId()) 
                      {
                          $info .= "تم تعيين الموظف : $empl كمدير لإدارة $depOrg <br>\n";
                          $nb_assg++;
                      }
                      else
                      {
                          $info .= "الموظف : $empl معين سابقا كمدير لإدارة $depOrg <br>\n";
                      }
                      $depOrg->set("id_responsible",$empl->getId());
                  }
                  $depOrg->commit();
                  
              }
              
              $info .= "بعد معاينة $nb سجل تم تحميلهم فقد تم إضافة $nb_empl موظف وتم تعيين $nb_assg مدير وتم إضافة $nb_org إدارة<br>\n";
              
              return array($error, $info);
        }
        */

    public function genereSubDepartments($lang = "ar", $regen = false)
    {

        // $file_dir_name = dirname(__FILE__); 
        // require_once("$file_dir_name/hday.php");

        $sub_dep_arr = [
            'IT' => "إدارة الموارد البشرية",
            'HR' => "إدارة تقنية المعلومات"
        ];

        // @todo here import SubDepartments depending on domain  

        $create_obj_if_not_found = true;
        foreach ($sub_dep_arr as $sub_dep_code => $sub_dep) {
            $sdepObj = self::findOrgunit(
                self::$orgunit_type_department,
                $this->getId(),
                $sub_dep_code,
                $sub_dep,
                $sub_dep,
                $this->getVal("id_domain"),
                $create_obj_if_not_found,
                $update_obj_if_found = false,
                $hrm_crm = "hrm"
            );

            $sdepObj->set("id_sh_parent", $this->getId());
            $sdepObj->update();
            if ($sdepObj and $sdepObj->is_new) $sub_dep_obj_arr[] = $sdepObj->getDisplay($lang);
        }

        $error = "";
        if (count($sub_dep_obj_arr) > 0)
            $info = " تم إنشاء الادارات التالية : " . implode("، ", $sub_dep_obj_arr);
        else
            $info = "لا يوجد حاجة لإنشاء إدارات جديدة";
        return array($error, $info);
    }


    public function hasSubOrgList()
    {
        return (in_array(
            $this->getVal("id_sh_type"),
            array(
                self::$orgunit_type_department,
                self::$orgunit_type_general_department,
                self::$orgunit_type_general_department,
                self::$orgunit_type_company_group,
                self::$orgunit_type_company,
                self::$orgunit_type_ministry,
                self::$orgunit_type_firm
            )
        ));
    }

    public function isAnIndependentOrganization()
    {
        return (in_array(
            $this->getVal("id_sh_type"),
            array(
                self::$orgunit_type_company,
                self::$orgunit_type_ministry,
                self::$orgunit_type_firm,
                self::$orgunit_type_college,
                self::$orgunit_type_institute,
            )
        ));
    }

    public function isDepartment()
    {
        return (in_array(
            $this->getVal("id_sh_type"),
            array(
                self::$orgunit_type_division,
                self::$orgunit_type_department,
                self::$orgunit_type_general_department,
                self::$orgunit_type_general_reg_department,
            )
        ));
    }


    public function attributeIsApplicable($attribute)
    {
        if ($attribute == "subOrgList") {
            return $this->hasSubOrgList();
        }

        if ($attribute == "allEmployeeList") {
            return $this->isAnIndependentOrganization();
        }

        if ($attribute == "employeeList") {
            return $this->isDepartment();
        }



        return true;
    }


    protected function getOtherLinksArray($mode, $genereLog = false, $step = "all")
    {
        $lang = AfwLanguageHelper::getGlobalLanguage();

        //$objme = AfwSession::getUserConnected();
        $otherLinksArray = $this->getOtherLinksArrayStandard($mode, false, $step);
        $my_id = $this->getId();
        $id_sh_org = $this->getVal("id_sh_org");
        $displ = $this->getDisplay($lang);
        /*
            if($mode=="mode_goalConcernList")
            {
                unset($link);
                $my_id = $this->getId();
                $link = array();
                $title = "إدارة اأهداف";
                $title_detailed = $title ."لـ : ". $displ;
                $link["URL"] = "main.php?Main_Page=afw_mode_qedit.php&cl=GoalConcern&currmod=b au&id_origin=$my_id&class_origin=Orgunit&module_origin=hrm&newo=10&limit=30&ids=all&fixmtit=$title_detailed&fixmdisable=1&fixm=orgunit_id=$my_id&sel_orgunit_id=$my_id";
                $link["TITLE"] = $title;
                $link["UGROUPS"] = array();
                $otherLinksArray[] = $link;
            }*/

        if ($mode == "mode_moduleOrgunitList") {
            unset($link);
            $my_id = $this->getId();
            $link = array();
            $title = "إدارة  الأنظمة المفيدة ";
            $title_detailed = $title . "لـ : " . $displ;
            $link["URL"] = "main.php?Main_Page=afw_mode_qedit.php&cl=ModuleOrgunit&currmod=ums&id_origin=$my_id&class_origin=Orgunit&module_origin=hrm&newo=10&limit=30&ids=all&fixmtit=$title_detailed&fixmdisable=1&fixm=id_orgunit=$my_id&sel_id_orgunit=$my_id";
            $link["TITLE"] = $title;
            $link["UGROUPS"] = array();
            $otherLinksArray[] = $link;
        }

        if ($mode == "mode_employeeList") {
            unset($link);
            $link = array();
            $title = "إضافة موظف جديد للإدارة/القسم";
            $title_detailed = $title . "لـ : " . $displ;
            $link["URL"] = "main.php?Main_Page=afw_mode_edit.php&cl=Employee&currmod=hrm&sel_id_sh_div=$my_id&sel_id_sh_org=$id_sh_org";
            $link["TITLE"] = $title;
            $link["UGROUPS"] = array();
            $otherLinksArray[] = $link;
        }

        if ($mode == "mode_allEmployeeList") {
            unset($link);
            $link = array();
            $title = "إضافة موظف جديد للمنشأة ";
            $title_detailed = $title . "لـ : " . $displ;
            $link["URL"] = "main.php?Main_Page=afw_mode_edit.php&cl=Employee&currmod=hrm&sel_id_sh_org=$my_id";
            $link["TITLE"] = $title;
            $link["UGROUPS"] = array();
            $otherLinksArray[] = $link;
        }

        if ($mode == "mode_subOrgList") {
            unset($link);
            $link = array();
            $title = "إضافة قسم جديد للمنشأة ";
            $title_detailed = $title . "لـ : " . $displ;
            $link["URL"] = "main.php?Main_Page=afw_mode_edit.php&cl=Orgunit&currmod=hrm&sel_id_sh_parent=$my_id";
            $link["TITLE"] = $title;
            $link["UGROUPS"] = array();
            $otherLinksArray[] = $link;
        }



        return $otherLinksArray;
    }

    // edited manually
    public function beforeDelete($id, $id_replace)
    {
        $server_db_prefix = AfwSession::config("db_prefix", "default_db_");

        if (!$id) {
            $id = $this->getId();
            $simul = true;
        } else {
            $simul = false;
        }

        if ($id) {
            $file_dir_name = dirname(__FILE__);
            $company = AfwSession::currentCompany();
            if (file_exists("$file_dir_name/../client-$company/organization_business.php")) {
                require_once("$file_dir_name/../client-$company/organization_business.php");
                list($return, $reason) = OrganizationBusiness::trigger_before_delete_organization($id, $id_replace, $simul);
                if (!$return) {
                    $this->deleteNotAllowedReason = $reason;
                    return false;
                }
            }
            if ($id_replace == 0) {
                // FK part of me - not deletable 
                // ums.module-الجهة المستفيدة	id_main_sh  أنا تفاصيل لها-OneToMany

                $obj = new Module();
                $obj->where("id_main_sh = '$id' and avail='Y' ");
                $nbRecords = $obj->count();
                // check if there's no record that block the delete operation
                if ($nbRecords > 0) {
                    $this->deleteNotAllowedReason = "Used in some Modules(s) as Main sh";
                    return false;
                }
                // if there's no record that block the delete operation perform the delete of the other records linked with me and deletable
                if (!$simul) $this->execQuery("delete from ${server_db_prefix}ums.module where id_main_sh = '$id' and avail='N'");



                // hrm.employee-المؤسسة/الشركة	id_sh_org  أنا تفاصيل لها-OneToMany

                $obj = new Employee();
                $obj->where("id_sh_org = '$id' and active='Y' ");
                $nbRecords = $obj->count();
                // check if there's no record that block the delete operation
                if ($nbRecords > 0) {
                    $this->deleteNotAllowedReason = "Used in some Employees(s) as Sh org";
                    return false;
                }
                // if there's no record that block the delete operation perform the delete of the other records linked with me and deletable
                if (!$simul) $this->execQuery("delete from ${server_db_prefix}hrm.employee where id_sh_org = '$id' and active='N'");

                // hrm.employee-الإدارة/القسم/الفرع	id_sh_div  أنا تفاصيل لها-OneToMany

                $obj = new Employee();
                $obj->where("id_sh_div = '$id' and active='Y' ");
                $nbRecords = $obj->count();
                // check if there's no record that block the delete operation
                if ($nbRecords > 0) {
                    $this->deleteNotAllowedReason = "Used in some Employees(s) as Sh div";
                    return false;
                }
                // if there's no record that block the delete operation perform the delete of the other records linked with me and deletable
                if (!$simul) $this->execQuery("delete from ${server_db_prefix}hrm.employee where id_sh_div = '$id' and active='N'");

                // hrm.orgunit-المؤسسة / الشركة	id_sh_org  أنا تفاصيل لها-OneToMany

                $obj = new Orgunit();
                $obj->where("id_sh_org = '$id' and active='Y' ");
                $nbRecords = $obj->count();
                // check if there's no record that block the delete operation
                if ($nbRecords > 0) {
                    $this->deleteNotAllowedReason = "Used in some Orgunit(s) as parent orgunit";
                    return false;
                }
                // if there's no record that block the delete operation perform the delete of the other records linked with me and deletable
                if (!$simul) $this->execQuery("delete from ${server_db_prefix}hrm.orgunit where id_sh_org = '$id' and active='N'");

                // hrm.orgunit-الوحدة الأم في الهيكل	id_sh_parent  أنا تفاصيل لها-OneToMany

                $obj = new Orgunit();
                $obj->where("id_sh_parent = '$id' and active='Y' ");
                $nbRecords = $obj->count();
                // check if there's no record that block the delete operation
                if ($nbRecords > 0) {
                    $this->deleteNotAllowedReason = "Used in some Orgunits(s) as Sh parent";
                    return false;
                }
                // if there's no record that block the delete operation perform the delete of the other records linked with me and deletable
                if (!$simul) $this->execQuery("delete from ${server_db_prefix}hrm.orgunit where id_sh_parent = '$id' and active='N'");








                $server_db_prefix = AfwSession::config("db_prefix", "default_db_"); // FK part of me - deletable 
                // ums.module_orgunit-الجهة المعنية بالنظام/ التطبيق	id_orgunit  أنا تفاصيل لها-OneToMany
                if (!$simul) $this->execQuery("delete from ${server_db_prefix}ums.module_orgunit where id_orgunit = '$id' ");

                if (class_exists('CrmCustomer', false)) {
                    // crm.crm_customer-جهة العميل	customer_orgunit_id  حقل يفلتر به-ManyToOne
                    if (!$simul) $this->execQuery("update ${server_db_prefix}crm.crm_customer set customer_orgunit_id='$id_replace' where customer_orgunit_id='$id' ");
                }
            } else {
                $server_db_prefix = AfwSession::config("db_prefix", "default_db_"); // FK on me 
                // ums.module-الجهة المستفيدة	id_main_sh  أنا تفاصيل لها-OneToMany
                if (!$simul) $this->execQuery("update ${server_db_prefix}ums.module set id_main_sh='$id_replace' where id_main_sh='$id' ");

                // hrm.employee-المؤسسة/الشركة	id_sh_org  أنا تفاصيل لها-OneToMany
                if (!$simul) $this->execQuery("update ${server_db_prefix}hrm.employee set id_sh_org='$id_replace' where id_sh_org='$id' ");
                // hrm.employee-الإدارة/القسم/الفرع	id_sh_div  أنا تفاصيل لها-OneToMany
                if (!$simul) $this->execQuery("update ${server_db_prefix}hrm.employee set id_sh_div='$id_replace' where id_sh_div='$id' ");
                // hrm.orgunit-المؤسسة / الشركة	id_sh_org  أنا تفاصيل لها-OneToMany
                if (!$simul) $this->execQuery("update ${server_db_prefix}hrm.orgunit set id_sh_org='$id_replace' where id_sh_org='$id' ");
                // hrm.orgunit-الوحدة الأم في الهيكل	id_sh_parent  أنا تفاصيل لها-OneToMany
                if (!$simul) $this->execQuery("update ${server_db_prefix}hrm.orgunit set id_sh_parent='$id_replace' where id_sh_parent='$id' ");

                // ums.module_orgunit-الجهة المعنية بالنظام/ التطبيق	id_orgunit  أنا تفاصيل لها-OneToMany
                if (!$simul) $this->execQuery("update ${server_db_prefix}ums.module_orgunit set id_orgunit='$id_replace' where id_orgunit='$id' ");


                /*    
                $file_dir_name = dirname(__FILE__);
                if (file_exists("$file_dir_name/../client-$company/organization_business.php")) {
                    require_once("$file_dir_name/../client-$company/organization_business.php");
                    $return = HrmOrgunitBusiness::trigger_before_delete_organization($id, $id_replace, $simul);
                    if (!$return) return false;
                }*/
            }
            return true;
        }
    }


    public function getBranchListOfIDs()
    {
        $parent = $this->het("id_sh_parent");
        if ($parent) $parent_branch = $parent->getBranchListOfIDs();
        else $parent_branch = "0";

        return $parent_branch . "," . $this->getId();
    }


    protected function getSpecialIconType()
    {
        if ($this->getVal("id_sh_type") == 9) {
            return "collegue";
        }

        if ($this->getVal("id_sh_type") == 10) {
            return "institute";
        }
        return "orgunit";
    }

    public function getUrl($extended_orgunit_class = "Orgunit", $extended_orgunit_module = "hrm")
    {
        return "main.php?Main_Page=afw_mode_display.php&cl=$extended_orgunit_class&currmod=$extended_orgunit_module&id=" . $this->getId();
    }

    public function getOrgunitMenu($extended_orgunit_class = "Orgunit", $extended_orgunit_module = "hrm")
    {
        global $lang, $MENU_ICONS, $menu_css_arr;

        $colors = array(0 => "red", 1 => "yellow", 2 => "orange", 3 => "green", 4 => "blue", 5 => "nili", 6 => "purpule");

        $menu_folder = array();

        $my_id = $this->getId();
        $menu_folder["id"] = $my_id;
        $menu_folder["menu_name"] = $this->getShortDisplay($lang);
        if ($lang == "ar") $lang_other = "en";
        else $lang_other = "ar";
        if (!trim($menu_folder["menu_name"])) $menu_folder["menu_name"] = "menu.orgunit" . $this->getId();
        $menu_folder["page"] = "main.php?Main_Page=org.php&org=" . $this->getId();
        $menu_folder["css"] = $menu_css_arr[$my_id];
        if (!$menu_folder["css"]) $menu_folder["css"] = "info";
        $menu_folder["icon"] = $MENU_ICONS[$my_id];
        $menu_folder["items"] = array();

        $menu_folder["items"][$my_id] = array();
        $title_lang =  $this->getShortDisplay($lang);
        if (!$title_lang) $title_lang = "orgunit-$my_id-$lang";
        $menu_folder["items"][$my_id]["id"] = $this->getId();
        $menu_folder["items"][$my_id]["menu_name"] = $title_lang;
        $menu_folder["items"][$my_id]["page"] = $this->getUrl($extended_orgunit_class, $extended_orgunit_module);
        $menu_folder["items"][$my_id]["css"] = "bf";
        $menu_folder["items"][$my_id]["color"] = $colors[$my_id % 7];

        $subOrgList = $this->get("subOrgList");
        $subOrgList_count = count($subOrgList);
        //die("Orgunit($my_id)->getOrgunitMenu() ->get(subOrgList) => (count=$subOrgList_count)".var_export($subOrgList,true));
        foreach ($subOrgList as $org_item) {
            if ($org_item and (is_object($org_item)) and $org_item->isActive()) {
                $menu_folder["items"][$org_item->getId()] = array();
                $title_lang =  $org_item->getShortDisplay($lang);
                $org_item_id =  $org_item->getId();
                if (!$title_lang) $title_lang = "orgunit-$org_item_id-$lang";
                $menu_folder["items"][$org_item->getId()]["id"] = $org_item->getId();
                $menu_folder["items"][$org_item->getId()]["menu_name"] = $title_lang;
                $menu_folder["items"][$org_item->getId()]["page"] = $org_item->getUrl($extended_orgunit_class, $extended_orgunit_module);
                $menu_folder["items"][$org_item->getId()]["css"] = "bf";
                $menu_folder["items"][$org_item->getId()]["icon"] = "sub-org";
                $menu_folder["items"][$org_item->getId()]["color"] = $colors[$org_item_id % 7];
            } else {
                /*
                      if($this->getId()==80)
                      {
                          throw new AfwRuntimeException("bf_item = ".var_export($bf_item,true));
                      }*/
            }
        }

        /*
              $menu_folder["sub-folders"] = array();
              $this_folders = $this->get("childList");
              
              foreach($this_folders as $folder_item)
              {
                 if($folder_item and (is_object($folder_item)) and $folder_item->isActive())
                 {
                     //$menu_folder["folders"][$folder_item->getId()] = array();
                     //$title_lang =  $folder_item->getDisplay($lang);
                     //$folder_item_id =  $folder_item->getId();
                     //if(!$title_lang) $title_lang = "folder-role-$folder_item_id-$lang";
                     //$menu_folder["folders"][$folder_item->getId()]["title"] = $title_lang;
                     //$menu_folder["folders"][$folder_item->getId()]["page"] = $folder_item->getFolderUrl();
                     //$menu_folder["folders"][$folder_item->getId()]["css"] = "role";
                     $menu_folder["sub-folders"][$folder_item->getId()] = $folder_item->getRoleMenu();
                 }
                     
              }
              */
        return $menu_folder;
    }

    public function beforeMaj($id, $fields_updated)    // 
    {
        if (!$this->hrm_code) {
            $this->hrm_code = substr(AfwStringHelper::hzmArabicToLatinRepresentation($this->title), 0, 5) . "-" . round(rand(10001, 99999));
        }

        $parent = $this->hetParent();
        if ($parent) {
            //if(!$this->getVal("id_domain")) $this->set("id_domain", $parent->getVal("id_domain"));
        }

        //$this->afterInsert($id, $fields_updated);

        return true;
    }

    public function afterInsert($id, $fields_updated, $disableAfterCommitDBEvent = false)    // 
    {
        $file_dir_name = dirname(__FILE__);
        $company = AfwSession::currentCompany();
        if (file_exists("$file_dir_name/../client-$company/organization_business.php") and $this->getVal("titre_short")) {
            require_once("$file_dir_name/../client-$company/organization_business.php");
            OrganizationBusiness::trigger_new_organization($this);
        }
    }


    public function beLongsTo($orgunit_id, $recursive = true, $belongsToMySelf = true)
    {
        if (!$orgunit_id) return false;
        if (!$recursive) return ($this->getVal("id_sh_parent") == $orgunit_id);

        if ($belongsToMySelf and ($this->id == $orgunit_id)) return true;

        if ($this->getVal("id_sh_org") == $orgunit_id) return true;

        $parent = $this->het("id_sh_parent");

        if (!$parent) return false;

        return $parent->beLongsTo($orgunit_id, $recursive, $belongsToMySelf);
    }

    public function instanciated($numInstance)
    {
        global $MODE_BATCH_LOURD;
        if (($numInstance > 1400) and (!$MODE_BATCH_LOURD)) {
            AfwRunHelper::lightSafeDie("orgunit trop dinstances $numInstance", AfwCacheSystem::getSingleton());
        }
        return true;
    }

    public function getChildInDomain($id_domain)
    {
        if (!$this->id) return null;
        $obj = new Orgunit();
        $obj->select("id_sh_parent", $this->id);
        $obj->select("id_domain", $id_domain);
        $obj->load();

        if ($obj->id) return $obj;
        else return null;
    }

    public function myShortNameToAttributeName($attribute)
    {
        if ($attribute == "orgtype") return "id_sh_type";
        if ($attribute == "title") return "titre_short";
        if ($attribute == "domain") return "id_domain";
        if ($attribute == "parent") return "id_sh_parent";
        if ($attribute == "resp") return "id_responsible";
        return $attribute;
    }
}
