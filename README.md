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

Run test:

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

RESPONSE 201: PositionResource
```

List positions:

```
REQUEST:
GET api/v1/positions

RESPONSE 200: PositionResource collection
```

Edit position:

```
REQUEST:
PATCH api/v1/positions/{position:id}
BODY: {
    "name": "string|optional|unique",
    "type": "string|optional" (regular|management)
}
RESPONSE 202: PositionResource
```

Show single position:

```
REQUEST:
GET api/v1/positions/{position:id}

RESPONSE 200: PositionResource
```

Delete position:

```
REQUEST:
DELETE api/v1/positions/{position:id}

RESPONSE 204: No Content
```

### Employee

Create employee:

```
REQUEST:
POST api/v1/employee

BODY: {
    "name": "string|required",
    "superior_id": "int|nullable",
    "position_id": "int|required",
    "start_date": "date_format:"Y-m-d"|required",
    "end_date": "date_format:"Y-m-d"|required",
}

RESPONSE 201: EmployeeResource
```

Create employee:

```
REQUEST:
POST api/v1/employees

BODY: {
    "name": "string|required",
    "superior_id": "int|nullable",
    "position_id": "int|required",
    "start_date": "date_format:"Y-m-d"|required",
    "end_date": "date_format:"Y-m-d"|required",
}

RESPONSE 201: EmployeeResource
```

List all employees:

```
REQUEST:
GET api/v1/employees

QUERY PARAMETERS => page (optional) ie.  api/v1/employees?page=2

RESPONSE 200: EmployeeResource collection paginated
```

Show employee:

```
REQUEST:
GET api/v1/employees/{employee:id}

RESPONSE 200: EmployeeResource
```

Update employee:

```
REQUEST:
PATCH api/v1/employees/{employee:id}

BODY: {
    "name": "string|optional",
    "superior_id": "int|optional",
    "position_id": "int|optional",
    "start_date": "date_format:"Y-m-d"|optional",
    "end_date": "date_format:"Y-m-d"|optional",
}

RESPONSE 202: EmployeeResource
```
