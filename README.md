E-Contract BVBA - Steam package
=============================================

This package offers the integration of the econtract Steam API. This API can be used by partners and affiliates of econtract to leverage various services and utility resources owned by econtract on their own websites.




## Installation

Pull this package in through Composer:

```js

    {
        "require": {
            "econtract/steam": "2.*"
        }
    }

```


### Laravel installation

Add the service provider to your `config/app.php` file:

```php

    'providers'             => array(

        //...
        \Econtract\Steam\SteamServiceProvider::class,

    ),

```

Add the API as an alias to your `config/app.php` file

```php

    'facades'               => array(

        //...
        'Steam'                 => \Econtract\Steam\Facades\Steam::class,

    ),

```



## License

This package is proprietary software and may not be copied or redistributed without explicit permission.




## Contact

Charles Dekkers (owner)

- Email: charles@aanbieders.be


Jan Oris (developer)

- Email: jan.oris@ixudra.be
- Telephone: +32 496 94 20 57
