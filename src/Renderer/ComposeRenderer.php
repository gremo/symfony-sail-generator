<?php

namespace App\Renderer;

use App\Context\ContextAdapter;
use App\Context\GlobalContext;
use App\Generator\GeneratorInterface;
use App\Generator\GeneratorVisitorInterface;
use App\Options\ComposeTarget;
use App\Options\DatabaseType;
use App\Service\AbstractDatabaseService;
use App\Service\FrankenPHPService;
use App\Service\MailpitService;
use App\Service\MariaDBService;
use App\Service\MySQLService;
use App\Service\PhpMyAdminService;
use App\Service\PostgresSQLService;
use App\Service\ServiceInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;
use Symfony\Component\Yaml\Yaml;

class ComposeRenderer implements GeneratorVisitorInterface
{
    #[\Override]
    public static function getPriority(): int
    {
        return 8;
    }

    public function __construct(
        #[AutowireLocator(ServiceInterface::class)]
        private readonly ContainerInterface $container,
        private readonly ContextAdapter $contextAdapter,
    ) {
    }

    #[\Override]
    public function acceptGenerator(GeneratorInterface $generator): void
    {
        $generator->genereComposeFile($this);
    }

    public function render(ComposeTarget $target, GlobalContext $context): string
    {
        $services = iterator_to_array($this->getServices($context));
        $contexts = iterator_to_array($this->resolveContexts($context, $services));

        $yaml = ['services' => [], 'volumes' => []];
        foreach ($services as $service) {
            $context = $contexts[$service::class];

            $yaml = array_merge_recursive(
                $yaml,
                ['services' => $service->getService($target, $context) ?? []],
                ['volumes' => $service->getVolumes($target, $context) ?? []],
            );
        }

        if (empty($yaml['services'])) {
            unset($yaml['services']);
        }

        if (empty($yaml['volumes'])) {
            unset($yaml['volumes']);
        }

        $output = Yaml::dump($yaml, 5, 4);
        foreach (array_keys(array_slice($yaml['services'], 1)) as $i => $serviceName) {
            $output = preg_replace('/^( +)' . $serviceName . ':/m', "\n$1" . $serviceName . ":", $output);
        }
        $output = preg_replace('/^volumes:/m', "\nvolumes:", $output);
        $output = str_replace("'", "", $output);
        $output = str_replace("null", "", $output);

        return $output;
    }

    /**
     * @return ServiceInterface[]
     */
    private function getServices(GlobalContext $context): iterable
    {
        // @phpstan-ignore symfonyContainer.privateService
        yield $this->container->get(FrankenPHPService::class);

        if ($context->dbms) {
            yield $this->container->get(match ($context->dbms) {
                DatabaseType::MariaDB => MariaDBService::class,
                DatabaseType::MySQL => MySQLService::class,
                DatabaseType::PostgreSQL => PostgresSQLService::class,
            });

            if ($context->enablePhpMyAdmin) {
                match ($context->dbms) {
                    // @phpstan-ignore symfonyContainer.privateService
                    DatabaseType::MariaDB, DatabaseType::MySQL => yield $this->container->get(PhpMyAdminService::class),
                    default => null,
                };
            }
        }

        if ($context->enableMailpit) {
            // @phpstan-ignore symfonyContainer.privateService
            yield $this->container->get(MailpitService::class);
        }
    }

    /**
     * @param ServiceInterface[] $services
     * @return array<class-string, object>
     */
    private function resolveContexts(GlobalContext $context, array $services): iterable
    {
        foreach ($services as $service) {
            $serviceContext = $this->contextAdapter->adapt($context, $service);

            yield $service::class => $this->contextAdapter->adapt($context, $service);
            if ($service instanceof AbstractDatabaseService) {
                yield AbstractDatabaseService::class => $serviceContext;
            }
        }
    }
}
