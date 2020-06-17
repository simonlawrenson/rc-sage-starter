<?php
/**
 * This is the template that renders an accordion
 *
 */
?>
<?php $padding 				 = get_field('padding'); ?>
<?php $accordion_title = get_field('accordion_title'); ?>
<?php $row_num = get_row_index(); ?>
<?php if( have_rows('accordion') ) : $i = 1; ?>
	<section class="block-container accordion-container <?= ( $padding ? 'padding-'. $padding : 'padding-top-bottom'); ?>">
		<div class="container-fluid opt-container-fluid">
			<?= ( $accordion_title ? '<div class="row"><div class="col-12"><h3 class="block-title accordion-title">'. $accordion_title .'</h3></div></div>' : false ); ?>
			<div class="row">
				<div class="col-12">
					<div class="accordion" id="accordion<?= $row_num; ?>" role="tablist" aria-multiselectable="true">
						<?php while( have_rows('accordion') ) : the_row(); ?>
							<?php $a_question = get_sub_field('accordion_question'); ?>
							<?php $a_answer 	= get_sub_field('accordion_answer'); ?>
							<?php if( $a_question || $a_answer ) : ?>
								<div class="card">
							    <div class="card-header <?= ( $i == 1 ? false : 'collapsed' ); ?>" id="heading<?= $row_num . $i;?>" role="button" data-toggle="collapse" data-target="#collapse<?= $row_num . $i;?>" aria-expanded="<?= ( $i == 1 ? 'true' : 'false' ); ?>" aria-controls="collapse<?= $row_num . $i;?>">
							      <h5 class="mb-0 d-flex"><?= $a_question; ?> <i class="fas fa-arrow-circle-up ml-auto"></i></h5>
							    </div>

							    <div id="collapse<?= $row_num . $i;?>" class="collapse <?= ( $i == 1 ? 'show' : false ); ?>" aria-labelledby="heading<?= $row_num . $i++;?>" data-parent="#accordion<?= $row_num; ?>">
							      <div class="card-body">
							        <?= $a_answer; ?>
							      </div>
							    </div>
								</div> <!-- end .card -->
							<?php endif; ?>
						<?php endwhile; ?>
					</div> <!-- end #accordion-->
				</div> <!-- end .col-12-->
			</div> <!-- end .row -->
		</div> <!-- end .container-fluid -->
	</section> <!-- end .call-to-action-container -->
<?php endif; ?>