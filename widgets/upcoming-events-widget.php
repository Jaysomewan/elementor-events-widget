<?php
/**
 * Upcoming Events Widget Class
 */
class Upcoming_Events_Widget extends \Elementor\Widget_Base {
    /**
     * Get widget name
     */
    public function get_name() {
        return 'upcoming_events';
    }

    /**
     * Get widget title
     */
    public function get_title() {
        return __('Upcoming Events', 'elementor-events-widget');
    }

    /**
     * Get widget icon
     */
    public function get_icon() {
        return 'eicon-calendar';
    }

    /**
     * Get widget categories
     */
    public function get_categories() {
        return ['eew-category'];
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'elementor-events-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __('Title', 'elementor-events-widget'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Upcoming Events', 'elementor-events-widget'),
                'placeholder' => __('Enter your title', 'elementor-events-widget'),
            ]
        );

        $this->add_control(
            'number_of_events',
            [
                'label' => __('Number of Events', 'elementor-events-widget'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 20,
                'step' => 1,
                'default' => 5,
            ]
        );

        $this->end_controls_section();

        // Style Tab
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Style', 'elementor-events-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'elementor-events-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eew-widget-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __('Title Typography', 'elementor-events-widget'),
                'selector' => '{{WRAPPER}} .eew-widget-title',
            ]
        );

        $this->add_control(
            'event_title_color',
            [
                'label' => __('Event Title Color', 'elementor-events-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eew-event-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'event_date_color',
            [
                'label' => __('Event Date Color', 'elementor-events-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eew-event-date' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output on the frontend
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Get today's date
        $today = date('Y-m-d');
        
        // Query for upcoming events
        $args = array(
            'post_type' => 'events',
            'posts_per_page' => $settings['number_of_events'],
            'meta_key' => '_event_date',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => '_event_date',
                    'value' => $today,
                    'compare' => '>=',
                    'type' => 'DATE'
                )
            )
        );
        
        $events_query = new WP_Query($args);
        
        echo '<div class="eew-events-widget">';
        
        if ($settings['title']) {
            echo '<h2 class="eew-widget-title">' . esc_html($settings['title']) . '</h2>';
        }
        
        if ($events_query->have_posts()) {
            echo '<ul class="eew-events-list">';
            
            while ($events_query->have_posts()) {
                $events_query->the_post();
                $event_date = get_post_meta(get_the_ID(), '_event_date', true);
                $formatted_date = date_i18n(get_option('date_format'), strtotime($event_date));
                
                echo '<li class="eew-event-item">';
                echo '<h3 class="eew-event-title">' . get_the_title() . '</h3>';
                echo '<div class="eew-event-date">' . esc_html($formatted_date) . '</div>';
                echo '<div class="eew-event-description">' . wp_trim_words(get_the_content(), 30, '...') . '</div>';
                echo '<a href="' . get_permalink() . '" class="eew-event-link">' . __('Read More', 'elementor-events-widget') . '</a>';
                echo '</li>';
            }
            
            echo '</ul>';
        } else {
            echo '<p>' . __('No upcoming events found.', 'elementor-events-widget') . '</p>';
        }
        
        echo '</div>';
        
        wp_reset_postdata();
    }
}