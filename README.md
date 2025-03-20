# Elementor Events Widget

A WordPress plugin that adds a custom Elementor widget to display upcoming events from a custom post type.

## Description

Elementor Events Widget enhances your WordPress site with a powerful yet easy-to-use events management system. The plugin creates a custom 'Events' post type and integrates with Elementor page builder to provide a widget that displays your upcoming events beautifully on any page.

### Key Features

- **Custom Events Post Type** - Dedicated section in your WordPress admin for managing events
- **Event Date Field** - Easy-to-use date picker for scheduling events
- **Elementor Integration** - Seamless integration with Elementor page builder
- **Customizable Widget** - Control the appearance, number of events, and styling
- **Upcoming Events Filter** - Automatically shows only future events sorted by date
- **Responsive Design** - Looks great on all devices

## Installation

### Manual Installation

1. Download the plugin zip file
2. Navigate to your WordPress admin area and go to Plugins → Add New
3. Click the "Upload Plugin" button at the top of the page
4. Choose the plugin zip file and click "Install Now"
5. After installation, click "Activate Plugin"

### Direct Installation from WordPress Repository

1. In your WordPress admin, go to Plugins → Add New
2. Search for "Elementor Events Widget"
3. Click "Install Now"
4. After installation, click "Activate"

## Requirements

- WordPress 5.0 or higher
- Elementor 2.0.0 or higher
- PHP 7.0 or higher

## Usage

### Adding Events

1. From your WordPress admin dashboard, navigate to "Events" → "Add New"
2. Enter the event title and description in the editor
3. Set the event date using the date picker in the "Event Details" meta box
4. Add a featured image (optional)
5. Publish your event

### Displaying Events on Your Site

1. Edit a page with Elementor
2. Look for the "Upcoming Events" widget in the "Event Widgets" category
3. Drag the widget to your desired section
4. Customize the widget settings:
   - Set the widget title
   - Choose how many events to display
   - Adjust colors and typography
5. Save and publish your page

## Customization

### Widget Options

- **Title** - Set a custom heading for your events list
- **Number of Events** - Control how many events to display (1-20)
- **Title Color** - Change the color of the widget heading
- **Event Title Color** - Set the color for individual event titles
- **Event Date Color** - Customize the appearance of event dates
- **Typography** - Control the font family, size, weight, and more

## Frequently Asked Questions

### Can I display past events?

By default, the widget only shows upcoming events. To display past events, you would need to modify the query in the widget's render method.

### Can I add more fields to events?

Yes, you can extend the plugin to add more custom fields to the Events post type by modifying the code or using a custom fields plugin like Advanced Custom Fields.

### Does this work with other page builders?

Currently, the plugin is designed specifically for Elementor. Support for other page builders may be added in future versions.

## Support

If you encounter any issues or have questions about using the plugin, please:

1. Check the documentation
2. Search existing support topics
3. Open a new support ticket if needed

## Changelog

### 1.0.0
- Initial release

## License

This plugin is licensed under the GPL v2 or later.