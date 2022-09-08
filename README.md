# Company

## Requirements:
* php 7.3+ (https://www.techiediaries.com/install-laravel-8-php-7-3-composer/)
* composer 1.10.25

### Installation:

Run this command in projects directory:

* ```git clone git@github.com:Zarioza/company.git```

change directory:

* ```cd company```

install dependencies:

* ```composer install```

create .env.testing file:

```cp .env.testing.example .env.testing```

create database:

```php artisan db:create company```

create test database:

```php artisan db:create company_test```

run migrations:

```php artisan migrate```

start the app:

```php artisan serve```

### Testing

```php artisan test```

## API endpoints:

### Positions

Create position:

```
REQUEST:
POST api/v1/positions

BODY: {
    "name": "string|required|unique",
    "type": "string|required" (regular|management)
}

RESPONSE: PositionResource
```

List positions:

```
REQUEST:
GET api/v1/positions

RESPONSE: PositionResource collection
```

Edit positions:

```
REQUEST:
PATCH api/v1/positions/{position:id}
BODY: {
    "name": "string|optional|unique",
    "type": "string|optional" (regular|management)
}
RESPONSE: PositionResource
```

Show single position:

```
REQUEST:
GET api/v1/positions/{position:id}

RESPONSE: PositionResource
```

