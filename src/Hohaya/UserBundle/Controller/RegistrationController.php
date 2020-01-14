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
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class RegistrationController extends Controller
{
    private $eventDispatcher;
    private $formFactory;
    private $userManager;
    private $tokenStorage;

    public function __construct(EventDispatcherInterface $eventDispatcher, FactoryInterface $formFactory, UserManagerInterface $userManager, TokenStorageInterface $tokenStorage)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->formFactory = $formFactory;
        $this->userManager = $userManager;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function registerAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->userManager->createUser();
        $user->setEnabled(false);

        $event = new GetResponseUserEvent($user, $request);
        $this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $this->formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $event = new FormEvent($form, $request);
                $this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

                $forms = $request->request->get("fos_user_registration_form");
                $media = $request->files->get('fos_user_registration_form');

                $user->setRoles($forms["roles"]);

                $this->userManager->updateUser($user);

                $user1 = $em->getRepository('HohayaUserBundle:Utilisateur')->find($user->getId());
                $oldImageFile = $user1->getPhoto();

                // dossier temporaire qui va contenir la photo de l'utilisateur
                $dossier = __DIR__ . "/../../../../web/uploads/Fichiers/Utilisateurs/" . $user->getId();

                $image = $_FILES["fos_user_registration_form"]["name"]["file"];
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
                        $filename = $user1->getId() . '' . $extension;

                        //si le fichier n'existe pas on le cree
                        if (!file_exists('$dossier/$filename')) {
                            $user1->setPhoto($filename);
                            move_uploaded_file($_FILES["fos_user_registration_form"]["tmp_name"]["file"], "$dossier/$filename");
                        }
                    }
                }
                else
                {
                    $user1->setPhoto($oldImageFile);
                }

                $em->persist($user1);

                $em->flush();

                // Historique des actions effectuées par utilisateur
//                $className = $em->getClassMetadata(get_class($user))->getName();
//                $user1 = $this->get('security.token_storage')->getToken()->getUser();

                //$this->get('gestransport.librairie_controller_service')->setUserLog($em, $className, $user->getId(), "Ajout", $user1);

                if (null === $response = $event->getResponse()) {
                    $url = $this->generateUrl('hohaya_user_registration_confirmed', ['id' => $user->getId()]);
                    $response = new RedirectResponse($url);
                }

                $this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                return $response;
            }

            $event = new FormEvent($form, $request);
            $this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_FAILURE, $event);

            if (null !== $response = $event->getResponse()) {
                return $response;
            }
        }

        return $this->render('@HohayaUser/Utilisateur/Registration/register.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Tell the user to check their email provider.
     */
    public function checkEmailAction(Request $request)
    {
        $email = $request->getSession()->get('fos_user_send_confirmation_email/email');

        if (empty($email)) {
            return new RedirectResponse($this->generateUrl('hohaya_user_registration_register'));
        }

        $request->getSession()->remove('fos_user_send_confirmation_email/email');
        $user = $this->userManager->findUserByEmail($email);

        if (null === $user) {
            return new RedirectResponse($this->container->get('router')->generate('hohaya_user_security_login'));
        }

        return $this->render('@HohayaUser/Utilisateur/Registration/check_email.html.twig', array(
            'user' => $user,
        ));
    }

    /**
     * Receive the confirmation token from user email provider, login the user.
     *
     * @param Request $request
     * @param string  $token
     *
     * @return Response
     */
    public function confirmAction(Request $request, $token)
    {
        $userManager = $this->userManager;

        $user = $userManager->findUserByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('L\'utilisateur avec jeton de confirmation "%s" n\'existe pas.', $token));
        }

        $user->setConfirmationToken(null);
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_CONFIRM, $event);

        $userManager->updateUser($user);

        if (null === $response = $event->getResponse()) {
            $url = $this->generateUrl('hohaya_user_registration_confirmed');
            $response = new RedirectResponse($url);
        }

        $this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_CONFIRMED, new FilterUserResponseEvent($user, $request, $response));

        return $response;
    }

    /**
     * Tell the user his account is now confirmed.
     */
    public function confirmedAction(Request $request, $id)
    {
        $userManager = $this->userManager;
        $user = $userManager->findUserBy(['id' => $id]);
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('Cet utilisateur n\'a pas accès à cette section.');
        }

        return $this->render('@HohayaUser/Utilisateur/Registration/confirmed.html.twig', array(
            'user' => $user,
            'targetUrl' => $this->getTargetUrlFromSession($request->getSession()),
        ));
    }

    /**
     * @return string|null
     */
    private function getTargetUrlFromSession(SessionInterface $session)
    {
        $key = sprintf('_security.%s.target_path', $this->tokenStorage->getToken()->getProviderKey());

        if ($session->has($key)) {
            return $session->get($key);
        }

        return null;
    }
}
