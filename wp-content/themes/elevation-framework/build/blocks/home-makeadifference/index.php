<?php
use ElevationFramework\Lib\BlockLibrary\Helpers;
/**
 * Homepage - How We make a Difference Callback Function.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
function home_makeadifference_block_render_callback($block, $content = '', $is_preview = false, $post_id = 0, $wp_block = false, $context = false)
{

	// Create id attribute allowing for custom "anchor" value.
	$id = 'block-home-makeadifference--' . $block['id'] . '-' . wp_unique_id();
	if (!empty($block['anchor'])) {
		$id = $block['anchor'];
	}

	// Create class attribute allowing for custom "className" and "align" values.
	$data_id = 'home-makeadifference';
	$className = 'dynamic-assets-load block block-home-makeadifference';
	if (!empty($block['className'])) {
		$className .= ' ' . $block['className'];
	}
	if (!empty($block['align'])) {
		$className .= ' align' . $block['align'];
	}
	// Load values and handle defaults.
  

	$cards = get_field('homemakeadifference_cards');
	$image = get_field('homemakeadifference_image');

?>

	<div data-id="<?php echo esc_attr($data_id); ?>" id="<?= esc_attr($id); ?>" class="<?= esc_attr($className); ?>">
		<div class="block-home-makeadifference__container container">
			<div class="block-home-makeadifference__row row justify-content-center">

				<div class="col-xl-11 col-xxl-10">

					<div class="block-home-makeadifference__row-inner row">			
						<?php if ($cards) : ?>
							<div class="block-home-makeadifference__cards">
								<div class="block-home-makeadifference__cards-row row row-cols-lg-2 row-cols-1">
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
	
								</div>
							</div>	
						<?php endif; ?>
	
						<?php if (isset($image) && !empty($image)) : ?>
							<div class="block-home-makeadifference__image">
								<?php Helpers::global_image($image, ['class' => 'block-home-makeadifference__photo', 'decorative' => false]); ?>
							</div>
						<?php endif; ?>
					</div>

					
				</div>

			</div>
		</div>
	</div>

<?php
}
