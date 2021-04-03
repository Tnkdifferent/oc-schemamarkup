<?php namespace Linkonoid\SchemaMarkup\Classes;

use Yaml;
use File;
use Cache;
use Cms\Classes\Theme;
use Cms\Classes\ComponentBase;
use October\Rain\Filesystem\Filesystem;
use Linkonoid\SchemaMarkup\Models\Settings;

/**
 * @package linkonoid\schemamarkup
 * @idea and sponsored by Mightyhaggis (https://mightyhaggis.com)
 * @coding Linkonoid (https://github.com/linkonoid, https://linkonoid.com)
 * @based on https://github.com/spatie/schema-org
**/

class Schemas
{
    public static $schemasCachePeriod = 3600;

    public static $schemasTypePrefix = '_';

    public static $schemasPath;

    public static function getSchemasByKey($key)
    {
        $schemas = self::getSchemasYaml();

    	if (isset($schemas[$key])) return $schemas[$key];
    }

    public static function getSchemasOptions()
    {
    	$options = [];
    	$schemas = self::getSchemasYaml();

        foreach ($schemas as $key => $value)
		{
			$options[$key] = $key;
		}

		return $options;
    }

    public static function getSchemasYaml()
    {
    	$yamls = [];
        $schemas = self::getSchemas();

        self::$schemasTypePrefix = Settings::instance()->get('schemasTypePrefix',self::$schemasTypePrefix);

		foreach ($schemas as $value)
		{
		    if (isset($value['yaml'][self::$schemasTypePrefix.'type'])){
		    	$type = $value['yaml'][self::$schemasTypePrefix.'type'];
		    	unset($value['yaml'][self::$schemasTypePrefix.'type']);
		    } else {
		    	$typeArr = explode('.',$value['filename'])[0];
	            $type = (isset($typeArr[1]) ? $typeArr[0] : $value['filename']);
		    }

			$yamls = array_merge($yamls, [$type => $value['yaml']]);
		}

		return $yamls;
    }

    public static function getSchemas()
    {
		$schemas = [];

		$settings = \Linkonoid\SchemaMarkup\Models\Settings::instance();
		self::$schemasPath = $settings->get('schemasPath');
		self::$schemasCachePeriod = $settings->get('schemasCachePeriod',self::$schemasCachePeriod);

        return self::getSchemasFrom();
    }

    public static function getSchemasPath()
    {
        if (empty(self::$schemasPath))
        {
			$theme = Theme::getActiveTheme();
			self::$schemasPath = $theme->getPath().'/schemas';
			self::$schemasPath = File::normalizePath(self::$schemasPath);
			if (!File::isDirectory(self::$schemasPath) || File::isDirectoryEmpty(self::$schemasPath))
			{
				self::$schemasPath = plugins_path('linkonoid/schemamarkup/schemas');
    		}
		}

  		return self::$schemasPath = File::normalizePath(self::$schemasPath);
    }


    public static function getSchemasFrom()
    {
		$schemas = [];

        self::$schemasPath = self::getSchemasPath();

        $schemas = Cache::remember('linkonoid_schemamarkup', self::$schemasCachePeriod, function(){

            $schemas = [];

		    if (!File::isDirectoryEmpty(self::$schemasPath)) {

		        $schemasFiles = File::allFiles(self::$schemasPath,true);

		        foreach ($schemasFiles as $schemaFile){
		        	$realPath = $schemaFile->getRealPath();
		        	$file = File::get($realPath);
                    $yaml = Yaml::parse($file);
                    $schemas[] = [
						'path' => $schemaFile->getPath(),
						'realPath' => $realPath,
						'filename' => $schemaFile->getFilename(),
						'basename' => $schemaFile->getBasename(),
						'pathname' => $schemaFile->getPathname(),
						'extension' => $schemaFile->getExtension(),
						'aTime' => $schemaFile->getATime(),
						'mTime' => $schemaFile->getMTime(),
						'cTime' => $schemaFile->getCTime(),
						'size' => $schemaFile->getSize(),
						'content' => $file,
						'yaml' => $yaml
                    ];
		        }
		    }

		    return $schemas;
		});

        return $schemas;
    }

}