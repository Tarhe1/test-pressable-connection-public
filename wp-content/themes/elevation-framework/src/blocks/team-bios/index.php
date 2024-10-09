<?php

use ElevationFramework\Lib\BlockLibrary\Helpers;

/**
 * teams Block Callback Function.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
function teambios_block_render_callback($block, $content = '', $is_preview = false, $post_id = 0, $wp_block = false, $context = false)
{

	// Create id attribute allowing for custom "anchor" value.
	$id = 'block__team--' . $block['id'] . '-' . wp_unique_id();
	if (!empty($block['anchor'])) {
		$id = $block['anchor'];
	}

	// Create class attribute allowing for custom "className" and "align" values.
	$data_id = 'team-bios';
	$className = 'dynamic-assets-load block block__team';
	if (!empty($block['className'])) {
		$className .= ' ' . $block['className'];
	}
	if (!empty($block['align'])) {
		$className .= ' align' . $block['align'];
	}

	// Load values and handle defaults.
	$teams_header = get_field('teams_header');
	$items = get_field('teams_items');
	$teams_grid = get_field('teams_grid');
	$teams_layout = get_field('teams_layout');
	$teams_hr = get_field('teams_hr');

	$content_alignment = get_field('content_alignment');
	$container_alignment = get_field('container_alignment');
	$container_width = get_field('container_width');
	$offset_container_width = get_field('offset_container_width');
	if (get_field('padding_disable')) {
		if (!get_field('padding_top')) {
			$pd_top = ' pt-0 ';
		} else {
			$pd_top = '';
		}
		if (!get_field('padding_bottom')) {
			$pd_bottom = ' pb-0 ';
		} else {
			$pd_bottom = '';
		}
	} else {
		$pd_top = '';
		$pd_bottom = '';
	}

	$swiper = get_field('slider_settings');
	$swiper_attrs = Helpers::get_swiper_attrs($swiper, [
		'centered-slides' => false,
		'grab-cursor' => true,
		'slides-per-view' => 1,
		'space-between' => 30,
		'slides-per-view-md' => 2,
		'slides-per-view-xl' => 3,
		'slide-to-click-slide' => true,
		'delay' => 7000,
		'disable-on-interaction' => false
	]);

	$pulled_by_post_type = get_field('pulled_by_post_type');
	$teams_post_type = get_field('teams_post_type');

?>
	<div data-id="<?php echo esc_attr($data_id); ?>" id="<?= esc_attr($id); ?>" class="<?= esc_attr($className) . esc_attr($pd_top) . esc_attr($pd_bottom); ?>">
		<div class="block__container container">
			<div class="block__row row justify-content-<?= esc_html($container_alignment); ?>">
				<div class="block__col col-xl-<?= esc_html($container_width); ?> col-12 offset-xl-<?= esc_html($offset_container_width); ?>">

					<div class="block__content <?= esc_html($content_alignment); ?>">
						<?php if ($teams_header != NULL) : ?>
							<?php Helpers::global_content($teams_header['editor'], $teams_header['links']); ?>
						<?php endif; ?>
					</div>

					<?php if (!$pulled_by_post_type && !empty($items)) : $count = 1; ?>

						<div class="block__team block__team<?= '--' . esc_html($teams_layout); ?>">

							<?= $teams_hr ? '<hr class="line hr-top dark">' : ''; ?>

							<?php if ($teams_layout == "carousel") : ?>

								<div class="swiper__teams swiper__container--team" id="swiper-<?= esc_attr($id); ?>" <?php echo esc_attr($swiper_attrs); ?>>

									<div class="swiper-wrapper">

									<?php elseif ($teams_layout == "card-filter") : ?>

										<div class="filterTeam">

											<button class="filterTeam__item active" data-filter="all">all</button>

											<?php $filter_items = []; ?>
											<?php foreach ($items as $item) : ?>

												<?php if (isset($item['filter']) && !empty($item['filter'])) : ?>
													<?php if (!in_array($item['filter'], $filter_items)) : ?>
														<button class="filterTeam__item" data-filter="<?= sanitize_title($item['filter']); ?>">
															<?= esc_html($item['filter']); ?>
														</button>
														<?php $filter_items[] = $item['filter']; ?>
													<?php endif; ?>
												<?php endif; ?>
											<?php endforeach; ?>

										</div>

										<div class="block__team-row row row-cols-xl-<?= esc_html($teams_grid); ?> row-cols-md-2 row-cols-1">

										<?php else : ?>

											<div class="block__team-row row row-cols-xl-<?= esc_html($teams_grid); ?> row-cols-md-2 row-cols-1">

											<?php endif; ?>

											<?php foreach ($items as $item) : ?>

												<?php if ($teams_layout == "carousel") : ?>

													<div class="card swiper-slide">

													<?php else : ?>

														<div class="col-card col <?= sanitize_title($item['filter']); ?>">

															<div class="card">

															<?php endif; ?>
															<?php if (isset($item['image']) && !empty($item['image'])) : ?>

															<?php if (isset($item['link']) && !empty($item['link'])) : ?>
																<a class="card__image card__image--link" href="<?= esc_url($item['link']['url']); ?>" target="<?= esc_attr($item['link']['target']); ?>">
																<?php else : ?>
																	<button class="card__image card__image--link" type="button" data-bs-toggle="modal" data-bs-target="#teamBlockModal<?= $count . '-' . $block['id']; ?>" aria-label="open pop-up <?= esc_html($item['title']); ?>">
																	<?php endif; ?>

																		<?= Helpers::global_image(
																			$item['image'],
																			[
																				'echo' => false,
																				'class' =>  'card__image card__image--hover-effect',
																				'is_figure' => false
																			]
																		); ?>

																	<?php if (isset($item['link']) && !empty($item['link'])) : ?>
																</a>
															<?php else : ?>
																</button>
															<?php endif; ?>
															<?php endif; ?>

															<div class="card__body">

																<?php if ($teams_layout == "card-filter") : ?>
																	<?php if (isset($item['filter']) && !empty($item['filter'])) : ?>
																		<span class="card__body__filter h5"><?= wp_kses_post($item['filter']); ?></span>
																	<?php endif; ?>
																<?php endif; ?>

																<?php if (isset($item['title']) && !empty($item['title'])) : ?>

																	<div class="card__body__title h5">

																		<?php if (isset($item['link']) && !empty($item['link'])) : ?>
																			<a href="<?= esc_url($item['link']['url']); ?>" target="<?= esc_attr($item['link']['target']); ?>">
																			<?php else : ?>
																				<button class="card__body__title--link" type="button" data-bs-toggle="modal" data-bs-target="#teamBlockModal<?= $count . '-' . $block['id']; ?>">
																				<?php endif; ?>

																				<?= wp_kses_post($item['title']); ?>

																				<?php if (isset($item['link']) && !empty($item['link'])) : ?>
																			</a>
																		<?php else : ?>
																			</button>
																		<?php endif; ?>

																	</div>

																<?php endif; ?>

																<?php if (isset($item['position']) && !empty($item['position'])) : ?>
																	<span class="card__body__position h5"><?= wp_kses_post($item['position']); ?></span>
																<?php endif; ?>

																<?php if (isset($item['description']) && !empty($item['description'])) : ?>

																	<?php if ($teams_layout == "carousel") : ?>
																		<div class="card__body__excerpt line-clamp-4">
																		<?php else : ?>
																			<div class="card__body__excerpt">
																			<?php endif; ?>
																			<p><?= wp_kses_post($item['description']); ?></p>
																			</div>
																		<?php endif; ?>

																		<hr class="line card__body__divider">

																		<?php if ($teams_layout == "license") : ?>
																			<div class="card__body__wrapper">
																			<?php endif; ?>

																			<div class="card__body__contact">
																				<?php if (isset($item['email']) && !empty($item['email'])) : ?>
																					<div class="card__body__contact--email">
																						<a href="mailto:<?= esc_html($item['email']); ?>"><?= esc_html($item['email']); ?></a>
																					</div>
																				<?php endif; ?>

																				<?php if (isset($item['phone']) && !empty($item['phone'])) : ?>
																					<div class="card__body__contact--phone">
																						<?= wp_kses_post($item['phone']); ?>
																					</div>
																				<?php endif; ?>
																			</div>

																			<?php if (isset($item['social_network']) && !empty($item['social_network'])) : ?>

																				<hr class="line card__body__divider">

																				<div class="card__body__social">

																					<ul class=" social-network">

																						<?php foreach ($item['social_network'] as $social) : ?>

																							<?php if (isset($social['url']) && !empty($social['url']) || isset($social['email']) && !empty($social['email'])) : ?>

																								<li>
																									<?php if (isset($social['email']) && !empty($social['email'])) : ?>
																										<a href="mailto:<?= esc_html($social['email']); ?>" target="_blank" class="dark-icon" rel="noopener noreferrer">
																										<?php else : ?>
																											<a href="<?= esc_html($social['url']); ?>" target="_blank" class="dark-icon" rel="noopener noreferrer">
																											<?php endif; ?>
																											<span class="icon--<?= esc_html($social['icon']); ?>"></span>
																											<span class="visually-hidden"><?= esc_html($social['icon']); ?></span>
																											</a>
																								</li>

																							<?php endif; ?>

																						<?php endforeach; ?>

																					</ul>

																				</div>

																			<?php endif; ?>

																			<?php if ($teams_grid == "license") : ?>
																			</div>
																		<?php endif; ?>

																		</div>

																		<?php if ($teams_layout == "carousel") : ?>

															</div>

														<?php elseif ($teams_layout == "license") : ?>


															</div>

														</div>
													</div>

												<?php else : ?>

											</div>

										</div>

									<?php endif; ?>

									<div class="modal fade" id="teamBlockModal<?= $count . '-' . $block['id']; ?>" role="dialog" tabindex="-1" aria-labelledby="teamBlockModal<?= $count . '-' . $block['id']; ?>Title" aria-hidden="true">
										<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title visually-hidden" id="teamBlockModal<?= $count . '-' . $block['id']; ?>Title">teamBlockModal<?= $count . '-' . $block['id']; ?>Title</h5>
													<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
														<span aria-hidden="true" class="visually-hidden">close modal</span>
													</button>
												</div>
												<div class="modal-body">
													<div class="team__modal">
														<div class="team__modal__profile">

															<?php if (isset($item['image']) && !empty($item['image'])) : ?>
																<?= Helpers::global_image(
																	$item['image'],
																	[
																		'echo' => false,
																		'class' => 'team__modal__image ratio ratio-1x1',
																	]
																); ?>
															<?php endif; ?>

															<?php if ($item['email'] || $item['phone'] || $item['social_network']) : ?>

																<div class="team__modal__body">

																	<?php if ($item['email'] || $item['phone']) : ?>

																		<div class="team__modal__contact">
																			<?php if (isset($item['email']) && !empty($item['email'])) : ?>
																				<div class="team__modal__contact--email">
																					<a href="mailto:<?= esc_html($item['email']); ?>"><?= esc_html($item['email']); ?></a>
																				</div>
																			<?php endif; ?>

																			<?php if (isset($item['phone']) && !empty($item['phone'])) : ?>
																				<div class="team__modal__contact--phone">
																					<?= wp_kses_post($item['phone']); ?>
																				</div>
																			<?php endif; ?>
																		</div>

																	<?php endif; ?>

																	<?php if (isset($item['social_network']) && !empty($item['social_network'])) : ?>

																		<ul class="card__body__social social-network">

																			<?php foreach ($item['social_network'] as $social) : ?>

																				<?php if (isset($social['url']) && !empty($social['url']) || isset($social['email']) && !empty($social['email'])) : ?>

																					<li>
																						<?php if (isset($social['email']) && !empty($social['email'])) : ?>
																							<a href="mailto:<?= esc_html($social['email']); ?>" target="_blank" class="dark-icon" rel="noopener noreferrer">
																							<?php else : ?>
																								<a href="<?= esc_html($social['url']); ?>" target="_blank" class="dark-icon" rel="noopener noreferrer">
																								<?php endif; ?>
																								<span class="icon--<?= esc_html($social['icon']); ?>"></span>
																								<span class="visually-hidden"><?= esc_html($social['icon']); ?></span>
																								</a>
																					</li>

																				<?php endif; ?>

																			<?php endforeach; ?>

																		</ul>

																	<?php endif; ?>

																</div>

															<?php endif; ?>

														</div>

														<div class="team__modal__info">

															<?php if (isset($item['title']) && !empty($item['title'])) : ?>
																<span class="team__modal__title h4">
																	<?= wp_kses_post($item['title']); ?>
																</span>
															<?php endif; ?>

															<?php if (isset($item['position']) && !empty($item['position'])) : ?>
																<span class="team__modal__position"><?= wp_kses_post($item['position']); ?></span>
															<?php endif; ?>

															<hr class="line team__modal__divider">

															<?php if (isset($item['description']) && !empty($item['description'])) : ?>
																<div class="team__modal__excerpt">
																	<p><?= wp_kses_post($item['description']); ?></p>
																</div>
															<?php endif; ?>

														</div>

													</div>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn-close" data-bs-dismiss="modal"><span class="visually-hidden">Close Popup</span></button>
												</div>
											</div>
										</div>
									</div>

									<?php $count++; ?>
								<?php endforeach; ?>

								<?php if ($teams_layout == "carousel") : ?>

									</div>
									<div class="swiper__container-controls center">
										<div class="swiper-button-prev"></div>
										<div class="swiper-pagination"></div>
										<div class="swiper-button-next"></div>

										<?php if ($count > 3) : ?>
											<button type="button" aria-label="play" class="swiper-button-play-pause">
												<span class="icon" aria-hidden="true"></span>
											</button>
										<?php endif; ?>

									</div>
								</div>
							<?php else : ?>

						</div>

					<?php endif; ?>
				<?php endif; ?>


				<?php if ($pulled_by_post_type) :

				?>

					<div class="block__team block__team<?= '--' . esc_html($teams_layout); ?>">

						<?= $teams_hr ? '<hr class="line hr-top dark">' : ''; ?>

						<?php if ($teams_layout == "carousel") : ?>

							<div class="swiper__teams swiper__container--team" id="swiper-<?= esc_attr($id); ?>" <?php echo esc_attr($swiper_attrs); ?>>

								<div class="swiper-wrapper">

								<?php elseif ($teams_layout == "card-filter") : ?>

									<div class="filterTeam">

										<button class="filterTeam__item active" data-filter="all">all</button>

										<?php $filter_items = []; ?>
										<?php
										if (!empty($teams_post_type)) : $count = 1;

											foreach ($teams_post_type as $single_team) :

												$filter = get_field('filter', $single_team); ?>
												<?php if (isset($filter)) : ?>
													<?php if (!in_array($filter, $filter_items)) : ?>
														<button class="filterTeam__item" data-filter="<?= sanitize_title($filter); ?>">
															<?= esc_html($filter); ?>
														</button>
														<?php $filter_items[] = $filter; ?>
													<?php endif; ?>
												<?php endif; ?>
										<?php endforeach;
										endif;
										?>

									</div>

									<div class="block__team-row row row-cols-xl-<?= esc_html($teams_grid); ?> row-cols-md-2 row-cols-1">

									<?php else : ?>

										<div class="block__team-row row row-cols-xl-<?= esc_html($teams_grid); ?> row-cols-md-2 row-cols-1">

										<?php endif; ?>

										<?php
										if (!empty($teams_post_type)) : $count = 1;

											foreach ($teams_post_type as $id_team) :
												$filter = get_field('filter', $id_team);
												$name = get_the_title($id_team);
												$link = get_the_permalink($id_team);
												$position = get_field('position', $id_team);
												$email = get_field('email', $id_team);
												if (has_post_thumbnail()) {
													$image       = get_the_post_thumbnail_url();
													$thumbnailID = get_post_thumbnail_id($id_team);
													$altImage    = sanitize_text_field(get_post_meta($thumbnailID, '_wp_attachment_image_alt', true));
												} else {
													$image    = get_template_directory_uri() . '/src/assets/images/default.svg';
												}
										?>

												<?php if ($teams_layout == "carousel") : ?>

													<div class="card swiper-slide">

													<?php else : ?>

														<div class="col-card col <?= sanitize_title($filter); ?>">

															<div class="card">

															<?php endif; ?>

															<?php if (isset($link)) : ?>
																<a class="card__image card__image--link" href="<?= esc_url($link); ?>">
																<?php else : ?>
																	<button class="card__image card__image--link" type="button" data-bs-toggle="modal" data-bs-target="#teamBlockModal<?= $count . '-' . $block['id']; ?>" aria-label="open pop-up <?= esc_html($name); ?>">
																	<?php endif; ?>

																	<?php if (isset($image) && !empty($image)) : ?>
																		<?= Helpers::global_image(
																			$image,
																			[
																				'echo' => false,
																				'class' =>  'card__image card__image--hover-effect',
																				'is_figure' => false
																			]
																		); ?>
																	<?php endif; ?>

																	<?php if (isset($link)) : ?>
																</a>
															<?php else : ?>
																</button>
															<?php endif; ?>

															<div class="card__body">

																<?php if ($teams_layout == "card-filter") : ?>
																	<?php if (isset($filter)) : ?>
																		<span class="card__body__filter h5"><?= esc_html($filter); ?></span>
																	<?php endif; ?>
																<?php endif; ?>

																<?php if (isset($name) && !empty($name)) : ?>

																	<div class="card__body__title h5">

																		<?php if (isset($link)) : ?>
																			<a href="<?= esc_url($link); ?>">
																			<?php else : ?>
																				<button class="card__body__title--link" type="button" data-bs-toggle="modal" data-bs-target="#teamBlockModal<?= $count . '-' . $block['id']; ?>">
																				<?php endif; ?>

																				<?= esc_html($name); ?>

																				<?php if (isset($link)) : ?>
																			</a>
																		<?php else : ?>
																			</button>
																		<?php endif; ?>

																	</div>

																<?php endif; ?>

																<?php if (isset($position)) : ?>
																	<span class="card__body__position h5"><?= esc_html($position); ?></span>
																<?php endif; ?>

																<?php if (isset($description)) : ?>

																	<?php if ($teams_layout == "carousel") : ?>
																		<div class="card__body__excerpt line-clamp-4">
																		<?php else : ?>
																			<div class="card__body__excerpt">
																			<?php endif; ?>
																			<p><?= wp_kses_post($description); ?></p>
																			</div>
																		<?php endif; ?>

																		<hr class="line card__body__divider">

																		<?php if ($teams_layout == "license") : ?>
																			<div class="card__body__wrapper">
																			<?php endif; ?>

																			<div class="card__body__contact">
																				<?php if (isset($email)) : ?>
																					<div class="card__body__contact--email">
																						<a href="mailto:<?= esc_html($email); ?>"><?= esc_html($email); ?></a>
																					</div>
																				<?php endif; ?>

																				<?php if (isset($phone) && !empty($phone)) : ?>
																					<div class="card__body__contact--phone">
																						<?= wp_kses_post($phone); ?>
																					</div>
																				<?php endif; ?>
																			</div>

																			<?php if (isset($social_network) && !empty($social_network)) : ?>

																				<hr class="line card__body__divider">

																				<div class="card__body__social">

																					<ul class=" social-network">

																						<?php foreach ($social_network as $social) : ?>

																							<?php if (isset($social['url']) && !empty($social['url']) || isset($social['email']) && !empty($social['email'])) : ?>

																								<li>
																									<?php if (isset($social['email']) && !empty($social['email'])) : ?>
																										<a href="mailto:<?= esc_html($social['email']); ?>" target="_blank" class="dark-icon" rel="noopener noreferrer">
																										<?php else : ?>
																											<a href="<?= esc_html($social['url']); ?>" target="_blank" class="dark-icon" rel="noopener noreferrer">
																											<?php endif; ?>
																											<span class="icon--<?= esc_html($social['icon']); ?>"></span>
																											<span class="visually-hidden"><?= esc_html($social['icon']); ?></span>
																											</a>
																								</li>

																							<?php endif; ?>

																						<?php endforeach; ?>

																					</ul>

																				</div>

																			<?php endif; ?>

																			<?php if ($teams_grid == "license") : ?>
																			</div>
																		<?php endif; ?>

																		</div>

																		<?php if ($teams_layout == "carousel") : ?>

															</div>

														<?php elseif ($teams_layout == "license") : ?>


															</div>

														</div>
													</div>

												<?php else : ?>

										</div>

									</div>

								<?php endif; ?>


								<?php $count++; ?>
						<?php endforeach;
										endif;
						?>

						<?php if ($teams_layout == "carousel") : ?>

								</div>
								<div class="swiper__container-controls center">
									<div class="swiper-button-prev"></div>
									<div class="swiper-pagination"></div>
									<div class="swiper-button-next"></div>

									<?php if ($count > 3) : ?>
										<button type="button" aria-label="play" class="swiper-button-play-pause">
											<span class="icon" aria-hidden="true"></span>
										</button>
									<?php endif; ?>

								</div>
							</div>
						<?php else : ?>
					</div>
				<?php endif; ?>

			<?php endif; ?>

			<?= $teams_hr ? '<hr class="line hr-bottom dark">' : ''; ?>

				</div>

			</div>
		</div>
	</div>

<?php
}
