parsidev/jalali
======

This Package helps developers to easily work with Jalali (Shamsi or Iranian) dates in Laravel 5 applications, based on Jalali (Shamsi) DateTime class.

<a name="installation"></a>
## Installation

In the `require` key of `composer.json` file add the following

```yml
    "parsidev/jalali": "dev-master"
```

Run the Composer update comand

    $ composer update

In your `config/app.php` add `'Parsidev\Jalali\JalaliServiceProvider'` to the end of the `$providers` array

```php
    'providers' => array(

        'Illuminate\Foundation\Providers\ArtisanServiceProvider',
        'Illuminate\Auth\AuthServiceProvider',
        ...
        'Parsidev\Jalali\JalaliServiceProvider',

    ),
```

<a name="basic-usage"></a>
## Basic Usage
## Examples ##

Some Examples (based on examples provided by Sallar)

```php
// default timestamp is now
$date = jDate::forge();

// pass timestamps
$date = jDate::forge(1333857600);

// pass strings to make timestamps
$date = jDate::forge('last sunday');

// get the timestamp
$date = jDate::forge('last sunday')->time(); // 1333857600

// format the timestamp
$date = jDate::forge('last sunday')->format('%B %d، %Y'); // دی 02، 1391

// get a predefined format
$date = jDate::forge('last sunday')->format('datetime'); // 1391-10-02 00:00:00
$date = jDate::forge('last sunday')->format('date'); // 1391-10-02
$date = jDate::forge('last sunday')->format('time'); // 00:00:00

// amend the timestamp value, relative to existing value
$date = jDate::forge('2012-10-12')->reforge('+ 3 days')->format('date'); // 1391-07-24

// get relative 'ago' format
$date = jDate::forge('now - 10 minutes')->ago() // ۱۰ دقیقه پیش
```
