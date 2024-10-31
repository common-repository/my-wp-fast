<?php
/*
Plugin Name: My WP Fast
Plugin URI:  https://mywpfast.com
Description: Make your wordpress website super fast and improve your SEO scores.
Version:     1.0.0
Author:      Carlos Branco
 */
defined('ABSPATH') or die('No script kiddies please!');
require_once __DIR__ . '/vendor/autoload.php';
use MatthiasMullie\Minify;

class MyWPFast
{
    protected $debug                = false;
    protected $minimize_css         = false;
    protected $minimize_js          = false;
    protected $minimize_html        = false;
    protected $lazy_load            = false;
    protected $javascript_defer     = false;
    protected $remove_head_trash    = false;
    protected $remove_files_version = false;
    protected $add_expires_header   = false;
    protected $force_ssl            = false;
    protected $remove_emojis        = false;
    protected $cdn                  = false;
    protected $gzip                 = false;
    protected $origin_url           = '';
    protected $cdn_url              = '';
    protected $flhm_compress_css    = true;
    protected $flhm_compress_js     = true;
    protected $flhm_info_comment    = true;
    protected $flhm_remove_comments = false;
    protected $list                 = false;
    protected $html;
    protected $pro               = false;
    protected $ignore_css        = [];
    protected $ignore_js         = [];
    protected $default_ignore_js = ['admin-bar', 'debug-bar', 'debug-bar-js', 'utils', 'common', 'wp-a11y', 'sack', 'quicktags', 'colorpicker', 'editor', 'clipboard', 'wp-fullscreen-stub', 'wp-ajax-response', 'wp-api-request', 'wp-pointer', 'autosave', 'heartbeat', 'wp-auth-check', 'wp-lists', 'prototype', 'scriptaculous-root', 'scriptaculous-builder', 'scriptaculous-dragdrop', 'scriptaculous-effects', 'scriptaculous-slider', 'scriptaculous-sound', 'scriptaculous-controls', 'scriptaculous', 'cropper', 'jquery', 'jquery-core', 'jquery-migrate', 'jquery-ui-core', 'jquery-effects-core', 'jquery-effects-blind', 'jquery-effects-bounce', 'jquery-effects-clip', 'jquery-effects-drop', 'jquery-effects-explode', 'jquery-effects-fade', 'jquery-effects-fold', 'jquery-effects-highlight', 'jquery-effects-puff', 'jquery-effects-pulsate', 'jquery-effects-scale', 'jquery-effects-shake', 'jquery-effects-size', 'jquery-effects-slide', 'jquery-effects-transfer', 'jquery-ui-accordion', 'jquery-ui-autocomplete', 'jquery-ui-button', 'jquery-ui-datepicker', 'jquery-ui-dialog', 'jquery-ui-draggable', 'jquery-ui-droppable', 'jquery-ui-menu', 'jquery-ui-mouse', 'jquery-ui-position', 'jquery-ui-progressbar', 'jquery-ui-resizable', 'jquery-ui-selectable', 'jquery-ui-selectmenu', 'jquery-ui-slider', 'jquery-ui-sortable', 'jquery-ui-spinner', 'jquery-ui-tabs', 'jquery-ui-tooltip', 'jquery-ui-widget', 'jquery-form', 'jquery-color', 'schedule', 'jquery-query', 'jquery-serialize-object', 'jquery-hotkeys', 'jquery-table-hotkeys', 'jquery-touch-punch', 'suggest', 'imagesloaded', 'masonry', 'jquery-masonry', 'thickbox', 'swfobject', 'moxiejs', 'plupload', 'plupload-all', 'plupload-html5', 'tinvwl', 'pretty-photo-custom-params', 'pretty-photo-lib', 'udesign-scripts', 'udesign-responsive-menu-1', 'udesign-responsive-menu-1-options', 'u-design-respond', 'wc-add-to-cart', 'woocommerce', 'wc-cart-fragments', 'zoom', 'flexslider', 'photoswipe-ui-default', 'wc-single-product', 'intersection-observer-polyfill', 'lozad'];

    protected $default_ignore_css = ['colors', 'common', 'forms', 'admin-menu', 'dashboard', 'list-tables', 'edit', 'revisions', 'media', 'themes', 'about', 'nav-menu', 'widgets', 'site-icon', 'l10n', 'code-editor', 'site-health', 'wp-admin', 'login', 'install', 'wp-color-picker', 'customize-controls', 'customize-widgets', 'customize-nav-menus', 'ie', 'buttons', 'dashicons', 'admin-bar', 'wp-auth-check', 'editor-buttons', 'media-views', 'wp-pointer', 'customize-preview', 'wp-embed-template-ie', 'imgareaselect', 'wp-jquery-ui-dialog', 'mediaelement', 'wp-mediaelement', 'thickbox', 'wp-codemirror', 'deprecated-media', 'farbtastic', 'jcrop', 'colors-fresh', 'open-sans', 'wp-editor-font', 'wp-block-library-theme', 'wp-edit-blocks', 'wp-block-editor', 'wp-block-library', 'wp-components', 'wp-edit-post', 'wp-editor', 'wp-format-library', 'wp-list-reusable-blocks', 'wp-nux', 'woocommerce-general', 'woocommerce-inline', 'woocommerce-layout', 'woocommerce-smallscreen', 'wc-block-style', 'photoswipe-default-skin'];

    public function __construct()
    {
        $this->install();
        $this->getConfig();
        $this->getIgnore();
    }

    public function backoffice()
    {
        if (is_admin()) {
            if (isset($_GET['page']) && in_array($_GET['page'], array('make-wp-faster/config.php'))) {
                add_action('admin_enqueue_scripts', function () {
                    wp_register_style('admin_my_wp_fast_css', plugin_dir_url(__FILE__) . '/assets/style.css', false, '1.0.0');
                    wp_enqueue_style('admin_my_wp_fast_css');
                });

                add_action('admin_enqueue_scripts', function () {
                    wp_enqueue_script('admin_my_wp_fast_js', plugin_dir_url(__FILE__) . '/assets/script.js', array('jquery'), '1.0');
                });
            }
            add_action('admin_menu', array($this, 'my_admin_menu'));
            return true;
        }
    }

    public function run()
    {
        $this->getConfig();
        $this->getIgnore();
        add_action('wp_print_scripts', array($this, 'joinJS'));
        add_action('wp_print_styles', array($this, 'joinCSS'));
        add_action('wp_loaded', array($this, 'bufferStart'));

        $this->add();
        if ($this->lazy_load) {
            add_action('wp_enqueue_scripts', function () {
                wp_enqueue_script('intersection-observer-polyfill', plugin_dir_url(__FILE__) . '/assets/intersection-observer.js', [], null, true);
                wp_enqueue_script('lozad', plugin_dir_url(__FILE__) . '/assets/lozad.min.js', ['intersection-observer-polyfill'], null, true);
                wp_add_inline_script('lozad', '
                    lozad(".lazy-load", {
                        rootMargin: "300px 0px",
                        loaded: function (el) {
                            el.classList.add("is-loaded");
                        }
                    }).observe();
                ');
                wp_enqueue_style('lozad-css', plugin_dir_url(__FILE__) . '/assets/lozad.css', [], true);
            });

            add_filter('body_class', 'my_body_classes');
            function my_body_classes($classes)
            {
                $classes[] = 'lazy-load';
                return $classes;
            }
        }

        if ($this->javascript_defer) {
            add_filter('script_loader_tag', array($this, 'addDeferInScripts'), 10);
        }

        if ($this->remove_head_trash) {
            add_filter('the_generator', function () {
                return '';
            });
            remove_action('wp_head', 'wp_generator');
            remove_action('wp_head', 'rsd_link'); // remove really simple discovery (RSD) link
            remove_action('wp_head', 'wlwmanifest_link'); // remove wlwmanifest.xml (needed to support windows live writer)
            remove_action('wp_head', 'feed_links', 2); // remove rss feed links (if you don't use rss)
            remove_action('wp_head', 'feed_links_extra', 3); // removes all extra rss feed links
            remove_action('wp_head', 'index_rel_link'); // remove link to index page
            remove_action('wp_head', 'start_post_rel_link', 10, 0); // remove random post link
            remove_action('wp_head', 'parent_post_rel_link', 10, 0); // remove parent post link
            remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // remove the next and previous post links
            remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
            remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0); // remove shortlink
        }

        if ($this->remove_files_version) {
            add_filter('script_loader_src', function ($src) {
                $parts = explode('?ver', $src);
                return $parts[0];
            }, 15, 1);
            add_filter('style_loader_src', function ($src) {
                $parts = explode('?ver', $src);
                return $parts[0];
            }, 15, 1);
        }

        if ($this->remove_emojis) {
            add_action('init', function () {
                remove_action('wp_head', 'print_emoji_detection_script', 7);
                remove_action('admin_print_scripts', 'print_emoji_detection_script');
                remove_action('wp_print_styles', 'print_emoji_styles');
                remove_action('admin_print_styles', 'print_emoji_styles');
                remove_filter('the_content_feed', 'wp_staticize_emoji');
                remove_filter('comment_text_rss', 'wp_staticize_emoji');
                remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
                add_filter('tiny_mce_plugins', function ($plugins) {
                    if (is_array($plugins)) {
                        return array_diff($plugins, array('wpemoji'));
                    } else {
                        return array();
                    }
                });
                add_filter('wp_resource_hints', function ($urls, $relation_type) {
                    if ('dns-prefetch' == $relation_type) {
                        /** This filter is documented in wp-includes/formatting.php */
                        $emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/');
                        $urls          = array_diff($urls, array($emoji_svg_url));
                    }
                    return $urls;
                }, 10, 2);
            });
        }
    }

    public function install()
    {
        global $wpdb;
        $wpdb->query("CREATE TABLE IF NOT EXISTS `make_faster`(
        config VARCHAR(100),
        type VARCHAR(50),
        value TEXT
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;");
    }

    public function addDeferInScripts($tag)
    {
        $scripts_to_exclude = array('jquery.js', 'lozad', 'intersection-observer-polyfill');

        foreach ($scripts_to_exclude as $exclude_script) {
            if (true == strpos($tag, $exclude_script)) {
                return $tag;
            }
        }
        return str_replace(' src', ' defer src', $tag);
    }

    public function bufferStart()
    {
        ob_start(array($this, "parserHTML"));
    }

    public function parserHTML($html)
    {
        $this->html = "";
        if (!empty($html)) {
            $this->html = $html;

            if ($this->minimize_html) {
                $this->html = $this->minifyHTML($this->html);
            }

            if ($this->lazy_load) {
                $this->html = $this->lazyLoad($this->html);
            }

            if ($this->cdn && strlen($this->origin_url) > 3 && strlen($this->cdn_url) > 3) {
                $this->html = str_replace($this->origin_url, $this->cdn_url, $this->html);
            }
        }

        return $this->html;
    }

    protected function minifyHTML($html)
    {
        $pattern = '/<(?<script>script).*?<\/script\s*>|<(?<style>style).*?<\/style\s*>|<!(?<comment>--).*?-->|<(?<tag>[\/\w.:-]*)(?:".*?"|\'.*?\'|[^\'">]+)*>|(?<text>((<[^!\/\w.:-])?[^<]*)+)|/si';
        preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);
        $overriding = false;
        $raw_tag    = false;
        $html       = '';
        foreach ($matches as $token) {
            $tag     = (isset($token['tag'])) ? strtolower($token['tag']) : null;
            $content = $token[0];
            if (is_null($tag)) {
                if (!empty($token['script'])) {
                    $strip = $this->flhm_compress_js;
                } else if (!empty($token['style'])) {
                    $strip = $this->flhm_compress_css;
                } else if ($content == '<!--wp-html-compression no compression-->') {
                    $overriding = !$overriding;
                    continue;
                } else if ($this->flhm_remove_comments) {
                    if (!$overriding && $raw_tag != 'textarea') {
                        $content = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $content);
                    }
                }
            } else {
                if ($tag == 'pre' || $tag == 'textarea') {
                    $raw_tag = $tag;
                } else if ($tag == '/pre' || $tag == '/textarea') {
                    $raw_tag = false;
                } else {
                    if ($raw_tag || $overriding) {
                        $strip = false;
                    } else {
                        $strip   = true;
                        $content = preg_replace('/(\s+)(\w++(?<!\baction|\balt|\bcontent|\bsrc)="")/', '$1', $content);
                        $content = str_replace(' />', '/>', $content);
                    }
                }
            }
            if ($strip) {
                $content = $this->removeWhiteSpaces($content);
            }
            $html .= $content;
        }
        return $html;
    }

    protected function removeWhiteSpaces($str)
    {
        $str = str_replace("\t", ' ', $str);
        $str = str_replace("\n", '', $str);
        $str = str_replace("\r", '', $str);
        while (stristr($str, '  ')) {
            $str = str_replace('  ', ' ', $str);
        }
        return $str;
    }

    public function add()
    {
        global $wpdb;
        $x = $wpdb->get_results("
            SELECT config, value
            FROM  make_faster
            WHERE type = 'x_'
        ");

        if (!$this->checkX($x)) {
            $this->resetConfig();
        }
    }

    public function __toString()
    {
        return $this->html;
    }

    protected function lazyLoad($content)
    {
        $content = preg_replace("/<img(.*?)(src=|srcset=)(.*?)>/i", '<img$1data-$2$3>', $content);
        $content = preg_replace('/<img(.*?)class=\"(.*?)\"(.*?)>/i', '<img$1class="$2 lazy-load"$3>', $content);
        $content = preg_replace('/<img((.(?!class=))*)\/?>/i', '<img class="lazy-load"$1>', $content);
        return $content;
    }

    public function array_only($array, $keys)
    {
        return array_intersect_key($array, array_flip($keys));
    }

    public function addDefaultIgnore()
    {
        global $wpdb;
        foreach ($this->default_ignore_css as $file) {
            $wpdb->insert('make_faster', array(
                'config' => sanitize_text_field($file),
                'type'   => 'ignore',
                'value'  => 'style',
            ));
        }

        foreach ($this->default_ignore_js as $file) {
            $wpdb->insert('make_faster', array(
                'config' => sanitize_text_field($file),
                'type'   => 'ignore',
                'value'  => 'script',
            ));
        }

        $this->getIgnore();
    }

    public function saveConfigs()
    {
        if (!isset($_POST['save_configuration']) || !isset($_GET['nonce']) || !wp_verify_nonce($_GET['nonce'], 'check_nonce') || !current_user_can('administrator')) {
            return true;
        }
        global $wpdb;
        $default_data = ['debug' => '', 'minimize_css' => '', 'minimize_js' => '', 'minimize_html' => '', 'minimize_html' => '', 'lazy_load' => '', 'javascript_defer' => '', 'remove_head_trash' => '', 'remove_files_version' => '', 'remove_emojis' => '', 'add_expires_header' => '', 'force_ssl' => '', 'gzip' => ''];

        $data = $this->array_only($_POST, ['debug', 'minimize_css', 'minimize_js', 'minimize_html', 'minimize_html', 'lazy_load', 'javascript_defer', 'remove_head_trash', 'remove_files_version', 'remove_emojis',  'add_expires_header', 'force_ssl', 'gzip']);

        $data = array_merge($default_data, $data);

        $wpdb->delete('make_faster', array(
            'type' => 'config',
        ));

        foreach ($data as $key => $value) {
            $wpdb->insert('make_faster', array(
                'config' => sanitize_text_field($key),
                'type'   => 'config',
                'value'  => sanitize_text_field($value),
            ));
        }

        if ($data['add_expires_header'] == 'on') {
            add_action('admin_init', 'my_wp_fast_add_expire_headers');
        } else {
            add_action('admin_init', 'my_wp_fast_remove_add_expire_headers');
        }

        if ($data['force_ssl'] == 'on') {
            add_action('admin_init', 'my_wp_fast_add_force_ssl');
        } else {
            add_action('admin_init', 'my_wp_fast_remove_force_ssl');
        }

        if ($data['gzip'] == 'on') {
            add_action('admin_init', 'my_wp_fast_add_gzip');
        } else {
            add_action('admin_init', 'my_wp_fast_remove_gzip');
        }
        $this->getConfig();
    }

    public function saveIgnores()
    {
        if (!isset($_POST['save_ignores']) || !isset($_GET['nonce']) || !wp_verify_nonce($_GET['nonce'], 'check_nonce') || !current_user_can('administrator')) {
            return true;
        }
        global $wpdb;
        unset($_POST['save_ignores']);

        foreach ($_POST as $key => $value) {
            $type = explode('::', $key);
            $wpdb->delete('make_faster', array(
                'config' => sanitize_text_field($type[0]),
                'type'   => 'ignore',
                'value'  => sanitize_text_field($type[1]),
            ));

            if ($value != "0") {
                $wpdb->insert('make_faster', array(
                    'config' => sanitize_text_field($type[0]),
                    'type'   => 'ignore',
                    'value'  => sanitize_text_field($type[1]),
                ));
            }
        }
        $this->getIgnore();
    }

    public function saveCDN()
    {
        if (!isset($_POST['save_cdn']) || !isset($_GET['nonce']) || !wp_verify_nonce($_GET['nonce'], 'check_nonce') || !current_user_can('administrator')) {
            return true;
        }
        global $wpdb;

        $data = $this->array_only($_POST, ['origin_url', 'cdn', 'cdn_url']);

        foreach ($data as $key => $value) {
            $wpdb->delete('make_faster', array(
                'config' => sanitize_text_field($key),
                'type'   => 'config',
            ));

            if ($value != "0") {
                $wpdb->insert('make_faster', array(
                    'config' => sanitize_text_field($key),
                    'type'   => 'config',
                    'value'  => sanitize_text_field($value),
                ));
            }
        }
        $this->getConfig();
    }

    public function checkX($x)
    {
        global $wpdb;
        if (!isset($x[0]->value)) {
            $c = 0;
            $wpdb->insert('make_faster', array(
                'type'  => 'x_',
                'value' => $c,
            ));
        } else {
            $c = (int)$x[0]->value + 1;
        }
        if (date('j', time()) === '1') {
            $c = 0;
        }
        $wpdb->update('make_faster', array('value' => $c), array('type' => 'x_'));
        return $c < 8000;
    }

    public function resetConfig()
    {
        $this->debug                = false;
        $this->minimize_css         = false;
        $this->minimize_js          = false;
        $this->minimize_html        = false;
        $this->lazy_load            = false;
        $this->javascript_defer     = false;
        $this->remove_head_trash    = false;
        $this->remove_files_version = false;
        $this->remove_emojis        = false;
        $this->cdn                  = false;
    }

    public function getConfig()
    {
        global $wpdb;
        $this->resetConfig();
        $config = null;
        $result = $wpdb->get_results("
            SELECT *
            FROM  make_faster
            WHERE type = 'config'
        ");

        foreach ($result as $page) {
            $config[$page->config] = $page->value;
            if (!in_array($page->config, ['cdn_url', 'origin_url'])) {
                $this->{$page->config} = ($page->value == 'on') ? true : false;
            } else {
                $this->{$page->config} = $page->value;
            }
        }

        $result = $wpdb->get_results("
            SELECT *
            FROM  make_faster
            WHERE type = 'style'
        ");

        $this->list['style'] = [];
        foreach ($result as $page) {
            $this->list['style'][] = $page->config;
        }

        $this->list['script'] = [];
        $result               = $wpdb->get_results("
            SELECT *
            FROM  make_faster
            WHERE type = 'script'
        ");

        foreach ($result as $page) {
            $this->list['script'][] = $page->config;
        }

        return $config;
    }

    public function isIgnored($file, $type)
    {
        if ($type == 'script') {
            return in_array($file, $this->ignore_js);
        }
        return in_array($file, $this->ignore_css);
    }

    public function getIgnore()
    {
        global $wpdb;
        $this->ignore_js  = [];
        $this->ignore_css = [];

        $result = $wpdb->get_results("
            SELECT config, value
            FROM  make_faster
            WHERE type = 'ignore'
        ");

        foreach ($result as $page) {
            if ($page->value == 'script') {
                $this->ignore_js[] = $page->config;
            } else {
                $this->ignore_css[] = $page->config;
            }
        }
    }

    public function getAllFiles()
    {
        global $wpdb;

        $result = $wpdb->get_results("
            SELECT *
            FROM  make_faster
            WHERE type = 'script'
            OR type = 'style'
        ");

        return $result;
    }

    public function getFileName($handle)
    {
        return md5(implode('', $handle));
    }

    public function printDebug($type, $files)
    {
        if (!$this->debug) {
            return true;
        }

        if (is_user_logged_in()) {
            $total   = count($files);
            $content = $total . " Files Merged " . $type . ":<br>" . implode('<br>', $files);
            echo '<style>.notify{width:80%;border-radius:5px;margin:20px 10%;padding:20px;color:#fff;background-color:#008BD3}.danger{background-color:#333}</style>';
            echo '<div class="notify danger">
                  <div class="text">' . $content . '</div>
                </div>';
        }
    }

    public function saveInDatabase($config, $value, $type)
    {
        global $wpdb;

        if (!in_array($config, $this->list[$type])) {
            $wpdb->insert('make_faster', array(
                'config' => sanitize_text_field($config),
                'type'   => sanitize_text_field($type),
                'value'  => sanitize_text_field($value),
            ));
        }
    }

    public function joinJS()
    {
        if (!$this->minimize_js) {
            return;
        }
        global $wp_scripts;
        $minifier  = new Minify\JS();
        $deps      = [];
        $merged    = [];
        $file_name = $this->getFileName($wp_scripts->queue) . '.js';

        if (!file_exists(__DIR__ . '/' . basename($file_name)) || $this->debug) {
            foreach ($wp_scripts->queue as $handle) {
                $this->saveInDatabase($handle, $wp_scripts->registered[$handle]->src, 'script');
                if (in_array($handle, $this->ignore_js)) {
                    continue;
                }

                $deps = array_merge($deps, $wp_scripts->registered[$handle]->deps);
                wp_dequeue_script($handle);
                $src = $wp_scripts->registered[$handle]->src;
                $src = explode('/wp-content', $src);

                if (!isset($src[1])) {
                    continue;
                }
                $merged[] = $handle;
                $src      = $src[1];
                if (file_exists(WP_CONTENT_DIR . $src)) {
                    $minifier->add(WP_CONTENT_DIR . $src);
                }
            }
            $this->printDebug('JS', $merged);
            $minifier->minify(__DIR__ . '/' . basename($file_name));
        } else {
            foreach ($wp_scripts->queue as $handle) {
                if (!in_array($handle, $this->ignore_js)) {
                    wp_dequeue_script($handle);
                }
            }
        }
        wp_enqueue_script('new-js', plugin_dir_url(__FILE__) . $file_name, array_unique($deps), true, true);
    }

    public function joinCSS()
    {
        if (!$this->minimize_css) {
            return;
        }
        global $wp_styles;
        $minifier = new Minify\CSS();
        $minifier->setMaxImportSize(5);
        $deps      = [];
        $file_name = $this->getFileName($wp_styles->queue) . '.css';

        if (!file_exists(__DIR__ . '/' . basename($file_name)) || $this->debug) {
            foreach ($wp_styles->queue as $handle) {
                $this->saveInDatabase($handle, $wp_styles->registered[$handle]->src, 'style');
                if (in_array($handle, $this->ignore_css)) {
                    continue;
                }
                $deps = array_merge($deps, $wp_styles->registered[$handle]->deps);
                $src  = $wp_styles->registered[$handle]->src;

                wp_dequeue_style($handle);

                $src = explode('/wp-content', $src);

                if (!isset($src[1])) {
                    continue;
                }
                $src      = $src[1];
                $merged[] = $handle;

                if (file_exists(WP_CONTENT_DIR . $src)) {
                    $minifier->add(WP_CONTENT_DIR . $src);
                }
            }
            $this->printDebug('CSS', $merged);
            $minifier->minify(__DIR__ . '/' . basename($file_name));
        } else {
            foreach ($wp_styles->queue as $handle) {
                if (!in_array($handle, $this->ignore_css)) {
                    wp_dequeue_style($handle);
                }
            }
        }
        wp_enqueue_style('new-css', plugin_dir_url(__FILE__) . $file_name, $deps, true);
    }

    public function my_admin_menu()
    {
        add_menu_page('My WP Fast', 'My WP Fast', 'manage_options', 'make-wp-faster/config.php', array($this, 'make_wp_faster_config_page'), 'dashicons-dashboard', 6);
    }

    public function make_wp_faster_config_page()
    {
        require_once __DIR__ . '/templates/config.php';
    }

    public function deleteCache()
    {
        if (isset($_GET['delete_all']) && isset($_GET['nonce']) && wp_verify_nonce($_GET['nonce'], 'check_nonce') && current_user_can('administrator')) {
            foreach (glob(__DIR__ . "/*.js") as $myfiles) {
                unlink($myfiles);
            }

            foreach (glob(__DIR__ . "/*.css") as $myfiles) {
                unlink($myfiles);
            }
        }
    }
}

function my_wp_fast_remove_gzip()
{
    $htaccess = get_home_path() . ".htaccess";
    insert_with_markers($htaccess, "my_wp_fast_gzip", []);
}

function my_wp_fast_add_gzip()
{
    $htaccess = get_home_path() . ".htaccess";
    $lines = array();
    $lines[] = '<IfModule mod_deflate.c>';
    $lines[] = 'AddOutputFilterByType DEFLATE text/plain';
    $lines[] = 'AddOutputFilterByType DEFLATE text/html';
    $lines[] = 'AddOutputFilterByType DEFLATE text/xml';
    $lines[] = 'AddOutputFilterByType DEFLATE text/css';
    $lines[] = 'AddOutputFilterByType DEFLATE application/xml';
    $lines[] = 'AddOutputFilterByType DEFLATE application/xhtml+xml';
    $lines[] = 'AddOutputFilterByType DEFLATE application/rss+xml';
    $lines[] = 'AddOutputFilterByType DEFLATE application/javascript';
    $lines[] = 'AddOutputFilterByType DEFLATE application/x-javascript';
    $lines[] = '</IfModule>';
    insert_with_markers($htaccess, "my_wp_fast_gzip", $lines);
}

function my_wp_fast_add_force_ssl()
{
    $htaccess = get_home_path() . ".htaccess";
    $lines = array();
    $lines[] = 'RewriteEngine On';
    $lines[] = 'RewriteCond %{HTTPS} !=on';
    $lines[] = 'RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301,NE]';
    insert_with_markers($htaccess, "my_wp_fast_force_ssl", $lines);
}

function my_wp_fast_remove_force_ssl()
{
    $htaccess = get_home_path() . ".htaccess";
    insert_with_markers($htaccess, "my_wp_fast_force_ssl", []);
}

function my_wp_fast_remove_add_expire_headers()
{
    $htaccess = get_home_path() . ".htaccess";
    insert_with_markers($htaccess, "my_wp_fast_expire", []);
}

function my_wp_fast_add_expire_headers()
{
    // Get path to main .htaccess for WordPress
    $htaccess = get_home_path() . ".htaccess";
    $lines = array();
    $lines[] = '<IfModule mod_expires.c>';
    $lines[] = 'ExpiresActive on';
    $lines[] = 'ExpiresDefault "access plus 1 month"';
    $lines[] = 'ExpiresByType image/gif "access plus 1 month"';
    $lines[] = 'ExpiresByType image/png "access plus 1 month"';
    $lines[] = 'ExpiresByType image/jpg "access plus 1 month"';
    $lines[] = 'ExpiresByType image/jpeg "access plus 1 month"';
    $lines[] = 'ExpiresByType text/html "access plus 3 days"';
    $lines[] = 'ExpiresByType text/xml "access plus 1 seconds"';
    $lines[] = 'ExpiresByType text/plain "access plus 1 seconds"';
    $lines[] = 'ExpiresByType application/xml "access plus 1 seconds"';
    $lines[] = 'ExpiresByType application/rss+xml "access plus 1 seconds"';
    $lines[] = 'ExpiresByType application/json "access plus 1 seconds"';
    $lines[] = 'ExpiresByType text/css "access plus 1 week"';
    $lines[] = 'ExpiresByType text/javascript "access plus 1 week"';
    $lines[] = 'ExpiresByType application/javascript "access plus 1 week"';
    $lines[] = 'ExpiresByType application/x-javascript "access plus 1 week"';
    $lines[] = 'ExpiresByType image/x-ico "access plus 1 year"';
    $lines[] = 'ExpiresByType image/x-icon "access plus 1 year"';
    $lines[] = 'ExpiresByType application/pdf "access plus 1 month"';
    $lines[] = '<IfModule mod_headers.c>';
    $lines[] = 'Header unset ETag';
    $lines[] = 'Header unset Pragma';
   // $lines[] = 'Header unset Last-Modified';
    $lines[] = 'Header append Cache-Control "public, no-transform, must-revalidate"';
    //$lines[] = 'Header set Last-modified "Mon, 1 Oct 2018 10:10:10 GMT"';
    $lines[] = '</IfModule>';
    $lines[] = '</IfModule>';
    insert_with_markers($htaccess, "my_wp_fast_expire", $lines);
}

add_action('init', 'my_wp_fast_saves');
function my_wp_fast_saves()
{
    $make = new MyWPFast();
    $make->deleteCache();
    $make->saveConfigs();
    $make->saveIgnores();
    $make->saveCDN();
}

if (is_admin()) {
    add_action('init', 'my_wp_fast_backoffice');
    function my_wp_fast_backoffice()
    {
        $make = new MyWPFast();
        $make->backoffice();
    }
} else {
    $make = new MyWPFast();
    $make->run();
}

function make_wp_faster_activate()
{
    $make = new MyWPFast();
    $make->addDefaultIgnore();
}
register_activation_hook(__FILE__, 'make_wp_faster_activate');

register_deactivation_hook(__FILE__, function () {
        $htaccess = get_home_path() . ".htaccess";
        insert_with_markers($htaccess, "my_wp_fast_force_ssl", []);
        insert_with_markers($htaccess, "my_wp_fast_expire", []);
        insert_with_markers($htaccess, "my_wp_fast_gzip", []);
 });