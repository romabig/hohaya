<?php

namespace HohayaBundle\Controller;

use Doctrine\ORM\EntityManager;
use HohayaBundle\Entity\Menu;
use HohayaBundle\Entity\Publication;
use HohayaBundle\Entity\SMenu;
use HohayaBundle\Entity\SSMenu;
use HohayaBundle\Entity\UserLog;
use Hohaya\UserBundle\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LibrairieController extends AbstractController
{
    private static $entityManager;

    /**
     * Gère l'ensemble des menus du site
     * @return '@Hohaya/Default/afficherMenu.html.twig'
     */
    public function afficherMenuAction()
    {
        $menus = $this->getDoctrine()
            ->getRepository(Menu::class)
            ->findAllOrderedById();

        $smenus = $this->getDoctrine()
            ->getRepository(SMenu::class)
            ->findAllOrderedById();

        return $this->render('@Mica/Default/afficherMenu.html.twig', array(
            'menus' => $menus,
            'smenus' => $smenus,
        ));
    }

    /**
     * Gère l'ensemble des Smenus du site
     * @return '@Hohaya/Default/afficherSMenu.html.twig'
     */
    public function afficherSMenuAction($id)
    {
        $smenus = $this->getDoctrine()
            ->getRepository(SMenu::class)
            ->findByMenu($id);

        return $this->render('@Hohaya/Default/afficherSMenu.html.twig', array(
            'smenus' => $smenus,
        ));
    }

    public function afficherCommuniqueAction()
    {
        $em = $this->getDoctrine()->getManager();

        // liste des communiqués
        $listeCommuniques = $this->getDoctrine()
            ->getRepository(Publication::class)
            ->listeCommunique();

        return $this->render('@Hohaya/Default/afficher.communique.html.twig', array(
            'listeCommuniques' => $listeCommuniques,
        ));
    }

    public function afficherAppelDOffreAction()
    {
        $em = $this->getDoctrine()->getManager();

        // liste des offres
        $listeAppelDOffres = $this->getDoctrine()
            ->getRepository(Publication::class)
            ->listeAppelDOffre();

        return $this->render('@Hohaya/Default/afficher.offre.html.twig', array(
            'listeAppelDOffres' => $listeAppelDOffres,
        ));
    }

    public function afficherDerniereNouvellesAction()
    {
        $em = $this->getDoctrine()->getManager();

        // liste des nouvelles
        $listeDerniereNouvelles = $this->getDoctrine()
            ->getRepository(Publication::class)
            ->listeDerniereNouvelles();

        return $this->render('@Hohaya/Default/afficher.listeDerniereNouvelles.html.twig', array(
            'listeDerniereNouvelles' => $listeDerniereNouvelles,
        ));
    }

    public function afficherDerniereNouvellesFooterAction()
    {
        $em = $this->getDoctrine()->getManager();

        // liste des nouvelles
        $listeDerniereNouvelles = $this->getDoctrine()
            ->getRepository(Publication::class)
            ->listeDerniereNouvelles();

        return $this->render('@Hohaya/Default/afficher.listeDerniereNouvellesFooter.html.twig', array(
            'listeDerniereNouvelles' => $listeDerniereNouvelles,
        ));
    }

    /**
     * Gère la rubrique des liens utiles
     * @return '@Hohaya/Default/afficher.listeLienUtiles.html.twig'
     */
    public function afficherLienUtilesAction()
    {
        $em = $this->getDoctrine()->getManager();

        // liste des nouvelles
        $listeLienUtile = $this->getDoctrine()
            ->getRepository(Publication::class)
            ->listeLienUtile();

        return $this->render('@Hohaya/Default/afficher.listeLienUtiles.html.twig', array(
            'listeLienUtiles' => $listeLienUtile,
        ));
    }

    /**
     * Gère la rubrique bon à savoir
     * @return '@Hohaya/Default/afficher.bonAsavoir.html.twig'
     */
    public function afficherBonASavoirAction()
    {
        $em = $this->getDoctrine()->getManager();

        // liste des bon a savoir
        $listeBonASavoirs = $this->getDoctrine()
            ->getRepository(Publication::class)
            ->listeBonASavoir();

        return $this->render('@Hohaya/Default/afficher.bonAsavoir.html.twig', array(
            'listeBonASavoirs' => $listeBonASavoirs,
        ));
    }

    /**
     * Gère les organismes sous tutelle
     * @return '@Hohaya/Default/afficher.organisme.html.twig'
     */
    public function afficherOrganismeAction()
    {
        $em = $this->getDoctrine()->getManager();

        // liste des bon a savoir
        $listeOrganismes = $this->getDoctrine()
            ->getRepository(SSMenu::class)
            ->listeOrganisme();

        return $this->render('@Hohaya/Default/afficher.organisme.html.twig', array(
            'listeOrganismes' => $listeOrganismes,
        ));
    }

    /**
     * Gère le menu gauche de certaines pages
     * @return '@Hohaya/Default/afficher.menu.gauche.html.twig'
     */
    public function afficherMenuGaucheAction($id = null, $cle = null, $child = null)
    {
        $em = $this->getDoctrine()->getManager();

        $retour = self::element($em, $id, $cle, null, $child);

        return $this->render('@Hohaya/Default/afficher.menu.gauche.html.twig', array(
            'retour' => $retour,
        ));
    }

    /**
     * Gère le slide à afficher sur la page d'accueil
     * @return '@Hohaya/Default/slides.html.twig'
     */
    public function slidesAction()
    {
        $em = $this->getDoctrine()->getManager();

        $listeSlides = $this->getDoctrine()
            ->getRepository(Publication::class)
            ->listeSlides();

        return $this->render('@Hohaya/Default/slides.html.twig', array(
            'listeSlides' => $listeSlides,
        ));
    }

    /**
     * Gère l'arborescence de chaque page
     * @return '@Hohaya/Default/arborescence.html.twig'
     */
    public function arborescenceAction($id = null, $cle = null)
    {
        $em = $this->getDoctrine()->getManager();

        $retour = self::element($em, $id, $cle, null, false);

        return $this->render('@Hohaya/Default/arborescence.html.twig', array(
            'retour' => $retour,
        ));
    }

    /**
     * Retourne la vue de pagination
     * @return '@Hohaya/Default/pagination.html.twig'
     */
    public function AfficherPaginationAction($cle = null, $currentpage = 1, $totalpages, $parent = null)
    {
        return $this->render('@Hohaya/Default/pagination.html.twig', array(
            'currentpage' => $currentpage,
            'totalpages' => $totalpages,
            'parent' => $parent,
            'cle' => $cle,
        ));
    }

    public static function setUserLog(EntityManager $entityManager, $entityName, $entityId, $userAction, Utilisateur $user)
    {
        $em = $entityManager;

        $log = new UserLog();
        $log->setEntityName($entityName);
        $log->setEntityId($entityId);
        $log->setUserAction($userAction);
        $log->setUtilisateur($user);
        $log->setDateCreation(new \DateTime());

        $em->persist($log);
        $em->flush();
    }

    public static function getUserId()
    {
        // $user = $this->get('security.context')->getToken()->getUser();

        // return $user;
    }

    /**
     * Calcul et renvoi le numéro de la page courante, le nombre total de pages et l'offset
     * @return array
     */
    public static function paginateTools($currentpage = 1, $total, $rowsperpage)
    {
        // calcul le nombre total de pages
        $totalpages = (int) ceil($total / $rowsperpage);

        // Récupère la page par défaut ou définit une par défaut
        if (isset($currentpage) && is_numeric($currentpage)) {
           // convertion de varchar en int
           $currentpage = (int) $currentpage;
        } else {
           // numéro de la page par défaut
           $currentpage = 1;
        } // fin

        // Si le numéro de la page courante est plus grand que le nombre total de pages...
        if ($currentpage > $totalpages) {
           // Définit la page courante comme la dernière
           $currentpage = $totalpages;
        } // fin
        // Si le numéro de la page courante est plus petit que le numéro de la première page...
        if ($currentpage < 1) {
           // Définit la page courante comme la première
           $currentpage = 1;
        } // fin

        // L'offset de la liste, est calculé à base du numéro de la page courante 
        $offset = ($currentpage - 1) * $rowsperpage;

        $result["currentpage"] = $currentpage;
        $result["totalpages"] = $totalpages;
        $result["offset"] = $offset;

        return $result;
    }

    /**
     * $entityManager permet de communiquer avec doctrine symfony
     * $id contient l'id d'un menu, d'un sous menu, d'un sous sous menu ou d'une publication
     * $cle permet de savoir si c'est un menu, sous menu ou sous sous menu
     * $ids contient l'id de la publication 
     * $child permet de savoir si la page doit afficher un contenu par défaut ou non
     * $rowsperpage contient le nombre de lignes par page pour les paginations
     * $offset contient l'offset
     * @return array
     */
    public static function element(EntityManager $entityManager, $id = null, $cle = null, $ids = null, $child = false, $rowsperpage = 20, $offset = 0)
    {
        $em = $entityManager;
        self::desactiverMenu($em);

        $retour = array();
        $retour["classname"] = "";

        // renvoi l'id du sous sous menu ou de la publication dont 
        //le contenu sera affiché par défaut dans le menu gauche
        $first_ssmenu_id = 0;

        // Retourne l'ensemble des publications liées au menu, sous menu, ou sous sous menu dont l'id est $id
        $retour["publications"] = $em->getRepository('HohayaBundle:Publication')->paginate($cle, $id, $rowsperpage, $offset);

        switch ($cle) {
            case 'm':
                self::activerMenu($em, $id);
                // Contient l'élément menu dont l'id est $id
                $retour["element"] = $em->getRepository('HohayaBundle:Menu')->findBy(['id' => $id, 'supprimer' => 0]);
                
                $retour["menusGauche"] = array();
                // $retour["publications"] = $em->getRepository('HohayaBundle:Publication')->findBy(["menu" => $id, 'supprimer' => 0]);
                break;
            case 'sm':
                // Contient l'élément sous menu dont l'id est $id
                $retour["element"] = $em->getRepository('HohayaBundle:SMenu')->findBy(['id' => $id, 'supprimer' => 0]);
                
                // Active l'élément dans le menu
                self::activerMenu($em, $retour["element"][0]->getMenu()->getId());

                // Contient tous les menus appartenant à l'élément
                $retour["menusGauche"] = array();

                if (!$child) {
                    $retour["classname"] = "sm";
                    $enfants = $em->getRepository('HohayaBundle:SMenu')->findBy(["menu" => $retour["element"][0]->getMenu()->getId(), 'supprimer' => 0]);
                } else {
                    $retour["classname"] = "ssm";
                    $enfants = $em->getRepository('HohayaBundle:SSMenu')->findBy(["smenu" => $id, 'supprimer' => 0]);
                }

                if (!$child) {
                    foreach ($enfants as $key => $enfant) {
                        // if ($enfant->getId() != $id) {
                            array_push($retour["menusGauche"], $enfant);
                        // }
                    }
                } else {
                    $cpt = 0;
                    foreach ($enfants as $key => $enfant) {
                        if ($key == 0) {
                            $first_ssmenu_id = $enfant->getId();
                        }

                        array_push($retour["menusGauche"], $enfant);
                        $cpt++;
                    }

                    if($first_ssmenu_id == 0)
                    {
                        foreach ($retour["publications"] as $key => $enfant) {
                            if ($key == 0) {
                                $first_ssmenu_id = $enfant->getId();
                            }
                        }
                    }
                }

                // $retour["publications"] = $em->getRepository('HohayaBundle:Publication')->findBy(["smenu" => $id, 'supprimer' => 0]);
                break;
            case 'ssm':
                $retour["element"] = $em->getRepository('HohayaBundle:SSMenu')->findBy(["id" => $id, 'supprimer' => 0]);
                self::activerMenu($em, $retour["element"][0]->getSmenu()->getMenu()->getId());
                $retour["menusGauche"] = array();
                $enfants = $em->getRepository('HohayaBundle:SSMenu')->findBy(["smenu" => $retour["element"][0]->getSmenu()->getId(), 'supprimer' => 0]);
                foreach ($enfants as $enfant) {
                    // if ($enfant->getId() != $id) {
                        array_push($retour["menusGauche"], $enfant);
                    // }
                }
                $retour["classname"] = "ssm";
                // $retour["publications"] = $em->getRepository('HohayaBundle:Publication')->findBy(["ssmenu" => $id, 'supprimer' => 0]);
                break;
            default:
                break;
        }

        if ($ids) {
            $retour["publication"] = $em->getRepository('HohayaBundle:Publication')->find($ids);
        } else {
            $retour["publication"] = null;
        }

        $retour["id"] = $id;
        $retour["ids"] = $ids;
        $retour["cle"] = $cle;
        $retour["first_ssmenu_id"] = $first_ssmenu_id;

        return $retour;
    }

    /**
     * Active tous les menus actifs
     * $id contient du menu à désactiver
     */
    public static function activerMenu(EntityManager $entityManager, $id)
    {
        $em = $entityManager;

        $menu = $em->getRepository('HohayaBundle:Menu')->find($id);

        if ($menu) {
            $menu->setEstActif(true);
            $em->persist($menu);
            $em->flush();
        }
    }

    /**
     * Désactive tous les menus actifs
     */
    public static function desactiverMenu(EntityManager $entityManager)
    {
        $em = $entityManager;

        $menus = $em->getRepository('HohayaBundle:Menu')->findBy(['supprimer' => 0]);

        foreach ($menus as $menu) {
            if ($menu->getEstActif()) {
                $menu->setEstActif(false);
                $em->persist($menu);
                $em->flush();
            }
        }
    }
}
