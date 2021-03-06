<?php
/**
 * Checkout Form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;

wc_print_notices();

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->enable_signup && ! $checkout->enable_guest_checkout && ! is_user_logged_in() ) {
	echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) );
	return;
}

// filter hook for include new pages inside the payment method
$get_checkout_url = apply_filters( 'woocommerce_get_checkout_url', $woocommerce->cart->get_checkout_url() ); ?>

<form name="checkout" method="post" class="checkout" action="<?php echo esc_url( $get_checkout_url ); ?>">
    <!-- Step 1 -->
    <div class="checkout-step first-step">
        <?php if ( sizeof( $checkout->checkout_fields ) > 0 ) : ?>
        
            <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

            <div class="form-wrapper clearfix">

                <div class="checkout-item billing-address">

                    <?php do_action( 'woocommerce_checkout_billing' ); ?>

                </div>

                <div class="checkout-item shipping-address">

                    <?php do_action( 'woocommerce_checkout_shipping' ); ?>

                </div>

            </div>

            <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

            <div class="clear"></div>

            <div class="next-step">
                <table>
                    <tr>
                        <td>
                            <a href="#" class="button-1 custom-font-1"><span><?php _e('Continue to next step', PLSH_THEME_DOMAIN); ?></span></a><b><?php _e('or', PLSH_THEME_DOMAIN); ?> <a href="<?php echo home_url('/'); ?>"><?php _e('Return to store', PLSH_THEME_DOMAIN); ?></a></b>
                        </td>
                    </tr>
                </table>
            </div>
            
        <?php endif; ?>
    </div>
            
    <?php do_action( 'woocommerce_checkout_order_review' ); ?>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>