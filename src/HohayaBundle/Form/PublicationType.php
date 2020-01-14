<?php

namespace HohayaBundle\Form;

use Doctrine\ORM\EntityManagerInterface;
use HohayaBundle\Form\EventListener\AddMenuFieldSubscriber;
use HohayaBundle\Form\EventListener\AddSMenuFieldSubscriber;
use HohayaBundle\Form\EventListener\AddSSMenuFieldSubscriber;
use Symfony\Component\Form\AbstractType;

// 1. Include Required Namespaces
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PublicationType extends AbstractType
{
    private $em;

    /**
     * The Type requires the EntityManager as argument in the constructor. It is autowired
     * in Symfony 3.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $propertyPathToSSMenu = 'ssmenu';
        // $propertyPathToSMenu = 'smenu';
        // $propertyPathToMenu = 'menu';

        $builder
            ->addEventSubscriber(new AddSSMenuFieldSubscriber($propertyPathToSSMenu))
            ->addEventSubscriber(new AddSMenuFieldSubscriber($propertyPathToSSMenu))
            ->addEventSubscriber(new AddMenuFieldSubscriber($propertyPathToSSMenu, $this->em))
        ;

        $builder
        ->add('estActif', CheckboxType::class, array(
            'attr' => ['class' => 'minimal'],
            'label'    => 'Est actif ?',
            'required' => false,
        ))
        ->add('estSlide', CheckboxType::class, array(
            'attr' => ['class' => 'minimal'],
            'label'    => 'Est Slide ?',
            'required' => false,
        ))
        ->add('titre')
        ->add('description', TextareaType::class, array(
            'attr' => array('class' => 'form-control'),
            'required' => false,
            
        ))
        ->add('icon', TextType::class, array(
            'attr' => array('class' => 'form-control'),
            'required' => false, 
        ))
        ->add('titreImage', TextType::class, array(
            'attr' => array('class' => 'form-control'),
            'required' => false, 
        ))
        ->add('contenu', TextareaType::class, array(
            'attr' => array('class' => 'form-control tinymce', 'data-theme' => 'advanced'),
        ))
        ->add('nomImage', FileType::class, [
            'data_class' => null,
            'multiple' => false,
            'required' => false,
            'label' => false,
        ])
        ->add('nomPDF', FIleType::class, [
            'data_class' => null,
            'multiple' => false,
            'required' => false,
            'label' => false,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HohayaBundle\Entity\Publication',
            'allow_extra_fields' => true
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'hohayabundle_publication';
    }
}
