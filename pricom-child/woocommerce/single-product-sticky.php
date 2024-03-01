<?php
/**
 * @package    HaruTheme
 * @version    1.0.0
 * @author     Administrator <admin@harutheme.com>
 * @copyright  Copyright 2022, HaruTheme
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $product;

if(class_exists('Wdr\App\Controllers\ManageDiscount')) {
    // $base_price_ct = $product->get_price();
    $quantity = 1;
    $price_rule_woo = Wdr\App\Controllers\ManageDiscount::calculateInitialAndDiscountedPrice($product, $quantity);
    $discounted_price = $price_rule_woo['discounted_price'];
}
$price = $product->price;
$product_id = get_the_ID();
$class = new NBD_FRONTEND_PRINTING_OPTIONS;
$option_id = $class->get_product_option( $product_id );
if( $option_id ){
    $_options = $class->get_option( $option_id );
    $options = unserialize( $_options['fields'] );
    if( isset( $options ) ){
        $min_quantity = $options['quantity_breaks'][0]['val'];
    }
}

if(isset($discounted_price)) {
    $total_price = $discounted_price * $min_quantity;
    $price_no_discout = $price * $min_quantity;
} else {
    $total_price = $price * $min_quantity;
}

$single_layout = get_post_meta( get_the_ID(), 'haru_layout', true );
if ( ( $single_layout == '' ) || ( $single_layout == 'default' ) ) {
    $single_layout = haru_get_option( 'haru_single_product_layout', 'haru-container' );
}

$product_sticky_cart = get_post_meta( get_the_ID(), 'haru_product_sticky_cart', true );
if ( ( $product_sticky_cart == '' ) || ( $product_sticky_cart == 'default' ) ) {
    $product_sticky_cart = haru_get_option( 'haru_single_product_sticky_cart', 'no-sticky' );
}

if ( $product_sticky_cart != 'sticky' ) return; 

if ( is_product() ) :
?>
    <div class="single-product-sticky">
        <div class="<?php echo esc_attr( $single_layout ); ?>">
            <div class="single-product-sticky__content">
                <div class="single-product-sticky__image">
                    <img src="<?php echo wp_get_attachment_url( $product->get_image_id() ); ?>" alt="<?php echo esc_attr( $product->get_title() ); ?>"/>
                </div>
                <div class="single-product-sticky__info">
                    <div class="single-product-sticky__title"><?php echo esc_html__( 'You\'re viewing: ', 'pricom' ); ?><strong><?php echo wp_kses_post( $product->get_title() ); ?></strong></div>
                    <div class="single-product-sticky__summary">
                        <div class="single-product-sticky__price">
                            <?php if(isset($min_quantity) && isset($discounted_price)) { ?>
                                <span class="price" style="color: #000;font-weight: 600;"><?php echo $min_quantity; ?> starting at <span class="nodiss" style="text-decoration: line-through;color: #9b9b9b;"><?php echo wc_price($price_no_discout); ?></span> <span class="ydiss"><?php echo wc_price($total_price); ?></span>
                            <?php } elseif ($price_html = $product->get_price_html() && isset($min_quantity)) { ?>
                                <span class="price" style="color: #000;font-weight: 600;"><?php echo $min_quantity; ?> starting at <?php echo wc_price($total_price); ?></span>
                            <?php } else { ?>
                                <span class="price" style="color: #000;font-weight: 600;"><?php echo $product->get_price_html(); ?></span>
                            <?php } ?>
                        </div>
                        <div class="single-product-sticky__rating">
                            <?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
                        </div>
                    </div>
                </div>
                <div class="single-product-sticky__btn">
                    <!-- <?php echo do_shortcode( '[add_to_cart id=' . $product->get_id() . ' show_price="false" style=""]' ) ?> -->
                    <div class="product-button product-button--add-to-cart cth">
                        <span class="haru-tooltip button-tooltip" style="border-radius: 5px;background: #DD1D26;color: #fff;display: inline-block;font-weight: 600;outline: none;border: none;height: 48px;line-height: 48px;padding: 0 25px;transition: all 0.3s;cursor: pointer;">Add to cart</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
    endif;