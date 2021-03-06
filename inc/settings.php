<?php
/**
 * Crypto Ticker General Settings
 *
 * @category Wpau_Stock_Ticker_Settings
 * @package Crypto Ticker
 * @author Alexander Morris
 * @license https://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link https://blockchain.wtf
 */

if ( ! class_exists( 'Wpau_Stock_Ticker_Settings' ) ) {

	/**
	 * Wpau_Stock_Ticker_Settings Class provide general plugins settings page
	 *
	 * @category Class
	 * @package Crypto Ticker
	 * @author Alexander Morris
	 * @license https://www.gnu.org/copyleft/gpl.html GNU General Public License
	 * @link https://blockchain.wtf
	 */
	class Wpau_Stock_Ticker_Settings {
		/**
		 * Construct the plugin object
		 */
		public function __construct() {
			global $wpau_stockticker;

			// Get default values.
			$this->slug = $wpau_stockticker->plugin_slug;
			$this->option_name = $wpau_stockticker->plugin_option;
			$this->defaults = $wpau_stockticker->defaults; // get_option( $this->option_name );

			add_action( 'admin_init', array( &$this, 'register_settings' ) );
			add_action( 'admin_menu', array( &$this, 'add_menu' ) );
		} // END public function __construct

		/**
		 * Hook into WP's register_settings action hook
		 */
		public function register_settings() {
			global $wpau_stockticker;

			// Add general settings section.
			add_settings_section(
				'wpaust_general',
				__( 'General', 'wpaust' ),
				array( &$this, 'settings_general_section_description' ),
				$wpau_stockticker->plugin_slug
			);

			add_settings_field(
				$this->option_name . 'all_symbols',
				__( 'All Token Symbols', 'wpaust' ),
				array( &$this, 'settings_field_input_text' ),
				$wpau_stockticker->plugin_slug,
				'wpaust_general',
				array(
					'field'       => $this->option_name . '[all_symbols]',
					'description' => __( 'These tokens will be downloaded and saved to the database. See the list at the bottom of this page for all supported tokens.', 'wpaust' ),
					'class'       => 'widefat',
					'value'       => $this->defaults['all_symbols'],
				)
			);
	

			// --- Register setting General so $_POST handling is done ---
			register_setting(
				'wpaust_general',
				$this->option_name,
				array( &$this, 'sanitize_options' )
			);

			// Add default settings section.
			add_settings_section(
				'wpaust_default',
				__( 'Default', 'wpaust' ),
				array( &$this, 'settings_default_section_description' ),
				$wpau_stockticker->plugin_slug
			);
			// Add setting's fields.
			add_settings_field(
				$this->option_name . 'symbols',
				__( 'Show Symbols', 'wpaust' ),
				array( &$this, 'settings_field_input_text' ),
				$wpau_stockticker->plugin_slug,
				'wpaust_default',
				array(
					'field'       => $this->option_name . '[symbols]',
					'description' => __( 'These symbols will be displayed in the ticker. (Must be fetched in the set above.)', 'wpaust' ),
					'class'       => 'widefat',
					'value'       => $this->defaults['symbols'],
				)
			);
			add_settings_field(
				$this->option_name . 'show',
				__( 'Show Company as', 'wpaust' ),
				array( &$this, 'settings_field_select' ),
				$wpau_stockticker->plugin_slug,
				'wpaust_default',
				array(
					'field'       => $this->option_name . '[show]',
					'description' => sprintf(
						__( 'What to show as Company identifier by default for shortcodes if not provided shortcode parameter %s.', 'wpaust' ),
						"'show'"
					),
					'items'       => array(
						'name'   => __( 'Company Name', 'wpaust' ),
						'symbol' => __( 'Crypto Symbol', 'wpaust' ),
					),
					'value' => $this->defaults['show'],
				)
			);

			add_settings_field(
				$this->option_name . 'number_format',
				__( 'Number format', 'wpaust' ),
				array( &$this, 'settings_field_select' ),
				$wpau_stockticker->plugin_slug,
				'wpaust_default',
				array(
					'field'       => $this->option_name . '[number_format]',
					'description' => __( 'Select default number format', 'stock-quote' ),
					'items'       => array(
						'cd' => '0,000.00',
						'dc' => '0.000,00',
						'sd' => '0 000.00',
						'sc' => '0 000,00',
					),
					'value' => $this->defaults['number_format'],
					'class'       => 'regular-text',
				)
			);
			add_settings_field(
				$this->option_name . 'decimals',
				__( 'Decimal places', 'wpaust' ),
				array( &$this, 'settings_field_select' ),
				$wpau_stockticker->plugin_slug,
				'wpaust_default',
				array(
					'field'       => $this->option_name . '[decimals]',
					'description' => __( 'Select amount of decimal places for numbers', 'stock-quote' ),
					'items'       => array(
						'1' => __( 'One', 'stock-quote' ),
						'2' => __( 'Two', 'stock-quote' ),
						'3' => __( 'Three', 'stock-quote' ),
						'4' => __( 'Four', 'stock-quote' ),
					),
					'value' => $this->defaults['decimals'],
					'class'       => 'regular-text',
				)
			);
			// Color pickers.
			// Unchanged.
			add_settings_field(
				$this->option_name . 'quote_zero',
				__( 'Unchanged Quote', 'wpaust' ),
				array( &$this, 'settings_field_colour_picker' ),
				$wpau_stockticker->plugin_slug,
				'wpaust_default',
				array(
					'field'       => $this->option_name . '[zero]',
					'description' => __( 'Set colour for unchanged quote', 'wpaust' ),
					'value'       => $this->defaults['zero'],
				)
			);
			// Minus.
			add_settings_field(
				$this->option_name . 'quote_minus',
				__( 'Negative Change', 'wpaust' ),
				array( &$this, 'settings_field_colour_picker' ),
				$wpau_stockticker->plugin_slug,
				'wpaust_default',
				array(
					'field'       => $this->option_name . '[minus]',
					'description' => __( 'Set colour for negative change', 'wpaust' ),
					'value'       => $this->defaults['minus'],
				)
			);
			// Plus.
			add_settings_field(
				$this->option_name . 'quote_plus',
				__( 'Positive Change', 'wpaust' ),
				array( &$this, 'settings_field_colour_picker' ),
				$wpau_stockticker->plugin_slug,
				'wpaust_default',
				array(
					'field'       => $this->option_name . '[plus]',
					'description' => __( 'Set colour for positive change', 'wpaust' ),
					'value'       => $this->defaults['plus'],
				)
			);
			add_settings_field(
				$this->option_name . 'symbolchoice',
				__( 'Change Direction Icon For Ticker', 'wpaust' ),
				array( &$this, 'settings_field_select' ),
				$wpau_stockticker->plugin_slug,
				'wpaust_default',
				array(
					'field'       => $this->option_name . '[symbolchoice]',
					'description' => sprintf(
						__( 'Choose the icon to be used to indicate the change direction. See fontawesome.com for the actual icon images.', 'wpaust' ),
						"'show'"
					),
					'items'       => array(
						'arrow'   => __( 'fa-arrow', 'wpaust' ),
						'caret' => __( 'fa-caret', 'wpaust' ),
						'arrow-circle' => __( 'fa-arrow-circle', 'wpaust' ),
					),
					'value' => $this->defaults['symbolchoice'],
				)
			);

			add_settings_field(
				$this->option_name . 'currencychoice',
				__( 'Display Currency', 'wpaust' ),
				array( &$this, 'settings_field_select' ),
				$wpau_stockticker->plugin_slug,
				'wpaust_default',
				array(
					'field'       => $this->option_name . '[currencychoice]',
					'description' => sprintf(
						__( 'Choose the currency to display quotes in. Note: This may take some time to update the db after a change.', 'wpaust' ),
						"'show'"
					),
					'items'       => array(
						'usd'   => __( 'US Dollar', 'wpaust' ),
						'can' => __( 'Canadian Dollar', 'wpaust' ),
						'eur' => __( 'Euro', 'wpaust' ),
						'aus' => __( 'Australian Dollar', 'wpaust' ),
						'gbp' => __( 'British Pound', 'wpaust' ),
						'yen' => __( 'Chinese Yen', 'wpaust' ),
						'won' => __( 'South Korean Won', 'wpaust' ),
					),
					'value' => $this->defaults['currencychoice'],
				)
			);
			// --- Register setting Default so $_POST handling is done ---
			register_setting(
				'wpaust_default',
				$this->option_name,
				array( &$this, 'sanitize_options' )
			);

			// Add advanced settings section.
			add_settings_section(
				'wpaust_advanced',
				__( 'Advanced', 'wpaust' ),
				array( &$this, 'settings_advanced_section_description' ),
				$wpau_stockticker->plugin_slug
			);
			// Add setting's fields.
			// Ticker speed.
			add_settings_field(
				$this->option_name . 'speed',
				__( 'Ticker Speed', 'wpaust' ),
				array( &$this, 'settings_field_input_number' ),
				$wpau_stockticker->plugin_slug,
				'wpaust_advanced',
				array(
					'field'       => $this->option_name . '[speed]',
					'description' => __( 'Define speed of ticker scrolling in pixels per second (default is 50)', 'wpaust' ),
					'class'       => 'num small-text',
					'value'       => isset( $this->defaults['speed'] ) ? $this->defaults['speed'] : 50,
					'min'         => 10,
					'max'         => 200,
					'step'        => 1,
				)
			);
			add_settings_field(
				$this->option_name . 'template_title',
				__( 'Display Format (Title)', 'wpaust' ),
				array( &$this, 'settings_field_textarea' ),
				$wpau_stockticker->plugin_slug,
				'wpaust_advanced',
				array(
					'field'       => $this->option_name . '[template_title]',
					'description' => sprintf(
						__( 'Custom template for item title in ticker display. You can use macro keywords %1$s and %2$s mixed with HTML tags %3$s and/or %4$s.', 'wpaust' ),
						'%icon%, %company%',
						'%price%',
						'&lt;span&gt;, &lt;em&gt;',
						'&lt;strong&gt;'
					),
					'class' => 'widefat',
					'rows'  => 2,
					'value' => $this->defaults['template_title'],
				)
			);

			add_settings_field(
				$this->option_name . 'template_price',
				__( 'Display Format ( Price )', 'wpaust' ),
				array( &$this, 'settings_field_textarea' ),
				$wpau_stockticker->plugin_slug,
				'wpaust_advanced',
				array(
					'field'       => $this->option_name . '[template_price]',
					'description' => sprintf(
						__( 'Custom template for item price in ticker display. You can use macro keys %1$s and %2$s (Add "-, _, (, ), [, ], |, <, >" as desired for formatting) mixed with HTML tags %3$s and/or %4$s.', 'wpaust' ),
						'%price%, %volume%, %change%',
						'%changep%',
						'&lt;span&gt;, &lt;em&gt;',
						'&lt;strong&gt;'
					),
					'class' => 'widefat',
					'rows'  => 2,
					'value' => $this->defaults['template_price'],
				)
			);
			// Toggle for the WTF tag
			add_settings_field(
				$this->option_name . 'tag_enabled',
				__( 'Disable Blockchain.wtf Tail?', 'wpaust' ),
				array( &$this, 'settings_field_checkbox' ),
				$wpau_stockticker->plugin_slug,
				'wpaust_advanced',
				array(
					'field'       => $this->option_name . '[globalassets]',
					'description' => __( '', 'wpaust' ),
					'class'       => 'checkbox',
					'value'       => isset( $this->defaults['tag_enabled'] ) ? $this->defaults['tag_enabled'] : false,
				) // args
			);
			// Custom name.
			add_settings_field(
				$this->option_name . 'legend',
				__( 'Custom Names', 'wpaust' ),
				array( &$this, 'settings_field_textarea' ),
				$wpau_stockticker->plugin_slug,
				'wpaust_advanced',
				array(
					'field'       => $this->option_name . '[legend]',
					'class'       => 'widefat',
					'value'       => $this->defaults['legend'],
					'rows'        => 7,
					'description' => __(
						'Define custom names for symbols. Single symbol per row in format EXCHANGE:SYMBOL;CUSTOM_NAME',
						'wpaust'
					),
				)
			);

			// Default styling.
			add_settings_field(
				$this->option_name . 'style',
				__( 'Custom Style', 'wpaust' ),
				array( &$this, 'settings_field_textarea' ),
				$wpau_stockticker->plugin_slug,
				'wpaust_advanced',
				array(
					'field'       => $this->option_name . '[style]',
					'class'       => 'widefat',
					'rows'        => 2,
					'value'       => $this->defaults['style'],
					'description' => __( 'Define custom CSS style for ticker item (font family, size, weight)', 'wpaust' ),
				)
			);

			// Global enqueue assets.
			add_settings_field(
				$this->option_name . 'globalassets',
				__( 'Load assets on all pages?', 'wpaust' ),
				array( &$this, 'settings_field_checkbox' ),
				$wpau_stockticker->plugin_slug,
				'wpaust_advanced',
				array(
					'field'       => $this->option_name . '[globalassets]',
					'description' => __( 'By default, Crypto Ticker will load corresponding JavaScript files on demand. But, if you need to load assets on all pages, check this option. (For example, if you have some plugin that load widgets or content via Ajax, you should enable this option)', 'wpaust' ),
					'class'       => 'checkbox',
					'value'       => isset( $this->defaults['globalassets'] ) ? $this->defaults['globalassets'] : false,
				) // args
			);

			// --- Register setting Advanced so $_POST handling is done ---
			register_setting(
				'wpaust_advanced',
				$this->option_name,
				array( &$this, 'sanitize_options' )
			);

		} // END public static function register_settings()

		public function settings_js_forcedatafetch() {
			?>
			<p class="description">After you update settings, you can force initial Crypto data fetching by click on button below. If you get too much minuses during fetch, try to increase Fetch Timeout option, save settings and fetch data again.</p>
			<button name="st_force_data_fetch" class="button button-secondary">Fetch Crypto Data Now!</button>
			<div class="st_force_data_fetch"></div>
			<?php
		}

		/**
		 * Print description for General section
		 */
		public function settings_general_section_description() {
			// Think of this as help text for the section.
			esc_attr_e(
				'Predefine general settings for CryptoTicker. Here you can set the symbols and format to use in the ticker.',
				'wpaust'
			);
		}

		/**
		 * Print description for Default section
		 */
		public function settings_default_section_description() {
			// Think of this as help text for the section.
			esc_attr_e(
				'Predefine default settings for CryptoTicker. Here you can set Crypto symbols and how you wish to present currencies in ticker.',
				'wpaust'
			);
		}

		/**
		 * Print description for Advanced section
		 */
		public function settings_advanced_section_description() {
			// Think of this as help text for the section.
			esc_attr_e( 'Set advanced options important for caching quote feeds.', 'wpaust' );
		}

		/**
		 * This function provides text inputs for settings fields
		 * @param  array $args Array of field arguments.
		 */
		public function settings_field_input_text( $args ) {
			printf(
				'<input type="text" name="%s" id="%s" value="%s" class="%s" /><p class="description">%s</p>',
				esc_attr( $args['field'] ),
				esc_attr( $args['field'] ),
				esc_attr( $args['value'] ),
				sanitize_html_class( $args['class'] ),
				esc_html( $args['description'] )
			);
		} // END public function settings_field_input_text($args)

		/**
		 * This function provides password inputs for settings fields
		 * @param  array $args Array of field arguments.
		 */
		public function settings_field_input_password( $args ) {
			printf(
				'<input type="password" name="%s" id="%s" value="%s" class="%s" /><p class="description">%s</p>',
				esc_attr( $args['field'] ),
				esc_attr( $args['field'] ),
				esc_attr( $args['value'] ),
				sanitize_html_class( $args['class'] ),
				$args['description']
			);
		} // END public function settings_field_input_text($args)


		/**
		 * This function provides number inputs for settings fields
		 * @param  array $args Array of field arguments.
		 */
		public function settings_field_input_number( $args ) {
			printf(
				'<input type="number" name="%1$s" id="%2$s" value="%3$s" min="%4$s" max="%5$s" step="%6$s" class="%7$s" /><p class="description">%8$s</p>',
				esc_attr( $args['field'] ),            // 1
				esc_attr( $args['field'] ),            // 2
				(int) $args['value'],                  // 3
				(int) $args['min'],                    // 4
				(int) $args['max'],                    // 5
				(int) $args['step'],                   // 6
				sanitize_html_class( $args['class'] ), // 7
				esc_html( $args['description'] )       // 8
			);
		} // END public function settings_field_input_number($args)

		/**
		 * This function provides textarea for settings fields
		 * @param  array $args Array of field arguments.
		 */
		public function settings_field_textarea( $args ) {
			if ( empty( $args['rows'] ) ) {
				$args['rows'] = 7;
			}
			printf(
				'<textarea name="%s" id="%s" rows="%s" class="%s">%s</textarea><p class="description">%s</p>',
				esc_attr( $args['field'] ),
				esc_attr( $args['field'] ),
				(int) $args['rows'],
				sanitize_html_class( $args['class'] ),
				esc_textarea( $args['value'] ),
				esc_html( $args['description'] )
			);
		} // END public function settings_field_textarea($args)

		/**
		 * This function provides select for settings fields
		 * @param  array $args Array of field arguments.
		 */
		public function settings_field_select( $args ) {
			if ( empty( $args['class'] ) ) {
				$args['class'] = 'regular-text';
			}
			printf(
				'<select id="%1$s" name="%1$s" class="%2$s">',
				esc_attr( $args['field'] ),
				sanitize_html_class( $args['class'] )
			);
			foreach ( $args['items'] as $key => $val ) {
				$selected = ( $args['value'] == $key ) ? 'selected=selected' : '';
				printf(
					'<option %1$s value="%2$s">%3$s</option>',
					esc_attr( $selected ),      // 1
					sanitize_key( $key ),       // 2
					sanitize_text_field( $val ) // 3
				);
			}
			printf( '</select><p class="description">%s</p>', esc_html( $args['description'] ) );
		} // END public function settings_field_select($args)

		/**
		 * This function provides checkbox for settings fields
		 * @param  array $args Array of field arguments.
		 */
		public function settings_field_checkbox( $args ) {
			$checked = ( ! empty( $args['value'] ) ) ? 'checked="checked"' : '';
			printf(
				'<label for="%1$s"><input type="checkbox" name="%1$s" id="%1$s" value="1" class="%2$s" %3$s />%4$s</label>',
				esc_attr( $args['field'] ),
				$args['class'],
				$checked,
				$args['description']
			);

		} // END public function settings_field_checkbox($args) {

		/**
		 * Generate colour picker field
		 * @param  array $args Array of field arguments.
		 */
		public function settings_field_colour_picker( $args ) {
			printf(
				'<input type="text" name="%1$s" id="%2$s" value="%3$s" class="wpau-color-field" /> <p class="description">%4$s</p>',
				esc_attr( $args['field'] ),
				esc_attr( $args['field'] ),
				esc_attr( $args['value'] ),
				esc_html( $args['description'] )
			);
		} // END public function settings_field_colour_picker($args)

		/**
		 * Sanitize settings options
		 * @param  array $input Array of option values entered on settings page.
		 * @return array        Sanitized settings values
		 */
		public function sanitize_options( $options ) {

			$sanitized = get_option( $this->option_name );
			$previous_options = $sanitized;

			// If there is no POST option_page keyword, return initial plugin options
			if ( empty( $_POST['option_page'] ) ) {
				return $sanitized;
			}

			foreach ( $options as $key => $value ) {
				switch ( $key ) {
					case 'avapikey':
						// Allow only numbers (0-9) and English uppercase letters (A-Z)
						$value = preg_replace( '/[^0-9A-Z]+/', '', $value );
						break;
					case 'symbols':
						// Always uppercase
						$value = self::sanitize_symbols( $value );
						$value = self::alpha_symbols( $value, 'symbols' );
						break;
					case 'all_symbols':
						// Always uppercase
						$value = self::sanitize_symbols( $value );
						$value = self::alpha_symbols( $value, 'all_symbols' );
						// Add error if there is not supported exchanges
						// add_settings_error( 'all_symbols', 'all_symbols', 'You have unsupported exchange markets in All Symbols. Please remove them!', 'error' );
						break;
					case 'legend':
					case 'loading_message':
					case 'error_message':
					case 'style':
						$value = strip_tags( stripslashes( $value ) );
						break;
					case 'zero':
					case 'minus':
					case 'plus':
						$value = preg_replace( '/\#[^0-9a-f]/i', '', $value );
						break;
					case 'show':
						$value = strip_tags( stripslashes( $value ) );
						if ( ! in_array( $value, array( 'name', 'symbol' ) ) ) {
							$value = 'name';
						}
						break;
					case 'template':
						$value = strip_tags( $value, '<span><em><strong>' );
						break;
					// case 'cache_timeout':
					// 	$value = (int) $value;
					// 	$value = ! empty( $value ) ? $value : 180;
					// 	break;
					case 'fetch_timeout':
					case 'timeout':
						$value = (int) $value;
						$value = ! empty( $value ) ? $value : 2;
						break;
					case 'refresh_timeout':
						$value = (int) $value;
						$value = ! empty( $value ) ? $value : 5 * MINUTE_IN_SECONDS;
						break;
					case 'speed':
						$value = (int) $value;
						$value = ! empty( $value ) ? $value : 50;
						break;
					case 'refresh':
						$value = ! empty( $value ) ? true : false;
						break;
					case 'decimals':
						$value = (int) $value;
						$value = ! empty( $value ) ? $value : 2;
						break;
					case 'number_format':
						$value = strip_tags( stripslashes( $value ) );
						if ( ! in_array( $value, array( 'dc','sd','sc','cd' ) ) ) {
							$value = 'dc';
						}
						break;
				}
				$sanitized[ $key ] = $value;
			}

			// Generate static CSS
			$css = "ul.stock_ticker li .sqitem{{$sanitized['style']}}";
			$css .= "ul.stock_ticker li.zero .sqitem,ul.stock_ticker li.zero .sqitem:hover {color:{$sanitized['zero']}}";
			$css .= "ul.stock_ticker li.minus .sqitem,ul.stock_ticker li.minus .sqitem:hover {color:{$sanitized['minus']}}";
			$css .= "ul.stock_ticker li.plus .sqitem,ul.stock_ticker li.plus .sqitem:hover {color:{$sanitized['plus']}}";

			// Now write content to file
			$upload_dir = wp_upload_dir();
			if ( ! file_put_contents( $upload_dir['basedir'] . '/stock-ticker-custom.css', $css, LOCK_EX ) ) {
				$error = error_get_last();
				add_settings_error(
					'stock-ticker-update',
					esc_attr( 'stock-ticker-custom-css' ),
					sprintf(
						__( 'Failed to write custom CSS file because of error <em>%1$s</em><br />Please create mentioned file manually and add following code to it:<br /><code>%2$s</code>', 'stock-ticker' ),
						$error['message'],
						$css
					),
					'error'
				);
			}
			unset( $css );

			// Generate static refresh JS
			if ( ! empty( $sanitized['refresh'] ) ) {
				$js = sprintf( 'var stockTickers = setInterval(function(){ stock_tickers_load() }, %s);', $sanitized['refresh_timeout'] * 1000 );
				if ( ! file_put_contents( $upload_dir['basedir'] . '/stock-ticker-refresh.js', $js, LOCK_EX ) ) {
					$error = error_get_last();
					add_settings_error(
						'stock-ticker-update',
						esc_attr( 'stock-ticker-custom-js' ),
						sprintf(
							__( 'Failed to write custom JS file for auto refreshing ticker because of error <em>%1$s</em><br />Please create mentioned file manually and add following code to it:<br /><code>%2$s</code>', 'stock-ticker' ),
							$error['message'],
							$js
						),
						'error'
					);
				}
				unset( $js );
			}

			// Clear transient but only if changed one of:
			// API key, All Crypto Symbols, Cache Timeout or Fetch Timeout
			// @TODO remove cache_timeout
			if (
				$previous_options['avapikey'] !== $sanitized['avapikey'] ||
				$previous_options['all_symbols'] !== $sanitized['all_symbols'] ||
				$previous_options['cache_timeout'] !== $sanitized['cache_timeout'] ||
				$previous_options['timeout'] !== $sanitized['timeout']
			) {
				Wpau_Stock_Ticker::log( 'Crypto Ticker: Restarting data fetching from first symbol' );
				Wpau_Stock_Ticker::restart_av_fetching();
			}

			Wpau_Stock_Ticker::log( 'Crypto Ticker: Settings have been updated' );
			return $sanitized;
		} // END public function sanitize_options($sanitized) {

		/**
		 * Add a menu
		 */
		public function add_menu() {
			global $wpau_stockticker;
			// Add a page to manage this plugin's settings.
			add_options_page(
				__( 'Crypto Ticker', 'wpaust' ),
				__( 'Crypto Ticker', 'wpaust' ),
				'manage_options',
				$wpau_stockticker->plugin_slug,
				array( &$this, 'plugin_settings_page' )
			);
		} // END public function add_menu()

		/**
		 * Menu Callback
		 */
		public function plugin_settings_page() {
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
			}

			// Render the settings template.
			include( sprintf( '%s/../templates/settings.php', dirname( __FILE__ ) ) );
		} // END public function plugin_settings_page()

		/**
		 * Allow only numbers, alphabet, comma, dot, semicolon, equal and carret
		 * @param  string $symbols Unfiltered value of Crypto symbols
		 * @return string          Sanitized value of Crypto symbols
		 */
		private function sanitize_symbols( $symbols ) {
			$symbols = preg_replace( '/[^0-9A-Z\=\.\,\:\^]+/', '', strtoupper( $symbols ) );
			return $symbols;
		} // END private function sanitize_symbols( $symbols )

		/**
		 * Strip unsupported Crypto symbols and throw message with list of removed symbols
		 * @param  string $symbols All Crypto symbols
		 * @param  string $control Name of field where symbols goes
		 * @return string          Only symbols supported by AlphaVantage.co
		 */
		private function alpha_symbols( $symbols, $control ) {
			$symbols_supported = array();
			$symbols_removed = array();
			$symbols_arr = explode( ',', $symbols );
			// Remove unsupported Crypto exchanges from global array to prevent API errors
			foreach ( $symbols_arr as $symbol_pos => $symbol_to_check ) {
				// If there is semicolon, it's symbol with exchange
				if ( strpos( $symbol_to_check, ':' ) ) {
					// Explode symbol so we can get exchange code
					$symbol_exchange = explode( ':', $symbol_to_check );
					// If exchange code is supported, add symbol to query array
					if ( ! empty( Wpau_Stock_Ticker::$exchanges[ strtoupper( trim( $symbol_exchange[0] ) ) ] ) ) {
						$symbols_supported[] = $symbol_to_check;
					} else {
						$symbols_removed[] = $symbol_to_check;
					}
				} else {
					// Add symbol w/o exchange to query array
					$symbols_supported[] = $symbol_to_check;
				}
			}
			// Set back supported symbols
			$symbols = join( ',', $symbols_supported );
			// If we have removed symbols, add settings error message
			if ( ! empty( $symbols_removed ) ) {
				$symbols_removed_str = join( ', ', $symbols_removed );
				$opt_name = 'all_symbols' == $control ? 'All Crypto Symbols' : 'Crypto Symbols';
				add_settings_error(
					$control,
					$control,
					sprintf(
						'Field %1$s had symbols from unsupported exchange markets, so we removed them: %2$s',
						$opt_name,
						$symbols_removed_str
					),
					'updated'
				);
			}
			return $symbols;
		} // END private function alpha_symbols( $symbols, $control )

	} // END class Wpau_Stock_Ticker_Settings
} // END if(!class_exists( 'Wpau_Stock_Ticker_Settings' ))
