## Installation

to apply DB migrations:
```bash
php bin/console doctrine:migrations:migrate --env=dev
php bin/console doctrine:migrations:migrate --env=test
```

to start mysql and swagger:
```bash
docker-compose up -d
```

to see API description:
```bash
http://localhost:8080
```

to load DB fixtures:
```bash
php bin/console doctrine:fixtures:load
```

to run tests:
```bash
php vendor/bin/phpunit
```