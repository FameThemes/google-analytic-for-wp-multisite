<?php
/*
Plugin Name: FameThemes Tracking Code
Plugin URI: http://www.famethemes.com
Description: Simple plugin for adding tracking code to site head and footer section.
Author: famethemes
Version: 1.0.0
Author URI: http://www.famethemes.com
*/

if ( defined( ABSPATH ) ) {
    die();
}

if (!class_exists( 'FT_Tracking_Code' )) :
    class FT_Tracking_Code {

        private $isSaved;

        function __construct(){
            add_action( 'admin_menu',   array( $this, 'menu_item' ) );
            add_action( 'wp_head',      array( $this, 'wp_head' ) );
            add_action( 'wp_footer',    array( $this, 'wp_footer' ) );
        }

        function menu_item(){
            add_submenu_page( 'options-general.php', 'Tracking Code', 'Tracking Code', 'manage_options', 'ft-tracking-code', array( $this, 'init' ));
        }

        function init(){

            if( isset($_POST['save']) ){
                if( check_admin_referer('tracking_code') && !empty($_POST['data']) ){
                    update_option( 'tracking_code', $_POST['data'] );
                    $this->isSaved;
                }
            }

            $data = get_option('tracking_code');
            $this->form( stripslashes_deep( $data ) );
        }

        function wp_head(){
            $data = get_option('tracking_code');
            if( empty( $data['tracking_head']['disable'] ) && !empty( $data['tracking_head']['code'] ) )
                echo stripslashes( $data['tracking_head']['code'] );
        }

        function wp_footer(){
            $data = get_option('tracking_code');
            if( empty( $data['tracking_footer']['disable'] ) && !empty( $data['tracking_footer']['code'] ) )
                echo stripslashes( $data['tracking_footer']['code'] );
        }

        function form( $data ){
            ?>
            <div class="wrap">
                <div id="icon-options-general" class="icon32 icon32-posts-page"><br /></div>
                <form method="post" action="">
                    <h2>Tracking Code</h2>
                    <p>Add web tracking code to html head or footer section.</p>
                    <?php if( !empty( $this->isSaved ) ) : ?>
                        <div class="updated"><p><strong><?php echo "Saved Successfully."; ?></strong></p></div>
                    <?php endif; ?>
                    <p>
                    <h3>Add Tracking Code to HTML head</h3>
                    <textarea rows="20" style="width:100%" name="data[tracking_head][code]"><?php echo @$data['tracking_head']['code'] ?></textarea>
                    <br />
                    <input type="checkbox" name="data[tracking_head][disable]" id="tracking_head_disable" <?php checked( @$data['tracking_head']['disable'], 'on' ); ?>  />
                    <label for="tracking_head_disable">Disable this head tracking code</label>
                    </p>

                    <p><br /></p>

                    <p>
                    <h3>Add Tracking Code to footer</h3>
                    <textarea rows="20" style="width:100%" name="data[tracking_footer][code]"><?php echo @$data['tracking_footer']['code'] ?></textarea>
                    <br />
                    <input type="checkbox" name="data[tracking_footer][disable]" id="tracking_footer_disable"  <?php checked( @$data['tracking_footer']['disable'], 'on' ); ?> />
                    <label for="tracking_footer_disable">Disable this footer tracking code</label>
                    </p>

                    <?php wp_nonce_field( 'tracking_code' ); ?>

                    <p><input class="button-primary" type="submit" name="save" value="Save Changes"/></p>

                </form>
            </div>
        <?php
        }

    }
endif;

$trackingCode = new FT_Tracking_Code;
