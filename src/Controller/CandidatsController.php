<?php

namespace App\Controller;

use App\Entity\Candidats;
use App\Form\CandidatsType;
use App\Repository\CandidatsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\File;


/**
* @IsGranted("ROLE_ADMIN")
*/

class CandidatsController extends AbstractController
{
   
    #[Route('/candidats/', name: 'app_candidats_index', methods: ['GET'])]
    public function index(CandidatsRepository $candidatsRepository): Response
    {
        return $this->render('candidats/index.html.twig', [
            'candidats' => $candidatsRepository->findAllPasEncoreAdmis(),
        ]);
    }
    
   /* #[Route('/', name: 'app_candidats_index', methods: ['GET'])]
    public function index(CandidatsRepository $candidatsRepository): Response
    {
        return $this->render('candidats/index1.html.twig', [
            'candidats' => $candidatsRepository->findAll(),
        ]);
    }*/

    #[Route('/candidats/new', name: 'app_candidats_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $candidat = new Candidats();
        $form = $this->createForm(CandidatsType::class, $candidat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageCandidatFile = $form->get('imagefichiername')->getData();

            if ($imageCandidatFile) {
                $originalFilename = pathinfo($imageCandidatFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageCandidatFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imageCandidatFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $candidat->setImagefichiername($newFilename);
            }

            $entityManager->persist($candidat);
            $entityManager->flush();

            return $this->redirectToRoute('app_candidats_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('candidats/new.html.twig', [
            'candidat' => $candidat,
            'form' => $form,
        ]);
    }

    #[Route('/candidats/{id}', name: 'app_candidats_show', methods: ['GET'])]
    public function show(Candidats $candidat): Response
    {
        return $this->render('candidats/show.html.twig', [
            'candidat' => $candidat,
        ]);
    }

    #[Route('/candidatsEdit/{id}', name: 'app_candidats_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Candidats $candidat, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        //$candidat->setImagefichiername(new File($this->getParameter('images_directory').'/'.$candidat->getImagefichiername()));
        $form = $this->createForm(CandidatsType::class, $candidat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageCandidatFile = $form->get('imagefichiername')->getData();

            if ($imageCandidatFile) {
                $originalFilename = pathinfo($imageCandidatFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageCandidatFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imageCandidatFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $candidat->setImagefichiername($newFilename);
            }
            
            $entityManager->flush();

            return $this->redirectToRoute('app_candidats_index', [], Response::HTTP_SEE_OTHER);
        }

       
        return $this->render('candidats/edit.html.twig', [
            'candidat' => $candidat,
            'form' => $form,
        ]);
    }

    #[Route('/candidats/{id}', name: 'app_candidats_delete', methods: ['POST'])]
    public function delete(Request $request, Candidats $candidat, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$candidat->getId(), $request->request->get('_token'))) {
            $entityManager->remove($candidat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_candidats_index', [], Response::HTTP_SEE_OTHER);
    }
}
