<?php $this->load->view('header', array('title' => 'Ghideaza-Ma.com', 'page' => 'home')); ?>

<script type="text/javascript">

	/**
	 * Called on dom load
	 * @return {[type]} [description]
	 */
	function initialize() {
		var autocomplete = new google.maps.places.Autocomplete(document.getElementById('search_box'));
		google.maps.event.addListener(autocomplete, 'place_changed', function() {
			formSubmit();
		});
	}

	/**
	 * Redirects user to place/info/<place>
	 * @return {[type]} [description]
	 */
	function formSubmit() {
		window.location = '<?php echo site_url('place/info/'); ?>' + '/' + escape($('#search_box').val());
	}

	// Dom listener
	google.maps.event.addDomListener(window, 'load', initialize);
</script>

<section id="content" class="container clearfix">

	<h2 class="slogan align-center">Caută, explorează, descoperă!<br />
		<div>
			<form id="search" action="javascript: formSubmit();">
			<input type="text" placeholder="Caută un oraș, o stațiune sau obiective turistice." id="search_box">
			<input type="submit" value="Cauta">
		</form>
		</div>
	</h2>

	<section id="photos-slider" class="ss-slider">

		<?php for ($i = 0; $i < count($slide); $i++): ?>
		<article class="slide">

			<img src="<?php echo $slide[$i]['src']; ?>" alt="" class="slide-bg-image" />

			<div class="slide-button image">
				<img src="<?php echo $slide[$i]['src']; ?>" alt="" />
			</div>

			<div class="slide-content">
				<h4><a href="<?php echo $slide[$i]['href']; ?>"><?php echo $slide[$i]['name']; ?></a></h4>
			</div>

		</article><!-- end .slide -->
		<?php endfor; ?>

	</section><!-- end #photos-slider -->

	<h6 class="section-title">Cautări recente</h6>

	<ul class="projects-carousel clearfix">

		<?php for ($i = 0; $i < count($last_search); $i++): ?>
		<li>
			<a href="<?php echo $last_search[$i]['place']; ?>">
				<img src="<?php echo $last_search[$i]['photo']; ?>" alt="">
				<h5 class="title"><?php echo urldecode($last_search[$i]['query']); ?></h5>
			</a>
		</li>
		<?php endfor; ?>

	</ul><!-- end .projects-carousel -->

</section><!-- end #content -->

<?php $this->load->view('footer'); ?>