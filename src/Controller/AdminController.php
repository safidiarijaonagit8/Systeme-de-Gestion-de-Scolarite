<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Form\AdminType;
use App\Repository\AdminRepository;
use App\Repository\ParametreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class AdminController extends AbstractController
{
    /**
* @IsGranted("ROLE_ADMIN")
*/
    #[Route('/admin/', name: 'app_admin_index', methods: ['GET'])]
    public function index(AdminRepository $adminRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'admins' => $adminRepository->findAll(),
        ]);
    }

    
     /**
* @IsGranted("ROLE_ADMIN")
*/
    #[Route('/admin/new', name: 'app_admin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,UserPasswordHasherInterface $passwordHasher): Response
    {
        $admin = new Admin();
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$admin->setPassword(
               // $passwordEncoder->encodePassword($admin, $admin->getPassword()));
               $hashedPassword = $passwordHasher->hashPassword(
                $admin, $admin->getPassword());
                $roles[] = 'ROLE_ADMIN';
                $admin->setRoles($roles);
                $admin->setPassword($hashedPassword);
                
            $entityManager->persist($admin);
            $entityManager->flush();


          //  return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
            //redirect login  app_login
            $message = 'inscription réussie';
            return $this->render('security/login.html.twig', [
                'message' => $message,'last_username'=> $admin->getUsername()
               
            ]);
        }

        return $this->render('admin/new.html.twig', [
            'admin' => $admin,
            'form' => $form,
        ]);
    }


       /**
* @IsGranted("ROLE_ADMIN")
*/
    #[Route('/admin/{id}', name: 'app_admin_show', methods: ['GET'])]
    public function show(Admin $admin): Response
    {
        return $this->render('admin/show.html.twig', [
            'admin' => $admin,
        ]);
    }

     /**
* @IsGranted("ROLE_ADMIN")
*/
    #[Route('/adminEdit/{id}', name: 'app_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Admin $admin, EntityManagerInterface $entityManager,UserPasswordHasherInterface $passwordHasher): Response
    {
       /* $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/edit.html.twig', [
            'admin' => $admin,
            'form' => $form,
        ]);*/
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             //   $utilisateur->setPassword($passwordEncoder->encodePassword($utilisateur, $utilisateur->getPassword()));
             $hashedPassword = $passwordHasher->hashPassword(
                $admin, $admin->getPassword());
               
                $admin->setPassword($hashedPassword);
                $entityManager->flush();

                return $this->redirectToRoute('app_admin_index');
        }

       

        return $this->render('admin/edit.html.twig', [
            'admin' => $admin,
        'form' => $form->createView(),
        ]);
    }


       /**
* @IsGranted("ROLE_ADMIN")
*/
    #[Route('/admin/{id}', name: 'app_admin_delete', methods: ['POST'])]
    public function delete(Request $request, Admin $admin, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$admin->getId(), $request->request->get('_token'))) {
            $entityManager->remove($admin);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
    }

   
}
