# Hooks

## `Inpsyde\GoogleTagManager\Event\DataLayerRendererEvent::FILTER_SCRIPT_ATTRIBUTES`

This filter allows you to add custom attributes to the `<script>`-tag of the `dataLayer`.

```php
<?php
use Inpsyde\GoogleTagManager\Event\DataLayerRendererEvent;
use Inpsyde\GoogleTagManager\DataLayer\DataLayer;

add_filter(
    DataLayerRendererEvent::FILTER_SCRIPT_ATTRIBUTES,
    static function( array $attributes, DataLayer $dataLayer ): array {
        
        $attributes['data-custom-attribute'] = 'My custom value';
        
        return $attributes;
    }
)
```


## `Inpsyde\GoogleTagManager\Event\GtmScriptTagRendererEvent::*`

- `ACTION_BEFORE_SCRIPT` - This hook allows you to render **before** the `<script>`-tag custom data.
- `ACTION_AFTER_SCRIPT` - This hook allows you to render **after** the `<script>`-tag custom data.



## `Inpsyde\GoogleTagManager\Event\GtmScriptTagRendererEvent::FILTER_SCRIPT`

This filter allows you to change the Google Tag Manager inject script.

```php
<?php
use Inpsyde\GoogleTagManager\Event\GtmScriptTagRendererEvent;
use Inpsyde\GoogleTagManager\DataLayer\DataLayer;

add_filter(
    GtmScriptTagRendererEvent::FILTER_SCRIPT,
    static function(string $script, DataLayer $dataLayer): string
    {

        return $script;
    }
)
```

## `Inpsyde\GoogleTagManager\Event\GtmScriptTagRendererEvent::FILTER_SCRIPT_ATTRIBUTES`

This filter allows you to add custom attributes to the `<script>`-tag which injects the Google Tag Manager script.

```php
use Inpsyde\GoogleTagManager\Event\GtmScriptTagRendererEvent;
use Inpsyde\GoogleTagManager\DataLayer\DataLayer;

add_filter(
    GtmScriptTagRendererEvent::FILTER_SCRIPT_ATTRIBUTES,
    static function(array $attributes, DataLayer $dataLayer): string
    {
        $attributes['data-custom-attribute'] = 'My custom value';
        
        return $script;
    }
)
```

## `Inpsyde\GoogleTagManager\Event\LogEvent::ACTION`

This action is being used to trigger custom log messages which can be logged into a system.

```php
<?php

use Inpsyde\GoogleTagManager\Event\LogEvent;

add_action(
    LogEvent::ACTION,
    static function( string $errorLevel, string $message, array $context ): void
    {
        // Send the message to a Logging System or listen to specific levels.
    }
)

```
