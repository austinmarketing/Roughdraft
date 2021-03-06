<?php
/*
Author: Mark Inns
URL: https://markinns.com/roughdraft/

This is where you can drop your custom functions or
just edit things like thumbnail sizes, header images,
sidebars, comments, etc.
*/

// Put on yor roughdraft CORE (if you remove this, the theme will break)
require_once( 'library/roughdraft.php' );

// CUSTOMIZE THE WORDPRESS ADMIN (off by default)
// require_once( 'library/admin.php' );

/*********************
Put on your roughdraft
Let's get everything up and running.
*********************/

function roughdraft_ahoy() {

  //Allow editor style.
  add_editor_style( get_stylesheet_directory_uri() . '/library/css/editor-style.css' );

  // let's get language support going, if you need it
  load_theme_textdomain( 'roughdraft', get_template_directory() . '/languages' );

  // launching operation cleanup
  add_action( 'init', 'roughdraft_head_cleanup' );
  // remove WP version from RSS
  add_filter( 'the_generator', 'roughdraft_rss_version' );
  // remove pesky injected css for recent comments widget
  add_filter( 'wp_head', 'roughdraft_remove_wp_widget_recent_comments_style', 1 );
  // clean up comment styles in the head
  add_action( 'wp_head', 'roughdraft_remove_recent_comments_style', 1 );
  // clean up gallery output in wp
  add_filter( 'gallery_style', 'roughdraft_gallery_style' );

  // enqueue base scripts and styles
  add_action( 'wp_enqueue_scripts', 'roughdraft_scripts_and_styles', 999 );
  // ie conditional wrapper

  // launching this stuff after theme setup
  roughdraft_theme_support();
  
  // This feature enables Custom_Headers support for a theme as of Version 3.4
  add_theme_support( 'custom-header' );
  // Allow title to be controlled by WP as recomended from 4.1
  add_theme_support( 'title-tag' );

  // adding sidebars to Wordpress (these are created in functions.php)
  add_action( 'widgets_init', 'roughdraft_register_sidebars' );

  // cleaning up random code around images
  add_filter( 'the_content', 'roughdraft_filter_ptags_on_images' );
  // cleaning up excerpt
  add_filter( 'excerpt_more', 'roughdraft_excerpt_more' );

} /* end roughdraft ahoy */

// let's get this party started
add_action( 'after_setup_theme', 'roughdraft_ahoy' );


/************* OEMBED SIZE OPTIONS *************/

if ( ! isset( $content_width ) ) {
	$content_width = 680;
}

/* Disable WordPress Admin Bar for all users. */
show_admin_bar(false);

/************* THUMBNAIL SIZE OPTIONS *************/

// Thumbnail sizes
add_image_size( 'roughdraft-thumb-600', 600, 150, true );
add_image_size( 'roughdraft-thumb-300', 300, 100, true );

/*
to add more sizes, simply copy a line from above
and change the dimensions & name. As long as you
upload a "featured image" as large as the biggest
set width or height, all the other sizes will be
auto-cropped.

To call a different size, simply change the text
inside the thumbnail function.

For example, to call the 300 x 100 sized image,
we would use the function:
<?php the_post_thumbnail( 'roughdraft-thumb-300' ); ?>
for the 600 x 150 image:
<?php the_post_thumbnail( 'roughdraft-thumb-600' ); ?>

You can change the names and dimensions to whatever
you like. Enjoy!
*/

add_filter( 'image_size_names_choose', 'roughdraft_custom_image_sizes' );

function roughdraft_custom_image_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'roughdraft-thumb-600' => __('600px by 150px', 'roughdraft'),
        'roughdraft-thumb-300' => __('300px by 100px', 'roughdraft'),
    ) );
}

/*
The function above adds the ability to use the dropdown menu to select
the new images sizes you have just created from within the media manager
when you add media to your content blocks. If you add more image sizes,
duplicate one of the lines in the array and name it according to your
new image size.
*/

/************* THEME CUSTOMIZE *********************/

/* 
  A good tutorial for creating your own Sections, Controls and Settings:
  http://code.tutsplus.com/series/a-guide-to-the-wordpress-theme-customizer--wp-33722
  
  Good articles on modifying the default options:
  http://natko.com/changing-default-wordpress-theme-customization-api-sections/
  http://code.tutsplus.com/tutorials/digging-into-the-theme-customizer-components--wp-27162
*/

// Load theme customiser (kept in seperate file)
include 'library/theme-customizer.php';

/************* ACTIVE SIDEBARS ********************/

// Sidebars & Widgetizes Areas
function roughdraft_register_sidebars() {
	register_sidebar(array(
		'id' => 'sidebar1',
		'name' => __( 'Sidebar 1', 'roughdraft' ),
		'description' => __( 'The first (primary) sidebar.', 'roughdraft' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));

	/*
	to add more sidebars or widgetized areas, just copy
	and edit the above sidebar code. In order to call
	your new sidebar just use the following code:

	Just change the name to whatever your new
	sidebar's id is, for example:

	register_sidebar(array(
		'id' => 'sidebar2',
		'name' => __( 'Sidebar 2', 'roughdraft' ),
		'description' => __( 'The second (secondary) sidebar.', 'roughdraft' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));

	To call the sidebar in your template, you can just copy
	the sidebar.php file and rename it to your sidebar's name.
	So using the above example, it would be:
	sidebar-sidebar2.php

	*/
} // don't remove this bracket!


/************* COMMENT LAYOUT *********************/

// Comment Layout
function roughdraft_comments( $comment, $args, $depth ) {
   $GLOBALS['comment'] = $comment; ?>
  <div id="comment-<?php comment_ID(); ?>" <?php comment_class('cf'); ?>>
    <article  class="cf">
      <header class="comment-author vcard">
        <?php
        /*
          this is the new responsive optimized comment image. It used the new HTML5 data-attribute to display comment gravatars on larger screens only. What this means is that on larger posts, mobile sites don't have a ton of requests for comment images. This makes load time incredibly fast! If you'd like to change it back, just replace it with the regular wordpress gravatar call:
          echo get_avatar($comment,$size='32',$default='<path_to_url>' );
        */
        ?>
        <?php // custom gravatar call ?>
        <?php
          // create variable
          $bgauthemail = get_comment_author_email();
        ?>
        <img data-gravatar="http://www.gravatar.com/avatar/<?php echo md5( $bgauthemail ); ?>?s=40" class="load-gravatar avatar avatar-48 photo" height="40" width="40" src="<?php echo get_template_directory_uri(); ?>/library/images/nothing.gif" />
        <?php // end custom gravatar call ?>
        <?php printf(__( '<cite class="fn">%1$s</cite> %2$s', 'roughdraft' ), get_comment_author_link(), edit_comment_link(__( '(Edit)', 'roughdraft' ),'  ','') ) ?>
        <time datetime="<?php echo comment_time('Y-m-j'); ?>"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_time(__( 'F jS, Y', 'roughdraft' )); ?> </a></time>

      </header>
      <?php if ($comment->comment_approved == '0') : ?>
        <div class="alert alert-info">
          <p><?php _e( 'Your comment is awaiting moderation.', 'roughdraft' ) ?></p>
        </div>
      <?php endif; ?>
      <section class="comment_content cf">
        <?php comment_text() ?>
      </section>
      <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
    </article>
  <?php // </li> is added by WordPress automatically ?>
<?php
} // don't remove this bracket!


/*
This is a modification of a function found in the
twentythirteen theme where we can declare some
external fonts. If you're using Google Fonts, you
can replace these fonts, change it in your css files
and be up and running in seconds.
*/
function roughdraft_fonts() {
  wp_enqueue_style('googleFonts', 'http://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic');
}

add_action('wp_enqueue_scripts', 'roughdraft_fonts');

/* DON'T DELETE THIS CLOSING TAG */ ?>
