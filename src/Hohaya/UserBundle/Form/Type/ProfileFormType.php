<?php

namespace Hohaya\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use HohayaBundle\Services\RolesHelper;

class ProfileFormType extends BaseType
{
    /**
     * @var RolesHelper
     */
    private $roles;

    /**
     * @param string $class The User class name
     * @param RolesHelper $roles Array or roles.
     */
    public function __construct($class, RolesHelper $roles)
    {
        parent::__construct($class);

        $this->roles = $roles;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('estActif', CheckboxType::class, array(
                'attr' => ['class' => 'minimal'],
                'label' => 'Est actif ?',
                'required' => false,
            ))
            ->add('nom')
            ->add('roles', ChoiceType::class, [
                'placeholder' => 'Sélectionner un ou plusieurs roles',
                'attr' => ['class' => 'form-control show-tick', 'data-live-search' => 'true'],
                'multiple' => true,
                'choices' => $this->roles->getRoles(),
            ])
            ->add('prenom')
            ->add('file', FileType::class, [
                'data_class' => null,
                'multiple' => false,
                'required' => false,
                'label' => false,
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '20M',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Veuillez une image valide',
                    ])
                ],
            ])
            ->add('adresse');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'allow_extra_fields' => true
            ]
        );
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_profile';
    }
}