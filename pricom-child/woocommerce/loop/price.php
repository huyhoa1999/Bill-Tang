<?php
/**
 * Loop Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woo.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

if(class_exists('Wdr\App\Controllers\ManageDiscount')) {
    $base_price_ct = $product->get_price();
    $quantity = 1;
    $price_rule_woo = Wdr\App\Controllers\ManageDiscount::calculateInitialAndDiscountedPrice($product, $quantity);
    $discounted_price = $price_rule_woo['discounted_price'];
}
$price = $product->price;
$product_id = $product->get_id();
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

?>

<?php if(isset($min_quantity) && isset($discounted_price)) { ?>
	<span class="price" style="color: #000;font-weight: 600;"><?php echo $min_quantity; ?> starting at <span class="nodiss" style="text-decoration: line-through;color: #9b9b9b;"><?php echo wc_price($price_no_discout); ?></span> <span class="ydiss"><?php echo wc_price($total_price); ?></span>
<?php } elseif ($price_html = $product->get_price_html() && isset($min_quantity)) { ?>
	<span class="price" style="color: #000;font-weight: 600;"><?php echo $min_quantity; ?> starting at <?php echo wc_price($total_price); ?></span>
<?php } else { ?>
	<span class="price" style="color: #000;font-weight: 600;"><?php echo $product->get_price_html(); ?></span>
<?php } ?>