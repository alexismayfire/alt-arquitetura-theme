<?php

if ( ! class_exists( 'Altarq_Theme_Options' ) ) {

	class Altarq_Theme_Options {
		public function __construct() {
			// We only need to register the admin panel on the back-end
			if ( is_admin() ) {
				add_action( 'admin_menu', array( 'Altarq_Theme_Options', 'add_admin_menu' ) );
				add_action( 'admin_init', array( 'Altarq_Theme_Options', 'register_settings' ) );
			}
		}

		public static function get_theme_options() {
			return get_option( 'theme_options' );
        }
        
		public static function get_theme_option( $id ) {
			$options = self::get_theme_options();
			if ( isset( $options[$id] ) ) {
				return $options[$id];
			}
		}

		public static function add_admin_menu() {
			add_menu_page(
				'Opções do Tema',
				'Opções do Tema',
				'manage_options',
				'theme-settings',
				array( 'Altarq_Theme_Options', 'create_admin_page' )
			);
		}

		/**
		 * Register a setting and its sanitization callback.
		 *
		 * We are only registering 1 setting so we can store all options in a single option as
		 * an array. You could, however, register a new setting for each option
		 *
		 * @since 1.0.0
		 */
		public static function register_settings() {
            register_setting( 'theme_options', 'theme_options', array( 'Altarq_Theme_Options', 'sanitize' ) );
		}

		public static function sanitize( $options ) {
			if ( $options ) {
                if ( ! empty( $options['ga_code'] ) ) {
                    $options[$field] = sanitize_text_field( $options[$field] );
                } else {
                    unset( $options[$field] );
                }

				if ( ! empty( $options['ga_debug'] ) ) {
					$options['ga_debug'] = 'on';
				} else {
					unset( $options['ga_debug'] );
                }

                if ( ! empty( $options['whatsapp_number'] ) ) {
                    $options[$field] = sanitize_text_field( $options[$field] );
                } else {
                    unset( $options[$field] );
                }

                if ( ! empty( $options['whatsapp_message'] ) ) {
                    $options[$field] = sanitize_textarea_field( $options['whatsapp_message'] );
                } else {
                    unset( $options[$field] );
                }
            }
            
			return $options;

		}

		public static function create_admin_page() { ?>

			<div class="wrap">
              <h1>Opções do Tema</h1>
				<form method="post" action="options.php" class="options-form">
				<?php settings_fields( 'theme_options' ); ?>
				  <h3>Google Analytics</h3>
				  <div class="options-field">
					<label for="theme_options[ga_code]">Propriedade do Google Analytics <span class="label-example">(ex.: 'UA-XXXXXXXXX-X')</span></label>
					<?php $value = self::get_theme_option( 'ga_code' ); ?>
					<input type="text" name="theme_options[ga_code]" value="<?php echo esc_attr( $value ); ?>">
				  </div>
				  <div class="options-field">
					<label for="theme_options[ga_debug]">Desabilitar Google Analytics?</label>
					<?php $value = self::get_theme_option( 'ga_debug' ); ?>
					<span>
					  <input type="checkbox" name="theme_options[ga_debug]" <?php checked( $value, 'on' ); ?>>
					  <span class="label-checkbox">(apenas para desenvolvimento)</span>
					</span>
				  </div>
				  <h3>WhatsApp</h3>
				  <div class="options-field">
					<label for="theme_options[whatsapp_number]">Número do WhatsApp <span class="label-example">(DDD + telefone, apenas números)</span></label>
					<?php $value = self::get_theme_option( 'whatsapp_number' ); ?>
					<input type="text" name="theme_options[whatsapp_number]" value="<?php echo esc_attr( $value ); ?>">
				  </div>
				  <div class="options-field">
					<label for="theme_options[whatsapp_message]">Mensagem padrão do WhatsApp</label>
					<?php $value = self::get_theme_option( 'whatsapp_message' ); ?>
					<textarea rows="4" name="theme_options[whatsapp_message]"><?php echo esc_attr( $value ); ?></textarea>
				  </div>
				  <?php submit_button(); ?>
				</form>
			</div>
		<?php }

	}
}

if( is_admin() ) {
    new Altarq_Theme_Options();
}

function altarq_get_theme_option ( $id = '' ) {
    return Altarq_Theme_Options::get_theme_option( $id );
}

?>