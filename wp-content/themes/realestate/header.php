<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package realestate
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/vio.css" type="text/css">
<!--<script type="text/javascript">

windowWidth = window.innerWidth;

if (screen.width <= 767 || windowWidth <= 767) {
    window.location = "http://xn--fachanwaltfrarbeitsrecht-5sc.net/";
}
</script>-->



<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>



<div class="wrapper">


<header class="top">

<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img src="<?php echo get_template_directory_uri(); ?>/images/logo_03.png" class="logo" /></a>

<div class="red">

<div class="estab">

<h3>Established 1922</h3>

<h2>856-354-9700</h2>

</div>

<ul>
<?php shailan_dropdown_menu(); ?>
</ul>

</div>

</header>


<section class="content">