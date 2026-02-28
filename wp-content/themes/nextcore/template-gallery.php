<?php
/**
 * Template Name: Galeri
 * Description: Galeri sayfası — filtreler, grid, lightbox
 *
 * @package nextcore
 */

get_header();
?>

<main id="primary" class="site-main site-main--gallery">

	<?php get_template_part( 'template-parts/page', 'gallery' ); ?>

</main>

<?php
get_footer();
