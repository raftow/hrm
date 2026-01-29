<?php
// ------------------------------------------------------------------------------------
// alter table ".$server_db_prefix."hrm.employee add   domain_id int(11) DEFAULT NULL  after id_sh_div;

// 16/3/2022, rafik does this data fix :
// delete from employee where auser_id is null;
// update employee set username = (select username from ".$server_db_prefix."ums.auser where id = auser_id) where username is null or username = '';
// delete from employee where username is null; -- means that the employee.auser_id is not in auser table otherwise previous would have updated it.
// so that this query :
// mysql> select id_sh_org, username, count(*), max(created_at) from employee where 1 group by id_sh_org, username having  count(*)> 1;
// should return empty result :
// Empty set (0.02 sec)
// Than create this important unique index
// alter table employee add unique index ui_sh_org_username(id_sh_org, username);

// Rafik 2/6/2022
// alter table employee add id_sh_dep int after id_sh_org;
// update employee set id_sh_dep = id_sh_div;
// update employee set id_sh_dep = id_sh_org where id_sh_dep is null;

// INSERT INTO `employee` (`id`, `created_by`, `created_at`, `updated_by`, `updated_at`, `validated_by`, `validated_at`, `active`, `version`, `update_groups_mfk`, `delete_groups_mfk`, `display_groups_mfk`, `sci_id`, `auser_id`, `gender_id`, `firstname`, `f_firstname`, `g_f_firstname`, `lastname`, `firstname_en`, `f_firstname_en`, `g_f_firstname_en`, `lastname_en`, `birth_date`, `country_id`, `address`, `city_id`, `id_sh_org`, `id_sh_dep`, `id_sh_div`, `domain_id`, `username`, `emp_num`, `mobile`, `phone`, `email`, `desk`, `job`, `jobrole_mfk`, `last_empl_date`, `em_name`, `em_relship_id`, `em_mobile`, `id_sh_div2`, `id_sh_div3`, `jobrole_id`, `idn_type_id`, `idn`) VALUES ('2', '1', '2024-12-26 07:54:05.000000', '1', '2024-12-26 07:54:05.000000', NULL, NULL, 'Y', NULL, NULL, NULL, NULL, NULL, '2', '1', 'المهمة', NULL, NULL, 'الآلية', 'Scheduled', NULL, NULL, 'Task', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

// ------------------------------------------------------------------------------------

$file_dir_name = dirname(__FILE__);

// old include of afw.php

class Employee extends AFWObject
{
    public static $HEAD_COMPANY = 3;

    public static $MY_ATABLE_ID = 13453;

    // إحصائيات حول الموظفين
    public static $BF_STATS_EMPLOYEE = 102130;

    // إدارة الموظفين
    public static $BF_QEDIT_EMPLOYEE = 102125;

    // البحث في الموظفين
    public static $BF_SEARCH_EMPLOYEE = 102128;

    // بحث سريع في الموظفين
    public static $BF_QSEARCH_EMPLOYEE = 102129;

    // تحرير موظف
    public static $BF_EDIT_EMPLOYEE = 102124;

    // عرض تفاصيل موظف
    public static $BF_DISPLAY_EMPLOYEE = 102127;

    // مسح موظف
    public static $BF_DELETE_EMPLOYEE = 102126;

    public static $DATABASE = '';

    public static $MODULE = 'hrm';

    public static $TABLE = 'employee';

    public static $DB_STRUCTURE = null;

    public function __construct()
    {
        parent::__construct('employee', 'id', 'hrm');
        HrmEmployeeAfwStructure::initInstance($this);
    }

    public static function loadById($id)
    {
        $obj = new Employee();
        $obj->select_visibilite_horizontale();
        if ($obj->load($id)) {
            return $obj;
        } else
            return null;
    }

    public static function loadByOrgunitAndUsername($id_sh_org, $user_name)
    {
        $obj = new Employee();

        if (!$id_sh_org)
            throw new AfwRuntimeException('loadByOrgunitAndUsername : to create employee id_sh_org is mandatory field');
        if (!$user_name)
            throw new AfwRuntimeException('loadByOrgunitAndUsername : user_name is mandatory field');

        $obj->select('id_sh_org', $id_sh_org);
        $obj->select('username', $user_name);
        if ($obj->load()) {
            return $obj;
        } else
            return null;
    }

    public static function loadAndUpdateFromExternalHRSystem($username, $employee_num)
    {
        $file_dir_name = dirname(__FILE__);
        $company = AfwSession::currentCompany();
        require_once("$file_dir_name/../../client-$company/external_hrm_employee.php");
        if (AfwStringHelper::stringContain($username, '@')) {
            // it means it is not employee of global company or its branchs
            // it is a guest employee
            $resEmployee['company_id'] = Orgunit::$ID_DEFAULT_EXTERNAL_ORG;
            $resEmployee['email'] = $username;
            $resEmployee['guest'] = true;
            $ok = true;
        } else {
            if (AfwSession::config('user_name_is_lower_case', true)) {
                $username = strtolower($username);
            }
            list($ok, $error, $resEmployee) = ExternalHrmEmployee::loadJsonFromExternalHRSystem($username, $employee_num);
        }

        // die("resEmployee : ".var_export($resEmployee,true));
        if ($ok and $resEmployee['company_id'] and $resEmployee['email']) {
            $employee = Employee::loadByEmail($resEmployee['company_id'], $resEmployee['email'], $create_obj_if_not_found = true);

            if (!$resEmployee['guest'])
                $employee->updateMeFromJson($resEmployee);
        } else {
            $resEmployee['error'] = $error;
        }

        return array($employee, 'HR External data : ' . var_export($resEmployee, true));
    }

    private function suggestDivisionId()
    {
        $depObj = $this->hetDepartment();
        if ($depObj) {
            $main_domain_id = AfwSession::config('main_domain_id', 13);
            $divObj = $depObj->getChildInDomain($main_domain_id);
            if ($divObj)
                return $divObj->id;
        }

        return 0;
    }

    private function updateMeFromJson($resEmployee)
    {
        $lang = AfwLanguageHelper::getGlobalLanguage();
        if (!$lang)
            $lang = AfwGlobalVar::variable_get('lang', 'ar');
        $file_dir_name = dirname(__FILE__);
        $nbFields = 0;
        $this->set('id_sh_dep', $resEmployee['department_id']);
        if ((!$this->getVal('id_sh_div')) or $resEmployee['force_divison_id']) {
            if ($resEmployee['suggested_divison_id']) {
                $this->set('id_sh_div', $resEmployee['suggested_divison_id']);
            }

            if (!$this->getVal('id_sh_div')) {
                $this->set('id_sh_div', $this->suggestDivisionId());
            }
        }
        $nbFields++;

        $this->set('job', $resEmployee['jobTitleDesc']);
        $nbFields++;

        $this->set('firstname', $resEmployee['firstName']);
        $nbFields++;

        $this->set('f_firstname', $resEmployee['fatherName']);
        $nbFields++;

        $this->set('g_f_firstname', $resEmployee['grandFatherName']);
        $nbFields++;

        $this->set('lastname', $resEmployee['familyName']);
        $nbFields++;

        $this->set('firstname_en', $resEmployee['firstNameEng']);
        $nbFields++;

        $this->set('f_firstname_en', $resEmployee['fatherNameEng']);
        $nbFields++;

        $this->set('g_f_firstname_en', $resEmployee['grandFatherNameEng']);
        $nbFields++;
        // die("resEmployee = ".var_export($resEmployee,true));
        $this->set('lastname_en', $resEmployee['lastNameEng']);
        $nbFields++;

        $country_external_code = intval($resEmployee['nationalityCode']);
        if ($country_external_code > 0) {
            $countryObj = Country::loadByMainIndex($country_external_code);
            // throw new AfwRuntimeException("countryObj = ".$countryObj);
            if ($countryObj) {
                $this->set('country_id', $countryObj->getId());
                $nbFields++;
            }
        }

        $this->set('phone', $resEmployee['phone']);
        $nbFields++;

        if ($resEmployee['mobile']) {
            $resEmployee['mobile'] = AfwFormatHelper::formatMobile($resEmployee['mobile']);
            $mobile_error = AfwFormatHelper::mobileError($resEmployee['mobile'], $lang);
            if ((!$mobile_error) or (!$this->getVal('mobile'))) {
                $this->set('mobile', $resEmployee['mobile']);
                $nbFields++;
            }
        }

        list($idn_correct, $idn_type_id) = AfwFormatHelper::getIdnTypeId($resEmployee['identityNumber']);

        if ($idn_correct) {
            $this->set('idn_type_id', $idn_type_id);
            $this->set('idn', $resEmployee['identityNumber']);
            $nbFields++;
        }

        $this->set('gender_id', ($resEmployee['genderCode'] == 'M') ? 1 : 2);
        $nbFields++;

        $this->set('birth_date', $resEmployee['birthDate']);
        $nbFields++;

        $this->set('username', $resEmployee['login']);
        $nbFields++;

        $this->set('last_empl_date', $resEmployee['joiningDate']);
        $nbFields++;

        $this->set('emp_num', $resEmployee['employeeNo']);
        $nbFields++;

        $this->commit();

        return $nbFields;
    }

    /**
     * @return Employee
     */
    public static function loadByEmail($id_sh_org, $email, $create_obj_if_not_found = false)
    {
        $obj = new Employee();
        if ($create_obj_if_not_found and (!$id_sh_org))
            throw new AfwRuntimeException('loadByEmail : to create employee id_sh_org is mandatory field');
        if (!$email)
            throw new AfwRuntimeException('loadByEmail : email is mandatory field');
        // $obj->select("id_sh_org",$id_sh_org);
        $obj->select('email', $email);
        if ($obj->load()) {
            if ($create_obj_if_not_found)
                $obj->activate();
            return $obj;
        } elseif ($create_obj_if_not_found) {
            $obj->set('id_sh_org', $id_sh_org);
            $obj->set('email', $email);

            $obj->insertNew();
            $obj->is_new = true;
            return $obj;
        } else
            return null;
    }

    public static function loadByEmployeeNumber($id_sh_org, $emp_num, $create_obj_if_not_found = false)
    {
        $obj = new Employee();
        if (!$id_sh_org)
            throw new AfwRuntimeException('loadByEmployeeNumber : id_sh_org is mandatory field');
        if (!$emp_num)
            throw new AfwRuntimeException('loadByEmployeeNumber : emp_num is mandatory field');

        $obj->select('id_sh_org', $id_sh_org);
        $obj->select('emp_num', $emp_num);

        if ($obj->load()) {
            if ($create_obj_if_not_found)
                $obj->activate();
            return $obj;
        } elseif ($create_obj_if_not_found) {
            $obj->set('id_sh_org', $id_sh_org);
            $obj->set('emp_num', $emp_num);

            $obj->insert();
            $obj->is_new = true;
            return $obj;
        } else
            return null;
    }

    public static function loadByMainIndex($id_sh_org, $idn_type_id, $idn, $create_obj_if_not_found = false)
    {
        $obj = new Employee();
        if ($id_sh_org)
            $obj->select('id_sh_org', $id_sh_org);
        $obj->select('idn_type_id', $idn_type_id);
        $obj->select('idn', $idn);

        if ($obj->load()) {
            if ($create_obj_if_not_found)
                $obj->activate();
            return $obj;
        } elseif ($create_obj_if_not_found) {
            $obj->set('id_sh_org', $id_sh_org);
            $obj->set('idn_type_id', $idn_type_id);
            $obj->set('idn', $idn);

            $obj->insert();
            $obj->is_new = true;
            return $obj;
        } else
            return null;
    }

    public static function loadAllByJobrole($id_sh_org, $jobrole_id, $byId = false)
    {
        $obj = new Employee();
        $obj->select('id_sh_org', $id_sh_org);
        $obj->select('active', 'Y');
        $obj->mfkContain('jobrole_mfk', $jobrole_id);

        $objList = $obj->loadMany();

        if ($byId)
            return $objList;

        $obj_arr = array();

        foreach ($objList as $objItem) {
            $obj_arr[] = $objItem;
        }

        return $obj_arr;
    }

    public function getDisplay($lang = 'ar')
    {
        $data[] = $this->getVal('firstname');
        $data[] = $this->getVal('f_firstname');
        if ($this->getVal('g_f_firstname'))
            $data[] = $this->getVal('g_f_firstname');
        $data[] = $this->getVal('lastname');

        $disp = trim(implode(' ', $data));

        if (!$disp) {
            if ($this->getId() > 0)
                $disp = 'موظف ' . $this->getId();
            else
                $disp = '...';
        }

        return $disp;
    }

    public function beforeMaj($id, $fields_updated)
    {
        self::lookIfInfiniteLoop(1000, 'EmployeeBeforeMaj');
        $lang = AfwLanguageHelper::getGlobalLanguage();

        if (!$this->getVal('username')) {
            $usrname = $this->getUserName();
            $this->set('username', $usrname);
            // echo "$this set username=$usrname;<br>\n";
        }

        if ((!$this->getVal('idn_type_id')) and $this->getVal('idn')) {
            list($idn_correct, $idn_type_id) = AfwFormatHelper::getIdnTypeId($this->getVal('idn'));
            if ($idn_correct)
                $this->set('idn_type_id', $idn_type_id);
        }

        // die("me empl=".var_export($this,true));
        $this->updateMyUserInformation($lang, $from_ldap = false, $commit = false);

        $errs = null;
        return (!$errs);
    }

    public function afterUpdate($id, $fields_updated, $disableAfterCommitDBEvent = false)  // afterInsert
    {
        self::lookIfInfiniteLoop(1000, 'EmployeeAfterUpdate');
        global $auto_generate_employeenum;
        $id_sh_org = $this->getVal('id_sh_org');
        $emp_num = $this->getVal('emp_num');
        if ($emp_num == '--')
            $emp_num = '';

        if ((!$emp_num) and $auto_generate_employeenum) {
            $emp_num = 100000 * $id_sh_org + $this->getId();
            $this->set('emp_num', $emp_num);
            $this->commit();
        }

        if ($fields_updated['mobile']) {
            $usr = Auser::loadByEmail($this->getVal('email'), $create_obj_if_not_found = false);
            if (($usr) and (!$usr->getVal('mobile'))) {
                $usr->set('mobile', $this->getVal('mobile'));
                $usr->commit();
            }
        }
    }

    public function myPrevilegesDescription()
    {
        $usr = Auser::loadByEmail($this->getVal('email'), $create_obj_if_not_found = false);
        if (!$usr) return "no user - no previleges";
        return $usr->showAttribute('mau');
    }


    public function hasRole($module_code, $role_id)
    {
        $usr = Auser::loadByEmail($this->getVal('email'), $create_obj_if_not_found = false);
        if (!$usr) return false;
        return $usr->hasRole($module_code, $role_id);
    }

    public function updateMyInfosFromExternalSources($lang = 'ar')
    {
        if ($this->isFromOurCompany()) {
            $user_name = $this->getVal('username');
            if (AfwSession::config('user_name_is_lower_case', true)) {
                $user_name = strtolower($user_name);
            }

            if (($this->getId()) and $this->getVal('username') or $this->getVal('emp_num')) {
                $company = AfwSession::currentCompany();
                $file_dir_name = dirname(__FILE__);
                require_once("$file_dir_name/../../client-$company/external_hrm_employee.php");

                list($ok, $error, $resEmployee) = ExternalHrmEmployee::loadJsonFromExternalHRSystem($user_name, $this->getVal('emp_num'));

                $info = '';
                $info .= 'resEmployee=' . var_export($resEmployee, true);

                if ($ok and $resEmployee['company_id'] and $resEmployee['email']) {
                    $nbFields = $this->updateMeFromJson($resEmployee);
                    list($error2, $info2) = $this->updateMyUserInformation();

                    $info .= $info2 . ' ' . $nbFields . ' field(s) updated';
                    if ($error2)
                        $error .= $error2;
                } elseif (!$ok) {
                    $this->set('active', 'N');
                    $this->commit();
                    $info = '';
                } else {
                    $info = '';
                }

                return array($error, $info, '');
            } else {
                return array('', '', "This employee has no user name neither employee number, can't execute the retrieve of information from HR");
            }
        } else {
            return array('', '', 'This employee is not from the main company');
        }
    }

    /*
     * public function updateMyInfosFromExternalSources($lang="ar", $commit=true, $force=true)
     * {
     *         global $lang, $auto_generate_employeenum;
     *
     *         $errors_arr = array();
     *         $infos_arr = array();
     *
     *         // Rafik : should be keeped true because employee_num empty cause an exception of doublon for unique index (id_sh_org, emp_num)
     *         $auto_generate_employeenum = true;
     *
     *         if(!$lang) $lang = "ar";
     *         $file_dir_name = dirname(__FILE__);
     *
     *         $myId = $this->getId();
     *
     *         $orgunit = $this->hetOrgunit();
     *         $user_name = $this->getUserName();
     *         $email = $this->getVal("email");
     *
     *         $id_sh_org = $this->getVal("id_sh_org");
     *         $emp_num = $this->getVal("emp_num");
     *         if(!$id_sh_org)
     *         {
     *                 $errors_arr[] = "can't update this employee $myId record without company id";
     *                 $errors = implode("\n<br>",$errors_arr);
     *                 $infos = implode("\n<br>",$infos_arr);
     *                 return array($errors, $infos);
     *         }
     *
     *         if((!$email) and (!$user_name) and (!$emp_num))
     *         {
     *               $errors_arr[] = "to update employee record we need email or username or employee number";
     *               $errors = implode("\n<br>",$errors_arr);
     *               $infos = implode("\n<br>",$infos_arr);
     *               return array($errors, $infos);
     *         }
     *
     *
     *         $userObj = $this->het("auser_id");
     *         if((!$email) and $user_name) $email = $user_name."@".$orgunit->getVal("sh_code");
     *
     *         if((!$userObj) or (!is_object($userObj)) or ($userObj->isEmpty()))
     *         {
     *
     *             $userObj = Auser::loadByEmail($email,$create_obj_if_not_found=true);
     *             if((!$userObj) or (!is_object($userObj)) or ($userObj->isEmpty()))
     *             {
     *                      $err0 = "failed to find/create user object by email=[$email] ";
     *                      //if($objme and $objme->isSuperAdmin()) $err0 .= var_export($userObj,true);
     *                      $errors_arr[] = $err0;
     *                      $errors = implode("\n<br>",$errors_arr);
     *                      $infos = implode("\n<br>",$infos_arr);
     *                      return array($errors, $infos);
     *             }
     *             // if($userObj->is_new) die("new user created : $userObj"); else die("user existing found : $userObj");
     *         }
     *         else
     *         {
     *             //die("user already affected : $userObj");
     *         }
     *
     *
     *         require_once("$file_dir_name/../sdd/sempl.php");
     *
     *
     *         if((!$emp_num) and $auto_generate_employeenum)
     *         {
     *                 $emp_num = 1000000*$id_sh_org+$this->getId();
     *                 $emp_num_auto_generated = true;
     *         }
     *
     *         //die("user_name=$user_name");
     *         $semplObj = null;
     *
     *         if($id_sh_org and $user_name and (!$semplObj))
     *         {
     *                $semplObj = Sempl::loadByUserName($id_sh_org, $user_name);
     *         }
     *
     *         if($id_sh_org and $emp_num and (!$emp_num_auto_generated) and (!$semplObj))
     *         {
     *                $semplObj = Sempl::loadByMainIndex($id_sh_org, $emp_num, $create_obj_if_not_found=true);
     *         }
     *
     *         if(!$semplObj) $semplObj = new Sempl();
     *
     *         $semplObj->set("id_sh_org",$id_sh_org);
     *
     *         if((!$semplObj->getVal("username")) and $user_name)
     *         {
     *              $semplObj->set("username", $user_name);
     *         }
     *
     *         if((!$semplObj->getVal("emp_num")) and $emp_num and (!$emp_num_auto_generated))
     *         {
     *              $semplObj->set("emp_num", $emp_num);
     *         }
     *
     *
     *
     *        if($semplObj)
     *        {
     *              if((!$this->getVal("gender_id")) or $force)
     *              {
     *                  $this->set("gender_id", $semplObj->getVal("gender_id"));
     *              }
     *
     *              if((!$this->getVal("firstname")) or $force)
     *              {
     *                  $changed1 = $this->set("firstname", $semplObj->getVal("firstname"));
     *                  $changed2 = $this->set("f_firstname", $semplObj->getVal("f_firstname"));
     *                  $changed3 = $this->set("lastname", $semplObj->getVal("lastname"));
     *
     *                  $old_name = $semplObj->getDisplay($lang);
     *                  $new_name = $this->getDisplay($lang);
     *
     *                  if($changed1 or $changed2 or $changed3) $infos_arr[] = "تم تعديل اسم الموظف ";
     *                  else $infos_arr[] = " لا يوجد تعديل للاسم الاسم القديم = $old_name ,  الاسم الجديد = $new_name";
     *              }
     *              else
     *              {
     *                  if(!$force)  $infos_arr[] = "لم يتم تعديل اسم الموظف  لأن وضع التعديل الإجباري غير مفعل";
     *              }
     *
     *              if((!$this->getVal("mobile")) or $force)
     *              {
     *                  if($semplObj->getVal("mobile") and ($semplObj->getVal("mobile")!="05"))
     *                  {
     *                     if($this->set("mobile", $semplObj->getVal("mobile")))  $infos_arr[] = "تم تعديل  جوال الموظف ";
     *                  }
     *              }
     *              if((!$this->getVal("email")) or $force)
     *              {
     *                  if($this->set("email", $semplObj->getVal("email")))  $infos_arr[] = "تم تعديل  البريد الالكتروني للموظف ";
     *              }
     *              if((!$this->getVal("city_id")) or $force)
     *              {
     *                  $this->set("city_id", $semplObj->getVal("city_id"));
     *              }
     *              if((!$this->getVal("country_id")) or $force)
     *              {
     *                  $this->set("country_id", $semplObj->getVal("country_id"));
     *              }
     *
     *              if((!$this->getVal("address")) or $force)
     *              {
     *                  $this->set("address", $semplObj->getVal("address"));
     *              }
     *
     *              if((!$this->getVal("job")) or $force)
     *              {
     *                  if($this->set("job", $semplObj->getVal("job"))) $infos_arr[] = "تم تعديل  المسمى الوظيفي للموظف ";
     *              }
     *
     *              if(($semplObj->getVal("emp_num")) or $force)
     *              {
     *                  if($this->set("emp_num", $semplObj->getVal("emp_num"))) $infos_arr[] = "تم تعديل  الرقم الوظيفي للموظف ";
     *              }
     *
     *
     *        }
     *        else
     *        {
     *               $errors_arr[] = "sempl object is not found";
     *        }
     *
     *
     *        if($userObj)
     *        {
     *              $this->set("auser_id", $userObj->getId());
     *
     *              if(!$this->getVal("gender_id"))
     *              {
     *                  $this->set("gender_id", $userObj->getVal("genre_id"));
     *              }
     *
     *              if(!$this->getVal("firstname"))
     *              {
     *                  $this->set("firstname", $userObj->getVal("firstname"));
     *              }
     *              if(!$this->getVal("f_firstname"))
     *              {
     *                  $this->set("f_firstname", $userObj->getVal("f_firstname"));
     *              }
     *              if(!$this->getVal("lastname"))
     *              {
     *                  $this->set("lastname", $userObj->getVal("lastname"));
     *              }
     *              if(!$this->getVal("mobile"))
     *              {
     *                  $this->set("mobile", $userObj->getVal("mobile"));
     *              }
     *              if(!$this->getVal("email"))
     *              {
     *                  $this->set("email", $userObj->getVal("email"));
     *              }
     *              if(!$this->getVal("city_id"))
     *              {
     *                  $this->set("city_id", $userObj->getVal("city_id"));
     *              }
     *              if(!$this->getVal("country_id"))
     *              {
     *                  $this->set("country_id", $userObj->getVal("country_id"));
     *              }
     *              if(!$this->getVal("address"))
     *              {
     *                  $this->set("address", $userObj->getVal("address"));
     *              }
     *
     *
     *
     *              $jobroleList = $this->get("jobrole_mfk");
     *
     *              $arole_arr = array();
     *
     *              foreach($jobroleList as $jobroleObj)
     *              {
     *                     if($jobroleObj and (!$jobroleObj->isEmpty()))
     *                     {
     *                             $jobAroleList = $jobroleObj->get("jobAroleList");
     *                             foreach($jobAroleList as $jobAroleObj)
     *                             {
     *                                 if($jobAroleObj and (!$jobAroleObj->isEmpty()))
     *                                 {
     *                                      $mod_id = $jobAroleObj->getVal("module_id");
     *                                      if(!$mod_id) throw new AfwRuntimeException("job arole $jobAroleObj has no module defined");
     *                                      $arole_arr[$jobAroleObj->getVal("module_id")][] = $jobAroleObj->getVal("arole_id");
     *                                 }
     *                             }
     *                     }
     *              }
     *
     *              foreach($arole_arr as $module_id => $aroles)
     *              {
     *                     $userObj->giveMeModule($module_id,$aroles);
     *              }
     *
     *        }
     *        else
     *        {
     *               $errors_arr[] = "user object is not found";
     *        }
     *
     *        if(!$this->getVal("gender_id"))
     *        {
     *             if($orgunit) $this->set("gender_id", $orgunit->getVal("gender_id"));
     *        }
     *
     *        if($commit) $this->commit();
     *
     *        $errors = implode("\n<br>",$errors_arr);
     *        $infos = implode("\n<br>",$infos_arr);
     *        return array($errors, $infos);
     * }
     */

    protected function importRecord($dataRecord, $orgunit_id, $overwrite_data, $options, $lang, $dont_check_error)
    {
        $errors = [];

        foreach ($dataRecord as $key => $val)
            $$key = $val;
        if (!$idn) {
            $errors[] = $this->translateMessage('missed idn value', $lang);
            return array(null, $errors, [], []);
        }

        /*
         * if(!$mobile)
         * {
         *      $errors[] = $this->translateMessage("missed mobile value",$lang);
         *      return array(null,$errors,[],[]);
         * }
         */

        // idn and idn type identification
        $idn_type_id = 0;
        $idn_type_ok = false;
        if ($idn_type)
            list($idn_type_ok, $idn_type_id) = AfwStringHelper::parseAttribute($this, 'idn_type_id', $idn_type, $lang, false);
        if (!$idn_type_ok) {
            // find it from idn format
            list($idn_correct, $idn_type_id) = AfwFormatHelper::getIdnTypeId($idn);
        }

        if ($idn_correct) {
            // lookup for the student may be it exists
            $employee = self::loadByMainIndex($orgunit_id, $idn_type_id, $idn, $create_obj_if_not_found = true);
        } elseif ($email) {
            $employee = self::loadByEmail(1, $email, $create_obj_if_not_found = true);
        } elseif ($employee_num) {
            $employee = self::loadByEmployeeNumber(1, $employee_num, $create_obj_if_not_found = true);
        } else {
            $errors[] = $this->translateMessage('incorrect idn format', $lang) . ' : ' . $idn;
            $errors[] = $this->translateMessage('no email', $lang);
            $errors[] = $this->translateMessage('no employee num', $lang);
            return array(null, $errors, [], []);
        }

        // update if new or $overwrite_data
        if ($overwrite_data or $employee->is_new) {
            if ($genre)
                list($val_ok, $val_parsed_or_error) = AfwStringHelper::parseAttribute($employee, 'genre_id', $genre, $lang);
            else
                $val_ok = true;
            if (!$val_ok)
                $errors[] = $val_parsed_or_error;

            if ($nationality)
                list($val_ok, $val_parsed_or_error) = AfwStringHelper::parseAttribute($employee, 'country_id', $nationality, $lang);
            else
                $val_ok = true;
            if (!$val_ok)
                $errors[] = $val_parsed_or_error;

            if ($firstname)
                list($val_ok, $val_parsed_or_error) = AfwStringHelper::parseAttribute($employee, 'firstname', $firstname, $lang);
            else
                $val_ok = true;
            if (!$val_ok)
                $errors[] = $val_parsed_or_error;

            if ($fatherfirstname)
                list($val_ok, $val_parsed_or_error) = AfwStringHelper::parseAttribute($employee, 'f_firstname', $fatherfirstname, $lang);
            else
                $val_ok = true;
            if (!$val_ok)
                $errors[] = $val_parsed_or_error;

            if ($grandfathername)
                list($val_ok, $val_parsed_or_error) = AfwStringHelper::parseAttribute($employee, 'g_f_firstname', $grandfathername, $lang);
            else
                $val_ok = true;
            if (!$val_ok)
                $errors[] = $val_parsed_or_error;

            if ($lastname)
                list($val_ok, $val_parsed_or_error) = AfwStringHelper::parseAttribute($employee, 'lastname', $lastname, $lang);
            else
                $val_ok = true;
            if (!$val_ok)
                $errors[] = $val_parsed_or_error;

            if ($jobname)
                list($val_ok, $val_parsed_or_error) = AfwStringHelper::parseAttribute($employee, 'job', $jobname, $lang);
            else
                $val_ok = true;
            if (!$val_ok)
                $errors[] = $val_parsed_or_error;

            if ($mobile)
                list($val_ok, $val_parsed_or_error) = AfwStringHelper::parseAttribute($employee, 'mobile', $mobile, $lang);
            else
                $val_ok = true;
            if (!$val_ok)
                $errors[] = $val_parsed_or_error;

            if ($email)
                list($val_ok, $val_parsed_or_error) = AfwStringHelper::parseAttribute($employee, 'email', $email, $lang);
            else
                $val_ok = true;
            if (!$val_ok)
                $errors[] = $val_parsed_or_error;

            if ($employee_num)
                list($val_ok, $val_parsed_or_error) = AfwStringHelper::parseAttribute($employee, 'employee_num', $employee_num, $lang);
            else
                $val_ok = true;
            if (!$val_ok)
                $errors[] = $val_parsed_or_error;

            if ($dep_id)
                list($val_ok, $val_parsed_or_error) = AfwStringHelper::parseAttribute($employee, 'dep_id', $dep_id, $lang);
            else
                $val_ok = true;
            if (!$val_ok)
                $errors[] = $val_parsed_or_error;

            if ($dep_name)
                list($val_ok, $val_parsed_or_error) = AfwStringHelper::parseAttribute($employee, 'dep_name', $dep_name, $lang);
            else
                $val_ok = true;
            if (!$val_ok)
                $errors[] = $val_parsed_or_error;

            if (count($errors) == 0) {
                $errors = AfwDataQualityHelper::getDataErrors($employee, $lang);
                // throw new AfwRuntimeException("student->getDataErrors = ".var_export($errors,true));
            }
            if (count($errors) == 0) {
                $employee->commit();
            }
        } else {
            $errors[] = $this->translateMessage('This student already exists and overwrite is not allowed', $lang);
        }
        return array($employee, $errors, [], []);
    }

    protected function namingImportRecord($dataRecord, $lang)
    {
        return $dataRecord['firstname'] . ' ' . $dataRecord['fatherfirstname'] . ' ' . $dataRecord['lastname'];
    }

    protected function getRelatedClassesForImport($options = null)
    {
        $file_dir_name = dirname(__FILE__);

        include("$file_dir_name/module_tables_info.php");
        include("$file_dir_name/../ums/module_tables_info.php");

        $importClassesList = [];

        $importClassesList['Auser'] = ['table_id' => $TABLES_INFO['ums']['auser'], 'file' => "$file_dir_name/../ums/auser.php"];

        return $importClassesList;
    }

    public function getUserName()
    {
        $email = $this->getVal('email');
        list($username, $domain_name) = Auser::emailToUsername($email);

        return $username;
    }

    public function isFromOurCompany()
    {
        $email = $this->getVal('email');
        list($username, $domain_name) = Auser::emailToUsername($email);

        return $domain_name;
    }

    protected function beforeSetAttribute($attribute, $newvalue)
    {
        // if($attribute=="country_id") throw new AfwRuntimeException("country_id updated");
        return true;
    }

    public function beforeDelete($id, $id_replace)
    {
        if (!$id) {
            $id = $this->getId();
            $simul = true;
        } else {
            $simul = false;
        }

        if ($id) {
            $company = AfwSession::currentCompany();
            $file_dir_name = dirname(__FILE__);
            if (file_exists("$file_dir_name/../../client-$company/organization_business.php")) {
                require_once("$file_dir_name/../../client-$company/organization_business.php");
                if (class_exists('OrganizationBusiness')) {
                    list($return, $reason) = OrganizationBusiness::trigger_before_delete_employee($id, $id_replace, $simul);
                    if (!$return) {
                        $this->deleteNotAllowedReason = $reason;
                        return false;
                    }
                }
            }

            if ($id_replace == 0) {
                // FK part of me - not deletable
                // crm.request-المشرف المكلف	supervisor_id  أنا تفاصيل لها-OneToMany

                /*
                 * $obj = new Request();
                 *  $obj->where("supervisor_id = '$id' and active='Y' ");
                 *  $nbRecords = $obj->count();
                 *  // check if there's no record that block the delete operation
                 *  if($nbRecords>0)
                 *  {
                 *      $this->deleteNotAllowedReason = "Used in some Media requests(s) as Supervisor";
                 *      return false;
                 *  }
                 *  // if there's no record that block the delete operation perform the delete of the other records linked with me and deletable
                 *  if(!$simul) $this->execQuery("delete from ${server_db_prefix}crm.request where supervisor_id = '$id' and active='N'");
                 *
                 * // crm.request-المنسق المكلف	employee_id  أنا تفاصيل لها-OneToMany
                 *
                 *  $obj = new Request();
                 *  $obj->where("employee_id = '$id' and active='Y' ");
                 *  $nbRecords = $obj->count();
                 *  // check if there's no record that block the delete operation
                 *  if($nbRecords>0)
                 *  {
                 *      $this->deleteNotAllowedReason = "Used in some Media requests(s) as Account";
                 *      return false;
                 *  }
                 *  // if there's no record that block the delete operation perform the delete of the other records linked with me and deletable
                 *  if(!$simul) $this->execQuery("delete from ${server_db_prefix}crm.request where employee_id = '$id' and active='N'");
                 *
                 * // crm.response-الموظف المكلف بالرد	employee_id  أنا تفاصيل لها-OneToMany
                 *
                 *  $obj = new Response();
                 *  $obj->where("employee_id = '$id' and active='Y' ");
                 *  $nbRecords = $obj->count();
                 *  // check if there's no record that block the delete operation
                 *  if($nbRecords>0)
                 *  {
                 *      $this->deleteNotAllowedReason = "Used in some Media responses(s) as B m employee";
                 *      return false;
                 *  }
                 *  // if there's no record that block the delete operation perform the delete of the other records linked with me and deletable
                 *  if(!$simul) $this->execQuery("delete from ${server_db_prefix}crm.response where employee_id = '$id' and active='N'");
                 */

                $server_db_prefix = AfwSession::config('db_prefix', 'default_db_');  // FK part of me - deletable

                // FK not part of me - replaceable
                // hrm.orgunit-المدير /الرئيس	id_responsible  حقل يفلتر به-ManyToOne
                if (!$simul)
                    $this->execQuery("update ${server_db_prefix}hrm.orgunit set id_responsible='$id_replace' where id_responsible='$id' ");
            } else {
                $server_db_prefix = AfwSession::config('db_prefix', 'default_db_');  // FK on me
                // crm.request-المشرف المكلف	supervisor_id  أنا تفاصيل لها-OneToMany
                if (!$simul)
                    $this->execQuery("update ${server_db_prefix}crm.request set supervisor_id='$id_replace' where supervisor_id='$id' ");

                // crm.request-المنسق المكلف	employee_id  أنا تفاصيل لها-OneToMany
                if (!$simul)
                    $this->execQuery("update ${server_db_prefix}crm.request set employee_id='$id_replace' where employee_id='$id' ");

                // crm.response-الموظف المكلف بالرد	employee_id  أنا تفاصيل لها-OneToMany
                if (!$simul)
                    $this->execQuery("update ${server_db_prefix}crm.response set employee_id='$id_replace' where employee_id='$id' ");

                // hrm.orgunit-المدير /الرئيس	id_responsible  حقل يفلتر به-ManyToOne
                if (!$simul)
                    $this->execQuery("update ${server_db_prefix}hrm.orgunit set id_responsible='$id_replace' where id_responsible='$id' ");
            }
            return true;
        }
    }

    protected function getPublicMethods()
    {
        global $objme;

        $check_employee_from_external_system = AfwSession::config('check_employee_from_external_system', '');
        $pbms = array();
        if ($check_employee_from_external_system) {
            $color = 'red';
            $title_ar = 'تحديث البيانات من الأنظمة الخارجية';

            $pbms['BiHUc5'] = array('METHOD' => 'updateMyInfosFromExternalSources', 'COLOR' => $color, 'LABEL_AR' => $title_ar, 'ADMIN-ONLY' => true);
        }

        $color = 'green';
        $title_ar = 'تحديث بيانات المستخدم';
        $methodName = 'updateMyUserInformation';
        $pbms[AfwStringHelper::hzmEncode($methodName)] = array(
            'METHOD' => $methodName,
            'COLOR' => $color,
            'LABEL_AR' => $title_ar,
            'PUBLIC' => true,
            'BF-ID' => '',
            'HZM-SIZE' => 12,
        );
        return $pbms;
    }

    public function getFormuleResult($attribute, $what = 'value')
    {
        global $me, $URL_RACINE_SITE;

        switch ($attribute) {
            case 'full_name':
                $fn = '';  // trim($this->valPrefixe());
                $fn = trim($fn . ' ' . $this->valFirstname());
                $fn = trim($fn . ' ' . $this->valF_firstname());
                $fn = trim($fn . ' ' . $this->valLastname());

                return $fn;
                break;
        }
    }

    public function addMeThisJobrole($jobrole_id, $commit = true)
    {
        $this->addRemoveInMfk('jobrole_mfk', $ids_to_add_arr = [$jobrole_id], $ids_to_remove_arr = []);
        if ($commit)
            $this->commit();
    }

    public function removeMeThisJobrole($jobrole_id, $commit = true)
    {
        $this->addRemoveInMfk('jobrole_mfk', $ids_to_add_arr = [], $ids_to_remove_arr = [$jobrole_id]);
        if ($commit)
            $this->commit();
    }

    public function myModulesAnRoles()
    {
        $freinds = AfwSession::config('freinds', []);
        $moduleToGiveArr = array();
        $jobroleList = $this->get('jobrole_mfk');

        $debugg = false;

        $journal = [];


        foreach ($jobroleList as $jobroleId => $jobroleObj) {
            if ($jobroleObj and (!$jobroleObj->isEmpty())) {
                $jobAroleList = $jobroleObj->get('jobAroleList');
                if ($debugg) $journal[] = "jobrole $jobroleId has " . count($jobAroleList) . " roles : ";
                $counter = 0;
                foreach ($jobAroleList as $jobAroleId => $jobAroleObj) {
                    $counter++;
                    if ($jobAroleObj and (!$jobAroleObj->isEmpty())) {
                        $module_id = $jobAroleObj->getVal('module_id');
                        $role_id = $jobAroleObj->getVal('arole_id');
                        if ($debugg) $journal[] = "job-arole $jobAroleId not empty";
                        if ($module_id and $role_id) {
                            $moduleToGiveArr[$module_id][] = $role_id;
                            if ($debugg) $journal[] = "To give ++ (module_id=$module_id / role_id=$role_id)";
                        } else {
                            if ($debugg) $journal[] = "Not to give (module_id=$module_id / role_id=$role_id)";
                        }


                        if ($freinds["m$module_id"] and is_array($freinds["m$module_id"])) {
                            if ($debugg) $journal[] = "Module_id=$module_id has freinds :";
                            foreach ($freinds["m$module_id"] as $freind_module => $freindRoleArr) {
                                $freindModuleId = substr($freind_module, 1);
                                if ($debugg) $journal[] = "Module_id=$freindModuleId is freind opening roles :";
                                foreach ($freindRoleArr as $freindRole) {
                                    if ($freindRole == "r$role_id") {
                                        if ($debugg) $journal[] = "freind opened role : $freindRole";
                                        $moduleToGiveArr[$freindModuleId][] = $role_id;
                                    }
                                }
                            }
                        }
                    } else {
                        if ($debugg) $journal[] = "job-arole $jobAroleId is empty";
                    }
                }
            }
        }

        if ($debugg) die("Journal of my modules And roles : " . implode("<br>\n", $journal));
        // rafik 2/1/2026 why hard coded below ??
        // $moduleToGiveArr[1274][] = 340;

        return $moduleToGiveArr;
    }

    public function updateMyUserInformation($lang = 'ar', $from_ldap = '', $commit = true, $force_reset_pwd_for_user = false)
    {
        global $objme, $ldap_use;

        // @todo read from configuration file and remove all this not good practice of global vars
        if (!$from_ldap)
            $from_ldap = $ldap_use;

        $username = $this->getVal('username');
        $email = $this->getVal('email');

        $infos_arr = array();
        $errors_arr = array();

        if ((!$email) and (!$username)) {
            // die("username=$username, email=$email, employee to update =".var_export($this,true));
            return array("can't update user information without email and username", '');
        }

        if (!$email) {
            $orgunit = $this->hetOrgunit();
            if ($orgunit) {
                // the email-domain of a company is the sh_code attrobute
                // for example all employees of a company that have a specific domain like autobiz.fr,
                // the email-domain will be 'autobiz.fr' and the email of all employees will be [username]@autobiz.fr
                // for small startup companies they dont have a proper domain they can user an email-domain ".[company-code]@[known-email-provider-domain]"
                // example :
                // farouq omra company should use  like '.farouq@gmail.com'
                // if an employee mohammed work into farouq company if no email specified the system will give him mohammed.farouq@gmail.com

                $sh_code = $orgunit->getVal('sh_code');
                if ((!AfwStringHelper::stringStartsWith($sh_code, '@')) and (!AfwStringHelper::stringStartsWith($sh_code, '.')))
                    $sh_code = '@' . $sh_code;
                $email = $username . $sh_code;
            } else
                return array("can't update user information without email and without company email-domain defined", '');
        }

        $usr = Auser::loadByEmail($email, $create_obj_if_not_found = true);

        if ((!$usr) or (!is_object($usr)) or ($usr->isEmpty())) {
            throw new AfwRuntimeException('updateMyUserInformation need user object : ' . var_export($usr, true));
        }
        if ($this->getVal('firstname') and $this->getVal('lastname')) {
            $usr->set('firstname', $this->getVal('firstname'));
            $usr->set('f_firstname', $this->getVal('f_firstname'));
            $usr->set('lastname', $this->getVal('lastname'));
        }
        $usr->set('mobile', $this->getVal('mobile'));
        // rafik 18/01/1442
        // this not correct modifying the unique index here is source of sql unique index constraint broken error
        // and not needed
        // $usr->set("email",$this->getVal("email"));
        if ($this->getVal('idn_type_id') and $this->getVal('idn')) {
            $usr->set('idn_type_id', $this->getVal('idn_type_id'));
            $usr->set('idn', $this->getVal('idn'));
        }

        if ($this->getVal('address'))
            $usr->set('address', $this->getVal('address'));
        if ($this->getVal('city_id'))
            $usr->set('city_id', $this->getVal('city_id'));
        if ($this->getVal('mobile'))
            $usr->set('mobile', $this->getVal('mobile'));

        $usr->set('username', $this->getVal('username'));

        $usr->commit();
        if ($usr->is_new or $force_reset_pwd_for_user)
            list($errors_arr[], $infos_arr[]) = $usr->initUser($from_ldap);

        $this->set('auser_id', $usr->getId());
        // update my email if not set before
        $this->set('email', $email);
        if ($commit)
            $this->commit();

        $infos_arr[] = $this->updateMyModulesAnRoles($usr);
        return AfwFormatHelper::pbm_result($errors_arr, $infos_arr);
    }


    public function getMyAuser() {
        return Auser::loadByEmail($this->getVal('email'));
    }

    /**
     * @param Auser $usr
     */

    public function updateMyModulesAnRoles($usr = null)
    {
        if (!$usr) $usr = Auser::loadByEmail($this->getVal('email'));
        $moduleToGiveArr = $this->myModulesAnRoles();
        return $usr->giveMeTheseModulesAnRoles($moduleToGiveArr, $this->id_sh_org);
    }

    public function attributeIsApplicable($attribute)
    {
        global $LANGS_MODULE;

        if (AfwStringHelper::stringEndsWith($attribute, '_en'))
            return $LANGS_MODULE['en'];

        return true;
    }

    public function getFieldGroupInfos($fgroup)
    {
        if ($fgroup == 'employment')
            return array('name' => $fgroup, 'css' => 'pct_50');
        if ($fgroup == 'communication')
            return array('name' => $fgroup, 'css' => 'pct_50');

        return array('name' => $fgroup, 'css' => 'none');
    }

    public function getMyCLStep()
    {
        return 'wizardv1_li';  // return "wizard1";
    }

    public function getWizardStepsClass()
    {
        return 'steps_wizardv1 clearfix';  // return "hzmSteps";
    }

    public function getStepLiContentHtml($step_num, $step_name)
    {
        return "<span class=\"number\">${step_num}.</span> ${step_name}";
    }

    public function getWizardClass()
    {
        return 'wizardv1';
    }

    public function userCanDoOperationOnMe($auser, $operation, $operation_sql)
    {
        if (($operation == 'edit') or ($operation == 'delete') or ($operation == 'insert') or ($operation == 'update')) {
            if ($auser) {
                $employeeUser = $auser->getEmployee();
                if ($employeeUser->getVal('id_sh_org') == $this->getVal('id_sh_org')) {
                    return true;  // the check that user $auser have the BF authorized is checked outside not here (here horizontal editability)
                }
            }

            return false;
        } elseif ($operation == 'display') {
            // @todo : check if here it should be the horizontal visibily check by default (and make it in AFW Class also)
            return true;
        } else {
            // because unkown operation
            return false;
        }
    }

    public function fld_CREATION_USER_ID()
    {
        return 'created_by';
    }

    public function fld_CREATION_DATE()
    {
        return 'created_at';
    }

    public function fld_UPDATE_USER_ID()
    {
        return 'updated_by';
    }

    public function fld_UPDATE_DATE()
    {
        return 'updated_at';
    }

    public function fld_VALIDATION_USER_ID()
    {
        return 'validated_by';
    }

    public function fld_VALIDATION_DATE()
    {
        return 'validated_at';
    }

    public function fld_VERSION()
    {
        return 'version';
    }

    public function fld_ACTIVE()
    {
        return 'active';
    }

    public function beLongsTo($orgunit_id, $recursive = false)
    {
        if (!$orgunit_id)
            return false;
        if (!$recursive)
            return ($this->getVal('id_sh_div') == $orgunit_id);

        if ($this->getVal('id_sh_org') == $orgunit_id)
            return true;

        $company = $this->het('id_sh_org');
        if (!$company) {
            $div = $this->het('id_sh_div');
            if ($div)
                $company = $div->hetParent();
        }
        if (!$company)
            return false;

        return $company->beLongsTo($orgunit_id, true);
    }

    public function shouldBeCalculatedField($attribute)
    {
        if ($attribute == 'id_domain')
            return true;
        if ($attribute == 'id_domain1')
            return true;
        return false;
    }

    public function myShortNameToAttributeName($attribute)
    {
        if ($attribute == 'orgunit')
            return 'id_sh_org';
        if ($attribute == 'department')
            return 'id_sh_dep';
        if ($attribute == 'division')
            return 'id_sh_div';
        if ($attribute == 'domain')
            return 'domain_id';
        if ($attribute == 'user')
            return 'auser_id';

        return $attribute;
    }

    public static function getCustomerEmployee()
    {
        return self::loadById(3);
    }

    public static function getStandardJobEmployee()
    {
        return self::loadById(2);
    }

    public function loadablePropsBy($user)
    {
        // if($user->id > 0)
        return ['firstname', 'lastname', 'firstname_en', 'lastname_en', 'email'];
        return [];
    }

    /*
     * public function calcAll_tasks_nb()
     * {
     *     $tasksClassName = AfwSession::config("tasksClassName","");
     *     if($tasksClassName) return $tasksClassName::countAllTasksFor($this->id);
     *
     *     return "n/a";
     * }
     *
     * public function calcCurr_tasks_nb()
     * {
     *     $tasksClassName = AfwSession::config("tasksClassName","");
     *     if($tasksClassName) return $tasksClassName::countCurrTasksFor($this->id);
     *
     *     return "n/a";
     * }
     */
}
