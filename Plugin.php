<?php namespace Linkonoid\SchemaMarkup;

use System\Classes\PluginBase;

/**
 * @package linkonoid\schemamarkup
 * @idea and sponsored by Mightyhaggis (https://mightyhaggis.com)
 * @coding Linkonoid (https://github.com/linkonoid, https://linkonoid.com)
 * @based on https://github.com/spatie/schema-org
**/

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            'Linkonoid\SchemaMarkup\Components\LdJson' => 'ldJson'
        ];
    }

}
