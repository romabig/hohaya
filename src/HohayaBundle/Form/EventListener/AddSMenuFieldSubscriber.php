<?php
namespace HohayaBundle\Form\EventListener;
 
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Doctrine\ORM\EntityRepository;
use HohayaBundle\Entity\Menu;
use HohayaBundle\Entity\SMenu;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
 
class AddSMenuFieldSubscriber implements EventSubscriberInterface
{
    private $propertyPathToSSMenu;
 
    public function __construct($propertyPathToSSMenu)
    {
        $this->propertyPathToSSMenu = $propertyPathToSSMenu;
    }
 
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'OnPreSetData',
            FormEvents::PRE_SUBMIT   => 'OnPreSubmit'
        );
    }
 
    private function addSMenuForm($form, $menu_id, SMenu $smenu = null)
    {
        $formOptions = array(
            'required' => false,
            'class'         => 'HohayaBundle:SMenu',
            'choice_label' => 'titre',
            'placeholder' => 'Sélectionner un sous menu...',
            'mapped'        => false,
            'attr'          => array(
                'class' => 'form-control select2',
            ),
            'query_builder' => function (EntityRepository $repository) use ($menu_id) {
                
                $qb = ($menu_id) ? $repository->createQueryBuilder('smenu')
                    ->innerJoin('smenu.menu', 'menu')
                    ->where('menu.id = :menu')
                    ->setParameter('menu', $menu_id) : null
                ;
 
                return $qb;
            }
        );
 
        if ($smenu) {
            $formOptions['data'] = $smenu;
        }
 
        $form->add('smenu', EntityType::class, $formOptions);
    }
 
    public function OnPreSetData(FormEvent $event)
    {
        $publication = $event->getData();
        $form = $event->getForm();
 
        if (null === $publication) {
            return;
        }
 
        $accessor = PropertyAccess::createPropertyAccessor();
 
        //$ssmenu   = $accessor->getValue($data, $this->propertyPathToSSMenu);
        $smenu    = ($publication) ? $publication->getSmenu() : null;
        $menu_id  = ($smenu) ? $smenu->getMenu()->getId() : null;
 
        $this->addSMenuForm($form, $menu_id, $smenu);
    }
 
    public function OnPreSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
 
        $menu_id = array_key_exists('menu', $data) ? $data['menu'] : null;
 
        $this->addSMenuForm($form, $menu_id);
    }
}