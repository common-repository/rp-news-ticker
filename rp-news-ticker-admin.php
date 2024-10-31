<?php
if (!function_exists('add_action'))
{
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

class rp_news_ticker_admin
{
    var $php5run = false;
    var $name;
    var $dir;
    var $img;
    var $http;
    var $pagehook;
    var $options;
    
    function &gt($type = 'core_instance')
    {
        static $instance = array();
        if (!$instance || !$instance[$type])
        {
            $instance[$type] = & new rp_news_ticker_admin($type);
        }
        return $instance[$type];
    }

    function __construct()
    {
        $this->php5run  = true;
        $_main          = rp_news_ticker::gt();
        $this->name     = &$_main->name;
        $this->dir      = &$_main->dir;
        $this->http     = &$_main->http;
        $this->abspath  = &$_main->abspath;
        $this->options  = &$_main->options;        
    }

    function rp_news_ticker_admin()
    {
        if (!$this->php5run)
        {
            $this->php5run = true;
            $this->__construct();
        }
    }

    function init()
    {
        register_activation_hook($this->name, array('rp_news_ticker_admin', 'plugin_install'));
        //register_deactivation_hook($this->name, array('rp_news_ticker_admin', 'plugin_uninstall'));
        add_action('admin_menu', array(&$this, 'admin_menu'));
        add_action('admin_post_save_rp_news_ticker_settings', array(&$this, 'save_settings_admin'));
        add_action('admin_post_reset_rp_news_ticker_settings', array(&$this, 'reset_settings_admin'));
    }

    /* ===========================> Install/Uninstall */

    static function plugin_install()
    {
        //Transfer options from previous widgeted versions if they exist
        $old_options    = get_option('widget_rpliscrollticker');
        $option_name    = 'rp_news_ticker';

        if (!get_option($option_name))
        {
            $deprecated = ' ';
            $autoload = 'no';
            add_option( $option_name, !empty($old_options) ? $old_options : $this->default_options(), $deprecated, $autoload);
        }
    }

    static function plugin_uninstall()
    {

    }

    /* ===========================> Common */

    function admin_menu()
    {
        $this->pagehook = add_options_page(__('RP News Ticker', $this->name), __('RP News Ticker', $this->name),
            'manage_options', 'rp-news-ticker', array(&$this, 'display_page_general'));
        add_action('load-' . $this->pagehook, array(&$this, 'on_load_page'));
    }

    function on_load_page()
    {
        wp_enqueue_script('common');
        wp_enqueue_script('wp-lists');
        wp_enqueue_script('postbox');

        add_meta_box($this->name . '-sidebox-1', __('Usage', $this->name), array(&$this, 'on_sidebox_3_content'), $this->pagehook, 'side', 'core');
        add_meta_box($this->name . '-sidebox-2', __('Get Help', $this->name), array(&$this, 'on_sidebox_2_content'), $this->pagehook, 'side', 'core');
        add_meta_box($this->name . '-sidebox-3', __('RP Plugins', $this->name), array(&$this, 'on_sidebox_1_content'), $this->pagehook, 'side', 'core');
        add_meta_box($this->name . '-sidebox-4', __('Thank you for using our plugins!', $this->name), array(&$this, 'on_sidebox_4_content'), $this->pagehook, 'side', 'core');
    }

    function on_sidebox_1_content($data)
    {
        ?>
        <ul style="list-style-type:disc;margin-left:20px;">
            <li><a href="http://www.rationalplanet.com/php-related/rp-newsticker-plugin-for-wordpress.html">RP News Ticker</a>
                <?php _e('- a news scroller that is able to display different useful content', $this->name); ?>
                (<a href="http://chernivtsi.ws/" target="_blank"><?php _e('demo', $this->name); ?></a>,
                <a href="http://wordpress.org/extend/plugins/rp-news-ticker/" target="_blank"><?php _e('wordpress plugin page', $this->name); ?></a>).
            </li>
            <li><a href="http://www.rationalplanet.com/php-related/free-plugin-utility-for-wordpress-rp-recreate-slugs.html">RP Recreate Slugs</a><?php
                _e('- recreate articles and pages slugs in database.', $this->name); ?>
                (<a href="http://wordpress.org/extend/plugins/rp-recreate-slugs/" target="_blank"><?php
                    _e('wordpress plugin page', $this->name); ?></a>).
            </li>
        </ul>
        <?php
    }

    function on_sidebox_2_content($data)
    {
        ?>
        <ul style="list-style-type:disc;margin-left:20px;">
            <li>
                <a href="http://www.rationalplanet.com/php-related/creating-a-site-wide-sidebar-for-your-worpress-theme.html"
                   target="_blank" title="How To Create a Site-Wide Sidebar"><?php
                    _e('Creating a Site-Wide Sidebar for Your Worpress Theme', $this->name); ?></a>
            </li>
            <li>
                <a href="http://php.net/manual/en/function.date.php" target="_blank" title="Formatting Date in PHP"><?php
                    _e('Patterns to Format Date', $this->name); ?></a>
            </li>
            <li>
                <a href="http://www.rationalplanet.com/my-cv?from-plugin=rp-news-ticker" target="_blank" title="Hire a php freelancer"><?php
                    _e('Hire a PHP Freelancer', $this->name); ?></a>, <a href="http://www.rationalplanet.com/philosophy" target="_blank" title="What you get and how it works"><?php
                    _e('one more link worth of reading', $this->name); ?></a>
            </li>
        </ul>
        <?php
    }

    function on_sidebox_3_content($data)
    {
        ?>
        <table class="widefat">
            <tbody>
                <tr>
                    <td>
                        <h3><?php echo __('Shortcode (posts, pages)', $this->name); ?></h3>
                        <textarea style="width:100%;height:3em;" readonly="readonly">[rp-news-ticker]</textarea>
                    </td>
                </tr>
                <tr class="alternate">
                    <td>
                        <h3><?php echo __('Widget', $this->name); ?></h3>
                        <ol style="margin-left:20px;padding:10px 0;">
                            <li><?php echo __('Use simple "Text" widget', $this->name); ?></li>
                            <li><?php echo __('Add the shortcode above', $this->name); ?></li>
                            <li><?php echo __('Save', $this->name); ?></li>
                        </ol>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h3><?php echo __('Theme / PHP', $this->name); ?></h3>
                        <textarea style="width:100%;height:5em;" readonly="readonly"><?php echo htmlspecialchars('<?php if(function_exists(\'rp_news_ticker\')){ rp_news_ticker(); } ?>') ?></textarea>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php
    }

    function on_sidebox_4_content($data)
    {
        ?>
        <p>
            <strong><?php echo __('In the next version:', $this->name); ?></strong> <?php
                echo __('Change scroller speed, chose appear effect and more cool features!', $this->name); ?>
        </p>
        <table border=0 style="float:right">
            <tr>
                <td style="border:none;padding:0;" valign="top">
                    <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                        <input type="hidden" name="cmd" value="_s-xclick">
                        <input type="hidden" name="hosted_button_id" value="6968046">
                        <input type="image" src="http://www.paypal.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit"
                               alt="PayPal - The safer, easier way to pay online!">
                        <img alt="" border="0" src="http://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
                    </form>
                </td>
            </tr>
        </table>
        <p style="color:blue;">
            <em><?php echo __('Stimulate developers to release new versions of this free software', $this->name); ?></em>
        </p>                
        <?php
    }


    /*     * **************************** Save Settings ********************************************************************************************* */

    function save_settings_admin()
    {
        if (!current_user_can('manage_options'))
        {
            wp_die(__('Cheatin&#8217; uh?'));
        }
        check_admin_referer('rp-news-ticker-main');

        $option_name    = 'rp_news_ticker';
        $option_value   = array
        (
            'updates_off'       => isset($_POST['updates_off']) ? true : false,
            'category_ids'      => isset($_POST['category_ids']) ? trim(strval($_POST['category_ids'])) : '',
            'post_limit'        => isset($_POST['post_limit']) && intval($_POST['post_limit']) ? intval($_POST['post_limit']) : 0,
            'css'               => isset($_POST['css']) ? trim(strval($_POST['css'])) : '',
            'explicitcontent'   => isset($_POST['explicitcontent']) ? trim(strval($_POST['explicitcontent'])) : '',
            'usage_type'        => isset($_POST['usage_type']) && in_array($_POST['usage_type'], array(1,2,3,4,5,6,7)) ? $_POST['usage_type'] : 1,
            'date_format'       => isset($_POST['date_format']) ? trim(strval($_POST['date_format'])) : '',
        );

        if (!get_option($option_name))
        {
            $deprecated = ' ';
            $autoload = 'no';
            add_option($option_name, $option_value, $deprecated, $autoload);
        }
        else
        {
            update_option($option_name, $option_value);
        }

        $_main          = rp_news_ticker::gt();
        $_main->options = $option_value;
        $this->options  = &$_main->options;

        if (!@session_id())
        {
            @session_start();
        }

        $_SESSION['rpmessage'] = array('The settings are now updated.');
        wp_redirect($_POST['_wp_http_referer']);
    }

    function reset_settings_admin()
    {
        if (!current_user_can('manage_options'))
        {
            wp_die(__('Cheatin&#8217; uh?'));
        }
        check_admin_referer('rp-news-ticker-main');

        $option_name    = 'rp_news_ticker';
        $option_value   = $this->default_options();

        if (!get_option($option_name))
        {
            $deprecated = ' ';
            $autoload = 'no';
            add_option($option_name, $option_value, $deprecated, $autoload);
        }
        else
        {
            update_option($option_name, $option_value);
        }

        $_main          = rp_news_ticker::gt();
        $_main->options = $option_value;
        $this->options  = &$_main->options;

        if (!@session_id())
        {
            @session_start();
        }

        $_SESSION['rpmessage'] = array('The settings are now reset to default.');

        wp_redirect($_POST['_wp_http_referer']);
    }

    function default_options()
    {

$css =<<<EOT
/* THE DIV IS ORIGINALLY HIDDEN TO SAVE THE LAYOUT WHEN JAVASCRIPT IS OFF */
#rpliscrollticker {display:none;}
/* THE OUTER DIV */
.tickercontainer {background: #fff;width: 100%;height: 27px;margin: 0;padding: 0;overflow:hidden;}
/* SERVES AS A MASK. SO YOU GET A SORT OF PADDING BOTH LEFT AND RIGHT */
.tickercontainer .mask { position: relative;left: 10px;top: 8px;width: 98%;overflow: hidden;}
/* THAT'S YOUR LIST */
ul.newsticker {position: relative;left: 950px;font: bold 10px Verdana;list-style-type: none;margin: 0;padding: 0;}
/* IMPORTANT: DISPLAY INLINE GIVES INCORRECT RESULTS WHEN YOU CHECK FOR ELEM'S WIDTH */
ul.newsticker li {float: left; margin: 0;padding: 0;background: #fff;}
ul.newsticker a {white-space: nowrap;padding: 0;color: #ff0000;font: bold 10px Verdana;margin: 0 50px 0 0;}
ul.newsticker span {margin: 0 10px 0 0;}
EOT;

$explicitcontent = <<<EOT
<li><span>Freelancing:</span><a href="http://www.rationalplanet.com/freelancing-programming">Tools and Techniques Available to Clients Immediately</a></li>
<li><span>PHP Programmer since 2000:</span><a href="http://www.rationalplanet.com/my-cv">10+ years experience in PHP, MySQL, Javascript, fluent spoken English</a></li>
<li><span>Domains for Sale:</span><a href="http://www.rationalplanet.com/featured/a-group-of-domains-is-possibly-available-for-sale.html">rationalplanet.com is on sale</a></li>
<li><span>Free Wordpress Plugins:</span><a href="http://www.rationalplanet.com/category/webmaster/wordpress-programming">download plugins from developer's site</a></li>
EOT;
        return array
        (
            'updates_off'       => false,
            'category_ids'      => '',
            'post_limit'        => 5,
            'css'               => $css,
            'explicitcontent'   => $explicitcontent,
            'usage_type'        => 5,
            'date_format'       => 'D, F j, Y',
        );
    }

    /*     * **************************** General Settings ********************************************************************************************* */

    function display_page_general()
    {
        global $screen_layout_columns;
        $screen_layout_columns = 2;
        add_meta_box($this->name . '-contentbox-1', __('Settings', $this->name), array(&$this, 'on_contentbox_1_content'), $this->pagehook, 'normal', 'core');
        $data    = array();
        $message = array();
        if (!@session_id())
        {
            @session_start();
        }
        if(isset($_SESSION['rpmessage']))
        {
            $message = $_SESSION['rpmessage'];
            unset($_SESSION['rpmessage']);
        }
        ?>
        <?php if (count($message) > 0) echo '<div id="message" class="updated"><p><strong>', implode('<br /><br />', $message), '</strong></p></div>'; ?>
        <div id="<?php echo $this->name; ?>-main" class="wrap">
            <h2><img src="<?php echo $this->http, $this->img; ?>" style="margin:0 10px;" /><?php echo __('RP Newsticker', $this->name); ?></h2>
            <p style="font-style: italic;">
                <?php echo __('Plugin release version:', $this->name); ?> <strong><?php echo RP_NEWS_TICKER_VERSION; ?></strong><br />
                <?php echo __('Discussion:', $this->name); ?> <a href="http://www.rationalplanet.com/php-related/rp-newsticker-plugin-for-wordpress.html"
                               title="Vendor web site" target="_blank">rationalplanet.com</a>
            </p>
            <br class="clear" />
            <form action="admin-post.php" method="post" id="<?php echo $this->name; ?>">
                <?php wp_nonce_field($this->name . '-main'); ?>
                <?php wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false); ?>
                <?php wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false); ?>
                <input type="hidden" name="action" value="save_rp_news_ticker_settings" />
                <div id="poststuff" class="metabox-holder<?php echo 2 == $screen_layout_columns ? ' has-right-sidebar' : ''; ?>">
                    <div id="side-info-column" class="inner-sidebar">
                        <?php do_meta_boxes($this->pagehook, 'side', $data); ?>
                    </div>
                    <div id="post-body" class="has-sidebar">
                        <div id="post-body-content" class="has-sidebar-content">
                            <?php do_meta_boxes($this->pagehook, 'normal', $data); ?>

                            <!--h4><?php echo __('PLEASE NOTE:', $this->name); ?></h4>
                            <div class="highlight">
                                <?php echo __('<p>The <strong>next</strong> release of this plugin will be compatible only with php >= 5.3 and Wordpress >= 3.2.</p>',
                                    $this->name); ?>
                            </div>
                            <br /-->

                            <?php do_meta_boxes($this->pagehook, 'additional', $data);   ?>
                        </div>
                    </div>
                    <br class="clear"/>
                </div>
            </form>
        </div>
        <script type="text/javascript">
            //<![CDATA[
            jQuery(document).ready(function($) {

                $('.if-js-closed').removeClass('if-js-closed').addClass('closed');
                postboxes.add_postbox_toggles('<?php echo $this->pagehook; ?>');

                $('#rp_news_ticker_admin_option_reset').unbind('click').bind('click', function(){
                    if(confirm('<?php echo htmlspecialchars(__('Have you analyzed the situation pretty much carefully?\nWill you be satisfied with yourself after that?',
                        $this->name)); ?>'))
                    {
                        $('#<?php echo $this->name; ?>').find('input[name=action]').val('reset_rp_news_ticker_settings');
                        $('#<?php echo $this->name; ?>').submit();
                    }
                });
            });
            //]]>
        </script>
        <?php
    }

    function on_contentbox_1_content($data)
    {
        extract($data);
        ?>
        <table class="widefat">
            <thead>
                <tr>
                    <th scope="col" style="width:30%"><?php echo __('Description', $this->name); ?></th>
                    <th scope="col" style="width:70%"><?php echo __('Value', $this->name); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo __('Usage type', $this->name); ?></td>
                    <td><?php echo $this->select_usage_type(); ?></td>
                </tr>
                <tr class="alternate">
                    <td><?php echo __('Category IDs (comma separated))', $this->name); ?></td>
                    <td><?php echo $this->text_category_ids(); ?></td>
                </tr>
                <tr>
                    <td><?php echo __('Dates formatting', $this->name); ?></td>
                    <td><?php echo $this->text_date_format(); ?></td>
                </tr>
                <tr class="alternate">
                    <td><?php echo __('Limit latest posts', $this->name); ?></td>
                    <td><?php echo $this->text_post_limit(); ?></td>
                </tr>
                <tr>
                    <td><?php echo __('Scroller Styles', $this->name); ?></td>
                    <td><?php echo $this->textarea_css(); ?></td>
                </tr>
                <tr class="alternate">
                    <td><?php echo __('Explicit Content', $this->name); ?></td>
                    <td><?php echo $this->textarea_explicitcontent(); ?></td>
                </tr>
                <tr>
                    <td><?php echo __('Attempt to stop checking for this plugin\'s updates (works only if the plugin is activated)', $this->name); ?></td>
                    <td><?php echo $this->checkbox_no_updates(); ?></td>
                </tr>
                <tr class="alternate">
                    <td colspan="2">
                        <span style="float:left"><input class="button" type="button" id="rp_news_ticker_admin_option_reset" value="<?php
                            echo __('Reset to Defaults', $this->name); ?>" /></span>
                        <span style="float:right"><input class="button" type="submit" name="rp_news_ticker_admin_option_save" value="<?php
                            echo __('Save Changes', $this->name); ?>" /></span>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php
    }

    /*     * **************************** Options Elements ********************************************************************************************* */

    function checkbox_no_updates()
    {
        return sprintf('<input type="checkbox" name="updates_off"%s />',
            (!empty($this->options) && $this->options['updates_off'] ? ' checked="checked"' : ''));
    }

    function select_usage_type()
    {
        $usage_options = array(
            1 => array('label' =>
                __('from categories', $this->name) ),
            2 => array('label' =>
                __('from categories, randomized', $this->name) ),
            3 => array('label' =>
                __('explicit content', $this->name) ),
            4 => array('label' =>
                __('explicit content, randomized', $this->name) ),
            5 => array('label' =>
                __('both in randomized order', $this->name) ),
            6 => array('label' =>
                __('from categories, explicit content', $this->name) ),
            7 => array('label' =>
                __('explicit content, from categories', $this->name) ),
        );

        $str = '<select id="rpliscrollticker-usage_type" name="usage_type" >';
        foreach ($usage_options as $k => $v)
        {
            $str .= sprintf('<option value="%d"%s>%s</option>',
                $k,
                (isset($this->options['usage_type']) && $k == $this->options['usage_type'] ? ' selected="selected"' : ''),
                $v['label']
            );
        }
        $str .= '</select>';
        return $str;
    }

    function text_category_ids()
    {
        return sprintf('<input type="text" name="category_ids" value="%s" style="width:100%%" />',
            (!empty($this->options) && $this->options['category_ids'] ? htmlspecialchars(strval(stripslashes($this->options['category_ids']))) : ''))
        . '<br /><span class="description">'.__('Leave empty for all categories', $this->name).'</span>';
    }

    function text_date_format()
    {
        return sprintf('<input type="text" name="date_format" value="%s" style="width:16em" />',
            (!empty($this->options) && $this->options['date_format'] ? htmlspecialchars(strval(stripslashes($this->options['date_format']))) : ''))
        . '<br /><span class="description">'.__('PHP <em>date()</em> format used', $this->name).'</span>';
    }

    function text_post_limit()
    {
        return sprintf('<input type="text" name="post_limit" value="%s" style="width:4em;text-align:right;" />',
            (!empty($this->options) && $this->options['post_limit'] ? intval($this->options['post_limit']) : 0));
    }

    function textarea_css()
    {
        return sprintf('<textarea style="width:100%%;height:24em;font-family:\'Lucida Console\',Monaco,\'DejaVu Sans Mono\',\'Bitstream Vera Sans Mono\',\'Liberation Mono\',monospace;" name="css">%s</textarea>',
            (!empty($this->options) && $this->options['css'] ? htmlspecialchars(stripcslashes(trim(strval($this->options['css'])))) : ''));
    }

    function textarea_explicitcontent()
    {
        return sprintf('<textarea style="width:100%%;height:24em;font-size:small;font-family:\'Lucida Console\',Monaco,\'DejaVu Sans Mono\',\'Bitstream Vera Sans Mono\',\'Liberation Mono\',monospace;" name="explicitcontent">%s</textarea>',
            (!empty($this->options) && $this->options['explicitcontent'] ? htmlspecialchars(stripcslashes(trim(strval($this->options['explicitcontent'])))) : ''))
            . '<br /><span class="description">'
            . __('If this field is non-empty and proper settings are set, the scroller tries to use "li" elements from it.', $this->name).'<br />'
            . __('Best understood format for the content is:', $this->name).'<br />'
            . htmlspecialchars(__('<li><span>something</span><a href="something">something</a></li>', $this->name))
            .'</span>';
    }
}