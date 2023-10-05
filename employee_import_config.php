<?php
$importRequirement["idn"] = array(trad_ar=>"رقم الهوية", mandatory=>true, required=>true, type=>TEXT);
$importRequirement["idn_type"] = array(trad_ar=>"نوع الهوية", mandatory=>false, required=>true, type=>TEXT);
$importRequirement["genre"] = array(trad_ar=>"الجنس", mandatory=>false, required=>true, type=>TEXT);
$importRequirement["nationality"] = array(trad_ar=>"الجنسية", mandatory=>false, type=>TEXT, );
$importRequirement["mobile"] = array(trad_ar=>"جوال الموظف", mandatory=>true, required=>true, type=>TEXT);

$importRequirement["firstname"] = array(trad_ar=>"الاسم الأول", mandatory=>true, required=>true, type=>TEXT);
$importRequirement["fatherfirstname"] = array(trad_ar=>"اسم الأب", mandatory=>true, required=>true, type=>TEXT);
$importRequirement["grandfathername"] = array(trad_ar=>"اسم الجد", mandatory=>false, required=>true, type=>TEXT);
$importRequirement["lastname"] = array(trad_ar=>"اسم العائلة", mandatory=>true, required=>true, type=>TEXT);

$importRequirement["jobname"] = array(trad_ar=>"الوظيفة", mandatory=>true, required=>true, type=>TEXT);
$importRequirement["email"] = array(trad_ar=>"البريد الالكتروني", mandatory=>true, required=>true, type=>TEXT);
$importRequirement["employee_num"] = array(trad_ar=>"البريد الالكتروني", mandatory=>true, required=>true, type=>TEXT);
$importRequirement["dep_id"] = array(trad_ar=>"معرف الإدارة", mandatory=>true, required=>true, type=>TEXT);
$importRequirement["dep_name"] = array(trad_ar=>"مسمى الإدارة", mandatory=>true, required=>true, type=>TEXT);

?>