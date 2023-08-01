<?php

namespace App\Controller\Back;

use App\Entity\CoursNft;
use App\Form\CoursNftType;
use App\Repository\CoursNftRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/cours_nft', name: 'app_admin_cours_nft_')]
class CoursNftController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(CoursNftRepository $coursNftRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $cours_nfts = $paginator->paginate(
            $coursNftRepository->findAll(),
            $request->query->getInt('page', 1),
            8
        );

        return $this->render('back/pages/cours_nft/index.html.twig', [
            'cours_nfts' => $cours_nfts,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, CoursNftRepository $coursNftRepository): Response
    {
        $coursNft = new CoursNft();
        $form = $this->createForm(CoursNftType::class, $coursNft);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coursNftRepository->save($coursNft, true);

            return $this->redirectToRoute('app_admin_cours_nft_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/pages/cours_nft/new.html.twig', [
            'cours_nft' => $coursNft,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(CoursNft $coursNft): Response
    {
        return $this->render('back/pages/cours_nft/show.html.twig', [
            'cours_nft' => $coursNft,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CoursNft $coursNft, CoursNftRepository $coursNftRepository): Response
    {
        $form = $this->createForm(CoursNftType::class, $coursNft);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coursNftRepository->save($coursNft, true);

            return $this->redirectToRoute('app_admin_cours_nft_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/pages/cours_nft/edit.html.twig', [
            'cours_nft' => $coursNft,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, CoursNft $coursNft, CoursNftRepository $coursNftRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$coursNft->getId(), $request->request->get('_token'))) {
            $coursNftRepository->remove($coursNft, true);
        }

        return $this->redirectToRoute('app_admin_cours_nft_index', [], Response::HTTP_SEE_OTHER);
    }
}
