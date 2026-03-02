<?php
/**
 * Template Name: Kayıt ol
 * Description: Yeni hesap oluşturma sayfası
 *
 * @package nextcore
 */

get_header();
?>

<main id="primary" class="site-main site-main--kayit">

	<?php get_template_part( 'template-parts/page', 'kayit' ); ?>

</main>

<?php
add_action( 'wp_footer', function () {
	?>
	<script>
	(function(){
		var reveals = document.querySelectorAll('.kayit-page .reveal');
		function show() { reveals.forEach(function(el, i) { setTimeout(function() { el.classList.add('visible'); }, 80 * i); }); }
		if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', show);
		else show();
	})();
	</script>
	<?php
}, 20 );
get_footer();
