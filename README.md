#Schema Markup

A plugin that enables dynamically generates structured data.
Structured data (JSON-LD schema) based on Schema.org that can be used on a webpage to provide search engines with rich data in a format they can be use to create snippets of information. These snippets can enhance your search results and potentially help to improve your sites visibility / SEO.
A snippet could be your companies contact details, a carousel of products, reviews and much more.
See Google's search engine gallery for details of snippet and structured data the support.

##Features
- Easy for use - add component (or multiple components) "ldJson" on layout, page, partial and enjoi!
- Support Dynamic Twig-variables
- Support Custom Values as key/value from component alias define
- Support recursive Nested schema

##Live demo
>(schemamarkup/admin): https://schemamarkup.test.linkonoid.com/backend/backend/auth/signin


##Sponsored information
Thanks Mightyhaggis for your support.






#Documentation

This is very brief information. More information can be found on the support forum or see a demo version of the plugin, where all sorts of settings are implemented (they are also test ones).



##Settings

###Cache Life
It's the templates cache (loaded templates files into an array) to null-time reading from disk, since had to recursively read the files (it is updated either after the expiration of the time specified in the settings, or when the layouts were updated).
Twig and CMS have their own caches.


###Templates path
The priority of the paths is as follows:
- a) Custom path in Settings
- b) /temlates/activeTheme/schemas
- c) /plugins/linkonoid/schemaorgextendable/schemas

###YAML definition for Schema @
A problem with Yaml - it does not understand the "@" symbol in the definition of an array key, so you have to enclose it in quotes (but agree, even this option is more convenient than defining a schema in json).
For this reason, I changed '@type' to '_type', since there is no need to enclose it in quotes (but in any case, you can set it in the settings, as well as the symbol for nested layouts).


###Example schema-template

```
_type: Organization
"@id": "{{ ''|app }}"
name : "{{ test }}"
url: "{{ ''|app }}"
logo: "{{ this.theme.company_logo.path }}"
ContactPoint:
    telephone : "{{ this.theme.company_telephone }}"
    contactType: "customer service"

```


##Frontend


###Include in layout or partial
The "ldJson" plugin component can be integrated into any OctoberCMS element that supports the use of standard CMS components (Layouts, Pages, Partials).

###Dynamic variables
Plugin supports all avaiables Twig variables ans Twig syntaxis in templates and in users key/value variables.
Dynamic variables that are substituted into schematic templates can be called and used with templates in three different ways:
- From the component, through the "Custom value" key/value pairs 
Support key in dot notation format (parent.child). Value can be text or {{ twig_variable }} or link on &nestedSchema)
- From the page, pass the variable for rendering via the Code-section: onStart () {$this['twig_variable'] = '.....'} and use the template in the schema definition {{twig_variable}}
To pass an array of elements to template, use the Twig filter {{array_variable | json_encode}}
- The variable can be set directly in the code section (through the key in the schema: `` array_set($this->componentAlias->schema['schemaName'], 'schemaKey', $customVariable);`` ), without defining it via Twig markup.


###Nested schema
Use "&" (or your custom prefix from Settings) for define nested schema on Templates in this format "address: &PostalAddress"


