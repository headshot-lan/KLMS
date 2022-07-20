<?php

namespace App\Form;

use App\Entity\Sponsor;
use App\Entity\SponsorCategory;
use App\Helper\AuthorInsertSubscriber;
use App\Idm\Annotation\Entity;
use Doctrine\DBAL\Types\TextType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class SponsorType extends AbstractType
{
    private AuthorInsertSubscriber $userInsertSubscriber;
    private EntityManagerInterface $em;

    public function __construct(AuthorInsertSubscriber $userInsertSubscriber, EntityManagerInterface $em)
    {
        $this->userInsertSubscriber = $userInsertSubscriber;
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('url', null, ['label' => 'URL'])
            ->add('text')
            ->add('category', ChoiceType::class, [
                'label' => 'Kategorie',
                'choices' => $this->em->getRepository(SponsorCategory::class)->findAll(),
                'choice_label' => function (?SponsorCategory $content) {
                    return $content ? $content->getName() : '';
                },
                'multiple' => false,
                'expanded' => false,
            ])
        ;
        $builder->add('logoFile', VichImageType::class, [
            'label' => 'Logo',
            'required' => !$options['edit'],
            'allow_delete' => false,
            'download_uri' => false,
            'image_uri' => false,
            'asset_helper' => false,
            'imagine_pattern' => 'sponsor_logo',
        ]);
        $builder->addEventSubscriber($this->userInsertSubscriber);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sponsor::class,
            'edit' => false,
        ]);
        $resolver->setAllowedTypes('edit', 'bool');
    }
}