<?php

class templates {

           function template_open($file) {
           
           $fp = fopen($file,"r") or die ("Can't open template(");
	   $dump='';
           
           while(!feof($fp)) $dump.=fread($fp,4096);
		
	   $this->string=$dump;
           
           return TRUE;
           }
           
           
           function template_set($var, $new) {
           $var = '{_' . $var . '}';
	   $this->search[$var]=$new;
           }
           
           
           function template_show() {
           foreach($this->search as $key=>$value) $this->string=str_replace($key,$value,$this->string);
           return $this->string;
           }

};

?>
