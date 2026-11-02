<?php

namespace App\Controller;

use App\Dto\UserRegistrationDto;
use App\Entity\Utilisateur;
use App\Form\RegistrationType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    // ! page de connexion
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // ! déjà connecté : inutile de revoir le formulaire
        if ($this->getUser()) {
            return $this->redirectToRoute('app_client_index');
        }

        return $this->render('auth/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    // ! la déconnexion est gérée par le pare-feu, cette méthode n'est jamais exécutée
    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('Cette méthode est interceptée par le firewall.');
    }

    // ! création d'un compte
    #[Route('/inscription', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $em,
        UtilisateurRepository $utilisateurRepository,
    ): Response {
        $nombreUtilisateurs = $utilisateurRepository->count([]);

        // ! après le premier compte, seul un administrateur peut en créer d'autres
        if ($nombreUtilisateurs > 0) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }

        $dto = new UserRegistrationDto();
        $form = $this->createForm(RegistrationType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ! l'email doit être unique
            $existant = $utilisateurRepository->findOneBy(['email' => $dto->email]);

            if ($existant !== null) {
                $form->get('email')->addError(new FormError('Cet email est déjà utilisé.'));
            } else {
                $utilisateur = new Utilisateur();
                $utilisateur->setEmail($dto->email);
                $utilisateur->setPassword($hasher->hashPassword($utilisateur, $dto->plainPassword));

                // ! le tout premier compte est administrateur
                if ($nombreUtilisateurs === 0) {
                    $utilisateur->setRoles(['ROLE_ADMIN']);
                } else {
                    $utilisateur->setRoles(['ROLE_USER']);
                }

                $em->persist($utilisateur);
                $em->flush();

                $this->addFlash('success', 'Le compte a bien été créé.');

                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('auth/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}