<?php
/**
 * Created by PhpStorm.
 * User: z97
 * Date: 15-8-13
 * Time: 下午5:17
 */
namespace iamchenxin;
require_once (dirname(__FILE__)."/less.php/Less.php");

class BootstrapLessc{
    protected $bootstrap_dir;
    protected $mediawikiLess_dir;
    protected $bootstrap_mixin;
    protected $bootstrap_mixin_url;
    protected $mediawiki_mixin;
    protected $mediawiki_mixin_url;
    protected $cache_dir;

    public function __construct($cache_dir="cache"){
        //
        $IP="/var/www/lsbs";
        $wgScriptPath="";
        //

        $this->cache_dir=$cache_dir;

        $this->bootstrap_dir="$IP/vendor/twbs/bootstrap/less";
        $this->mediawikiLess_dir="$IP/resources/src/mediawiki.less";

        $this->bootstrap_mixin=$this->bootstrap_dir."/mixins.less";
        $this->bootstrap_mixin_url="$wgScriptPath/vendor/twbs/bootstrap";
        $this->mediawiki_mixin=$this->mediawikiLess_dir."/mediawiki.mixins.less";
        $this->mediawiki_mixin_url="$wgScriptPath/resources/src/mediawiki.less";
    }

    /*         $less_file=["less"=>"url"];
    $bootstrap_less="mixins","full","off";  $mediawiki_less="mixins","off";
    */
    public function Compile($less_files,$out_name,$modify_vars=[],$bootstrap_less="mixins",$mediawiki_less="mixins"){

        $lessphp=new Less_Parser($this->cache_dir);

        switch($bootstrap_less){
            case "mixins":
                $lessphp->parseFile($this->bootstrap_dir."/variables.less","");
                $lessphp->parseFile(__DIR__."/custom_variables.less","");
                $lessphp->parseFile($this->bootstrap_mixin,$this->bootstrap_mixin_url);
                break;
            case "full":
                $lessphp->SetImportDirs([$this->bootstrap_dir]);
                $lessphp->parseFile(__DIR__."/bootstrap.less","");
                break;
            case "off":
                break;
        }
        switch($mediawiki_less){
            case "mixins":
                $lessphp->parseFile($this->mediawiki_mixin,$this->mediawiki_mixin_url);
                break;
            case "off":
                break;
        }


        foreach($less_files as $less=>$url){
            $lessphp->parseFile($less,$url);
        }

        if($modify_vars){
            $lessphp->ModifyVars($modify_vars);
        }

        $css=$lessphp->getCss();

        file_put_contents($out_name,$css);

    }

}