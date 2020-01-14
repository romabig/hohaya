<?php

namespace Hohaya\UserBundle\Form\Type;

use HohayaBundle\Services\RolesHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class RegistrationFormType extends BaseType
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
            ->add('prenom')
            ->add('roles', ChoiceType::class, [
                'placeholder' => 'Sélectionner un ou plusieurs roles',
                'attr' => ['class' => 'form-control show-tick', 'data-live-search' => 'true'],
                'multiple' => true,
                'choices' => $this->roles->getRoles(),
            ])
            ->add('file', FileType::class, [
                'data_class' => null,
                'multiple' => false,
                'required' => false,
                'label' => false,
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/*',
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
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
        $resolver->setDefaults(array(
            'data_class' => 'Hohaya\UserBundle\Entity\Utilisateur',
            'allow_extra_fields' => true
        ));
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }
}