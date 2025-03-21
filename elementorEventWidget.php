<?php
/**
 * Plugin Name: Elementor Events Widget
 * Description: Custom Elementor widget to display upcoming events from custom post type
 * Version: 1.0.0
 * Author: Jay
 * Text Domain: elementor-events-widget
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Events Custom Post Type
 */
function eew_register_events_cpt() {
    $labels = array(
        'name'               => __('Events', 'elementor-events-widget'),
        'singular_name'      => __('Event', 'elementor-events-widget'),
        'add_new'            => __('Add New', 'elementor-events-widget'),
        'add_new_item'       => __('Add New Event', 'elementor-events-widget'),
        'edit_item'          => __('Edit Event', 'elementor-events-widget'),
        'new_item'           => __('New Event', 'elementor-events-widget'),
        'all_items'          => __('All Events', 'elementor-events-widget'),
        'view_item'          => __('View Event', 'elementor-events-widget'),
        'search_items'       => __('Search Events', 'elementor-events-widget'),
        'not_found'          => __('No events found', 'elementor-events-widget'),
        'not_found_in_trash' => __('No events found in Trash', 'elementor-events-widget'),
        'menu_name'          => __('Events', 'elementor-events-widget')
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'event'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-calendar',
        'supports'           => array('title', 'editor', 'thumbnail')
    );

    register_post_type('events', $args);
}
add_action('init', 'eew_register_events_cpt');

/**
 * Add custom meta box for event details
 */
function eew_add_event_meta_boxes() {
    add_meta_box(
        'eew_event_details',
        __('Event Details', 'elementor-events-widget'),
        'eew_event_details_callback',
        'events',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'eew_add_event_meta_boxes');

/**
 * Event details meta box callback
 */
function eew_event_details_callback($post) {
    wp_nonce_field('eew_save_event_details', 'eew_event_details_nonce');
    
    $event_date = get_post_meta($post->ID, '_event_date', true);
    
    ?>
    <p>
        <label for="event_date"><?php _e('Event Date:', 'elementor-events-widget'); ?></label>
        <input type="date" id="event_date" name="event_date" value="<?php echo esc_attr($event_date); ?>" class="widefat">
    </p>
    <?php
}

/**
 * Save event details
 */
function eew_save_event_details($post_id) {
    // Check if our nonce is set
    if (!isset($_POST['eew_event_details_nonce'])) {
        return;
    }

    // Verify the nonce
    if (!wp_verify_nonce($_POST['eew_event_details_nonce'], 'eew_save_event_details')) {
        return;
    }

    // If this is an autosave, we don't want to do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check the user's permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save the event date
    if (isset($_POST['event_date'])) {
        update_post_meta($post_id, '_event_date', sanitize_text_field($_POST['event_date']));
    }
}
add_action('save_post_events', 'eew_save_event_details');

/**
 * Register Elementor widget
 */
class Elementor_Events_Widget {
    /**
     * Plugin constructor
     */
    public function __construct() {
        add_action('elementor/widgets/widgets_registered', array($this, 'register_widgets'));
        add_action('elementor/elements/categories_registered', array($this, 'add_elementor_widget_categories'));
    }

    /**
     * Add a custom category for our widgets
     */
    public function add_elementor_widget_categories($elements_manager) {
        $elements_manager->add_category(
            'eew-category',
            [
                'title' => __('Event Widgets', 'elementor-events-widget'),
                'icon' => 'fa fa-calendar',
            ]
        );
    }

    /**
     * Register our widget
     */
    public function register_widgets() {
        // Using plugin_dir_path to get absolute path
        $widget_file = plugin_dir_path(__FILE__) . 'widgets/upcoming-events-widget.php';
        
        // Check if file exists before requiring it
        if (file_exists($widget_file)) {
            require_once($widget_file);
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Upcoming_Events_Widget());
        } else {
            // Add admin notice if widget file doesn't exist
            add_action('admin_notices', function() {
                echo '<div class="notice notice-error is-dismissible"><p>' . 
                     __('Elementor Events Widget Error: Widget file not found. Please ensure the widget file exists in the widgets folder.', 'elementor-events-widget') . 
                     '</p></div>';
            });
        }
    }
}

// Initialize the plugin
function eew_init() {
    // Check if Elementor is installed and activated
    if (!did_action('elementor/loaded')) {
        add_action('admin_notices', function() {
            $message = sprintf(
                __('Elementor Events Widget requires Elementor plugin to be active. %1$s%2$s%3$s', 'elementor-events-widget'),
                '<a href="' . esc_url(admin_url('plugins.php')) . '">',
                __('Go to Plugins page', 'elementor-events-widget'),
                '</a>'
            );
            echo '<div class="notice notice-warning is-dismissible"><p>' . $message . '</p></div>';
        });
        return;
    }
    
    // Create widgets directory if it doesn't exist
    $widgets_dir = plugin_dir_path(__FILE__) . 'widgets';
    if (!file_exists($widgets_dir)) {
        mkdir($widgets_dir, 0755, true);
    }
    
    new Elementor_Events_Widget();
}
add_action('plugins_loaded', 'eew_init');