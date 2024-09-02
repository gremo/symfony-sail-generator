<?php

namespace App\Form;

use App\Context\GlobalContext;
use App\Options\AssetsBuildMethod;
use App\Options\AssetsInstallMethod;
use App\Options\DatabaseType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfonycasts\DynamicForms\DependentField;
use Symfonycasts\DynamicForms\DynamicFormBuilder;

class GeneratorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder = new DynamicFormBuilder($builder);

        $builder
            ->add('phpVersion', TextType::class, [
                'label' => 'PHP version',
                'attr' => [
                    'placeholder' => 'PHP version'
                ],
                'help' => 'Version of PHP to use ("latest" or semantic version)',
            ])
            ->add('enableNode', CheckboxType::class, [
                'label' => 'Node.js',
                'help' => 'Add support for Node.js, including npm and Yarn package managers'
            ])
            ->addDependent('nodeVersion', 'enableNode', function (DependentField $field, bool $enableNode) {
                if (!$enableNode) {
                    return;
                }

                $field
                    ->add(TextType::class, [
                        'label' => 'Node.js version',
                        'attr' => [
                            'placeholder' => 'Node.js version'
                        ],
                        'help' => 'Version of Node.js to use ("current", "lts" or major)',
                    ])
                ;
            })
            ->add('enableCron', CheckboxType::class, [
                'label' => 'Cron',
                'help' => 'Time-based job scheduler to execute script or commands at specified intervals'
            ])
            ->add('enableSupervisor', CheckboxType::class, [
                'label' => 'Supervisor',
                'help' => 'Manage background processes with automatic restart, logging and failure recovery'
            ])
            ->add('enableFrankenPHPRuntime', CheckboxType::class, [
                'label' => 'FrankenPHP Runtime',
                'help' => 'FrankenPHP worker mode for improved performances'
            ])
            ->add('enableMailpit', CheckboxType::class, [
                'label' => 'Mailpit',
                'help' => 'Web-based tool for email and SMTP testing',
            ])
            ->add('enableOPcachePreload', CheckboxType::class, [
                'label' => 'OPcache preload',
                'help' => 'Preload some Symfony PHP files for improved performances',
            ])
            ->add('dbms', EnumType::class, [
                'class' => DatabaseType::class,
                'placeholder' => 'None',
            ])
            ->addDependent('enablePhpMyAdmin', 'dbms', function (DependentField $field, ?DatabaseType $dbms) {
                if (null === $dbms) {
                    return;
                }

                $field
                    ->add(CheckboxType::class, [
                        'label' => 'phpMyAdmin',
                        'help' => 'Web-based tool for managing databases via a graphical interface',
                        'disabled' => match ($dbms) {
                            DatabaseType::MariaDB, DatabaseType::MySQL => false,
                            default => true,
                        },
                    ])
                ;
            })
            ->addDependent('dbmsVersion', 'dbms', function (DependentField $field, ?DatabaseType $dbms) {
                if (null === $dbms) {
                    return;
                }

                $field
                    ->add(TextType::class, [
                        'label' => "{$dbms->name} version",
                        'attr' => [
                            'placeholder' => "{$dbms->name} version",
                        ],
                        'help' => 'Version of the DBMS version to use ("latest" or semantic version)',
                    ])
                ;
            })
            ->add('assetsInstallMethod', EnumType::class, [
                'class' => AssetsInstallMethod::class,
                'label' => 'Assets installation',
                'help' => 'The method of installing the project assets',
                'placeholder' => 'None',
            ])
            ->add('assetsBuildMethod', EnumType::class, [
                'class' => AssetsBuildMethod::class,
                'label' => 'Assets build',
                'help' => 'The method of building the project assets',
                'placeholder' => 'None',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GlobalContext::class,
            'required' => false,
            'allow_extra_fields' => true,
        ]);
    }
}
