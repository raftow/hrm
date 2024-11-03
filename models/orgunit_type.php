<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table orgunit_type : orgunit_type - أنواع عنصر الهيكل التنظيمي 
// ------------------------------------------------------------------------------------
 
 
$file_dir_name = dirname(__FILE__); 
 
// old include of afw.php
 
class OrgunitType extends AFWObject{
 
        public static $MY_ATABLE_ID=13685; 
        // إدارة أنواع عنصر الهيكل التنظيمي 
        public static $BF_QEDIT_ORGUNIT_TYPE = 103192; 
        // عرض تفاصيل نوع عنصر الهيكل التنظيمي 
        public static $BF_DISPLAY_ORGUNIT_TYPE = 103194; 
        // مسح نوع عنصر الهيكل التنظيمي 
        public static $BF_DELETE_ORGUNIT_TYPE = 103193; 
 
 
 // lookup Value List codes 
 
        // DIVISION - قسم  
        public static $ORGUNIT_TYPE_DIVISION = 3; 

        // DEPARTMENT - إدارة  
        public static $ORGUNIT_TYPE_DEPARTMENT = 4; 
 
        // ORGANIZATION - هيئة  
        public static $ORGUNIT_TYPE_ORGANIZATION = 5;  

        // COMPANY - مؤسسة  
        public static $ORGUNIT_TYPE_COMPANY = 6; 

        // MINISTRY - وزارة  
        public static $ORGUNIT_TYPE_MINISTRY = 7; 
 
        // FIRM - شركة / فرع شركة  
        public static $ORGUNIT_TYPE_FIRM = 8; 
 
        // COLLEGE - كلية  
        public static $ORGUNIT_TYPE_COLLEGE = 9; 

        // INSTITUTE - معهد  
        public static $ORGUNIT_TYPE_INSTITUTE = 10; 
 

 
	public static $DATABASE		= ""; 
        public static $MODULE		    = "hrm"; 
        public static $TABLE			= "orgunit_type"; 
        public static $DB_STRUCTURE = null; 
        
        public function __construct(){
		parent::__construct("orgunit_type","id","hrm");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "titre_short";
                $this->ORDER_BY_FIELDS = "id";
                $this->IS_LOOKUP = true; 
                $this->ignore_insert_doublon = true;
                $this->UNIQUE_KEY = array('lookup_code');
                $this->ENABLE_DISPLAY_MODE_IN_QEDIT = true;
                $this->showQeditErrors = true;
	}
 
        public static function loadById($id)
        {
           $obj = new OrgunitType();
           $obj->select_visibilite_horizontale();
           if($obj->load($id))
           {
                return $obj;
           }
           else return null;
        }
 
 
 
        public static function loadAll()
        {
           $obj = new OrgunitType();
           $obj->select("active",'Y');
 
           $objList = $obj->loadMany();
 
           return $objList;
        }
 
 
        public static function loadByMainIndex($lookup_code,$create_obj_if_not_found=false)
        {
           $obj = new OrgunitType();
           if(!$lookup_code) throw new AfwRuntimeException("loadByMainIndex : lookup_code is mandatory field");
 
 
           $obj->select("lookup_code",$lookup_code);
 
           if($obj->load())
           {
                if($create_obj_if_not_found) $obj->activate();
                return $obj;
           }
           elseif($create_obj_if_not_found)
           {
                $obj->set("lookup_code",$lookup_code);
 
                $obj->insert();
                $obj->is_new = true;
                return $obj;
           }
           else return null;
 
        }
        
        public static function loadByCode($external_code, $title, $create_obj_if_not_found=false)
        {
           $obj = new OrgunitType();
           if(!$external_code) throw new AfwRuntimeException("loadByCode : external_code is mandatory field");
 
           $obj->select("external_code",$external_code);
 
           if($obj->load())
           {
                
                if($create_obj_if_not_found) 
                {
                        $obj->set("titre_short",$title);
                        $obj->activate();
                }        
                return $obj;
           }
           elseif($create_obj_if_not_found)
           {
                $obj->set("external_code",$external_code);
                $obj->set("titre_short",$title);
 
                $obj->insert();
                $obj->is_new = true;
                return $obj;
           }
           else return null;
 
        }
 
 
        public function getDisplay($lang="ar")
        {
               if(!$lang)  $lang = "ar";
               $data = array();
               $link = array();
 
 
               $data["ar"] = $this->getVal("titre_short");
               $data["en"] = $this->getVal("titre_short_en");
 
 
               $return = $data[$lang];
               if(!$return) return $this->getVal("titre_short");
               return $return;
        }
 
 
 
 
 
        protected function getOtherLinksArray($mode, $genereLog = false, $step="all")      
        {
             global $me, $objme, $lang;
             $otherLinksArray = array();
             $my_id = $this->getId();
             $displ = $this->getDisplay($lang);
 
 
 
             return $otherLinksArray;
        }
 
        protected function getPublicMethods()
        {
 
            $pbms = array();
 
            $color = "green";
            $title_ar = "xxxxxxxxxxxxxxxxxxxx"; 
            //$pbms["xc123B"] = array("METHOD"=>"methodName","COLOR"=>$color, "LABEL_AR"=>$title_ar, "ADMIN-ONLY"=>true, "BF-ID"=>"");
 
 
 
            return $pbms;
        }
 
 
        public function beforeDelete($id,$id_replace) 
        {
            
 
            if($id)
            {   
               if($id_replace==0)
               {
                   $server_db_prefix = AfwSession::config("db_prefix","default_db_"); // FK part of me - not deletable 
 
 
                   $server_db_prefix = AfwSession::config("db_prefix","default_db_"); // FK part of me - deletable 
 
 
                   // FK not part of me - replaceable 
                       // bmu.b_m_orgunit-نوع العنصر	orgunit_type_id  حقل يفلتر به-ManyToOne
                        $this->execQuery("update ${server_db_prefix}bmu.b_m_orgunit set orgunit_type_id='$id_replace' where orgunit_type_id='$id' ");
                       // hrm.orgunit-نوع العنصر	id_sh_type  غير معروفة-unkn
                        $this->execQuery("update ${server_db_prefix}hrm.orgunit set id_sh_type='$id_replace' where id_sh_type='$id' ");
 
 
 
                   // MFK
 
               }
               else
               {
                        $server_db_prefix = AfwSession::config("db_prefix","default_db_"); // FK on me 
                       // bmu.b_m_orgunit-نوع العنصر	orgunit_type_id  حقل يفلتر به-ManyToOne
                        $this->execQuery("update ${server_db_prefix}bmu.b_m_orgunit set orgunit_type_id='$id_replace' where orgunit_type_id='$id' ");
                       // hrm.orgunit-نوع العنصر	id_sh_type  غير معروفة-unkn
                        $this->execQuery("update ${server_db_prefix}hrm.orgunit set id_sh_type='$id_replace' where id_sh_type='$id' ");
 
 
                        // MFK
 
 
               } 
               return true;
            }    
        }
        
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
 
}
?> 