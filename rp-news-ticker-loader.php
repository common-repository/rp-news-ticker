<?php
if (!function_exists('add_action'))
{
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

class rp_news_ticker
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
            $instance[$type] = & new rp_news_ticker($type);
        }
        return $instance[$type];
    }

    function __construct()
    {
        $this->php5run  = true;
        $this->name     = 'rp-news-ticker';
        $this->dir      = basename(dirname(__FILE__));
        $this->http     = WP_PLUGIN_URL . '/' . $this->name;
        $this->abspath  = dirname(__FILE__);
        $this->plugin   = plugin_basename(dirname(__FILE__).'/rp-news-ticker.php');
        $this->options  = get_option('rp_news_ticker');
    }

    function rp_news_ticker()
    {
        if (!$this->php5run)
        {
            $this->php5run = true;
            $this->__construct();
        }
    }

    function add_textdomain()
    {
        load_plugin_textdomain($this->name, false, $this->dir);
    }

    function init()
    {
        $locale_file = $this->abspath . DIRECTORY_SEPARATOR . $this->name . '-' . get_locale() . '.mo';
        if (file_exists($locale_file))
        {
            load_plugin_textdomain('rp-news-ticker', false, $this->plugin . DIRECTORY_SEPARATOR);
        }
        add_action('init', array($this, 'add_textdomain'));
        if (is_admin())
        {
            if($this->options['updates_off'])
            {
                add_filter('http_request_args', array(&$this, 'prevent_update_check'), 10, 2);
            }
            add_filter("plugin_action_links_". $this->plugin, array(&$this, 'settings_link'));
            $admin = $this->get_class('admin');
            $admin->init();
        }
        else
        {
            $front = $this->get_class('front');
            $front->init();            
            function rp_news_ticker()
            {
                $front = rp_news_ticker_front::gt();
                $front->display_frontend();
            }
        }
    }

    function &get_class($class)
    {
        $class = 'rp_news_ticker_' . $class;
        $file = str_replace('_', '-', $class) . '.php';
        if (!class_exists($class))
        {
            require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . $file;
        }
        $intsance = $class::gt();
        return $intsance;
    }

    function settings_link($links)
    {
		$settings_link = '<a href="options-general.php?page='.basename($this->plugin).'">Settings</a>';
		array_unshift($links, $settings_link);
		return $links;
	}

    function prevent_update_check($r, $url)
    {
        if (0 === strpos($url, 'http://api.wordpress.org/plugins/update-check/'))
        {
            $plugins = unserialize($r['body']['plugins']);
            unset($plugins->plugins[$this->plugin]);
            unset($plugins->active[array_search($this->plugin, $plugins->active)]);
            $r['body']['plugins'] = serialize($plugins);
        }
        return $r;
    }
      
}