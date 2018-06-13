<?php
/**
 * Template Name: Conversion - Design: Data Studio
 */

use IMAGA\Theme\Assets;
?>

<?php while (have_posts()) : the_post(); ?>

  <div class="jumbotron jumbotron-fluid bg-teal overflow-hidden mb-0">
    <div class="container text-white">

      <div class="row">
        <div class="col-6 py-6">
          <h1 class="display-5 mb-4">Get your data, neat.</h1>
          <p class="lead">Monitor eveything that happen on your site with our custom dashboards</p>
        </div>
        <div class="col-6 text-center">
          <img class="mt-6" src="<?php echo Assets\asset_path('images/promo/data-studio.png'); ?>" alt="Smartphone">
        </div>
      </div>

    </div>
  </div>

  <section>
    <div class="container py-5">

      <div class="row justify-content-center">
        <div class="col-8 text-center">
          <h1 class="display-6 mb-4">Our Conversion Optimization Research offers two kinds of data: Qualitative and Quantitative</h1>
          <p class="lead">Looking for experienced and certified Optimizers that follow a rigid and proven process? Marco is Google and ConversionXL certified and uses the CXL-Model to discover what matters most to your users and your bottom line. This Model is one of the best and internationally recognized Conversion Research frameworks and helps guide the Conversion Optimization processes and testing.</p>
        </div>
      </div>

      <div class="row">
        <div class="col-12 text-center py-5">
          <img class="img-fluid" src="<?php echo Assets\asset_path('images/insights-diagram.png'); ?>" alt="Insights">
        </div>
      </div>

      <div class="row justify-content-center">
        <div class="col-8 text-center">
          <p class="lead mb-3">Conversion Optimization Research and good, clean data is key to getting the valuable insights, making the right business decisions and moving your company forward.</p>
          <p class="lead">We view Conversion optimization work as a process. We don’t just assume what might be wrong and we certainly don’t just start ‘fixing’ and changing your website without doing the research first.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="bg-light">
    <div class="container text-center py-5">
      <div class="row">
        <div class="col-12">
          <h1 class="display-6 mb-4">When do you need what kind of data?</h1>
        </div>
      </div>
      <div class="row justify-content-center">
        <div class="col-4">
          <p class="lead">Qualitative<br>Conversion data</p>
          <a href="#Learn-more" class="btn btn-orange btn-lg">Learn more</a>
        </div>
        <div class="col-4">
          <p class="lead">Quantitative<br>Conversion data</p>
          <a href="#Learn-more" class="btn btn-orange btn-lg">Learn more</a>
        </div>
      </div>
    </div>
  </section>

  <section>
    <?php get_template_part('templates/sections/brands','banner'); ?>
  </section>

<?php endwhile; ?>
