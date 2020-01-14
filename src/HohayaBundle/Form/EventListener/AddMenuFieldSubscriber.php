<?php
namespace HohayaBundle\Form\EventListener;

use HohayaBundle\Entity\Menu;
 
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Doctrine\ORM\EntityManagerInterface;
 
class AddMenuFieldSubscriber implements EventSubscriberInterface
{
    private $propertyPathToSSMenu;
    private $em;
 
    public function __construct($propertyPathToSSMenu, EntityManagerInterface $em)
    {
        $this->propertyPathToSSMenu = $propertyPathToSSMenu;
        $this->em = $em;
    }
 
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'OnPreSetData',
            FormEvents::PRE_SUBMIT   => 'OnPreSubmit'
        );
    }
 
    private function addMenuForm($form, Menu $menu = null)
    {
        $formOptions = array(
            'required' => false,
            'class'         => 'HohayaBundle:Menu',
            'mapped'        => false,
            'placeholder' => 'Sélectionner un menu...',
            'choice_label' => 'titre',
            'attr'          => array(
                'class' => 'form-control select2',
            ),
        );

        // Sous menus vides, sauf s'il existe un menu sélectionné (vue Edition)
        $smenus = array();
 
        if ($menu) {
            $formOptions['data'] = $menu;

            $repoSMenu = $this->em->getRepository('HohayaBundle:SMenu');
            
            $smenus = $repoSMenu->createQueryBuilder("q")
                ->where("q.menu = :menuid")
                ->setParameter("menuid", $menu->getId())
                ->getQuery()
                ->getResult();
        }
 
        $form->add('menu', EntityType::class, $formOptions);
    }
 
    public function OnPreSetData(FormEvent $event)
    {
        $publication = $event->getData();
        $form = $event->getForm();
 
        if (null === $publication) {
            return;
        }
 
        $accessor = PropertyAccess::createPropertyAccessor();

        $menu = ($publication) ? (($publication->getMenu()) ? $publication->getMenu() : null) : null;
 
        $this->addMenuForm($form, $menu);
    }
 
    public function OnPreSubmit(FormEvent $event)
    {
        $form = $event->getForm();

        $data = $event->getData();
        
        // Rechercher un menu sélectionné et le convertir en une entité
        $menu = $this->em->getRepository('HohayaBundle:Menu')->find($data['menu']);
 
        $this->addMenuForm($form, $menu);
    }
}