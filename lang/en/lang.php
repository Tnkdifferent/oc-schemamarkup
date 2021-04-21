<?php return [

/**
 * @package linkonoid\schemamarkup
 * @idea and sponsored by Mightyhaggis (https://mightyhaggis.com)
 * @coding Linkonoid (https://github.com/linkonoid, https://linkonoid.com)
 * @based on https://github.com/spatie/schema-org
**/

    'plugin' => [
        'name' => 'Schema Markup',
        'description' => 'Use Schema.org markup to help improve your SEO'
    ],

    'permissions' => [
        'tab' => 'Linkonoid Frontend Bundle',
        'label' => 'Schema Markup',
    ],

    'components' => [
        'ldjson' => [
        	'name' => 'Schema Markup',
        	'description' => 'Use Schema.org markup to help improve your SEO',
        	'schema' => [
        		'title' => 'Template name',
        		'description' => 'Name of the Schema template to use'
        	],
        	'render' => [
        		'title' => 'Schema render',
        		'description' => 'Specify when to render this schema component, FALSE - defer rendering for later combined render'
        	],
        	'strip_slashes' => [
        		'title' => 'Strip Slashes',
        		'description' => 'Strip slashes from render result'
        	],
        	'custom' => [
        		'title' => 'Custom values',
        		'description' => 'Key in dot notation format (parent.child). Value can be text or {{ twig variable }}'
        	]
        ]
    ],

    'settings' => [
        'category' => 'Linkonoid Frontend Bundle',

        'tabs' => [
 	        'main' => 'Settings',
 	        'layouts' => 'Templates'
	    ],

        'labels' => [
            'configuration_section' => 'Configuration',
            'schemas_path' => 'Template Path',
            'schemas_cache_period' => 'Cache Life',
            'hooks_and_variables_section' => 'Hooks and variables',
            'schemas_type_prefix' => 'YAML definition for Schema @',
            'schemas_sub_node_prefix' => 'Sub template prefix',
            'schemas_layouts_section' => 'YAML templates',
            'schemas_layouts' => 'Layouts',
            'schemas_layouts_schema_name' => 'Template filename',
            'schemas_layouts_schema' => 'Schema template',
	    ],

        'placeholders' => [
            'schemas_path' => 'default theme/schemas or linkonoid/schemamarkup/schemas',
	    ],

	    'prompts' => [
            'schemas_layouts' => 'Add new Schema Template',
	    ],

        'comments_above' => [
            'schemas_path' => 'Path to load your templates from (optional)',
            'schemas_cache_period' => 'Cache life for the loaded template files (improving page load)',
            'hooks_and_variables_section' => 'Define characters that allow for YAML markup to become Schema',
            'schemas_type_prefix' => 'The specified character will be replaced with @ on rendering',
            'schemas_sub_node_prefix' => 'When used as part of a Value, the plugin will replace with the relevant schema template',
            'schemas_layouts_section' => 'Create and edit your YAML schema templates<br><br>
Templates can be recursive using the Sub Template Prefix as a value<br> Values can be dynamic using Twig markup {{ variable }}
',
            'schemas_layouts_schema' => 'Template using YAML markup',
            'schemas_type_prefix' => 'Replaced with @ on rendering. Example - "_type:" becomes "@type:" ',
            'schemas_sub_node_prefix' => 'Example - "company: &LocalBusiness"',
	    ],

        'comments' => [
            'schemas_path' => 'Path to load your templates from (optional)',
            'schemas_cache_period' => 'Cache life for the loaded template files (improving page load)',
            'hooks_and_variables_section' => 'Define characters that allow for YAML markup to become Schema',
	    ],
    ]
];