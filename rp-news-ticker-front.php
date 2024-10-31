<?php
if (!function_exists('add_action'))
{
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

class rp_news_ticker_front
{

    var $php5run = false;
    var $name;
    var $dir;
    var $abspath;
    var $http;
    var $plugin;
    var $options;

    function &gt($type = 'core_instance')
    {
        static $instance = array();
        if (!$instance || !$instance[$type])
        {
            $instance[$type] = & new rp_news_ticker_front($type);
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

    function rp_news_ticker_front()
    {
        if (!$this->php5run)
        {
            $this->php5run = true;
            $this->__construct();
        }
    }

    function init()
    {
        wp_enqueue_script('liScroll', $this->http . '/liScroll.min.js', array('jquery'), RP_NEWS_TICKER_VERSION, true);
        add_action('wp_head', array(&$this, 'wp_head'));
        add_shortcode('rp-news-ticker', array(&$this, 'shortcode_handler'));
        add_filter('widget_text', 'do_shortcode');
    }

    function shortcode_handler($atts, $content=null, $code='')
    {
        $this->display_frontend();
    }

    function display_frontend()
    {
        $options = $this->options;
        $options['explicitcontent'] = stripslashes($options['explicitcontent']);
        $explicit = explode("\n", str_replace("\r", "", $options['explicitcontent']));
            $categories = array();
            $in_config  = explode(',', $options['category_ids']);
            while($category = array_shift($in_config)){
                if(preg_match('/^[-]?[0-9]+$/', trim($category))){
                    $categories[] = $category;
                }
            }
        $myposts = !empty($categories)
            ? get_posts(sprintf('numberposts=%d&category=%s', $options['post_limit'], implode(',', $categories)))
            : get_posts(sprintf('numberposts=%d', $options['post_limit']));
        $in_categories = array();
        global $post;
        foreach($myposts as $post){
            setup_postdata($post);
            $in_categories[] = sprintf('<li><span>%s</span><a href="%s">%s</a></li>',
                apply_filters('the_time', get_the_time($options['date_format']), $options['date_format']),
                    apply_filters('the_permalink', get_permalink()), stripslashes(the_title('', '', false))
            );
        }
        $alldata = array();

/**

Krzysztof ✆ krzysztof.kuska@gmail.com 

I've got a small feature request for next release of RP Newsticker. Maybe on the
left side of the ticker there could be a place for something like : latest or
news or newest. I mean the same thing as in WP Newsticker (which I left in favor
for your plugin which thanks to you works as i want ;) )
*/

        switch($options['usage_type']){
            case 2:
                $alldata = $in_categories;
                shuffle($alldata);
                break;
            case 3:
                $alldata = $explicit;
                break;
            case 4:
                $alldata = $explicit;
                shuffle($alldata);
                break;
            case 5:
                $alldata = array_merge($in_categories, $explicit);
                shuffle($alldata);
                break;
            case 6:
                $alldata = array_merge($in_categories, $explicit);
                break;
            case 7:
                $alldata = array_merge($explicit, $in_categories);
                break;
            default:
                $alldata = $in_categories;
                break;
        }
        if(empty($alldata)){
            return;
        }
        ?>
        <ul id="rp-news-ticker">
            <?php echo implode("\n", $alldata); ?>
        </ul>
        <?php
    }

    function wp_head()
    {
        $options = get_option("rp_news_ticker");
	?>
<!-- rp news ticker support --><style><?php echo !isset($options['css']) ? '' : $this->compact_css($options['css']); ?></style><script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function($) {$("#rp-news-ticker").show().liScroll({travelocity: 0.04});});
//]]>
</script><!-- /rp news ticker support -->
	<?php
    }

    function compact_css($txt)
    {
        $txt = preg_replace ('/\'[^\'\\n\\r]*\'/', '', $txt);
        $txt = preg_replace ('/"[^"\\n\\r]*"/', '', $txt);
        $txt = preg_replace ('/\\/\\/[^\\n\\r]*[\\n\\r]/', '', $txt);
        $txt = preg_replace ('/\\/\\*[^*]*\\*+([^\\/][^*]*\\*+)*\\//', '', $txt);
        $txt = preg_replace('/^[ \t]*[\r\n]+/m', '', $txt);
        return $txt;
    }
}