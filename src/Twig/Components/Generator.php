<?php

namespace App\Twig\Components;

use App\Context\GlobalContext;
use App\Form\GeneratorType;
use App\Generator\FilesDisplayGenerator;
use App\Generator\OutputGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsLiveComponent]
class Generator extends AbstractController
{
    use ComponentWithFormTrait;

    #[LiveProp]
    public ?GlobalContext $model = null;

    /**
     * @var array<string, string>
     */
    private array $files = [];

    /**
     * @var array<string, string>
     */
    private array $presets = [];

    private ?string $optionsHash = null;

    public function __construct(
        private OutputGenerator $generator,
    ) {
    }

    public function mount(): void
    {
        $this->refresh();
    }

    public function __invoke(): void
    {
        $this->generate();
    }

    #[LiveAction]
    public function loadPreset(#[LiveArg] string $hash): void
    {
        /** @var GlobalContext $context */
        $context = unserialize(base64_decode($hash));

        $this->formValues['phpVersion'] = $context->phpVersion;
        $this->formValues['dbmsVersion'] = $context->dbmsVersion;
        $this->formValues['enableCron'] = $context->enableCron;
        $this->formValues['enableSupervisor'] = $context->enableSupervisor;
        $this->formValues['enableOPcachePreload'] = $context->enableOPcachePreload;
        $this->formValues['enableNode'] = $context->enableNode;
        $this->formValues['enableFrankenPHPRuntime'] = $context->enableFrankenPHPRuntime;
        $this->formValues['enablePhpMyAdmin'] = $context->enablePhpMyAdmin;
        $this->formValues['enableMailpit'] = $context->enableMailpit;
    }

    #[LiveAction]
    public function generate(): void
    {
        $this->submitForm();

        $context = $this->getForm()->getData();
        $this->refresh($context);
    }

    public function refresh(?GlobalContext $context = null): void
    {
        $context ??= new GlobalContext();

        $this->files = $this->generator->getResult($context, FilesDisplayGenerator::class);
        $this->optionsHash = base64_encode(serialize($context));
    }

    /**
     * @return array<string, string>
     */
    #[ExposeInTemplate]
    public function getPresets(): array
    {
        return $this->presets;
    }

    /**
     * @return array<string, string>
     */
    #[ExposeInTemplate]
    public function getFiles(): array
    {
        return $this->files;
    }

    #[ExposeInTemplate]
    public function getOptionsHash(): ?string
    {
        return $this->optionsHash;
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(GeneratorType::class, $this->model ?? new GlobalContext());
    }
}
