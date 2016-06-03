#Install

```bash
php composer.phar require "skobka/app-di: *"
```

#Usage
## Directory tree
```
config/ 
-- bootstrap.php
-- containers.php
-- parameters.yml
-- parameters.yml.dis
src/ 
-- Application.php
app.php
```

## File examples
### bootstrap.php
```php
use skobka\common\configuration\Builder;
use Symfony\Component\Yaml\Yaml;

$parameters = Yaml::parse(
    file_get_contents(__DIR__.'/parameters.yml'),
    Yaml::PARSE_OBJECT_FOR_MAP
);

$containers = include __DIR__ . '/containers.php';

$parameters->containers = $containers;

return Builder::build($parameters);
```

### containers.php
```php
return [
    PDO::class => \DI\object()->constructor(
        $parameters->pdo->dsn,
        $parameters->pdo->user,
        $parameters->pdo->password,
        []
    ),
];
```

### parameters.yml & parameters.yml.dist
```yaml
class: \skobka\common\configuration\BaseConfiguration
pdo:
  class: \skobka\common\configuration\BasePdoConfiguration
  dsn: mysql:host=localhost;dbname=test
  user: testUser
  password: testPassword

```

###Application.php
```php

use Interop\Container\ContainerInterface;
use skobka\common\interfaces\ApplicationInterface;
use skobka\common\interfaces\ContainerisedInterface;

/**
 * Class Application
 */
class Application implements ApplicationInterface, ContainerisedInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Start an application instance
     * @return void
     */
    public function start()
    {
        //TODO: create logic
    }

    /**
     * Set container to the current object
     * @param \Interop\Container\ContainerInterface $container
     * @return void
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }
}

```
###app.php
```php

use DI\ContainerBuilder;
use skobka\common\configuration\interfaces\ConfigurationInterface;
use skobka\common\configuration\interfaces\ContainerDefinitionInterface;
use skobka\common\interfaces\ApplicationInterface;
use skobka\common\interfaces\ContainerisedInterface;
use uroweb\push\Application;

include __DIR__."/vendor/autoload.php";
/* @var $conf ConfigurationInterface|ContainerDefinitionInterface */
$conf = include __DIR__."/config/bootstrap.php";

$build = new ContainerBuilder();
$build->addDefinitions($conf->getDefinitions());
$container = $build->build();

/* @var $appInstance ApplicationInterface|ContainerisedInterface */
$appInstance = $container->get(Application::class);
$appInstance->setContainer($container);

$appInstance->start();

```
