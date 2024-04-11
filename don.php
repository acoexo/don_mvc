<?php
/*
Plugin Name: Donicus
Plugin URI: https://donicus.com/
Description: Plugin for integrating Donicus services into WordPress.
Version: a0.1 Dev
Author: Your Name
Author URI: https://example.com/
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: donicus
Domain Path: /languages
*/

if (!defined('ABSPATH')) {
    exit; // Evita que el archivo sea llamado directamente
}

require_once plugin_dir_path(__FILE__) . 'src/functions/DBInit.php';

final class Donicus_Plugin
{
    public $version = '0.3.0';
    private $container = array();

    public function __construct()
    {
        $this->define_constants();
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array('App\Functions\DBInit', 'deactivate'));
        register_uninstall_hook(__FILE__, array('App\Functions\DBInit', 'uninstall'));
        add_action('plugins_loaded', array($this, 'init_plugin'));
    }

    public function activate()
    {
        $dbInit = new App\Functions\DBInit(); 
        $dbInit::activate();
        shell_exec('npm run init');
    }

    public static function init()
    {
        static $instance = false;
        if (!$instance) {
            $instance = new Donicus_Plugin();
        }
        return $instance;
    }

    public function __get($prop)
    {
        if (array_key_exists($prop, $this->container)) {
            return $this->container[$prop];
        }
        return $this->{$prop};
    }

    public function __isset($prop)
    {
        return isset($this->{$prop}) || isset($this->container[$prop]);
    }

    public function define_constants()
    {
        define('DONICUS_VERSION', $this->version);
        define('DONICUS_FILE', __FILE__);
        define('DONICUS_PATH', dirname(DONICUS_FILE));
        define('DONICUS_INCLUDES', DONICUS_PATH . '/includes');
        define('DONICUS_URL', plugins_url('', DONICUS_FILE));
        define('DONICUS_ASSETS', DONICUS_URL . '/assets');
    }

    public function init_plugin()
    {
        $this->includes();
        $this->init_hooks();
    }

    public function includes()
    {
        require_once DONICUS_INCLUDES . '/Assets.php';
        if ($this->is_request('admin')) {
            require_once DONICUS_INCLUDES . '/Admin.php';
        }
    }

    public function init_hooks()
    {
        add_action('init', array($this, 'init_classes'));
        add_action('init', array($this, 'localization_setup'));
        add_action('init', array($this, 'donicus_register_blocks'));
        add_action('enqueue_block_editor_assets', array($this, 'donicus_enqueue_block_editor_assets'));
        add_action('wp_footer', array($this, 'donicus_insert_footer'));
        add_filter('block_categories_all', array($this, 'donicus_add_new_block_category'), 2, 2);
    }

    public function init_classes()
    {
        if ($this->is_request('admin')) {
            $this->container['admin'] = new App\Admin();
            // Aquí podrías llamar a métodos del controlador relacionados con la administración
        }
        $this->container['assets'] = new App\Assets();
    }

    public function localization_setup()
    {
        load_plugin_textdomain('donicus', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    private function is_request($type)
    {
        switch ($type) {
            case 'admin':
                return is_admin();
            case 'ajax':
                return defined('DOING_AJAX');
            case 'rest':
                return defined('REST_REQUEST');
            case 'cron':
                return defined('DOING_CRON');
            case 'frontend':
                return (!is_admin() || defined('DOING_AJAX')) && !defined('DOING_CRON');
        }
        return false;
    }

    // Agrega métodos adicionales para controladores y modelos aquí

    // Método para registrar bloques
    // Método para encolar activos
    // Método para insertar contenido en el pie de página
    // Método para añadir nuevas categorías de bloques

}

$donicus_plugin = Donicus_Plugin::init();
