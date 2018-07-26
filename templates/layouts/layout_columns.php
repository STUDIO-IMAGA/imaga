<section class="Layout-columns <? the_sub_field('background_color'); ?> <? the_sub_field('text_color'); ?> text-center">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <h1 class="display-2 mb-4"><? the_sub_field('title'); ?></h1>
      </div>
    </div>

    <div class="row justify-content-center">
      <? if( have_rows('columns') ): ?>
        <? while ( have_rows('columns') ) : the_row(); ?>
          <div class="col-4">
            <div class="lead mb-3">
              <? the_sub_field('title'); ?>
            </div>
            <div class="lead">
              <? the_sub_field('content'); ?>
            </div>
          </div>
        <? endwhile; ?>
      <? endif;?>
    </div>
  </div>
</section>