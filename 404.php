<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="grid-container">

					<main id="main" class="grid-70" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

						<article id="post-not-found" class="hentry cf">

							<header class="article-header">

								<h1><?php _e( 'Error 404 - Article Not Found', 'roughdraft' ); ?></h1>

							</header>

							<section class="entry-content">

								<p><?php _e( 'The page you were looking for was not found.', 'roughdraft' ); ?></p>

							</section>

							<section class="search">

									<p><?php get_search_form(); ?></p>

							</section>

							<footer class="article-footer">

									<p><?php _e( 'This is the 404.php template.', 'roughdraft' ); ?></p>

							</footer>

						</article>

					</main>

				</div>

			</div>

<?php get_footer(); ?>
