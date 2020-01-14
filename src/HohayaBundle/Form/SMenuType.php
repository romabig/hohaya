<?php

namespace HohayaBundle\Form;
use HohayaBundle\Entity\Menu;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class SMenuType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('titre')
        ->add('menu', EntityType::class, array(
            'attr' => array('class' => 'form-control select2', 'style' => "width: 100%;"),
            'class' => Menu::class,
            'choice_label' => 'titre',
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
            'data_class' => 'HohayaBundle\Entity\SMenu',
            'allow_extra_fields' => true
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'hohayabundle_smenu';
    }


}
