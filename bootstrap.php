<?php
/*
    Plugin Name: Demonstrating AI Integration
    Description: Codes for testing the integration process with AI.
    Author: Hassan Mohammad
    Author URI: https://wa.me/201061237563
    Version: 1.1
    Requires at least: 6.6
    Requires PHP: 7.4
    License: GPLv2 or later
    Text Domain: demonstrating-ai-integration
*/
    
// Strict types for better type safety
declare(strict_types=1);

if ( !defined( 'ABSPATH' ) ) {
    die;
}

/**
 * Define plugin constants
 */
define('PLUGIN_VERSION', '1.0.0');
define('DAI_DIR', trailingslashit(plugin_dir_path(__FILE__)));
define('DAI_URI', trailingslashit(esc_url(plugin_dir_url(__FILE__))));
define('PLUGIN_COMPONENTS_DIR', DAI_DIR . 'Components/');

/**
 * Autoload plugin components
 */
function load_plugin_components(): void {
    if (!is_dir(PLUGIN_COMPONENTS_DIR)) {
        return;
    }

    $components = array_diff(scandir(PLUGIN_COMPONENTS_DIR), ['..', '.', '.DS_Store']);
    
    foreach ($components as $component) {
        $component_path = PLUGIN_COMPONENTS_DIR . $component;
        
        // Skip files and hidden directories
        if (!is_dir($component_path) || strpos($component, '.') === 0) {
            continue;
        }

        $bootstrap_file = $component_path . '/bootstrap.php';
        
        if (file_exists($bootstrap_file)) {
            require_once $bootstrap_file;
        }
    }
}

// Load components early
load_plugin_components();

/**
 * Main Plugin Class
 */
final class PluginBootstrap {
    use \AIintegration\Components\Ajax\Bootstrap;
    use \AIintegration\Components\Fields\Bootstrap;
    use \AIintegration\Components\Helpers\Bootstrap;
    use \AIintegration\Components\Posttype\Bootstrap;
    use \AIintegration\Components\CMB\Bootstrap;

    /**
     * Singleton instance
     * 
     * @var PluginBootstrap
     */
    private static $instance;

    /**
     * Get singleton instance
     * 
     * @return PluginBootstrap
     */
    public static function get_instance(): PluginBootstrap {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Private constructor for singleton pattern
     */
    private function __construct() {
        $this->initialize_hooks();
        $this->load_components();
    }

    /**
     * Initialize WordPress hooks
     */
    private function initialize_hooks(): void {
        // Register activation/deactivation hooks
        register_activation_hook(__FILE__, [$this, 'activate_plugin']);
        register_deactivation_hook(__FILE__, [$this, 'deactivate_plugin']);
    }

    /**
     * Load all plugin components
     */
    private function load_components(): void {
        if (!is_dir(PLUGIN_COMPONENTS_DIR)) {
            return;
        }

        $components = array_diff(scandir(PLUGIN_COMPONENTS_DIR), ['..', '.']);
        
        foreach ($components as $component) {
            $component_path = PLUGIN_COMPONENTS_DIR . $component;
            
            if (!is_dir($component_path)) {
                continue;
            }

            $bootstrap_file = $component_path . '/bootstrap.php';
            
            if (file_exists($bootstrap_file)) {
                require_once $bootstrap_file;
            }

            $init_method = strtolower($component) . '__construct';
            if (method_exists($this, $init_method)) {
                $this->$init_method();
            }
        }
    }

    /**
     * Plugin activation callback
     */
    public function activate_plugin(): void {
        // Flush rewrite rules
        flush_rewrite_rules();
        
        // Add default plugin options if needed
        // ...
    }

    /**
     * Plugin deactivation callback
     */
    public function deactivate_plugin(): void {
        // Clean up plugin options if needed
        // ...
    }

    /**
     * Prevent cloning
     */
    private function __clone() {}

    /**
     * Prevent unserialization
     */
    public function __wakeup() {
        throw new RuntimeException('Cannot unserialize singleton');
    }
}

// Initialize plugin

 PluginBootstrap::get_instance();
