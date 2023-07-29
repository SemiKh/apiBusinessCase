<?php

namespace App\Controller\Back;

use App\Entity\Nft;
use App\Form\NftType;
use App\Repository\NftRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/nft', name: 'app_admin_nft_')]
class NftController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(NftRepository $nftRepository): Response
    {
        return $this->render('back/pages/nft/index.html.twig', [
            'nfts' => $nftRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, NftRepository $nftRepository): Response
    {
        $nft = new Nft();
        $form = $this->createForm(NftType::class, $nft);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nftRepository->save($nft, true);

            return $this->redirectToRoute('app_admin_nft_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/pages/nft/new.html.twig', [
            'nft' => $nft,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Nft $nft): Response
    {
        return $this->render('back/pages/nft/show.html.twig', [
            'nft' => $nft,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Nft $nft, NftRepository $nftRepository): Response
    {
        $form = $this->createForm(NftType::class, $nft);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nftRepository->save($nft, true);

            return $this->redirectToRoute('app_admin_nft_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/pages/nft/edit.html.twig', [
            'nft' => $nft,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Nft $nft, NftRepository $nftRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$nft->getId(), $request->request->get('_token'))) {
            $nftRepository->remove($nft, true);
        }

        return $this->redirectToRoute('app_admin_nft_index', [], Response::HTTP_SEE_OTHER);
    }
}
