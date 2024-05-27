# symfony.example.loc

## Database Connection

#### 1.
#### 1.1 `composer require symfony/orm-pack`
#### 1.2 `composer require symfony/validator`

#### 2. patch .env -> `DATABASE_URL="mysql://root:tiger@mysql8:3306/symfony"`

#### 3. create db -> `php bin/console doctrine:database:create`

#### 4. create and fill entity -> ` php ./bin/console make:entity Article`

#### 5. create migration `php bin/console make:migration`

#### 6. migrate `php bin/console doctrine:migrations:migrate`
