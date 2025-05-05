import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { TextControl, PanelBody, Button, Spinner } from '@wordpress/components';
import { useState} from '@wordpress/element';
import './editor.scss';

export default function Edit(props) {
	const { attributes, setAttributes } = props;
	const { repoLink, repoData, repoPage } = attributes;
	const [loading, setLoading] = useState(false);
	const [error, setError] = useState(null);


	const fetchData = async (link) => {
		try {
			const regex = /^https?:\/\/github\.com\/([^/]+)\/([^/]+)\/?$/;
			const match = link.match(regex);

			if (!match) {
				throw new Error('Invalid GitHub URL');
			}

			const user = match[1];
			const repo = match[2];

			setLoading(true);
			setError(null);

			const API = await `https://api.github.com/repos/${user}/${repo}`;

			const response = await fetch(API);
	
			if (!response.ok) {
				throw new Error(`GitHub API Error: ${response.status}`);
			}

			const data = await response.json();
			setAttributes({
				repoData: {
					name: data.name,
					description: data.description,
					stars: data.stargazers_count,
					forks: data.forks_count,
					api_url: `https://api.github.com/repos/${user}/${repo}`

				}
			});
						
		} catch (err) {
			setError(err.message);
			setAttributes({ repoData: null });  
		} finally {
			setLoading(false);
		}
	};

	const handleFetchClick = () => {
		if (repoLink) {
			fetchData(repoLink);
		}
	};

	const renderRepo = () => {
		if (loading) return <Spinner />;
		if (error) return <p style={{ color: 'red' }}>{error}</p>;
		if (!repoData) return <p>No repo data. Please fetch a repo.</p>;

		return (
				<>
				<div className="name-info-container">
				<h4 className="repo-name">
					<a href={repoData.html_url} target="_blank" rel="noopener noreferrer">
						{repoData.name
						.replace(/-/g, ' ')          
						.replace(/\b\w/g, (c) => c.toUpperCase())}  
					</a>
				</h4>
					<div className='info-container'>
						<p>⭐ Stars: {repoData.stars}</p>
						<p>🍴 Forks: {repoData.forks}</p>

					</div>
				</div>
				<p>{repoData.description}</p>
				<div className='btn-container'>
					<button>Visit Repository</button>
					{repoPage ? <button>Case Study</button> : ""}
				</div>
				</>
		);
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('GitHub Repository', 'larris-github-showcase')}>
					<TextControl
						label={__('GitHub Repo Link', 'larris-github-showcase')}
						value={repoLink}
						onChange={(value) => setAttributes({ repoLink: value })}
					/>
					<TextControl
						label="Page about the repository" 
						help="Enter the URL of the page that provides detailed information or a case study about this repository." 
						value={repoPage}
						onChange={(value) => setAttributes({ repoPage: value })}
					/>
					<Button
						variant="primary"
						onClick={handleFetchClick}
						style={{ marginTop: '1em' }}
					>
						{__('Fetch Repository', 'larris-github-showcase')}
					</Button>
				</PanelBody>
			</InspectorControls>

			<div {...useBlockProps()}>
				{renderRepo()}
			</div>
		</>
	);
}
