## App setup
```
bash ./vendor/bin/sail up -d
bash ./vendor/bin/sail composer install
bash ./vendor/bin/sail npm install
bash ./vendor/bin/sail artisan migrate
bash ./vendor/bin/sail npm run build
bash ./vendor/bin/sail artisan queue:work --tries=3
```
