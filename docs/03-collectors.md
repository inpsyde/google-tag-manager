# Collectors

## Core Collectors

The concept of "Collectors" allows you to send data into the DataLayer based on the current page. By default the Plugin includes following Collectors:

### User

This Collector allows you to set a custom "visitor role" in case the user is not logged in. Following fields can be automatically added to the dataLayer:

- ID
- Role
- Nickname
- Description
- First name
- Last name
- E-Mail
- Url

**Example - not logged-in user:**

```js
dataLayer.push({
    "user":{
        "role":"visitor",
        "isLoggedIn":false
    }
});
```

**Example - logged-in user:**

```php
dataLayer.push({
    "user":{
        "ID":1,
        "role":"administrator",
        "nickname":"mmuster",
        "user_description":"",
        "first_name":"Max",
        "last_name":"Muster",
        "user_email":"max@muster.com",
        "url":"",
        "isLoggedIn":true
    }
});
```

### Site

This Collector sends data about the current blog and multisite.

Available Blog information fields:

- Name
- Description
- Url
- Charset
- Language

Available Multisite information fields:

- ID
- Network ID
- Blog name
- Site url
- Home

### Post

This Collector will send when `is_singular()` is `true` following data:

Available `WP_Post` fields:

- ID
- Title
- Name
- Author
- Date
- Date GMT
- Status
- Comment status
- Ping status
- Modified date
- Modified date GMT
- Parent ID
- Guid
- Post type
- Post mime type
- Comment count

Available Author fields:

- ID
- Name

**Example:**

```js
dataLayer.push({
    'post':{
        'ID':1,
        'post_title':'Hello world!',
        'post_name':'hello-world',
        'post_author':'1',
        'post_date':'2023-09-06 08:19:30',
        'post_date_gmt':'2023-09-06 08:19:30',
        'post_status':'publish',
        'comment_status':'closed',
        'ping_status':'closed',
        'post_modified':'2023-09-06 08:19:30',
        'post_modified_gmt':'2023-09-06 08:19:30',
        'guid':'http:\/\/example.com\/?p=1',
        'post_type':'post',
        'comment_count':'1'
    },
    'author':{
        'ID':1,
        'display_name':'mmusater'
    }
});
```

### Search

This Collector will send data when `is_search()` is `true`.

Available fields:

- Search query
- Post referer
- Count found posts

**Example:**

```js
dataLayer.push({
    'search':{
		'query':'hello',
		'referer':'http:\/\/example.com\/?p=1',
		'post_count':1
	}
});
```

## Custom Collectors

In case you want to add a custom Collector to the Plugin which can be managed through settings in backend, you need to at least implement the `Inpsyde\GoogleTagManager\DataLayer\DataCollector`-interface. The `Inpsyde\GoogleTagManager\Settings\SettingsSpecification`-interface is only needed in case you want to manage settings through the Settings Page.

### 1. Create a Collector class

```php
<?php
use Inpsyde\GoogleTagManager\DataLayer\DataCollector;

class MyCustomCollector implements DataCollector {

    public function id(): string 
    {
        return 'my-custom-collector';
    }

    public function name(): string
    {
        return __('My custom Collector');
    }

    public function description(): ?string
    {
        // Optional help text.
        return null;
    }

    /**
     * Called in front-office when `dataLayer.push()`-data is build. 
     * Return here your data.
     * 
     * @param array $settings
     * @return array|null
    */
    public function data(array $settings): ?array {
        
        if( something_is_not_true() ) {
            return null;
        }
        
        return []
    }
}
```

### 2. Register your Collector

In order to make the Collector accessible in the Settings Page you need to register it to the `Inpsyde\GoogleTagManager\Service\DataCollectorRegistry`:

```php
<?php
use Inpsyde\GoogleTagManager\Service\DataCollectorRegistry;
use Inpsyde\Modularity\Module\ExtendingModule;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Psr\Container\ContainerInterface;

class ExtendingClientModule implements ExtendingModule
{
    use ModuleClassNameIdTrait;
    
    public function extensions() : array{
 
        return [
            DataCollectorRegistry::class => static function(DataCollectorRegistry $registry, ContainerInterface $container): RulesRegistry
            {
                $registry->register( new MyCustomCollector() );

                return $registry;
            }
        ];
 
    }
}
```
