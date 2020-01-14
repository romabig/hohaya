<?php

namespace HohayaBundle\Controller;

use HohayaBundle\Entity\Menu;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Menu controller.
 *
 */
class MenuController extends Controller
{
    /**
     * Lists all menu entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $menus = $em->getRepository('HohayaBundle:Menu')->findBy(['supprimer' => 0]);

        return $this->render('@Hohaya/menu/index.html.twig', array(
            'menus' => $menus,
        ));
    }

    /**
     * Creates a new menu entity.
     *
     */
    public function newAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $menu = new Menu();
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

        if (is_null($id) || $id == '') {
            $nouveau = "oui";
        } else {
            $nouveau = "non";
            $menu = $em->getRepository('HohayaBundle:Menu')->find($id);
        }

        $form = $this->createForm('HohayaBundle\Form\MenuType', $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $forms = $request->request->get("HohayaBundle_menu");

            if ($menu) {
                $menu->setRoute($forms["route"]);
                $em->persist($menu);
                $em->flush();

                // Historique des actions effectuées par utilisateur
                $className = $em->getClassMetadata(get_class($menu))->getName();
                $user = $this->get('security.token_storage')->getToken()->getUser();

                $this->get('hohaya.librairie_controller_service')->setUserLog($em, $className, $menu->getId(), ($nouveau == "oui") ? "Ajout" : "Modification", $user);

                return $this->redirectToRoute('menu_show', array('id' => $menu->getId()));
            }       
        }

        return $this->render('@Hohaya/menu/new.html.twig', array(
            'menu' => $menu,
            'nouveau' => $nouveau,
            'allRoutes' => $allRoutes,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a menu entity.
     *
     */
    public function showAction(Menu $menu)
    {
        $deleteForm = $this->createDeleteForm($menu);

        return $this->render('@Hohaya/menu/show.html.twig', array(
            'menu' => $menu,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing menu entity.
     *
     */
    public function editAction(Request $request, Menu $menu)
    {
        $deleteForm = $this->createDeleteForm($menu);
        $editForm = $this->createForm('HohayaBundle\Form\MenuType', $menu);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('menu_edit', array('id' => $menu->getId()));
        }

        return $this->render('@Hohaya/menu/edit.html.twig', array(
            'menu' => $menu,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a menu entity.
     *
     */
    public function deleteAction(Request $request, Menu $menu)
    {
        $em = $this->getDoctrine()->getManager();
        $menu->setSupprimer(1);
        $em->flush();

        // Historique des actions effectuées par utilisateur
        $className = $em->getClassMetadata(get_class($menu))->getName();
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $this->get('hohaya.librairie_controller_service')->setUserLog($em, $className, $menu->getId(), "Suppression", $user);

        return $this->redirectToRoute('menu_index');
    }

    /**
     * Creates a form to delete a menu entity.
     *
     * @param Menu $menu The menu entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Menu $menu)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('menu_delete', array('id' => $menu->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
