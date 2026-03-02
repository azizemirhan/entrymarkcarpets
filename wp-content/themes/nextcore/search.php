<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package nextcore
 */

get_header();

$search_query = get_search_query();
?>

	<main id="primary" class="site-main site-main--search">

		<div class="search-results-wrap">

			<header class="search-results-header">
				<h1 class="search-results-title">
					<?php
					/* translators: %s: search query. */
					printf( esc_html__( 'Arama sonuçları: %s', 'nextcore' ), '<span class="search-results-query">' . esc_html( $search_query ) . '</span>' );
					?>
				</h1>
				<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="search-results-form">
					<input type="search" name="s" class="search-results-input" placeholder="<?php esc_attr_e( 'Yeniden ara...', 'nextcore' ); ?>" value="<?php echo esc_attr( $search_query ); ?>">
					<button type="submit" class="search-results-submit"><?php esc_html_e( 'Ara', 'nextcore' ); ?></button>
				</form>
			</header>

			<?php if ( have_posts() ) : ?>

				<div class="search-results-list">
					<?php
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/content', 'search' );
					endwhile;
					?>
				</div>

				<?php the_posts_navigation(); ?>

			<?php else : ?>

				<?php get_template_part( 'template-parts/content', 'none' ); ?>

			<?php endif; ?>

		</div>

	</main><!-- #main -->

<?php
get_footer();
