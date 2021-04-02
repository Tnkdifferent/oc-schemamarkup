# Schema Markup

A plugin that enables dynamically generates structured data.
Structured data (JSON-LD schema) based on Schema.org that can be used on a webpage to provide search engines with rich data in a format they can be use to create snippets of information. These snippets can enhance your search results and potentially help to improve your sites visibility / SEO.
A snippet could be your companies contact details, a carousel of products, reviews and much more.
See Google's search engine gallery for details of snippet and structured data the support.

## Features
- Easy for use - add component (or multiple components) "ldJson" on layout, page, partial and enjoi!
- Support Dynamic Twig-variables
- Support Custom Values as key/value from component alias define
- Support recursive Nested schema

## Live demo
>(schemamarkup/admin): https://schemamarkup.test.linkonoid.com/backend/backend/auth/signin


## Sponsored information
Thanks Mightyhaggis for your support.




# Documentation

This is very brief information. More information can be found on the support forum or see a demo version of the plugin, where all sorts of settings are implemented (they are also test ones).

## Settings


### Templates path
The priority of the paths is as follows:
- a) Custom path in Settings
- b) /temlates/activeTheme/schemas
- c) /plugins/linkonoid/schemaorgextendable/schemas

### Cache Life
As templates can be recussive and require multiple file reads cache is used to reduce this need and keep your site running fast.
The cache automatically expires upon the time limit (seconds) or when a layout being updated.

### YAML definition for Schema @
YAML does not understand the ***@*** symbol in the definition of an array key, this option allow you to markup correctly and have the correct syntax generated. Another option woulld be to wrap the key in "quotes".

### Sub template prefix
Used to indicate that the *value* is another template that should be included and replace *value*

### Example schema-template

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


## Frontend

### Include in layout or partial
The *Schema Markup* plugin component can be included in your website using the standard method of adding the component to the CMS Layout, Page or Partial 

### Dynamic variables
This plugin supports all avaiables Twig variables ans Twig markup in templates and in users *key:value* variables.

There are three methods to use dynamic variables
- In the component setup using the *Custom values* option. 
By specifying a key in dot notation *parent.child* you can targent values of any array depth.
Values can be *a direct input*, *{{ twig_variable }}* or a *&sub_Template*

- Within the Layout or Page varabiles can been set in the Code Section: ``onStart () {$this['twig_variable'] = '.....'}`` and used in your template with standard twig markup ``{{twig_variable}}``. 
An array can also be passed and processed with the Twig filter ``{{array_variable | json_encode()}}``. 

- Cariables can also be set directly in the Code Section by referencing the *key* in your schema template.
`` array_set($this->componentAlias->schema['schemaTemplate'], 'schemaKey', $customVariable);)``.
This will automatically update the value with no Twig markup needed.

### Nested schema
Use ***&*** (or your custom prefix from Settings) for define sub/nested schema templates in this format ``address: &PostalAddress``
