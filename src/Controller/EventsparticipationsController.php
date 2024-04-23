<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

use App\Repository\EventsparticipationsRepository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


use Symfony\Component\HttpFoundation\Request;
use App\Entity\Eventsparticipations;
use App\Entity\Users;
use App\Entity\Events;
class EventsparticipationsController extends AbstractController
{
 
    #[Route('/participate/{idevent}', name: 'participate')]
    public function participate(Request $request, ManagerRegistry $doctrine, int $idevent): Response
    {
        $em = $doctrine->getManager();
        
       
        $user = $em->getRepository(Users::class)->find(8);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        
       
        $eventRepository = $em->getRepository(Events::class);
        $event = $eventRepository->find($idevent);
        if (!$event) {
            throw $this->createNotFoundException('Event not found');
        }
        
 
        $existingParticipation = $em->getRepository(EventsParticipations::class)->findOneBy(['participant' => $user, 'event' => $event]);
        if ($existingParticipation) {
            return $this->redirectToRoute('showEvents');
             $this->addFlash('error', 'User already participated in this event');
        }
    
        
        $affectedRows = $eventRepository->updatePlaces($idevent);
        if ($affectedRows === 0) {
            return $this->redirectToRoute('showEvents');
             $this->addFlash('error', 'Event not found or no more places available');
        }
    
    
        $participant = new EventsParticipations();
        $participant->setParticipant($user);
        $participant->setEvent($event);
        $em->persist($participant);
        $em->flush();
    
        return $this->redirectToRoute('showEvents');
    }
    

    #[Route('/showMyParticipations', name: 'showMyParticipations')]
   public function showMyParticipations(ManagerRegistry $doctrine): Response
   {
 
     $user = $doctrine->getRepository(Users::class)->find(8);

    
     $participations = $doctrine->getRepository(EventsParticipations::class)->findBy(['participant' => $user]);

     if (!$participations) {
        $this->addFlash('error', 'You don\'t have any participation');
        return $this->redirectToRoute('showEvents');
    }

     return $this->render('events/frontOffice/MyParticipationEvent.html.twig', [
         'participations' => $participations
     ]);

}



#[Route('/cancelParticipation/{idparticipation}', name: 'cancelParticipation')]
public function cancelParticipation(int $idparticipation, ManagerRegistry $doctrine): RedirectResponse
{
    $entityManager = $doctrine->getManager();
    $participation = $entityManager->getRepository(EventsParticipations::class)->find($idparticipation);

    if (!$participation) {
        
        throw $this->createNotFoundException('La participation avec l\'ID ' . $idparticipation . ' n\'existe pas.');
    }

    $eventRepository = $entityManager->getRepository(Events::class);


    $eventRepository->updatePlacescancled($idparticipation);
    
    $entityManager->remove($participation);
    $entityManager->flush();

    return $this->redirectToRoute('showEvents');
}

        #[Route('/showparticipants/{idevent}', name: 'showparticipants')]
        public function showParticipants(int $idevent, EventsParticipationsRepository $epRepository): Response
        {

            $participants = $epRepository->findParticipantsByEventId($idevent);
    
            return $this->render('events/backOffice/ShowParticipant.html.twig', [
                'participants' => $participants
            ]);
        }


}