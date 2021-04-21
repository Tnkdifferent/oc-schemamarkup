<?php namespace Linkonoid\SchemaMarkup\Components;

use Yaml;
use File;
use Cache;
use Cms\Classes\Theme;
use Spatie\SchemaOrg\Schema;
use Cms\Classes\ComponentBase;
use October\Rain\Filesystem\Filesystem;
use Linkonoid\SchemaMarkup\Models\Settings;
use Linkonoid\SchemaMarkup\Classes\Schemas;

/**
 * @package linkonoid\schemamarkup
 * @idea and sponsored by Mightyhaggis (https://mightyhaggis.com)
 * @coding Linkonoid (https://github.com/linkonoid, https://linkonoid.com)
 * @based on https://github.com/spatie/schema-org
**/

class LdJson extends ComponentBase
{
    public $schema = null;
    private $schemasSubNodePrefix = '&';

    public function componentDetails()
    {
        return [
            'name'        => 'linkonoid.schemamarkup::lang.components.ldjson.name',
            'description' => 'linkonoid.schemamarkup::lang.components.ldjson.description'
        ];
    }

     public function defineProperties()
    {
        $properties = $this->getPropertiesArray();
        if (!empty($properties['schema'])) return $properties;
        return $properties;
    }


    public function getPropertiesArray()
    {
        return [
            'schema' => [
                'title' 			=> 'linkonoid.schemamarkup::lang.components.ldjson.schema.title',
                'description'   	=> 'linkonoid.schemamarkup::lang.components.ldjson.schema.description',
                'type' 				=> 'dropdown',
                'required'      	=> true,
                'options' 			=> Schemas::getSchemasOptions(),
                'showExternalParam' => false,
            ],
            'render' => [
                'title' 			=> 'linkonoid.schemamarkup::lang.components.ldjson.render.title',
                'description' 		=> 'linkonoid.schemamarkup::lang.components.ldjson.render.description',
                'default'     		=> 1,
                'type'        		=> 'checkbox',
                'showExternalParam' => false,
            ],
            'stripSlashes' => [
                'title' 			=> 'linkonoid.schemamarkup::lang.components.ldjson.strip_slashes.title',
                'description' 		=> 'linkonoid.schemamarkup::lang.components.ldjson.strip_slashes.description',
                'default'     		=> 1,
                'type'        		=> 'checkbox',
                'showExternalParam' => false,
            ],
            'custom' => [
                'title'             => 'linkonoid.schemamarkup::lang.components.ldjson.custom.title',
                'description'       => 'linkonoid.schemamarkup::lang.components.ldjson.custom.description',
                'type' => 'dictionary',
                'showExternalParam' => false,
                'default' => [],
                'ignoreIfDefault' 	=> true,
        	]
        ];
    }

    public function getBuildSchema($schemaName, $mergeSchemas = [])
    {
        $schemas = Schemas::getSchemasYaml();

        $schemas = !empty($mergeSchemas) ? array_merge($schemas, $mergeSchemas) : $schemas;

        $buildSchema = function ($schemaName) use (&$buildSchema,&$schemas) {

            if (isset($schemas[$schemaName])){

            	array_walk($schemas[$schemaName], function($item, $key) use (&$buildSchema,&$schemas,&$schema)  {

					if (!is_array($item) && (substr(trim($item), 0, 1) === $this->schemasSubNodePrefix)) {

                    	$schemaName = substr(trim($item), 1);

                    	$schema[$key] = $buildSchema($schemaName);

					} else {

					   $schema[$key] = $item;
					}
            	});
            }

			return $schema;
        };

        return $buildSchema($schemaName);
    }

    public function getRenderSchema($schemaTpl)
    {
        $createSchema = function ($arr) use (&$createSchema) {

            array_walk($arr, function($item, $key) use (&$createSchema,&$schema)  {

				if (is_array($item)) {

					if (method_exists('\\Spatie\\SchemaOrg\\Schema',$key)) {

                    	$className = '\\Spatie\\SchemaOrg\\Schema::'.$key;

                    	if (!isset($schema)) {
							$schema = $className();
						}

					    foreach($createSchema($item) as $key => $value){

					    	$schema[$key] = is_array($value) ? $createSchema($value) : $this->twigRender($value);
					    }

					} else {

						$schema[$key] = $item;
					}

				} else {

					$schema[$key] = $this->twigRender($item);
                }

            });

			return $schema;
        };

        return $createSchema($schemaTpl);
    }

    protected function prepareVars()
    {
        $schemaName = $this->property('schema');
        $this->schema[$schemaName] = $this->getBuildSchema($schemaName);
    }

    public function init()
    {
    	$this->schemas_sub_node_prefix = Settings::instance()->get('schemasSubNodePrefix',$this->schemasSubNodePrefix);
        $this->prepareVars();
    }

    public function onRender()
    {
        $schemaName = $this->property('schema');

        $customVars = !empty($this->property('custom')) ? $customVars = $this->property('custom') : [];

        foreach ($customVars as $key => $value){
        	array_set($this->schema[$schemaName], $key, $this->twigRender($value));
        }

        $this->schema[$schemaName] = $this->getBuildSchema($schemaName,$this->schema);

        $this->page['schemas'] = array_merge((empty($this->page['schemas']) ? [] : $this->page['schemas']),$this->schema);

        if ($this->property('render') && is_array($this->page['schemas'])){
        	$schema = $this->getRenderSchema($this->page['schemas'])->toScript();
        	$this->page['schemas'] = null;
        	return $this->property('stripSlashes') ? stripslashes($schema) : $schema;
        }
    }

    private function twigRender($value)
    {
        if (!is_array($value) && !empty($value)){
        	if (count(explode('{{',$value))>1 && count(explode('}}',$value))>1){
				$tpl = $this->controller->getTwig()->createTemplate('{% autoescape false %}'.$value.'{% endautoescape %}');
				$value = $tpl->render($this->controller->vars);
                return $this->property('stripSlashes') ? stripslashes($value) : $value;
			}
		}

		return $value;
    }

}
