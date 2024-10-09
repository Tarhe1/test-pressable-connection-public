<?php
use ElevationFramework\Lib\BlockLibrary\Helpers;
/**
 * What We Do - First Steps Callback Function.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
function whatwedo_firststeps_block_render_callback($block, $content = '', $is_preview = false, $post_id = 0, $wp_block = false, $context = false)
{

	// Create id attribute allowing for custom "anchor" value.
	$id = 'block-whatwedo-firststeps--' . $block['id'] . '-' . wp_unique_id();
	if (!empty($block['anchor'])) {
		$id = $block['anchor'];
	}

	// Create class attribute allowing for custom "className" and "align" values.
	$data_id = 'whatwedo-firststeps';
	$className = 'dynamic-assets-load block block-whatwedo-firststeps';
	if (!empty($block['className'])) {
		$className .= ' ' . $block['className'];
	}
	if (!empty($block['align'])) {
		$className .= ' align' . $block['align'];
	}
	// Load values and handle defaults.
  
	$editor = get_field('whatwedofirststeps_editor');
	$cards = get_field('whatwedofirststeps_cards');
	$lastcard = get_field('whatwedofirststeps_lastcard');
	$image = get_field('whatwedofirststeps_image');

?>

	<div data-id="<?php echo esc_attr($data_id); ?>" id="<?= esc_attr($id); ?>" class="<?= esc_attr($className); ?>">
		<div class="block-whatwedo-firststeps__container container">
			<div class="block-whatwedo-firststeps__row row justify-content-center">

				<div class="col-xl-11 col-xxl-10">

					<?php if (!empty($editor)) : ?>
						<div class="block-whatwedo-firststeps__content">
							<div class="block-whatwedo-firststeps__editor block__editor block__editor--color-dark">
								<?= wp_kses_post($editor); ?>
							</div>
						</div>
					<?php endif; ?>

					<div class="block-whatwedo-firststeps__row-inner row">			
						<?php if ($cards) : ?>
							<div class="block-whatwedo-firststeps__cards">
								<div class="block-whatwedo-firststeps__cards-row row row-cols-lg-2 row-cols-1">
									<?php foreach ($cards as $card) : ?>
	
										<div class="col">
											<div class="card">
												<div class="card__body">
												
													<?php if (isset($card['icon']) && !empty($card['icon'])) : ?>
														<?php Helpers::global_image($card['icon'], ['is_figure' => false, 'class' => 'card__icon']); ?>
													<?php endif; ?>
												
													<?php if (isset($card['title']) && !empty($card['title'])) : ?>
														<div class="card__title h6">
															<?= esc_html($card['title']); ?>
														</div>
													<?php endif; ?>
	
													<?php if (isset($card['description']) && !empty($card['description'])) : ?>
														<div class="card__excerpt">
															<p><?= wp_kses_post($card['description']); ?></p>
														</div>
													<?php endif; ?>
	
													<?php if (isset($card['link']) && !empty($card['link'])) : ?>
														<div class="card__button">
															<span class="cta-custom">
																<?= esc_html($card['link']['title']); ?>
															</span>
														</div>
													<?php endif; ?>
	
												</div>
	
												<?php if (isset($card['link']) && !empty($card['link'])) : 
													$target = $card['link']['target'] ? $card['link']['target'] : '_self';
												?>
	
													<a href="<?= esc_url($card['link']['url']); ?>" target="<?= esc_attr($target); ?>" class="stretched-link stretched-link-custom">
														<?= esc_html($card['link']['title']); ?>
													</a>
													
												<?php endif; ?>
											</div>
										</div>
	
									<?php endforeach; ?>

									<?php if ($lastcard) : ?>
										<div class="col">
											<div class="last-card">
												<div class="last-card__body">

													<?php if (isset($lastcard['subtitle']) && !empty($lastcard['subtitle'])) : ?>
														<div class="last-card__subtitle h5">
															<?= esc_html($lastcard['subtitle']); ?>
														</div>
													<?php endif; ?>
												
													<?php if (isset($lastcard['title']) && !empty($lastcard['title'])) : ?>
														<div class="last-card__title h4">
															<?= esc_html($lastcard['title']); ?>
														</div>
													<?php endif; ?>
	
													<?php if (isset($lastcard['link']) && !empty($lastcard['link'])) : ?>
														<div class="last-card__button">
															<span class="cta-custom">
																<?= esc_html($lastcard['link']['title']); ?>
															</span>
														</div>
													<?php endif; ?>
	
												</div>
	
												<?php if (isset($lastcard['link']) && !empty($lastcard['link'])) : 
													$target = $lastcard['link']['target'] ? $lastcard['link']['target'] : '_self';
												?>
	
													<a href="<?= esc_url($lastcard['link']['url']); ?>" target="<?= esc_attr($target); ?>" class="stretched-link stretched-link-custom">
														<?= esc_html($lastcard['link']['title']); ?>
													</a>
													
												<?php endif; ?>
											</div>
										</div>
										<?php endif; ?>
	
								</div>
							</div>	
						<?php endif; ?>
	
						<?php if (isset($image) && !empty($image)) : ?>
							<div class="block-whatwedo-firststeps__image">
								<?php Helpers::global_image($image, ['class' => 'block-whatwedo-firststeps__photo', 'decorative' => false]); ?>
							</div>
						<?php endif; ?>
					</div>

					
				</div>

			</div>
		</div>
	</div>

<?php
}
