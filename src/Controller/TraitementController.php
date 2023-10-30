<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ParametreRepository;
use App\Repository\CandidatsRepository;
use App\Repository\PaiementRepository;
use App\Repository\MoyenneRepository;
use App\Repository\ResteRepository;
use App\Repository\VFicheEtudiantRepository;
use App\Repository\VueresultatparsemestreRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Etudiants;
use App\Entity\Moyenne;
use App\Entity\Reste;
use App\Entity\Paiement;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;


/**
* @IsGranted("ROLE_ADMIN")
*/
class TraitementController extends AbstractController
{
    

    #[Route('/traitement/liste_etudiants', name: 'app_traitement_liste_etudiants')]
    public function liste_etudiants(Request $request,VFicheEtudiantRepository $ficheEtudiantRepository,PaginatorInterface $paginator): Response
    {
        $requete = $ficheEtudiantRepository->findAllEtudiantQuery();
        $etudiants = $paginator->paginate(
            $requete, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            5// Nombre de résultats par page
        );

         return $this->render('etudiants/index.html.twig', [
            //'listeetudiants' => $ficheEtudiantRepository->findAll(), 
            'listeetudiants' =>  $etudiants, 
        ]);
    }
  

    #[Route('/saisiemoyennesemestre/{idetudiant}', name: 'app_traitement_saisiemoyennesemestre', methods: ['GET'])]
    public function saisiemoyennesemestre(ParametreRepository $parametreRepository,Request $request, $idetudiant): Response
    {
         return $this->render('etudiants/saisiemoyenne.html.twig', [
            'idetudiant' => $idetudiant,
            'parametres' => $parametreRepository->findAll(),

        ]);
    }
    #[Route('/saisiepaiementsemestre/{idetudiant}', name: 'app_traitement_saisiepaiementsemestre', methods: ['GET'])]
    public function saisiepaiementsemestre(ParametreRepository $parametreRepository,Request $request, $idetudiant): Response
    {
         return $this->render('etudiants/saisiepaiement.html.twig', [
            'idetudiant' => $idetudiant,
            'parametres' => $parametreRepository->findAll(),

        ]);
    }
    #[Route('/resultatsemestre', name: 'app_traitement_resultat_semestre', methods: ['GET'])]
    public function resultatsemestre(ParametreRepository $parametreRepository): Response
    {
         return $this->render('etudiants/choixsemestreresultat.html.twig', [
            'parametres' => $parametreRepository->findAll(),

        ]);
    }
    #[Route('/voirresultatsemestre', name: 'app_traitement_voir_resultat_semestre', methods: ['POST'])]
    public function voirresultatsemestre(Request $request,VueresultatparsemestreRepository $vueresultatparsemestre): Response
    {
        $requete = $request->request->all();

        $resultatParSemestreArray = $vueresultatparsemestre->findResultatParSemestre($requete['semestre']);

         return $this->render('etudiants/semestreresultat.html.twig', [
            'resultats' => $resultatParSemestreArray,
            'semestre' => $requete['semestre'],

        ]);
    }
    #[Route('/savemoyennesemestre', name: 'app_traitement_savemoyennesemestre', methods: ['POST'])]
    public function savemoyennesemestre(Request $request,EntityManagerInterface $entityManager,VFicheEtudiantRepository $ficheEtudiantRepository,PaginatorInterface $paginator): Response
    {

        $requete = $request->request->all();

        $moyenne = new Moyenne(); 
        $moyenne->setIdetudiant($requete['idetudiant']); 
        $moyenne->setSemestre($requete['semestre']); 
        $moyenne->setMoyennegenerale($requete['moyennegenerale']); 

        $entityManager->persist($moyenne);
        $entityManager->flush();

        $requeteQuery = $ficheEtudiantRepository->findAllEtudiantQuery();
        $etudiants = $paginator->paginate(
            $requeteQuery, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            5// Nombre de résultats par page
        );

         return $this->render('etudiants/index.html.twig', [
            //'listeetudiants' => $ficheEtudiantRepository->findAll(), 
            'listeetudiants' =>  $etudiants, 
        ]);


      
    }

    #[Route('/savepaiementsemestre', name: 'app_traitement_savepaiementsemestre', methods: ['POST'])]
    public function savepaiementsemestre(Request $request,EntityManagerInterface $entityManager,VFicheEtudiantRepository $ficheEtudiantRepository,PaiementRepository $paiementRepository,ParametreRepository $parametreRepository,ResteRepository $resteRepository,PaginatorInterface $paginator): Response
    {

        $requete = $request->request->all();

        $paiementEffectue = $paiementRepository->findPaiementEffectue($requete['idetudiant'],$requete['semestre']);

        if($paiementEffectue == null)
        {
            //insert paiement
            $paiement = new Paiement(); 
            $paiement->setIdetudiant($requete['idetudiant']); 
            $paiement->setSemestre($requete['semestre']); 
            $paiement->setMontantpaye($requete['montantpaye']); 
    
             $entityManager->persist($paiement);
             $entityManager->flush();
         

              $reste = new Reste(); 
              $reste->setIdetudiant($requete['idetudiant']); 
              $reste->setSemestre($requete['semestre']); 

              $parametre = $parametreRepository->findMontantEcolageBySemestre($requete['semestre']);
              $montantEcolage = $parametre->getMontantecolage();
              $montantReste = $montantEcolage - $requete['montantpaye'];

            $reste->setMontant($montantReste); 

              $entityManager->persist($reste);
              $entityManager->flush();


           
        }
        else
        {
            //update montant payer
         $idPaiementEffectue = $paiementEffectue->getId();
         $montantPayerAvant = $paiementEffectue->getMontantpaye();
         $montantPayerNow = $montantPayerAvant+$requete['montantpaye'];

        $paiementRepository->updatePaiementEffectue($idPaiementEffectue,$montantPayerNow);

         //update reste = reste - montant vao naloa
        $resteExistante = $resteRepository->findResteExistante($requete['idetudiant'],$requete['semestre']);
        $idResteExistante = $resteExistante->getId();
        $montantResteAvant = $resteExistante->getMontant();
        $montantResteNow = $montantResteAvant - $requete['montantpaye'];

        $resteRepository->updateReste($idResteExistante,$montantResteNow);


        
         
        }

        $requeteQuery = $ficheEtudiantRepository->findAllEtudiantQuery();
        $etudiants = $paginator->paginate(
            $requeteQuery, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            5// Nombre de résultats par page
        );

         return $this->render('etudiants/index.html.twig', [
            //'listeetudiants' => $ficheEtudiantRepository->findAll(), 
            'listeetudiants' =>  $etudiants, 
        ]);

       
    }

 #[Route('/etudiantfiche/{idetudiant}', name: 'app_traitement_etudiantfiche', methods: ['GET'])]
    public function etudiantfiche(MoyenneRepository $moyenneRepository,ResteRepository $resteRepository,PaiementRepository $paiementRepository,EntityManagerInterface $entityManager,VFicheEtudiantRepository $ficheEtudiantRepository,$idetudiant): Response
    {

      

        // find by idetudiant vficheEtudiant
        $infoEtudiant = $ficheEtudiantRepository->findInfoEtudiant($idetudiant);
        // find by idetudiant moyenne
        $moyenneEtudiantArray = $moyenneRepository->findMoyennesEtudiant($idetudiant);
        // find by idetudiant paiement
        $paiementEtudiantArray = $paiementRepository->findPaiementsEtudiant($idetudiant);
        // find by idetudiant reste
        $resteEcolageEtudiantArray = $resteRepository->findRestesEcolageEtudiant($idetudiant);


        return $this->render('etudiants/fiche.html.twig', [
            'infoEtudiant' => $infoEtudiant, 
            'moyenneEtudiantArray' => $moyenneEtudiantArray, 
            'paiementEtudiantArray' => $paiementEtudiantArray, 
            'resteEcolageEtudiantArray' => $resteEcolageEtudiantArray, 

        ]);
    }


    
    #[Route('/traitement', name: 'app_traitement')]
    public function index(ParametreRepository $parametreRepository, CandidatsRepository $candidatRepository): Response
    {


        $semestre = 1;
        $place = $parametreRepository->findPlaceDispoBySemestre($semestre);  //return List

        $parametre = $place[0];
        $nbrplacedispo = $parametre->getNbrplacedispo();

        $listeCandidatsAdmissible = $candidatRepository->getListeCandidatsAdmissible($nbrplacedispo);


return $this->render('candidats/listeCandidatsAdmissible.html.twig', [
            'listecandidats' => $listeCandidatsAdmissible,  
        ]);
    }
    #[Route('/traitement/admission_etudiant', name: 'app_traitement_admission_etudiant')]
    public function admission_etudiant(ParametreRepository $parametreRepository, CandidatsRepository $candidatRepository,VFicheEtudiantRepository $ficheEtudiantRepository, EntityManagerInterface $entityManager,Request $request,PaginatorInterface $paginator): Response
    {


        $semestre = 1;
        $place = $parametreRepository->findPlaceDispoBySemestre($semestre);  //return List

        $parametre = $place[0];
        $nbrplacedispo = $parametre->getNbrplacedispo();

        $listeCandidatsAdmissible = $candidatRepository->getListeCandidatsAdmissible($nbrplacedispo);

        foreach($listeCandidatsAdmissible as $candidatadmis )
        {
            $etudiant = new Etudiants();
           
        
        $etudiant->setIdcandidat($candidatadmis->getId());

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($etudiant);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
        }

        foreach($listeCandidatsAdmissible as $candidatadmis )
        {
        $candidat = $candidatRepository->find($candidatadmis->getId());

        $candidat->setEstdejaadmis('oui');

        $entityManager->flush();

        }

        $requeteQuery = $ficheEtudiantRepository->findAllEtudiantQuery();
        $listeetudiants = $paginator->paginate(
            $requeteQuery, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            5// Nombre de résultats par page
        );

         return $this->render('etudiants/index.html.twig', [
            //'listeetudiants' => $ficheEtudiantRepository->findAll(), 
            'listeetudiants' =>  $listeetudiants, 
        ]);

        

    }

    
}
