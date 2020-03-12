# JsutGram.Backend

### Git First Push.

Use the package manager [composer](https://getcomposer.org/) to install foobar.

## Git Clone.

```bash
git clone https://joojanghelp@github.com/joojanghelp/joojang.backend.git Backend
```

## Composer.
```bash
composer install

```

## First Config.
```bash
cp .env.example .env
sh init.sh

```


## 기타 설정.
```bash
php artisan config:clear
php artisan config:cache
composer dump-autoload

php artisan route:cache

php artisan cache:clear

php artisan migrate:refresh --seed


php artisan optimize
composer dump-autoload

php artisan config:clear && php artisan optimize && composer dump-autoload

```

## Local Develop Server.

```bash
php artisan serve
```






## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)
