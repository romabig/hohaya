<?php

namespace HohayaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class MenuType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('titre')
                ->add('estActif', CheckboxType::class, array(
                    'attr' => ['class' => 'minimal'],
                    'label'    => 'Est actif ?',
                    'required' => false,
                ))
                ->add('masque', CheckboxType::class, array(
                    'attr' => ['class' => 'minimal'],
                    'label'    => 'Masqué ?',
                    'required' => false,
                ))
                ->add('ordreAffichage');
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HohayaBundle\Entity\Menu',
            'allow_extra_fields' => true
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'hohayabundle_menu';
    }


}
