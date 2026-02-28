<?php
/**
 * Anasayfa şablonu — Hero, Özellikler, CTA bölümleri (Next Content ile yönetilir)
 *
 * @package nextcore
 */

get_header();
?>

<main id="primary" class="site-main site-main--front">

	<?php get_template_part( 'template-parts/sections', 'home' ); ?>
	<?php get_template_part( 'template-parts/sections', 'specs' ); ?>

</main>

<?php
get_footer();
