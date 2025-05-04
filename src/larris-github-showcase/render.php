<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */


 $repoData = $attributes['repoData'] ?? null;
 $repoLink = $attributes['repoLink'] ?? null;

 $repoApi = $repoData['api_url'] ?? "no api";

?>
<div <?php echo get_block_wrapper_attributes(); ?>>
    <?php if (!$repoData): ?>
        <p>No repository data available.</p>
    <?php else: ?>
        <div class="name-info-container">
				<h4 class="repo-name">
						<a href="<?php echo esc_url($repoLink); ?>" target="_blank" rel="noopener noreferrer">
							<?php echo esc_html( ucwords( str_replace( '-', ' ', $repoData['name'] ) ) ); ?>
						</a>
					</h4>

				<div class="info-container">
					<p>
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="16" height="16" fill="black">
							<path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"/>
						</svg>
						<?php echo esc_html($repoData['stargazers_count']); ?> Stars
					</p>
					<p> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="black" viewBox="0 0 512 512">
						<path d="M352 0c-17.7 0-32 14.3-32 32v160c0 17.7-14.3 32-32 32s-32-14.3-32-32V32c0-17.7-14.3-32-32-32s-32 14.3-32 32v160c0 51.9 39.5 94.5 90 101.6V480c0 17.7 14.3 32 32 32s32-14.3 32-32V293.6c50.5-7.1 90-49.7 90-101.6V32c0-17.7-14.3-32-32-32s-32 14.3-32 32v160c0 17.7-14.3 32-32 32s-32-14.3-32-32V32c0-17.7-14.3-32-32-32z"/>
					</svg>
					<?php echo esc_html($repoData['forks_count']); ?> Forks</p>

				</div>
		</div>

            <?php if (!empty($repoData['description'])): ?>
                <p><?php echo esc_html($repoData['description']); ?></p>
            <?php endif; ?>
        
    <?php endif; ?>

</div>

