<?php
/**
 * Template Name: Hesabım
 * Description: Kullanıcı hesap sayfası — giriş yap / hesap özeti
 *
 * @package nextcore
 */

get_header();
?>

<main id="primary" class="site-main site-main--hesabim">

	<?php get_template_part( 'template-parts/page', 'hesabim' ); ?>

</main>

<?php
// Reveal animasyonu — kartlar yüklendiğinde görünür (bu şablon yüklendiğinde her zaman çalışır)
add_action( 'wp_footer', function () {
	?>
	<script>
	(function(){
		var reveals = document.querySelectorAll('.hesabim-page .reveal');
		function show() {
			reveals.forEach(function(el, i) {
				setTimeout(function() { el.classList.add('visible'); }, 80 * i);
			});
		}
		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', show);
		} else {
			show();
		}
	})();
	</script>
	<?php
}, 20 );
get_footer();
