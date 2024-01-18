<?php
// 3/1/2023
// ALTER TABLE `employee_candidate` CHANGE `level_class_id` `level_class_id` INT(11) NOT NULL DEFAULT '0';
// ALTER TABLE `employee_candidate` CHANGE `customer_id` `customer_id` BIGINT(20) NOT NULL DEFAULT '0';
// ALTER TABLE `employee_candidate` ADD `jobrole_mfk` VARCHAR(64) NULL AFTER `student_created`; 
                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class EmployeeCandidate extends AfwObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "hrm"; 
        public static $TABLE			= ""; 
        public static $DB_STRUCTURE = null; 
        
        public function __construct(){
		parent::__construct("employee_candidate","id","hrm");
                SisEmployeeCandidateAfwStructure::initInstance($this);                
	}
        
        public static function loadByMainIndex($orgunit_id, $campaign_id, $customer_id, $jobrole_mfk, $create_update=false)
        {
           if(!$campaign_id) throw new AfwRuntimeException("loadByMainIndex : campaign_id is mandatory field");
           if(!$orgunit_id) throw new AfwRuntimeException("loadByMainIndex : orgunit_id is mandatory field");
           if(!$customer_id) throw new AfwRuntimeException("loadByMainIndex : customer_id is mandatory field");


           $obj = new EmployeeCandidate();
           $obj->select("campaign_id",$campaign_id);
           $obj->select("orgunit_id",$orgunit_id);
           $obj->select("customer_id",$customer_id);

           if($obj->load())
           {
                if($create_update)
                {
                        /*$obj->set("eval",$eval);                
                        $obj->set("level",$level);
                        $obj->set("capacity",$capacity);
                        $obj->set("moral",$moral);*/
                        $obj->set("jobrole_mfk",$jobrole_mfk);
                        
                        $obj->activate();
                }
                return $obj;
           }
           elseif($create_update)
           {
                $obj->set("orgunit_id",$orgunit_id);
                $obj->set("campaign_id",$campaign_id);
                $obj->set("customer_id",$customer_id);
                /*$obj->set("eval",$eval);                
                $obj->set("level",$level);
                $obj->set("capacity",$capacity);
                $obj->set("moral",$moral);*/
                $obj->set("jobrole_mfk",$jobrole_mfk);
                
                $obj->insertNew();
                if(!$obj->id) return null; // means beforeInsert rejected insert operation
                $obj->is_new = true;
                return $obj;
           }
           else return null;
           
        }
        
        public function getDisplay($lang="ar")
        {
               //list($data,$link) = $this->displayAttribute("campaign_id");
               //list($data2,$link2) = $this->displayAttribute("orgunit_id");
               //list($data3,$link3) = $this->displayAttribute("customer_id");
               return "متقدم  : ".$this->getVal("full_name");
        }

        public function getShortDisplay($lang="ar")
        {
               // list($data3,$link3) = $this->displayAttribute("customer_id");
               return "متقدم  : ".$this->getVal("full_name");
        }
        
        public function list_of_campaign_id()
	{
		$file_dir_name = dirname(__FILE__);
                
                include_once("$file_dir_name/../afw/common_date.php");
                list($hijri_campaign_id,$mm,$dd) = AfwDateHelper::currentHijriDate("hlist");
                $hijri_campaign_id = intval($hijri_campaign_id);
                
                $arr_list_of_campaign_id = array();
                
                $hijri_campaign_id_m_1 = $hijri_campaign_id-1;
                $hijri_campaign_id_p_1 = $hijri_campaign_id+1;
                $hijri_campaign_id_p_2 = $hijri_campaign_id+2;
                
                $arr_list_of_campaign_id[$hijri_campaign_id_m_1] = "$hijri_campaign_id_m_1-$hijri_campaign_id";
                $arr_list_of_campaign_id[$hijri_campaign_id] = "$hijri_campaign_id-$hijri_campaign_id_p_1";
                $arr_list_of_campaign_id[$hijri_campaign_id_p_1] = "$hijri_campaign_id_p_1-$hijri_campaign_id_p_2";
                
                return $arr_list_of_campaign_id;
	}

        protected function initObject()
        {
                $this->set("candidate_hdate",AfwDateHelper::currentHijriDate());
                return true;
        }

        protected function getPublicMethods()
        {
            $pbms = array();
            
            if(true)
            {
                    $pbms["xHff34"] = array("METHOD"=>"repareMe", 
                                             "LABEL_AR"=>"تصحيح البيانات", 
                                             "LABEL_EN"=>"fix My Data",
                                             "BF-ID"=>"" 
                                             ); // 
            }
            
            return $pbms;  
        }


        public function repareMe($lang="ar", $fields_updated=[], $commit=true)
        {
                $obj = $this->hetStudent();
                if($obj)
                {
                        if(!$this->getVal("level")) $this->set("level", $obj->getVal("level"));
                        if(!$this->getVal("eval")) $this->set("eval", $obj->getVal("eval"));
                        $this->set("birth_date_en", $obj->getVal("birth_date_en"));
                        
                        $full_name = $obj->getDisplay("ar");
                        // die("full_name of $customer_id = ".var_export($full_name,true));
                        $this->set("full_name",$full_name);
                }

                $syObj = $this->getSchoolYearObject();
                if($syObj)
                {
                        $start_date_en = AfwDateHelper::hijriToGreg($syObj->getVal("orgunit_campaign_id_start_hdate"));
                        $this->set("start_date_en",$start_date_en); 
                }

                if(!$this->getVal("candidate_status_id"))
                {
                        // بسم الله
                        $this->set("candidate_status_id", 1); // pending
                }                
                
                if(!$this->getVal("candidate_hdate"))
                {
                        $this->set("candidate_hdate",AfwDateHelper::currentHijriDate());
                }

                if($commit) $this->commit();
        }
        
        protected function beforeMaj($id, $fields_updated) 
        {
                
                $this->repareMe("ar", $fields_updated, false);
                
                return true;
        }


        public function calcSchool_campaign_id_id()
        {
                return $this->getVal("orgunit_id").$this->getVal("campaign_id")."00";
        }

        public function getSchoolYearObject()
        {
                return SchoolYear::loadByMainIndex($this->getVal("orgunit_id"),$this->getVal("campaign_id"));
        }

        public function list_of_level() {
                return Student::list_of_level();
        }

        public function list_of_eval() {
                return Student::list_of_eval();
        }

        public function list_of_moral() {
                $list_of_items = array();
                if(AfwSession::config("moral_poor",false))
                {
                        $list_of_items[1] = "ضعيف جدا";
                        $list_of_items[2] = "ضعيف";
                }
                $list_of_items[3] = "مقبول";
                $list_of_items[4] = "جيد";
                $list_of_items[5] = "ممتاز";

                return  $list_of_items;
        }

        public function list_of_capacity() {
                $list_of_items = array();
                $list_of_items[1] = "مقبول";
                $list_of_items[2] = "ضعيف";
                $list_of_items[3] = "مقبول";
                $list_of_items[4] = "جيد";
                $list_of_items[5] = "ممتاز";

                return  $list_of_items;
        }

        public function list_of_presence() {
                $list_of_items = array();
                $list_of_items[1] = "حضوري";
                $list_of_items[2] = "عن بعد";

                return  $list_of_items;
        }

        public function calcAge()
        {
                $gdob = $this->getVal("birth_date_en");
                $start_date = $this->getVal("start_date_en"); //date("Y-m-d");
                $diff = diff_date($start_date,$gdob);
                $age = round(($diff/354.0)*2)/2;
                return $age; // "$age = round of $diff = diff_date($today,$gdob)";
        }

        public function calcGeneralEvaluation($lang = 'ar', $objSchool=null)
        {
                
                if(!$objSchool) $objSchool = $this->hetSchool();
                if(!$objSchool) return ["No orgunit defined for this candidate",""];

                $err_arr = [];
                $inf_arr = [];

                $age_coef = $objSchool->getVal("age_coef");
                $eval_coef = $objSchool->getVal("eval_coef");
                $moral_coef = $objSchool->getVal("moral_coef");
                $capacity_coef = $objSchool->getVal("capacity_coef");

                $general_max = $objSchool->getVal("general_max");
                if(!$general_max) $general_max = 100;

                $inf_arr[] = "General evaluation of ".$this->getDisplay($lang)." with general_max = $general_max";

                $ageV = $this->ageValue($general_max);
                $evalV = $this->evalValue($general_max);
                $moralV = $this->moralValue($general_max);
                $capacityV = $this->capacityValue($general_max);

                $general = round(($age_coef / 100)*$ageV + ($eval_coef / 100)*$evalV + ($moral_coef / 100)*$moralV + ($capacity_coef / 100)*$capacityV);
                $inf_arr[] = "general = round(($age_coef / 100)*$ageV + ($eval_coef / 100)*$evalV + ($moral_coef / 100)*$moralV + ($capacity_coef / 100)*$capacityV) = $general";
                $this->set("general", $general);


                $age_distrib = $objSchool->getVal("age_distrib");
                $eval_distrib = $objSchool->getVal("eval_distrib");
                $moral_distrib = $objSchool->getVal("moral_distrib");
                $capacity_distrib = $objSchool->getVal("capacity_distrib");

                $distrib_max = 100;

                $inf_arr[] = "Distrib evaluation of ".$this->getDisplay($lang)." with distrib_max = $distrib_max";

                $ageV = $this->ageValue($distrib_max);
                $evalV = $this->evalValue($distrib_max);
                $moralV = $this->moralValue($distrib_max);
                $capacityV = $this->capacityValue($distrib_max);

                $distrib = round(($age_distrib / 100)*$ageV + ($eval_distrib / 100)*$evalV + ($moral_distrib / 100)*$moralV + ($capacity_distrib / 100)*$capacityV);
                $inf_arr[] = "distrib = round(($age_distrib / 100)*$ageV + ($eval_distrib / 100)*$evalV + ($moral_distrib / 100)*$moralV + ($capacity_distrib / 100)*$capacityV) = $distrib";

                $this->set("distrib", $distrib);
                $inf_arr[] = "---------------------------------------------------------------------------------";
                
                $this->commit();

                return self::pbm_result($err_arr,$inf_arr);
        }


        public function capacityValue($max=100)
        {
                return round($this->getVal("capacity") * $max / 5);
        }

        public function moralValue($max=100)
        {
                return round($this->getVal("moral")  * $max / 5);
        }

        public function evalValue($max=100)
        {
                $max_eval_hrm = AfwSession::config("max_eval_hrm",30);
                return round($this->getVal("eval") * $max / $max_eval_hrm);
        }


        public function ageValue($max=100)
        {
                $age =  $this->calcAge();
                if($age<4) $age = 4;
                if($age>=54) $age = 54;

                $x = $age - 3;

                return round(self::circularInversed($x)*$max/100);
        }


        /**
         * $x between 1 and 51
         * return $y between 50 and 100
         * 
         */
        public static function circularInversed($x)
        {
                $w = 51- $x;
                $y = 50 + sqrt(2500 - $w*$w);

                return $y;
        }


        public function cancelApplyCondition($lang="ar")
        {
                $this->set("level_class_id",0);
                $this->set("candidate_status_id", 1);
                $this->set("comments","");
                $this->commit();

                return ["",$this->tm("applying conditions canceled on")." : ".$this->getDisplay($lang)];
        }


        public function applyCondition($lang="ar", $objSchool=null)
        {
                if(!$objSchool) $objSchool = $this->hetSchool();
                if(!$objSchool) return [$this->tm("no orgunit defined for the candidate",$lang)." : ".$this->getDisplay($lang),""];
                $tempObj = $objSchool->hetTemplate();
                if(!$tempObj) return [$this->tm("no levels template defined for this orgunit",$lang)." : ".$objSchool->getDisplay($lang),""];
                
                $accepted = 2;
                $reason = "";
                // APPLY GENERAL CONDITIONS
                // apply age condition

                // apply genre condition

                // apply orgunit level conditions
                if($accepted==2)
                {
                        // determine orgunit level and level class                       
                        $general = $this->getVal("general");
                        $levelClassObj = $tempObj->getLevelClassOf($general);
                        if(!$levelClassObj) return [$this->tm("no level class defined for this general evaluation ",$lang)." : ".$general,""];
                        $this->set("level_class_id",$levelClassObj->id);

                        // apply level class conditions
                        $scond = SchoolCondition::loadByMainIndex($objSchool->id,$levelClassObj->id);

                        if($scond)
                        {
                                // age cond
                                $age_min = $scond->getVal("age_min");
                                $age_max = $scond->getVal("age_max");
                                $age = $this->calcAge();
                                if(($age > $age_max) or ($age < $age_min))
                                {
                                        $accepted = 3; // rejected
                                        $reason = $this->tm("age is not in the requested interval",$lang);
                                }

                                // 'level_mfk' cond
                                if($accepted==2)
                                {
                                        $levels_mfk_arr = explode(",",trim($scond->getVal("level_mfk"),","));
                                        $level = $this->getVal("level");
                                        if(!in_array($level,$levels_mfk_arr))
                                        {
                                                $accepted = 3; // rejected
                                                $reason = $this->tm("level is not in the requested list",$lang);
                                        }
                                }

                                // 'eval_mfk' cond
                                if($accepted==2)
                                {
                                        $eval_mfk_arr = explode(",",trim($scond->getVal("eval_mfk"),","));
                                        $eval = $this->getVal("eval");
                                        if(!in_array($eval,$eval_mfk_arr))
                                        {
                                                $accepted = 3; // rejected
                                                $reason = $this->tm("eval is not in the requested list",$lang);
                                        }
                                }
                        }
                }

                $this->set("candidate_status_id",$accepted);
                $this->set("comments",$reason);
                $this->commit();

                return ["",$this->tm("end of applying conditions on")." : ".$this->getDisplay($lang)];
        }

        public function getClassNames()
        {
                global $clNames;
                $sy_id = $this->calcSchool_campaign_id_id();
                if($clNames[$sy_id]) return $clNames[$sy_id];
                $scObj = new SchoolClass();
                $scObj->select("orgunit_campaign_id_id", $sy_id);
                $scObj->select("active","Y");
                $scList = $scObj->loadMany();
                $return = [];
                foreach($scList as $scItem)
                {
                        $return[trim($scItem->getVal("jobrole_mfk"))] = trim($scItem->getVal("jobrole_mfk"));
                }

                $clNames[$sy_id] = $return;

                return $return;

        }

        
}
?>