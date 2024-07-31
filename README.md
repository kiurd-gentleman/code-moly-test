# test-frontend

## Project setup
```
Composer install
copy .env.example .env
```

### You have to generate key for the project
```
php artisan key:generate
```

### You have to create a database and configure the .env file
```
php artisan migrate --seed
```

### You have to install passport
```
php artisan passport:install
```

### To run this project
```
php artisan serve
```
