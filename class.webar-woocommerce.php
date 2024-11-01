<?php

class WebAR_WooCommerce {

    private static $className = "WebAR_WooCommerce";
    private static $initiated = false;
    private static $hostUrl = "https://portal.wpwebar.com";

    public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
	}

    public static function init_hooks() {
        add_filter( 'woocommerce_locate_template', array(self::$className, 'woo_adon_plugin_template'), 1, 3 );
    }

    
    function woo_adon_plugin_template( $template, $template_name, $template_path ) {
        global $woocommerce;
        $_template = $template;
        if(!$template_path ) 
            $template_path = $woocommerce->template_url;
            $plugin_path  = untrailingslashit( plugin_dir_path( __FILE__ ) )  . '/template/woocommerce/';
        
            // Look within passed path within the theme - this is priority
            $template = locate_template(
            array(
                $template_path . $template_name,
                $template_name
            )
        );
        
        if( ! $template && file_exists( $plugin_path . $template_name ) )
            $template = $plugin_path . $template_name;
        
        if ( ! $template )
            $template = $_template;

        return $template;
    }

}