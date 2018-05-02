# Yii2 Gallery

A basic demo project based on Yii2 framework.

## DIRECTORY STRUCTURE

      assets/             contains assets definition
      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      mail/               contains view files for e-mails
      models/             contains model classes
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources

## REQUIREMENTS

The minimum requirement by this project that your Web server supports PHP 7.2.0.

## INSTALLATION

### Using docker for development

Update your vendor packages

    docker-compose run --rm app composer update --prefer-dist

Run the installation triggers (creating cookie validation code)

    docker-compose run --rm app composer install

Start the container

    docker-compose up -d

Execute command inside php container

    docker-compose exec app php yii help

You can then access the application through the following URL:

    http://127.0.0.1:8000

**NOTES:**

* Minimum required Docker engine version `17.04` for development (see [Performance tuning for volume mounts](https://docs.docker.com/docker-for-mac/osxfs-caching/))
* The default configuration uses a host-volume in your home directory `.docker-composer` for composer caches

## CONFIGURATION

### Database

Edit the file `config/db.php` with real data, for example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=db;dbname=yii2gallery',
    'username' => 'root',
    'password' => 'secret',
    'charset' => 'utf8',
];
```

**NOTES:**

* Yii won't create the database for you, this has to be done manually before you can access it.
* Check and edit the other files in the `config/` directory to customize your application as required.
* Refer to the README in the `tests` directory for information specific to basic application tests.
* When using docker, database host must be equal to the database service name used in docker-compose.yml