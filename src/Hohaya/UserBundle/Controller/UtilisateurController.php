<?php
namespace Hohaya\UserBundle\Controller;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Form\Form;

use Hohaya\UserBundle\Entity\Utilisateur;

class UtilisateurController extends Controller
{
    private $eventDispatcher;
    private $formFactory;
    private $userManager;
    public function __construct(EventDispatcherInterface $eventDispatcher, FactoryInterface $formFactory, UserManagerInterface $userManager)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->formFactory = $formFactory;
        $this->userManager = $userManager;
    }
    /**
     * Show the user.
     */
    public function showAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('HohayaUserBundle:Utilisateur')->find($id);

        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('Cet utilisateur n\'a pas accès à cette section.');
        }

        $formFactory = $this->container->get('fos_user.change_password.form.factory');

        $form = $formFactory->createForm();
        //$form->remove('current_password');
        $form->setData($user);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $userManager = $this->container->get('fos_user.user_manager');
                $userManager->updateUser($user);

                return $this->redirect($this->generateUrl('hohaya_user_profile_show', array('id' => $user->getId())));
            }
        }

        return $this->render('@HohayaUser/Utilisateur/Profile/show.html.twig', array(
            'form'   => $form->createView(),
            'user' => $user,
            'id' => $id,
        ));
    }
    /**
     * Enable the user.
     */
    public function activerCompteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('HohayaUserBundle:Utilisateur')->find($id);

        $user->setEnabled(true);

        $this->userManager->updateUser($user);

        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('Cet utilisateur n\'a pas accès à cette section.');
        }

        $formFactory = $this->container->get('fos_user.change_password.form.factory');

        $form = $formFactory->createForm();
        $form->setData($user);

        return $this->render('@HohayaUser/Utilisateur/Profile/show.html.twig', array(
            'form'   => $form->createView(),
            'user' => $user,
            'id' => $id,
        ));
    }

    /**
     * Desactivate the user.
     */
    public function desactiverCompteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('HohayaUserBundle:Utilisateur')->find($id);

        $user->setEnabled(false);

        $this->userManager->updateUser($user);

        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('Cet utilisateur n\'a pas accès à cette section.');
        }
        $formFactory = $this->container->get('fos_user.change_password.form.factory');

        $form = $formFactory->createForm();
        //$form->remove('current_password');
        $form->setData($user);

        return $this->render('@HohayaUser/Utilisateur/Profile/show.html.twig', array(
            'form'   => $form->createView(),
            'user' => $user,
            'id' => $id,
        ));
    }

    /**
     * Edit the user.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('HohayaUserBundle:Utilisateur')->find($id);
        $oldImageFile = $user->getPhoto();
        
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('Cet utilisateur n\'a pas accès à cette section.');
        }

        $roles = [
            "ROLE_SIMPLE" => "ROLE_USER", 
            "ROLE_ADMIN"  => "ROLE_ADMIN", 
            "ROLE_SUPER_ADMIN" => "ROLE_SUPER_ADMIN"
        ];

        $event = new GetResponseUserEvent($user, $request);
        $this->eventDispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);
        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }
        $form = $this->formFactory->createForm();
        $form->setData($user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
//            var_dump($form->getData()->getRoles());
//            die();
            $event = new FormEvent($form, $request);
            $this->eventDispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);

            // dossier temporaire qui va contenir la photo de l'utilisateur
            $dossier = __DIR__ . "/../../../../web/uploads/Fichiers/Utilisateurs/" . $user->getId();

            $image = $_FILES["fos_user_profile_form"]["name"]["file"];
            
            if(!empty($image) && $image != "")
            {
                if (!file_exists($dossier)) {
                    mkdir($dossier, 0777, true);
                }

                $name = explode('.', $image);
                
                // Exemple de nom de fichier dont
                // on souhaite récupérer l'extension
                $extension = '.'.$name[1];

                if (!empty($extension)) {
                    //generation d'un name pour le fichier
                    $filename = $user->getId() . '' . $extension;

                    //si le fichier n'existe pas on le cree
                    if (!file_exists('$dossier/$filename')) {
                        $user->setPhoto($filename);
                        move_uploaded_file($_FILES["fos_user_profile_form"]["tmp_name"]["file"], "$dossier/$filename");
                    }
                }
            }
            else
            {
                $user->setPhoto($oldImageFile);
            }

            $user->setRoles($form->getData()->getRoles());

            $this->userManager->updateUser($user);

            // Historique des actions effectuées par utilisateur
//            $className = $em->getClassMetadata(get_class($user))->getName();
//            $user1 = $this->get('security.token_storage')->getToken()->getUser();
//
//            $this->get('gestransport.librairie_controller_service')->setUserLog($em, $className, $user->getId(), "Modification", $user1);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('hohaya_user_profile_show', array('id' => $id));
                $response = new RedirectResponse($url);
            }
            $this->eventDispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_COMPLETED, new FilterUserResponseEvent($user, $request, $response));
            return $response;
        }
        return $this->render('@HohayaUser/Utilisateur/Profile/edit.html.twig', array(
            'form' => $form->createView(),
            'user' => $user,
            'roles' => $roles,
            'id' => $id,
        ));
    }
}