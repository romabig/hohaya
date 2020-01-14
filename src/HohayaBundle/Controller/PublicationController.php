<?php

namespace HohayaBundle\Controller;

use HohayaBundle\Entity\Publication;
use HohayaBundle\Entity\Photo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * Publication controller.
 *
 */
class PublicationController extends Controller
{
    /**
     * Lists all publication entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $publications = $em->getRepository('HohayaBundle:Publication')->findBy(['supprimer' => 0]);

        return $this->render('@Hohaya/publication/index.html.twig', array(
            'publications' => $publications,
        ));
    }

    /**
     * Creates a new publication entity.
     *
     */
    public function newAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $publication = new Publication();
        $photos = null;
        $nouveau = null;

        if (is_null($id) || $id == '') {
            $nouveau = true;
        } else {
            $nouveau = false;
            $publication = $em->getRepository('HohayaBundle:Publication')->find($id);
            $photos = $publication->getPhotos();
        }

        $oldPDFFile = $publication->getNomPDF();
        $oldImageFile = $publication->getNomImage();

        $form = $this->createForm('HohayaBundle\Form\PublicationType', $publication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $forms = $request->request->get('HohayaBundle_publication');

            $menu = $em->getRepository('HohayaBundle:Menu')->find($forms["menu"]);
            $smenu = $em->getRepository('HohayaBundle:SMenu')->find($forms["smenu"]);
            $ssmenu = $em->getRepository('HohayaBundle:SSMenu')->find($forms["ssmenu"]);

            $publication->setMenu($menu);
            $publication->setSmenu($smenu);
            $publication->setSsmenu($ssmenu);

            $medias = $request->files->get('HohayaBundle_publication')["photos"];

            $em->persist($publication);
			
			$em->flush();

            //var_export($publication->getId());
            //die();

            // dossier temporaire qui va contenir les pdf concernant la publication
            $dossier = __DIR__ . "/../../../web/uploads/Images/Publications/pub_" . $publication->getId();

            $pdf = $_FILES["HohayaBundle_publication"]["name"]["nomPDF"];
            // $file_type = $_FILES['HohayaBundle_publication']['type']['nomPDF']; //returns the mimetype

            // $allowed = array("application/pdf");

            $erreur = "";

            if(!empty($pdf) && $pdf != "")
            {
                if (!file_exists($dossier)) {
                    mkdir($dossier, 0777, true);
                }

                $name = explode('.', $pdf);

                // $oldFilenamePath = $dossier.'/'.$oldPDFFile;
                // if (file_exists($oldFilenamePath)) {
                //     unlink($oldFilenamePath);
                // }

                // Exemple de nom de fichier dont
                // on souhaite récupérer l'extension
                $extension = '.'.$name[1];
                if (!empty($extension)) {
                    //generation d'un name pour le fichier
                    $filename = $name[0] . '' . $extension;
                    //si le fichier n'existe pas on le cree
                    if (!file_exists('$dossier/$filename')) {
                        $publication->setNomPDF($filename);
                        move_uploaded_file($_FILES["HohayaBundle_publication"]["tmp_name"]["nomPDF"], "$dossier/$filename");
                    }
                }
            }
            else
            {
                $publication->setNomPDF($oldPDFFile);
                // $erreur = "Seuls les fichiers pdf sont autorisés.";
                // return $this->render('@Hohaya/publication/new.html.twig', array(
                //     'publication' => $publication,
                //     'nouveau' => $nouveau,
                //     'erreurpdf' => $erreur,
                //     'photos' => $photos,
                //     'form' => $form->createView(),
                // ));
            }

            $image = $_FILES["HohayaBundle_publication"]["name"]["nomImage"];
            // $file_type = $_FILES['HohayaBundle_publication']['type']['nomImage']; //returns the mimetype

            // $allowed = array("image/jpeg", "image/gif");
            
            if(!empty($image) && $image != "")
            {
                if (!file_exists($dossier)) {
                    mkdir($dossier, 0777, true);
                }

                $name = explode('.', $image);

                // $oldFilenamePath = $dossier.'/'.$oldImageFile;
                // if (file_exists($oldFilenamePath)) {
                //     unlink($oldFilenamePath);
                // }

                // Exemple de nom de fichier dont
                // on souhaite récupérer l'extension
                $extension = '.'.$name[1];
                if (!empty($extension)) {
                    //generation d'un name pour le fichier
                    $filename = $name[0] . '' . $extension;
                    //si le fichier n'existe pas on le cree
                    if (!file_exists('$dossier/$filename')) {
                        $publication->setNomImage($filename);
                        move_uploaded_file($_FILES["HohayaBundle_publication"]["tmp_name"]["nomImage"], "$dossier/$filename");
                    }
                }
            }
            else
            {
                $publication->setNomImage($oldImageFile);
                // $erreurimg = "Seuls les fichiers jpg, png et gif sont autorisés.";
                // return $this->render('@Hohaya/publication/new.html.twig', array(
                //     'publication' => $publication,
                //     'nouveau' => $nouveau,
                //     'erreurimg' => $erreur,
                //     'photos' => $photos,
                //     'form' => $form->createView(),
                // ));
            }

            if (!empty($_FILES["HohayaBundle_publication"]["name"]["photos"])) {
                $allowed = array("image/jpeg", "image/gif");
                //on verifie si le dossier n'existe pas  sinon on le cree
                if (!file_exists($dossier)) {
                    mkdir($dossier, 0777, true);
                }

                $cpt = 0;
                foreach ($_FILES["HohayaBundle_publication"]["name"]["photos"] as $filedos) {
                    $file_type = $_FILES['HohayaBundle_publication']['type']['photos'][$cpt]; //returns the mimetype
                    if($request->request->get('HohayaBundle_publication')['IsModify'][$cpt] == "true" 
                    && $filedos != "")
                    {
                        $name = explode('.', $filedos);

                        // Exemple de nom de fichier dont
                        // on souhaite récupérer l'extension
                        $extension = '.'.$name[1];
                        if (!empty($extension)) {
                            //generation d'un name pour le fichier
                            $filename = $name[0] . '' . $extension;
                            //si le fichier n'existe pas on le cree
                            if (!file_exists('$dossier/$filename')) {

                                if(!empty($request->request->get('HohayaBundle_publication')['id'][$cpt]) 
                                && $request->request->get('HohayaBundle_publication')['id'][$cpt] > 0)
                                {
                                    $photo = $em->getRepository('HohayaBundle:Photo')->find($request->request->get('HohayaBundle_publication')['id'][$cpt]);
                                    $oldFilenamePath = $dossier.'/'.$photo->getName();
                                    if (file_exists($oldFilenamePath)) {
                                        unlink($oldFilenamePath);
                                    }
                                    $photo->setName($filename);
                                    $photo->setExtension($extension);
                                }
                                else
                                {
                                    $photo = new Photo();
                                    $photo->setName($filename);
                                    $photo->setExtension($extension);
                                    $photo->setChemin("");
                                    $photo->setPublication($publication);
                                    $em->persist($photo);
                                }
                                $em->flush();

                                move_uploaded_file($_FILES["HohayaBundle_publication"]["tmp_name"]["photos"][$cpt], "$dossier/$filename");
                            }
                        }
                        else
                        {
                            // $erreur = "AUTRES FICHIERS : Seuls les fichiers jpg, png et gif sont autorisés.";
                            // return $this->render('@Hohaya/publication/new.html.twig', array(
                            //     'publication' => $publication,
                            //     'nouveau' => $nouveau,
                            //     'erreurimg' => $erreur,
                            //     'photos' => $photos,
                            //     'form' => $form->createView(),
                            // ));
                        }
                    }
                    $cpt++;
                }
            }

            $em->flush();

            // Historique des actions effectuées par utilisateur
            $className = $em->getClassMetadata(get_class($publication))->getName();
            $user = $this->get('security.token_storage')->getToken()->getUser();

            $this->get('hohaya.librairie_controller_service')->setUserLog($em, $className, $publication->getId(), ($nouveau == "oui") ? "Ajout" : "Modification", $user);

            return $this->redirectToRoute('publication_show', array('id' => $publication->getId()));
        }

        return $this->render('@Hohaya/publication/new.html.twig', array(
            'publication' => $publication,
            'nouveau' => $nouveau,
            'id' => $id,
            'photos' => $photos,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a publication entity.
     *
     */
    public function showAction(Publication $publication)
    {
        $deleteForm = $this->createDeleteForm($publication);

        return $this->render('@Hohaya/publication/show.html.twig', array(
            'publication' => $publication,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing publication entity.
     *
     */
    public function editAction(Request $request, Publication $publication)
    {
        $deleteForm = $this->createDeleteForm($publication);
        $editForm = $this->createForm('HohayaBundle\Form\PublicationType', $publication);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('publication_edit', array('id' => $publication->getId()));
        }

        return $this->render('@Hohaya/publication/edit.html.twig', array(
            'publication' => $publication,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a publication entity.
     *
     */
    public function deleteAction(Request $request, Publication $publication)
    {
        $em = $this->getDoctrine()->getManager();
        $publication->setSupprimer(1);
        $em->flush();

        // Historique des actions effectuées par utilisateur
        $className = $em->getClassMetadata(get_class($publication))->getName();
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $this->get('hohaya.librairie_controller_service')->setUserLog($em, $className, $publication->getId(), "Suppression", $user);

        return $this->redirectToRoute('publication_index');
    }

    /**
     * Creates a form to delete a publication entity.
     *
     * @param Publication $publication The publication entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Publication $publication)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('publication_delete', array('id' => $publication->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    public function SMenuAction(Request $request)
    {
        $menu_id = $request->request->get('menu_id');

        // Récupérer Entity manager and repository
        $em = $this->getDoctrine()->getManager();
        $smenusRepository = $em->getRepository("HohayaBundle:SMenu");
        
        // Récupérer les sous menus appartenat au menu dont l'id a été envoyé en tant que paramètre "menuid"
        $smenus = $smenusRepository->createQueryBuilder("q")
            ->where("q.menu = :menuid")
            ->setParameter("menuid", $menu_id)
            ->getQuery()
            ->getResult();
        
        // Sérialiser dans un tableau les données dont nous avons besoin, dans ce cas uniquement le nom et l'id
        // Note: vous pouvez aussi utiliser un sérialiseur, pour des raisons d'explication, nous le ferons manuellement
        $responseArray = array();
        foreach($smenus as $smenu){
            $responseArray[] = array(
                "id" => $smenu->getId(),
                "titre" => $smenu->getTitre()
            );
        }

        return new JsonResponse($responseArray);
    }

    public function SSMenuAction(Request $request)
    {
        $smenu_id = $request->request->get('smenu_id');

        // Récupérer Entity manager and repository
        $em = $this->getDoctrine()->getManager();
        $ssmenusRepository = $em->getRepository("HohayaBundle:SSMenu");
        
        // Récupérer les sous sous menus appartenant au sous menu dont l'id a été envoyé en tant que paramètre "smenuid"
        $ssmenus = $ssmenusRepository->createQueryBuilder("q")
            ->where("q.smenu = :smenuid")
            ->setParameter("smenuid", $smenu_id)
            ->getQuery()
            ->getResult();
        
        // Sérialiser dans un tableau les données dont nous avons besoin, dans ce cas uniquement le nom et l'id
        // Note: vous pouvez aussi utiliser un sérialiseur, pour des raisons d'explication, nous le ferons manuellement
        $responseArray = array();
        foreach($ssmenus as $ssmenu){
            $responseArray[] = array(
                "id" => $ssmenu->getId(),
                "titre" => $ssmenu->getTitre()
            );
        }

        return new JsonResponse($responseArray);
    }
}
