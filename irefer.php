<?php
/*
  Plugin Name: iRefer.io
  Version: 1.2
  Tested up to: 4.9.7
  Description: Connect iRefer to your website. The easiest way for your customers to refer your products and services.

 */
// Make sure we don't expose any info if called directly
if (!function_exists('add_action')) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

define('IREFER_TEXTDOMAIN', 'IREFER-SIGNUP');

add_action('init', 'irefer_signup_init');
function irefer_signup_plugin_url()
{
    return untrailingslashit(plugins_url('/', __FILE__));
}

function irefer_signup_plugin_path()
{
    return untrailingslashit(plugin_dir_path(__FILE__));
}

function irefer_signup_init()
{
    
    add_action('admin_menu', 'irefer_signup_menu');
    if ($irefer_signup_script_src = get_option('irefer_signup_data')) {
        wp_enqueue_script('irefer-api', $irefer_signup_script_src, array(), null, true);
    }
//    add_action('wp_footer', 'irefer_signup_add_script', 999);
}

function irefer_signup_menu()
{
    $settings_page = add_menu_page(__('iRefer', IREFER_TEXTDOMAIN), __('iRefer', IREFER_TEXTDOMAIN), 'activate_plugins', 'irefer-signup-settings', 'irefer_signup_page', null);
    add_action('load-' . $settings_page, 'irefer_page_init');
}

function irefer_page_init()
{
    add_action('admin_enqueue_scripts', 'load_irefer_signup_admin_style');
}

function load_irefer_signup_admin_style()
{
    $wp_scripts = wp_scripts();
   wp_enqueue_style('irefer_signup_wp_admin-ui-css', irefer_signup_plugin_url() . '/assets/vendor/jqueryui/themes/smoothness/jquery-ui.min.css', false,  $wp_scripts->registered['jquery-ui-core']->ver, false);

    wp_register_style('irefer_signup_wp_admin_css', irefer_signup_plugin_url() . '/assets/css/style.css', false, '1.0.0');
    wp_enqueue_style('irefer_signup_wp_admin_css');

    wp_register_style('irefer_signup_wp_admin_fonts_css', irefer_signup_plugin_url() . '/assets/fonts/stylesheet.css', false, '1.0.0');
    wp_enqueue_style('irefer_signup_wp_admin_fonts_css');

    wp_enqueue_script('jquery-ui-tooltip');

    wp_register_script('irefer_signup_wp_admin_js', irefer_signup_plugin_url() . '/assets/js/script.js', array('jquery-ui-tooltip'), '1.0.0', true);
    wp_localize_script('irefer_signup_wp_admin_js', 'irefer_signup', array(
            'tooltop_img_src' => irefer_signup_plugin_url() . '/assets/img/tooltip.png',
    ));
    wp_enqueue_script('irefer_signup_wp_admin_js');
}

function irefer_signup_page()
{

    $errors = array();
    $messages = array();


    // Save settings if data has been posted
    if (!empty($_POST)) {

        if (empty($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'irefer-signup-settings')) {
            die(__('Action failed. Please refresh the page and retry.', IREFER_TEXTDOMAIN));
        }

        update_option('irefer_signup_data', esc_url_raw($_POST['irefer_signup_data']));
        $messages[] = __("Code has been succesfully added to your website", IREFER_TEXTDOMAIN);
    }

    ?>
    <div class="wrap">
        <h2>&nbsp;</h2>
        <div class="form-wrapper">
            <form method="post" action="" id="irefer-signup_form">
                <div class="form-header">
                    <div class="logo"><a href="https://app.irefer.io" target="_blank">
                            <img src="<?php echo irefer_signup_plugin_url() . "/assets/img/irefer-logo-wp.png"; ?>" alt="Irefer Logo">
                        </a>
                    </div>
                </div>
                <div class="form-content irefer-sign-ups">
                    <h2>1. Create a free account</h2>
                    <div class="form-des">First you will need to sign up and create your free account. <a href="https://irefer.io/pricing" target="_blank">Click Here</a></div>
                    <br> <br>
                    <h2>2. Connect iRefer to your website </h2>

                    <div class="form-des">Login to your <a href="https://app.irefer.io/login" target="_blank">iRefer account</a> and navigate to "Dashboard > Settings > Installation Code" copy the URL part of the code only and paste it into the field below. Click the <strong>"Add Code"</strong> button. Then refresh your website home page.</div>
                    <div class="clearfix">
                        <input name="irefer_signup_data" id="irefer_signup_data" type="text"  class="input-field" title="test" value="<?php echo esc_url(get_option('irefer_signup_data')); ?>" placeholder="http://api.irefer.io/id"/>
                        <input name="save" style=" "  class="add-code" type="submit" value="<?php esc_attr_e('ADD CODE', IREFER_TEXTDOMAIN); ?>" />
                    </div>
                    <div class="clearfix">
                        <?php
                        if (sizeof($errors) > 0) {
                            foreach ($errors as $error) {
                                echo '<p class="error">' . esc_html($error) . '</p>';
                            }
                        } elseif (sizeof($messages) > 0) {
                            foreach ($messages as $message) {
                                echo '<p class="sucess">' . esc_html($message) . '</p>';
                            }
                        }

                        ?>
                    </div>
                    <div class="line"></div>

                    <h2>3. Complete your installation </h2>
                    <div class="form-des">Next, log back in to your account <a href="https://app.irefer.io/login" target="_blank">https://app.irefer.io/login</a> and refresh the page "Dashboard > Settings > Installation Code", you will now see a green tick.<br/><br/>
                    </div>
                </div>
                <?php wp_nonce_field('irefer-signup-settings'); ?>
            </form>
        </div>
    </div>
    <?php
}
?>