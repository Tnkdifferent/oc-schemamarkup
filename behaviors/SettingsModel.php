<?php namespace Linkonoid\SchemaMarkup\Behaviors;

use Event;

/**
 * @package linkonoid\schemamarkup
 * @idea and sponsored by Mightyhaggis (https://mightyhaggis.com)
 * @coding Linkonoid (https://github.com/linkonoid, https://linkonoid.com)
 * @based on https://github.com/spatie/schema-org
**/

/**
 * This behavior based on original OctoberCMS SettingsModel behavior
**/

/**
 * Settings model extension
 *
 * Add this the model class definition:
 *
 *     public $implement = ['Linkonoid.Behaviors.SettingsModel'];
 *     public $settingsCode = 'author_plugin_code';
 *     public $settingsFields = 'fields.yaml';
 *
**/

class SettingsModel extends \System\Behaviors\SettingsModel
{
    public $fieldValues = [];

    /**
     * Populate the field values from the database record.
     */
    public function afterModelFetch()
    {
        $this->fieldValues = $this->model->value ?: [];
        Event::fire('linkonoid.settings.beforeFetch', [$this]);
        $this->model->attributes = array_merge($this->fieldValues, $this->model->attributes);
        Event::fire('linkonoid.settings.afterFetch', [$this]);
    }

    public function replaceValues($arr)
    {
        foreach($arr as $key => $value){
        	array_set($this->fieldValues, $key, $value);
        }
    }

    public function newValues($arr,$key)
    {
        if (!empty($this->fieldValues)) {
        	array_forget($this->fieldValues, $key);
        }

        $this->replaceValues($arr);
    }

}
