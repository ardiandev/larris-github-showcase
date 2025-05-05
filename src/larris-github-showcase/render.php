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
					 ⭐ <?php echo esc_html($repoData['stars']); ?> Stars
				 </p>
				 <p>
					 🍴 <?php echo esc_html($repoData['forks']); ?> Forks
				 </p>
			 </div>
			</div>
 
			 <?php if (!empty($repoData['description'])): ?>
				 <p><?php echo esc_html($repoData['description']); ?></p>
			 <?php endif; ?>
 
			 <p><strong>Source:</strong> <?php echo esc_html($repoData['source']); ?></p>
				<div class="btn-container">
					<a href="<?php echo esc_url($repoLink); ?>" target="_blank" rel="noopener noreferrer">
					<button>Visit repository</button>
					</a>
					<button>Case Study</button>
				</div>
			 </div>		 
	 <?php endif; ?>
 </div>
 