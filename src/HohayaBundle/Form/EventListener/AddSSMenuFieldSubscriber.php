<?php
namespace HohayaBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Doctrine\ORM\EntityRepository;
use HohayaBundle\Entity\SMenu;
use HohayaBundle\Entity\SSMenu;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AddSSMenuFieldSubscriber implements EventSubscriberInterface
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

    private function addSSMenuForm($form, $smenu_id)
    {
        $formOptions = array(
            'required' => false,
            'class'         => 'HohayaBundle:SSMenu',
            'choice_label' => 'titre',
            'placeholder' => 'Sélectionner un sous sous menu...',
            'attr'          => array(
                'class' => 'form-control select2',
            ),
            'query_builder' => function (EntityRepository $repository) use ($smenu_id) {
                $qb = ($smenu_id) ? $repository->createQueryBuilder('ssmenu')
                    ->innerJoin('ssmenu.smenu', 'smenu')
                    ->where('smenu.id = :smenu')
                    ->setParameter('smenu', $smenu_id) : null
                ;

                return $qb;
            }
        );

        $form->add($this->propertyPathToSSMenu, EntityType::class, $formOptions);
    }

    public function OnPreSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        $accessor = PropertyAccess::createPropertyAccessor();

        $ssmenu   = $accessor->getValue($data, $this->propertyPathToSSMenu);
        $smenu_id = ($ssmenu) ? $ssmenu->getSmenu()->getId() : null;

        $this->addSSMenuForm($form, $smenu_id);
    }

    public function OnPreSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        $smenu_id = array_key_exists('smenu', $data) ? $data['smenu'] : null;

        $this->addSSMenuForm($form, $smenu_id);
    }
}
