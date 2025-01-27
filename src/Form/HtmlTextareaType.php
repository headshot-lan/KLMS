<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HtmlTextareaType extends TextareaType
{
    private readonly HtmlHandlingSubscriber $htmlHandlingSubscriber;

    public function __construct(HtmlHandlingSubscriber $htmlHandlingSubscriber)
    {
        $this->htmlHandlingSubscriber = $htmlHandlingSubscriber;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder->addEventSubscriber($this->htmlHandlingSubscriber);
    }

    final public const FIX_URLS = 'fix_urls';
    final public const CLEAR_SCRIPTS = 'clear_scripts';
    final public const FIX_HEADLINES = 'fix_headlines';

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver
            ->setDefault(self::FIX_URLS, false)
            ->setAllowedTypes(self::FIX_URLS, ['bool', 'string'])
            ->setAllowedValues(self::FIX_URLS, [false, 'relative', 'absolute'])
        ;
        $resolver
            ->setDefault(self::CLEAR_SCRIPTS, true)
            ->setAllowedTypes(self::CLEAR_SCRIPTS, 'bool')
        ;
        $resolver
            ->setDefault(self::FIX_HEADLINES, true)
            ->setAllowedTypes(self::FIX_HEADLINES, 'bool')
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'tinymce';
    }
}
