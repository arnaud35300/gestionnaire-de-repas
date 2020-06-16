# configuration

## installation

```
composer install
```

config `.env.local` and `.env.test` files

```
php bin/console doctrine:database:create
php bin/console doctrine:migration:migrate
php bin/console doctrine:fixture:load
yarn 
yarn run dev 
```

use `yarn run build` if you are in production
