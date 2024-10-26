<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package PopularFX
 */
 
$footer_text = get_theme_mod('popularfx_footer_text');

?>

	<footer id="colophon" class="site-footer">
		<div class="site-info">
			<?php if(empty($footer_text)){ ?>
			<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'popularfx' ) ); ?>">
				<?php
				/* translators: %s: CMS name, i.e. WordPress. */
				printf( esc_html__( 'Proudly powered by %s', 'popularfx' ), 'WordPress' );
				?>
			</a>
			<span class="sep"> | </span>
			<?php
			/* translators: 1: Theme name, 2: Theme author. */
			printf( popularfx_theme_credits() );
			?>
			<?php }else{
				echo wp_kses($footer_text, 'post');
			} ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
