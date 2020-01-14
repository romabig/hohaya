<?php

namespace HohayaBundle\Controller;

use HohayaBundle\Entity\SMenu;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Smenu controller.
 *
 */
class SMenuController extends Controller
{
    /**
     * Lists all sMenu entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $sMenus = $em->getRepository('HohayaBundle:SMenu')->findBy(['supprimer' => 0]);

        return $this->render('@Hohaya/smenu/index.html.twig', array(
            'sMenus' => $sMenus,
        ));
    }

    /**
     * Creates a new sMenu entity.
     *
     */
    public function newAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $sMenu = new Smenu();
        $nouveau = "";

        $router = $this->container->get('router');
       
        $collection = $router->getRouteCollection();
        $allRoutes = $collection->all();

        $routes = array();

        foreach ($allRoutes as $route => $params) {
            $defaults = $params->getDefaults();

            if (isset($defaults['_controller'])) {
                $controllerAction = explode(':', $defaults['_controller']);
                $controller = $controllerAction[0];

                if (!isset($routes[$controller])) {
                    $routes[$controller] = array();
                }

                $routes[$controller][] = $route;
            }
        }

        $thisRoutes = isset($routes[get_class($this)]) ?
        $routes[get_class($this)] : null;

        if (is_null($id) || $id == ''){
            $nouveau = "oui";
        }else{
            $nouveau = "non";
            $sMenu = $em->getRepository('HohayaBundle:SMenu')->find($id);
        }

        $form = $this->createForm('HohayaBundle\Form\SMenuType', $sMenu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $forms = $request->request->get("HohayaBundle_smenu");

            $sMenu->setRoute($forms["route"]);

            $em->persist($sMenu);
            $em->flush();

            // Historique des actions effectuées par utilisateur
            $className = $em->getClassMetadata(get_class($sMenu))->getName();
            $user = $this->get('security.token_storage')->getToken()->getUser();

            $this->get('hohaya.librairie_controller_service')->setUserLog($em, $className, $sMenu->getId(), ($nouveau == "oui") ? "Ajout" : "Modification", $user);

            return $this->redirectToRoute('smenu_show', array('id' => $sMenu->getId()));
        }

        return $this->render('@Hohaya/smenu/new.html.twig', array(
            'sMenu' => $sMenu,
            'nouveau' => $nouveau,
            'allRoutes' => $allRoutes,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a sMenu entity.
     *
     */
    public function showAction(SMenu $sMenu)
    {
        $deleteForm = $this->createDeleteForm($sMenu);

        return $this->render('@Hohaya/smenu/show.html.twig', array(
            'sMenu' => $sMenu,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing sMenu entity.
     *
     */
    public function editAction(Request $request, SMenu $sMenu)
    {
        $deleteForm = $this->createDeleteForm($sMenu);
        $editForm = $this->createForm('HohayaBundle\Form\SMenuType', $sMenu);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('smenu_edit', array('id' => $sMenu->getId()));
        }

        return $this->render('@Hohaya/smenu/edit.html.twig', array(
            'sMenu' => $sMenu,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a sMenu entity.
     *
     */
    public function deleteAction(Request $request, SMenu $sMenu)
    {
        $em = $this->getDoctrine()->getManager();
        $sMenu->setSupprimer(1);
        $em->flush();

        // Historique des actions effectuées par utilisateur
        $className = $em->getClassMetadata(get_class($sMenu))->getName();
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $this->get('hohaya.librairie_controller_service')->setUserLog($em, $className, $sMenu->getId(), "Suppression", $user);

        return $this->redirectToRoute('smenu_index');
    }

    /**
     * Creates a form to delete a sMenu entity.
     *
     * @param SMenu $sMenu The sMenu entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(SMenu $sMenu)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('smenu_delete', array('id' => $sMenu->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
