<?php

namespace HohayaBundle\Controller;

use HohayaBundle\Entity\SSMenu;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Ssmenu controller.
 *
 */
class SSMenuController extends Controller
{
    /**
     * Lists all sSMenu entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $sSMenus = $em->getRepository('HohayaBundle:SSMenu')->findBy(['supprimer' => 0]);

        return $this->render('@Hohaya/ssmenu/index.html.twig', array(
            'sSMenus' => $sSMenus,
        ));
    }

    /**
     * Creates a new sSMenu entity.
     *
     */
    public function newAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $sSMenu = new Ssmenu();
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
            $sSMenu = $em->getRepository('HohayaBundle:SSMenu')->find($id);
        }

        $form = $this->createForm('HohayaBundle\Form\SSMenuType', $sSMenu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $forms = $request->request->get("HohayaBundle_ssmenu");

            $sSMenu->setRoute($forms["route"]);

            $em->persist($sSMenu);
            $em->flush();

            // Historique des actions effectuées par utilisateur
            $className = $em->getClassMetadata(get_class($sSMenu))->getName();
            $user = $this->get('security.token_storage')->getToken()->getUser();

            $this->get('hohaya.librairie_controller_service')->setUserLog($em, $className, $sSMenu->getId(), ($nouveau == "oui") ? "Ajout" : "Modification", $user);

            return $this->redirectToRoute('ssmenu_show', array('id' => $sSMenu->getId()));
        }

        return $this->render('@Hohaya/ssmenu/new.html.twig', array(
            'sSMenu' => $sSMenu,
            'nouveau' => $nouveau,
            'allRoutes' => $allRoutes,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a sSMenu entity.
     *
     */
    public function showAction(SSMenu $sSMenu)
    {
        $deleteForm = $this->createDeleteForm($sSMenu);

        return $this->render('@Hohaya/ssmenu/show.html.twig', array(
            'sSMenu' => $sSMenu,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing sSMenu entity.
     *
     */
    public function editAction(Request $request, SSMenu $sSMenu)
    {
        $deleteForm = $this->createDeleteForm($sSMenu);
        $editForm = $this->createForm('HohayaBundle\Form\SSMenuType', $sSMenu);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ssmenu_edit', array('id' => $sSMenu->getId()));
        }

        return $this->render('@Hohaya/ssmenu/edit.html.twig', array(
            'sSMenu' => $sSMenu,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a sSMenu entity.
     *
     */
    public function deleteAction(Request $request, SSMenu $ssMenu)
    {
        $em = $this->getDoctrine()->getManager();
        $ssMenu->setSupprimer(1);
        $em->flush();

        // Historique des actions effectuées par utilisateur
        $className = $em->getClassMetadata(get_class($ssMenu))->getName();
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $this->get('hohaya.librairie_controller_service')->setUserLog($em, $className, $ssMenu->getId(), "Suppression", $user);

        return $this->redirectToRoute('ssmenu_index');
    }

    /**
     * Creates a form to delete a sSMenu entity.
     *
     * @param SSMenu $sSMenu The sSMenu entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(SSMenu $sSMenu)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ssmenu_delete', array('id' => $sSMenu->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
