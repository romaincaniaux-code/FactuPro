<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/client')]
class ClientController extends AbstractController
{
    // ! liste de tous les clients
    #[Route(name: 'app_client_index', methods: ['GET'])]
    public function index(ClientRepository $clientRepository): Response
    {
        $clients = $clientRepository->findAll();

        return $this->render('client/index.html.twig', [
            'clients' => $clients,
        ]);
    }

    // ! ajout d'un client
    #[Route('/new', name: 'app_client_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($client);
            $entityManager->flush();

            return $this->redirectToRoute('app_client_index');
        }

        return $this->render('client/new.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
        ]);
    }

    // ! détail d'un client
    #[Route('/{id}', name: 'app_client_show', methods: ['GET'])]
    public function show(int $id, ClientRepository $clientRepository): Response
    {
        $client = $clientRepository->find($id);

        if ($client === null) {
            throw $this->createNotFoundException('Client introuvable');
        }

        return $this->render('client/show.html.twig', [
            'client' => $client,
        ]);
    }

    // ! modification d'un client
    #[Route('/{id}/edit', name: 'app_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id, ClientRepository $clientRepository, EntityManagerInterface $entityManager): Response
    {
        $client = $clientRepository->find($id);

        if ($client === null) {
            throw $this->createNotFoundException('Client introuvable');
        }

        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_client_index');
        }

        return $this->render('client/edit.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
        ]);
    }

    // ! suppression d'un client (formulaire POST protégé par un jeton CSRF)
    #[Route('/{id}', name: 'app_client_delete', methods: ['POST'])]
    public function delete(Request $request, int $id, ClientRepository $clientRepository, EntityManagerInterface $entityManager): Response
    {
        $client = $clientRepository->find($id);

        if ($client === null) {
            throw $this->createNotFoundException('Client introuvable');
        }

        $token = $request->request->get('_token');

        if ($this->isCsrfTokenValid('delete' . $client->getId(), $token)) {
            $entityManager->remove($client);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_client_index');
    }
}