<?php

class WebAR {

    private static $className = "WebAR";
    private static $initiated = false;
    private static $hostUrl = "https://portal.wpwebar.com";

    public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
	}

    function init_hooks()
    {
        self::$initiated = true;
        add_action('admin_menu', array(self::$className, 'webar_admin_menu_option'));

        // Shortcodes
		add_shortcode( 'webar-frame', array(self::$className, 'webar_frontend_frame'));
    }

    function activate() 
	{
		
	}

	function deactivate()
	{

	}

	function uninstall() 
	{

    }
        
    /**
     * webar_admin_menu_option
     *
     * @return void
     */
    function webar_admin_menu_option()
    {
        add_menu_page(
			'WebAR', // page_title
			'WebAR', // menu_title
			'manage_options', // capability
			'webar-settings', // menu_slug
			array( self::$className, 'webar_settings_create_admin_page' ), // function
			"data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNTIiIGhlaWdodD0iNTkiIHZpZXdCb3g9IjAgMCA1MiA1OSIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggb3BhY2l0eT0iMC41IiBkPSJNMjYgMUw1MSAxNC42OTg2TTI2IDFMMTguNDY1OCA1LjEyODM1TDguODc2NzEgMTAuMzgyNkwxIDE0LjY5ODZNMjYgMVYxMC4yNDY2VjI5LjQyNDdNMjYgMjkuNDI0N1Y1Ny44NDkzTTI2IDI5LjQyNDdMMSA0NC4xNTA3TTI2IDI5LjQyNDdMNTEgMTQuNjk4Nk0yNiAyOS40MjQ3TDUxIDQ0LjE1MDdNMjYgMjkuNDI0N0w4Ljg3NjcxIDE5LjMzODNMMSAxNC42OTg2TTI2IDU3Ljg0OTNMMSA0NC4xNTA3TTI2IDU3Ljg0OTNMNTEgNDQuMTUwN00xIDQ0LjE1MDdWMjQuMjg3N1YxNC42OTg2TTUxIDQ0LjE1MDdWMTQuNjk4NiIgc3Ryb2tlPSJ3aGl0ZSIvPgo8cGF0aCBkPSJNMSA0NC4xNTA2TDguODc2NzEgNDguNDY2N00xIDQ0LjE1MDZMOC44NzY3MSAzOS41MTA5TTEgNDQuMTUwNlYzNC41NjE2TTE4LjQ2NTggNS4xMjg3MkwyNiAxLjAwMDM2TTI2IDEuMDAwMzZWMTAuMjQ2OU0yNiAxLjAwMDM2TDMzLjUzNDIgNS4xMjg3Mk0xIDE0LjY5OTRMOC44NzY3MSAxMC4zODM0TTEgMTQuNjk5NEw4Ljg3NjcxIDE5LjMzOTFNMSAxNC42OTk0VjI0LjI4ODRNMTguNDY1OCA1My43NDA4TDI2IDU3Ljg2OTFNMjYgNTcuODY5MVY0OC42MjI2TTI2IDU3Ljg2OTFMMzMuNTM0MiA1My43NDA4TTE4LjQ2NTggMjQuOTczN0wyNiAyOS40MjU4TTI2IDI5LjQyNThWMzguNjcyM00yNiAyOS40MjU4TDMzLjUzNDIgMjQuOTczN001MSA0NC4xNTA2TDQzLjEyMzMgNDguNDY2N001MSA0NC4xNTA2TDQzLjEyMzMgMzkuNTEwOU01MSA0NC4xNTA2VjM0LjU2MTZNNTEgMTQuNjk5NEw0My4xMjMzIDEwLjM4MzRNNTEgMTQuNjk5NEw0My4xMjMzIDE5LjMzOTFNNTEgMTQuNjk5NFYyNC4yODg0IiBzdHJva2U9IndoaXRlIiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPgo8L3N2Zz4K",
            2 // position
		);

    }
    
    /**
     * webar_settings_create_admin_page
     *
     * @return void
     */
    function webar_settings_create_admin_page()
    {
        if(array_key_exists('submit_webar_settings', $_POST))
        {
            $private = sanitize_text_field(esc_html($_POST['webar_private_key'] ?? ""));

            if ($private != "") {

                $url = self::$hostUrl.'/website/validate';

                $body = array(
                    'private'   => $private,
                    'website'   => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'],
                );

                $args = array(
                    'body'        => $body,
                    'timeout'     => '0',
                    'redirection' => '0',
                    'httpversion' => '1.0',
                    'blocking'    => true,
                    'headers'     => array(),
                    'cookies'     => array(),
                );

                $wpresp = wp_remote_post($url, $args);
                $response = json_decode($wpresp["body"]);

                
                if(isset($response->status) && $response->status == "success" && isset($response->public) && $response->public != "") {
                    // All ok, save keys

                    update_option('webar_private_key' , $private);
                    update_option('webar_public_key' , $response->public);
                    update_option('webar_activated', true);

                    ?> 

                        <div id="setting-error-settings_updated" class="notice notice-success settings-error is-dismissible">
                            <p>
                                <strong>
                                    <?php echo $response->message ?? "Something went wrong"; ?>
                                </strong>
                            </p>
                        </div>

                    <?php

                } else {
                    ?> 

                        <div id="setting-error-settings_updated" class="notice notice-error settings-error is-dismissible">
                            <p>
                                <strong>
                                    <?php echo $response->message ?? "Something went wrong, please try again later."; ?>
                                </strong>
                            </p>
                        </div>

                    <?php
                }
                
            }else {
                ?> 

                    <div id="setting-error-settings_updated" class="notice notice-error settings-error is-dismissible">
                        <p>
                            <strong>
                                Please enter a valid license key.
                            </strong>
                        </p>
                    </div>

                <?php
            }

        }

        $privateKey = get_option('webar_private_key', "");
        $publicKey = get_option('webar_public_key', "");

        ?>

            <div class="wrap wpwebar-admin-page page-webar">

                <h2 class="nav-tab-wrapper" id="wpwebar-tabs">
                    <a class="nav-tab nav-tab-active" id="dashboard-tab" href="#top#authorization">Authorization</a>
                    <a class="nav-tab" href="https://portal.wpwebar.com" target="_blank">Portal</a>
                    <a class="nav-tab" href="https://wpwebar.com/guide" target="_blank">Guide</a>
                    <a class="nav-tab" href="https://wpwebar.com/faq" target="_blank">FAQ</a>
                    <a class="nav-tab" href="https://wordpress.org/support/plugin/wpwebar/" target="_blank">Support</a>
                </h2>

                <div id="authorization" class="wpwebartab active">

                    <form method="post" action="">
                        <table class="form-table" role="presentation">
                            <tbody>
                                <tr>
                                    <th scope="row"><label for="webar_private_key">License key</label></th>
                                    <td>
                                        <input name="webar_private_key" type="text" id="webar_private_key" value="<?php echo $privateKey; ?>" class="regular-text">
                                        <p>Keep your license key private!</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <p class="submit">
                            <input type="submit" name="submit_webar_settings" id="submit" class="button button-primary" value="Save license key">
                            <a href="https://portal.wpwebar.com/register" target="_blank">Get your license key</a>
                        </p>
                        
                    </form>
                
                </div>
            </div>

        <?php
    }
    
    /**
     * webar_frontend_frame
     *
     * @param  mixed $params
     * @return void
     */
    function webar_frontend_frame($params)
    {
        $publicKey = get_option('webar_public_key', "");
        $id = $params["id"] ?? null;
        $width = $params["width"] != "" ? $params["width"] : "100%";
        $height = $params["height"] != "" ? $params["height"] : "400px";

        if ($publicKey != "" && $id != "") {

            $url = self::$hostUrl."/modelviewer?id=" . $id . "&token=" . $publicKey;

            return "<iframe src='".$url."' width='".$width."' height='".$height."' allow='fullscreen' frameBorder='0'></iframe>";

        }else if($publicKey == "") {
            return "<p><strong>WebAR Error:</strong><br> Please set your personal WebAR token.</p>";
        }else if($id != "") {
            return "<p><strong>WebAR Error:</strong><br> Please provide the ID.</p>";
        }else {
            return "<p><strong>WebAR Error:</strong><br> Something wen't wrong, please check your Wordpress & plugins for updates.</p>";
        }
    }
    
    /**
     * view
     *
     * @param  mixed $name
     * @param  mixed $args
     * @return void
     */
    public static function view( $name, array $args = array() ) {
		$args = apply_filters( 'webar_view_arguments', $args, $name );
		
		foreach ( $args AS $key => $val ) {
			$$key = $val;
		}
		
		load_plugin_textdomain( 'webar' );

		$file = WEBAR__PLUGIN_DIR . 'views/'. $name . '.php';

		include( $file );
    }
        
    /**
     * getProduct
     *
     * @param  mixed $productName
     * @return void
     */
    public static function getProduct($productName) {
        $url = self::$hostUrl.'/model/get';

        $body = array(
            'token'   => WebAR::get_public_key(),
            'model'    => $productName,
            'domain'   => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'],
        );

        $args = array(
            'body'        => $body,
            'timeout'     => '0',
            'redirection' => '0',
            'httpversion' => '1.0',
            'blocking'    => true,
            'headers'     => array(),
            'cookies'     => array(),
        );

        $wpresp = wp_remote_post($url, $args);
        $response = json_decode($wpresp["body"]);

        return $response->model;
    }
    
    /**
     * get_public_key
     *
     * @return String
     */
    public static function get_public_key(): String
    {
        return get_option('webar_public_key');
    }

}

if(class_exists('WebAR')) {
    $webAR = new WebAR();
}

// Activation Hook
register_activation_hook( __FILE__, array($webAR, 'activate'));

// Deactivation Hook
register_deactivation_hook(__FILE__, array($webAR, 'deactivate'));

// Uninstall Hook
register_uninstall_hook(__FILE__, array($webAR, 'uninstall'));
