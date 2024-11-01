<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.1
 */

defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

global $product;

$webARProduct = WebAR::getProduct($product->get_slug());

$columns = apply_filters( 'woocommerce_product_thumbnails_columns', 5 );

$count = 0;
$attachment_ids = $product->get_gallery_image_ids();
$post_thumbnail_id = $product->get_image_id();
$wrapper_classes   = apply_filters(
	'woocommerce_single_product_image_gallery_classes',
	array(
		'woocommerce-product-gallery',
		'woocommerce-product-gallery--' . ( $product->get_image_id() ? 'with-images' : 'without-images' ),
		'woocommerce-product-gallery--columns-' . absint( $columns ),
		'images',
	)
);

?>
<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">
	<figure class="woocommerce-product-gallery__wrapper">
        <?php
            if ( $attachment_ids && $product->get_image_id() ) {
                foreach ( $attachment_ids as $attachment_id ) {
                    if ($counter == 0 && is_object($webARProduct)) {
                        $html = '<div data-thumb="'.esc_url( $webARProduct->image_src ).'" data-thumb-alt="'.$webARProduct->name.'" class="woocommerce-product-gallery__image">';
                        $html .= '<iframe src="https://portal.wpwebar.com/modelviewer?id='.$product->get_slug().'&token='.WebAR::get_public_key().'" width="100%" height="250px" allow="fullscreen" frameborder="0"></iframe></div>';
                        echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id );
                    }
                        
                    $html = wc_get_gallery_image_html( $attachment_id, false );
                    echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $attachment_id );

                    $counter++;
                }
            }
            
        ?>
	</figure>
</div>
