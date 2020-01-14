<?php

namespace HohayaBundle\Form;
use HohayaBundle\Entity\SMenu;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class SSMenuType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('titre')
        ->add('smenu', EntityType::class, array(
            'attr' => array('class' => 'form-control', 'style' => "width: 100%;"),
            'class' => SMenu::class,
            'choice_label' => 'titre',
        ))
         ->add('masque', CheckboxType::class, array(
                    'attr' => ['class' => 'minimal'],
                    'label'    => 'Masqué ?',
                    'required' => false,
        ))
        ->add('ordreAffichage');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HohayaBundle\Entity\SSMenu',
            'allow_extra_fields' => true
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'hohayabundle_ssmenu';
    }
}
