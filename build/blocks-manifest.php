<?php
// This file is generated. Do not modify it manually.
return array(
	'larris-github-showcase' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'create-block/larris-github-showcase',
		'version' => '0.1.0',
		'title' => 'Larris Github Showcase',
		'category' => 'widgets',
		'icon' => 'smiley',
		'description' => 'Example block scaffolded with Create Block tool.',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false
		),
		'textdomain' => 'larris-github-showcase',
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./index.css',
		'style' => 'file:./style-index.css',
		'render' => 'file:./render.php',
		'viewScript' => 'file:./view.js',
		'attributes' => array(
			'repoLink' => array(
				'type' => 'string'
			),
			'repoData' => array(
				'type' => 'object'
			),
			'repoPage' => array(
				'type' => 'string'
			)
		)
	)
);
