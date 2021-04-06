<?php namespace Linkonoid\SchemaMarkup\Models;

use Yaml;
use File;
use Flash;
use Event;
use Model;
use Cache;
use System\Classes\SettingsManager;
use Linkonoid\SchemaMarkup\Classes\Schemas;

/**
 * @package linkonoid\schemamarkup
 * @idea and sponsored by Mightyhaggis (https://mightyhaggis.com)
 * @coding Linkonoid (https://github.com/linkonoid, https://linkonoid.com)
 * @based on https://github.com/spatie/schema-org
**/

class Settings extends Model
{
    public $implement = [
        '@Linkonoid.SchemaMarkup.Behaviors.SettingsModel',
        //'@RainLab.Translate.Behaviors.TranslatableModel',
    ];

    public $translatable = [];

    public $settingsCode = 'linkonoid_schemamarkup_settings';

    public $settingsFields = 'fields.yaml';

    public function __construct() {
        parent::__construct();
        Event::listen('linkonoid.settings.beforeFetch', [$this, 'beforeModelFetch']);
    }

    public function initSettingsData(){

        $this->beforeModelFetch($this);
        $this->_schemasLayouts = $this->fieldValues['_schemasLayouts'];
    }

    public function schemasModelSettingsLoad(){

		Schemas::$schemasPath = $this->schemasPath;
		Schemas::$schemasCachePeriod = $this->schemasCachePeriod;

        $schemas = Schemas::getSchemasFrom();
        return $schemas;
    }

    public function beforeModelFetch($model)
    {
        Cache::forget('linkonoid_schemamarkup');

        $schemasArr = $this->schemasModelSettingsLoad();

        $newSchemasArr = [];
        foreach ($schemasArr as $key => $value){
        	$newSchemasArr['_schemasLayouts.'.$key.'.schema_name'] = $value['filename'];
        	$newSchemasArr['_schemasLayouts.'.$key.'.schema'] = $value['content'];//Yaml::render([$key => $value]);
        }

        $model->newValues($newSchemasArr,'_schemasLayouts');
    }

    public function beforeSave()
    {
        if (isset($this->value) && isset($this->value['_schemasLayouts'])){

	        $schemas = $this->schemasModelSettingsLoad();
	        $schemasLayouts = $this->value['_schemasLayouts'];

	        foreach ($schemasLayouts as $key => $value){

	            $path = Schemas::getSchemasPath();

	            $pathArr = explode('.',$value['schema_name']);
	            $schema_name = (isset($pathArr[1]) ? $pathArr[0] : $value['schema_name']).'.'.'yaml';

	            $fullPath = File::normalizePath($path.'/'.$schema_name);

                if (!empty($value['schema'])){

					try{
						Yaml::parse($value['schema']);
					} catch (\Exception $ex) {
						Flash::success('YAML Error: '.$ex->getMessage());
                        return;
					}
				}

	        	if (isset($schemas[$key])){

	            	if(($schemas[$key]['content'] === $value['schema']) && ($key === $value['schema_name'])) continue;

	        		File::put($schemas[$key]['realPath'], $value['schema']);
	        		if ($key !== $value['schema_name']){
	        			File::move($schemas[$key]['realPath'], $fullPath);
	        		}
	        	} else {
	                File::put($fullPath, $value['schema']);
	        	}
	        }

	        $removes = array_diff(array_keys($schemas),array_keys($schemasLayouts));
	        foreach ($removes as $key){
	        	if (isset($schemas[$key])) {
	        		File::delete($schemas[$key]['realPath']);
	        	}
	        }

        	unset($this->attributes->value['_schemasLayouts']);

        	Cache::forget('linkonoid_schemamarkup');
        }
    }
}