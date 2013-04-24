# Aaron4m - Static Pages

The purpose of this module is to create a simple, zero configuration static pages module for Zend Framework 2

## Installation
Added the following requirement to your projects composer.json file.

```php
"aaron4m/zf2-static-pages": "dev-master"
```

and run

php ./composer.phar update
and finally add StaticPages to the START of your modules list in config/application.php. It's important this comes first as it may override routes setup later.

## Usage
Simply drop a .phtml file into your module/application/view/static-pages folder (or any other module for that matter).

This page can now be viewed from either

/pages/yourfile/ (with or without trailing backslash)
or simply /yourfile/ (with or without trailing backslash)
