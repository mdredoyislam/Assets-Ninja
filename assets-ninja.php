<?php
/*
Plugin Name: Assets Ninja
Plugin URI: https://redoyit.com/
Description: Used by millions, WordCount is quite possibly the best way in the world to <strong>protect your blog from spam</strong>. WordCount Anti-spam keeps your site protected even while you sleep. To get started: activate the WordCount plugin and then go to your WordCount Settings page to set up your API key.
Version: 5.3
Requires at least: 5.8
Requires PHP: 5.6.20
Author: Md. Redoy Islam
Author URI: https://redoyit.com/wordpress-plugins/
License: GPLv2 or later
Text Domain: assetsninja
Domain Path: /languages
*/

/*
    function wordcount_activation_hook(){}
    register_activation_hook(__FILE__, "wordcount_activation_hook");

    function wordcount_deactivation_hook(){}
    register_deactivation_hook(__FILE__, "wordcount_deactivation_hook");
*/

//Plugin File Directory Constants Define
define('ASN_ASSETS_DIR', plugin_dir_url(__FILE__).'assets/');
define('ASN_ASSETS_PUBLIC_DIR', ASN_ASSETS_DIR.'public/');
define('ASN_ASSETS_PUBLIC_JS_DIR', ASN_ASSETS_PUBLIC_DIR.'js/');
define('ASN_ASSETS_PUBLIC_CSS_DIR', ASN_ASSETS_PUBLIC_DIR.'css/');
define('ASN_ASSETS_PUBLIC_IMG_DIR', ASN_ASSETS_PUBLIC_DIR.'images/');
define('ASN_ASSETS_ADMIN_DIR', ASN_ASSETS_DIR.'admin/');
define('ASN_ASSETS_ADMIN_JS_DIR', ASN_ASSETS_ADMIN_DIR.'js/');
define('ASN_ASSETS_ADMIN_CSS_DIR', ASN_ASSETS_ADMIN_DIR.'css/');
define('ASN_ASSETS_ADMIN_IMG_DIR', ASN_ASSETS_ADMIN_DIR.'images/');
define('ASN_VERSION', time());

//Class Defile Assets
class AssetsNinja{
    private $version;
    function __construct(){
        $this->version = time();
        add_action('init', array($this,'asn_init'));
        add_action('plugin_loaded', array($this, 'load_textdomain'));
        add_action('wp_enqueue_scripts', array($this, 'load_front_assets'));
        add_action('admin_enqueue_scripts', array($this, 'load_admin_assets'));
    }

    //Old CSS/JS File Deregister And New CSS/JS File Register
    function asn_init(){
        /*
            wp_deregister_style('tinyslider-css');
            wp_register_style('tinyslider-css','//cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.8.4/tiny-slider.css');
            wp_deregister_script('tinyslider-js');
            wp_register_script('tinyslider-js','tinyslider-js','//cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.8.4/min/tiny-slider.js', null, '1.0', true);
        */
    }

    //Admin/Dashbord Assets Load Function
    function load_admin_assets($screen){
        $_screen = get_current_screen();
        if('options-general.php' == $screen && 'taxonomy' == $_screen->post_type){
            wp_enqueue_script('asn-admin', ASN_ASSETS_ADMIN_JS_DIR ."admin.js", array('jquery'), $this->version, true);
        }
    }
    //Frontent Assets Load Function
    function load_front_assets(){
        wp_enqueue_style('asn-main', ASN_ASSETS_PUBLIC_CSS_DIR. "main.css", null, $this->version, false);
        //Assets Inline JS/CSS Load
        $data = <<<EOD
            <style>
                .class{line:00}
            </style>
        EOD;
        wp_add_inline_style( 'asn-main', $data );

        /*
            wp_enqueue_script('asn-main', ASN_ASSETS_PUBLIC_JS_DIR ."main.js", array('jquery', 'assetsninja-another'), $this->version, true);
            wp_enqueue_script('asn-another', ASN_ASSETS_PUBLIC_JS_DIR ."another.js", array('jquery','assetsninja-more'), $this->version, true);
            wp_enqueue_script('asn-more', ASN_ASSETS_PUBLIC_JS_DIR ."more.js", array('jquery'), $this->version, true);
        */
        //JS Load Loop
        $js_files = array(
            'asn-main' => array('path'=>ASN_ASSETS_PUBLIC_JS_DIR.'main.js', 'dep'=> array('jquery', 'assetsninja-another')),
            'asn-another' => array('path'=>ASN_ASSETS_PUBLIC_JS_DIR.'another.js', 'dep'=> array('jquery','assetsninja-more')),
            'asn-more' => array('path'=>ASN_ASSETS_PUBLIC_JS_DIR.'more.js', 'dep'=> array('jquery')),
        );
        foreach($js_files as $handle=>$fileinfo){
            wp_enqueue_script($handle, $fileinfo['path'], $fileinfo['dep'], $this->version, true);
        }
    
        //PHP Data Pass Javascript Start
        $data = array(
            'name' => 'MD. REDOY ISLAM',
            'url' => 'https://redoyit.com/'
        );
        wp_localize_script('asn-more', 'sitedata', $data);

        $translated_strings = array(
            'greetings' => __('Hello World','assetsninja'),
        );
        wp_localize_script('asn-more', 'translated_data', $translated_strings);
        //PHP Data Pass Javascript End
    }
    function load_textdomain(){ 
        load_plugin_textdomain('assetsninja', false, dirname(__FILE__) . '/languages');
    }
}
new AssetsNinja();