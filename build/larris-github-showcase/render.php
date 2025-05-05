<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

 $repoDataAttr = $attributes['repoData'] ?? null;
 $repoApi      = $repoDataAttr['api_url'] ?? null;
 $repoLink     = $attributes['repoLink'] ?? null;
 $transientKey = 'github_repo_data_' . md5($repoApi);
 $cachedData   = get_transient($transientKey);
 $repoData     = null;
 $repoPage 	   = $attributes['repoPage'] ?? null;

 if ($cachedData) {
	 // Use cached data
	 $repoData = $cachedData;
	 $repoData['source'] = 'transient';
 } elseif ($repoApi) {
	 // Fetch from GitHub API
	 $response = wp_remote_get($repoApi);
 
	 if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
		 $body = json_decode(wp_remote_retrieve_body($response), true);
 
		 if (is_array($body)) {
			 $repoData = [
				 'name'        => $body['name'],
				 'description' => $body['description'],
				 'stars'       => $body['stargazers_count'],
				 'forks'       => $body['forks_count'],
				 'api_url'     => $repoApi,
				 'source'      => 'api',
			 ];
			 set_transient($transientKey, $repoData, HOUR_IN_SECONDS);
		 }
	 }
 
	 // Fallback to editor data if API fails
	 if (!$repoData && $repoDataAttr) {
		 $repoData = $repoDataAttr;
		 $repoData['source'] = 'fallback';
		 set_transient($transientKey, $repoData, HOUR_IN_SECONDS);
	 }
 }
 ?>
 
 <div <?php echo get_block_wrapper_attributes(); ?>>
	 <?php if (!$repoData): ?>
		 <p>No repository data available.</p>
	 <?php else: ?>
		 <div class="name-info-container">
			 <h4 class="repo-name">
				 <a href="<?php echo esc_url($repoLink); ?>" target="_blank" rel="noopener noreferrer">
					 <?php echo esc_html(ucwords(str_replace('-', ' ', $repoData['name']))); ?>
				 </a>
			 </h4>
 
			 <div class="info-container">
			 <p>
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="16" height="16" fill="black">
					<path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"/>
				</svg>
				<?php echo esc_html($repoData['stars']); ?>
			</p>
			<p>
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="16" height="16" fill="black">
					<path d="M80 104a24 24 0 1 0 0-48 24 24 0 1 0 0 48zm80-24c0 32.8-19.7 61-48 73.3l0 38.7c0 17.7 14.3 32 32 32l160 0c17.7 0 32-14.3 32-32l0-38.7C307.7 141 288 112.8 288 80c0-44.2 35.8-80 80-80s80 35.8 80 80c0 32.8-19.7 61-48 73.3l0 38.7c0 53-43 96-96 96l-48 0 0 70.7c28.3 12.3 48 40.5 48 73.3c0 44.2-35.8 80-80 80s-80-35.8-80-80c0-32.8 19.7-61 48-73.3l0-70.7-48 0c-53 0-96-43-96-96l0-38.7C19.7 141 0 112.8 0 80C0 35.8 35.8 0 80 0s80 35.8 80 80zm208 24a24 24 0 1 0 0-48 24 24 0 1 0 0 48zM248 432a24 24 0 1 0 -48 0 24 24 0 1 0 48 0z"/>
				</svg>
				<?php echo esc_html($repoData['forks']); ?>
			</p>

			 </div>
			</div>
 
			 <?php if (!empty($repoData['description'])): ?>
				 <p><?php echo esc_html($repoData['description']); ?></p>
			 <?php endif; ?>
 
				<div class="btn-container">
					<a href="<?php echo esc_url($repoLink); ?>" target="_blank" rel="noopener noreferrer">
						<button>Visit repository</button>
					</a>
					<?php if (!empty($repoPage)): ?>
					<a href="<?php echo esc_url($repoPage); ?>" target="_blank" rel="noopener noreferrer">
						<button>Case Study</button>
					</a>
					 <?php endif; ?>
			 	</div>		 
	 <?php endif; ?>
 </div>
 