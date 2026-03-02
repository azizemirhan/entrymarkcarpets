<?php
/**
 * Template Name: Giriş yap
 * Description: Giriş sayfası
 *
 * @package nextcore
 */

get_header();
?>

<main id="primary" class="site-main site-main--giris">

	<?php get_template_part( 'template-parts/page', 'giris' ); ?>

</main>

<?php
add_action( 'wp_footer', function () {
	?>
	<script>
	(function(){
		var reveals = document.querySelectorAll('.giris-page .reveal');
		function show() { reveals.forEach(function(el, i) { setTimeout(function() { el.classList.add('visible'); }, 80 * i); }); }
		if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', show);
		else show();
	})();
	</script>
	<?php
}, 20 );
get_footer();
