<?php

function generateDesignerHTML($id) {
  $cardEnd = '';
  $designerPost = new WP_Query(array(
    'post_type' => 'designer',
    'p' => $id
  ));

  while($designerPost->have_posts()) {
    $designerPost->the_post();
   
    $cardEnd = '<p><strong><a href="' . get_the_permalink() . '">Learn more about ' . get_the_title() . '&raquo;</a></strong></p>';

    ob_start(); ?>
      <div class="professor-callout">
        <div class="professor-callout__photo" style="background-image: url(<?php the_post_thumbnail_url('designerPortrait'); ?>)"></div>
        <div class="professor-callout__text">
          <h5><?php the_title(); ?></h5>
          <!-- <p><?php echo wp_trim_words(get_the_content(), 30); ?></p> -->
          <?php echo "<p>" . wp_trim_words(get_field('main_body_content'), 30) . "</p>"; ?>

          <!-- DIRECTLY RELATED -->
          <?php
            $relatedPatterns = new WP_Query(array(
              'posts_per_page' => 3,
              'post_type' => 'pattern',
              'meta_key' => 'related_designer',
              'orderby' => 'meta_value_num',
              'order' => 'DESC',
              'meta_query' => array(
                'key' => 'related_designer',
                'compare' => 'LIKE',
                'value' => '"' . get_the_ID() . '"'
              )
            ));
            
            if ($relatedPatterns->have_posts()) {
              echo '<p>Name patterns: ';
          
              while ($relatedPatterns->have_posts()) {
                $relatedPatterns->the_post(); ?>
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                <?php
                if (($relatedPatterns->current_post + 1) != $relatedPatterns->post_count && $relatedPatterns->post_count > 1) {
                  echo ', ';
                }
              } ?>.
              </p>
            <?php }
            echo $cardEnd;
          ?>
        </div>
      </div>
    <?php 
    wp_reset_postdata();
    return ob_get_clean();
  }
}