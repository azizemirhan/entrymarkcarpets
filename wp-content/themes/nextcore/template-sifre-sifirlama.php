<?php
/**
 * Template Name: Şifre sıfırlama
 * Description: Özel şifre sıfırlama sayfası (WordPress wp-login yönlendirmesi yok)
 *
 * @package nextcore
 */

get_header();
?>

<main id="primary" class="site-main site-main--sifre-sifirlama">

	<?php get_template_part( 'template-parts/page', 'sifre-sifirlama' ); ?>

</main>

<?php
add_action( 'wp_footer', function () {
	?>
	<script>
	(function(){
		var reveals = document.querySelectorAll('.sifre-sifirlama-page .reveal');
		function show() { reveals.forEach(function(el, i) { setTimeout(function() { el.classList.add('visible'); }, 80 * i); }); }
		if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', show);
		else show();
	})();
	</script>
	<?php
}, 20 );
get_footer();
