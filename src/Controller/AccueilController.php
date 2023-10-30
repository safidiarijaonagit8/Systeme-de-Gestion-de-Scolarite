<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\VFicheEtudiantRepository;
use Symfony\Component\HttpFoundation\Request;
use \Datetime;
use App\Repository\PaiementRepository;
use App\Repository\MoyenneRepository;
use App\Repository\ResteRepository;
use Knp\Component\Pager\PaginatorInterface;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(): Response
    {
        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
        ]);
    }
    #[Route('/accueil/liste_etudiants', name: 'app_accueil_liste_etudiants')]
    public function liste_etudiants(Request $request,VFicheEtudiantRepository $ficheEtudiantRepository,PaginatorInterface $paginator): Response
    {
        $requete = $ficheEtudiantRepository->findAllEtudiantQuery();
        $etudiants = $paginator->paginate(
            $requete, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            5// Nombre de résultats par page
        );


        return $this->render('vuecoteclient/listeetudiantadmis.html.twig', [
           // 'listeetudiantsadmis' => $ficheEtudiantRepository->findAll(), 
           'listeetudiantsadmis' => $etudiants, 
        ]);
    }
   
    #[Route('/accueil/login_etudiant', name: 'app_accueil_ecran_login_etudiant')]
    public function ecran_login_etudiant(): Response
    {
        return $this->render('vuecoteclient/loginetudiant.html.twig');
    }
   


    #[Route('/accueil/verif_login_etudiant', name: 'app_accueil_traitement_login_etudiant', methods: ['POST'])]
    public function verif_login_etudiant(Request $request,VFicheEtudiantRepository $ficheEtudiantRepository,ResteRepository $resteRepository,PaiementRepository $paiementRepository): Response
    {
        $requete = $request->request->all();
        $idetudiant = $requete['idetudiant'];
        $mdp = $requete['mdp'];
      //  $dateObject = DateTime::createFromFormat('d-m-Y', $mdp);
        $dateDeNaissanceString = date("Y-m-d", strtotime($mdp));  
      //  $dateObject = DateTime::createFromFormat('Y-m-d', $dateDeNaissanceString);

        $etudiant = $ficheEtudiantRepository->verifLoginEtudiant(intval($idetudiant),  $dateDeNaissanceString);
        if($etudiant == null)
        {
           // $message = $dateObject->format('d-m-Y')." ".intval($idetudiant);
           $message = "erreur login";
            return $this->render('vuecoteclient/loginetudiant.html.twig', [
                'message' => $message, 
            ]);
        }
        else
        {
            //$message = "success";
            $session = $request->getSession();
            $session->set('idetudiant', $idetudiant);
            $infoEtudiant = $ficheEtudiantRepository->findInfoEtudiant($idetudiant);
          
            // find by idetudiant paiement
            $paiementEtudiantArray = $paiementRepository->findPaiementsEtudiant($idetudiant);
            // find by idetudiant reste
            $resteEcolageEtudiantArray = $resteRepository->findRestesEcolageEtudiant($idetudiant);
    

            return $this->render('vuecoteclient/accueiletudiant.html.twig', [
            'infoEtudiant' => $infoEtudiant, 
            'paiementEtudiantArray' => $paiementEtudiantArray, 
            'resteEcolageEtudiantArray' => $resteEcolageEtudiantArray, 
            ]);
        }
       
    }
    #[Route('/accueil/resultat_etudiant', name: 'app_accueil_resultat')]
    public function resultat_etudiant(Request $request,MoyenneRepository $moyenneRepository): Response
    {
        $session = $request->getSession();
      if ($session->has('idetudiant'))
      {
        $idetudiant = $session->get('idetudiant');

        $moyenneEtudiantArray = $moyenneRepository->findMoyennesEtudiant($idetudiant);

        return $this->render('vuecoteclient/resultatetudiant.html.twig', [
            'moyenneEtudiantArray' => $moyenneEtudiantArray, 
            'idetudiant' => $idetudiant,
            ]);
        }
        else
        {
            $message = "Vous n'êtes pas connectés ";
            return $this->render('vuecoteclient/loginetudiant.html.twig', [
                'message' => $message, 
            ]);

        }

    }
    #[Route('/accueil/fiche_etudiant', name: 'app_accueil_fiche')]
    public function fiche_etudiant(Request $request,VFicheEtudiantRepository $ficheEtudiantRepository,ResteRepository $resteRepository,PaiementRepository $paiementRepository): Response
    {
        $session = $request->getSession();
        if ($session->has('idetudiant'))
        {
            $idetudiant = $session->get('idetudiant');
            $infoEtudiant = $ficheEtudiantRepository->findInfoEtudiant($idetudiant);
          
            // find by idetudiant paiement
            $paiementEtudiantArray = $paiementRepository->findPaiementsEtudiant($idetudiant);
            // find by idetudiant reste
            $resteEcolageEtudiantArray = $resteRepository->findRestesEcolageEtudiant($idetudiant);
    

            return $this->render('vuecoteclient/accueiletudiant.html.twig', [
            'infoEtudiant' => $infoEtudiant, 
            'paiementEtudiantArray' => $paiementEtudiantArray, 
            'resteEcolageEtudiantArray' => $resteEcolageEtudiantArray, 
            ]);
        }
        else
        {
            $message = "Vous n'êtes pas connectés ";
            return $this->render('vuecoteclient/loginetudiant.html.twig', [
                'message' => $message, 
            ]);

        }

    }
    
    #[Route('/accueil/logout_etudiant', name: 'app_accueil_logout_etudiant')]
    public function logout_etudiant(Request $request): Response
    {
        $session = $request->getSession();
        $session->remove('idetudiant');
        return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
    }
}
