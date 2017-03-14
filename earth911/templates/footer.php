<footer id="page-footer" class="earth-footer col-md-12" role="contentinfo">
	<div class="col-md-4 about-earth">
		<img src="http://localhost:8888/earth911/wp-content/uploads/2017/02/logo.png">
		<p class="footer-text">At Earth911, we’ve created a community that helps consumers find their own shade of green, match their values to their purchase behaviors, adopt environmentally sound practices and drive impactful environmental changes. We are here to deliver a mix of targeted content and eco-conscious products that influence positive environmental actions so that you can live a happier, healthier, sustainable lifestyle; one that protects this wonderful planet we call home.</p>
	</div>
	<div class="col-md-8 footer-menu-widgets">
		<?php wpscom_footer_content(); ?>
	</div>
	<div class="col-md-12 footer-date">
		<?php wp_nav_menu( array(
    'menu' => 'Footer'
) ); ?>
		Copyright &copy;.&nbsp;&nbsp;<?php echo date("Y"); ?>&nbsp;&nbsp;Earth911. All Rights Reserved.
	</div>

	<div class="back-to-top">
		<a href="#to-top"><i class="fa fa-angle-up" aria-hidden="true"></i></a>
	</div>
</footer>