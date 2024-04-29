<?php

namespace App\Controller;

use App\Entity\Events;
use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\AddEventFormType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Repository\EventsRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Form\UpdateEventFormType;

class EventsController extends AbstractController
{
    #[Route('/Add-Event', name: 'AddEvent')]
    public function Addevent(Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger)
    {
        $event = new Events();
        $form = $this->createForm(AddEventFormType::class, $event);
        $user = $doctrine->getManager()->getRepository(Users::class)->find(8);
        $em = $doctrine->getManager();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {    
            $eventImage = $form->get('attachment')->getData();
            if ($eventImage) {
                $originalFilename = pathinfo($eventImage->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $eventImage->guessExtension();
                try {
                    $eventImage->move(
                        $this->getParameter('events_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $e->getMessage();
                }
                $event->setAttachment($newFilename);
                $event->setOwner($user);
                $em->persist($event);
                $em->flush();
            }
    
            return $this->redirectToRoute('showEventsDashboardtutor');
        }
    
        return $this->render('events/backOffice/addNewEvent.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

    #[Route('/showEventsDashboardtutor', name: 'showEventsDashboardtutor')]
    public function showAllEventsDashbord(EventsRepository $rep): Response
    {
        $events = $rep->findByOwner(8); 
        return $this->render("events/backOffice/eventDashboardTutor.html.twig", ["Events" => $events]);

    }
    #[Route('/eventsDetails{idevent}', name: 'eventsDetails')]
    public function eventsDetails(ManagerRegistry $doctrine,Request $request): Response
    {
        $event = $doctrine->getManager()->getRepository(Events::class)->find($request->get('idevent'));
        return $this->render('events/backOffice/eventDetailstutor.html.twig', [
            'event' => $event
        ]);
    }
    #[Route('/deleteEvent{idevent}', name: 'deleteEvent')]
    public function deleteEvent(ManagerRegistry $doctrine,Request $request,Filesystem $filesystem){
        $em = $doctrine->getManager();
        $event = $em->getRepository(Events::class)->find($request->get('idevent'));
        $eventImage = $event->getAttachment();
        $em->remove($event);
        $em->flush();
        if ($eventImage) {
            $eventsDirectory = $this->getParameter('events_directory');
            $imagePath = $eventsDirectory . '/' . $eventImage;
            if ($filesystem->exists($imagePath)) {
                $filesystem->remove($imagePath);
            }
        }
        return $this->redirectToRoute('showEventsDashboardtutor');
    }
    #[Route('/updateEvent{idevent}', name: 'updateEvent')]
    public function editevent(ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger)
    {
        $em = $doctrine->getManager();
        $event = $em->getRepository(Events::class)->find($request->get('idevent'));
        $form = $this->createForm(UpdateEventFormType::class, $event);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $eventImage = $form->get('attachment')->getData();
            if ($eventImage instanceof UploadedFile) {
                $originalFilename = pathinfo($eventImage->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $eventImage->guessExtension();
                try {
                    $eventImage->move(
                        $this->getParameter('events_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                
                }
                $event->setAttachment($newFilename);
            }
            $em->persist($event);
            $em->flush();
            return $this->redirectToRoute('eventsDetails', ['idevent' => $event->getIdevent()]);
        }
        return $this->render('events/backOffice/updateEventtutor.html.twig', [
            'editform' => $form->createView(),
        ]);
    }


    #[Route('/showEvents', name: 'showEvents')]
    public function showAllEvents(EventsRepository $eventsRepository): Response
    {
        $event = $eventsRepository->findAll();
        return $this->render('events/frontOffice/homePageAllEvent.html.twig', [
            'event' => $event
        ]);
    }


    #[Route('/eventsDetailsfront/{idevent}', name: 'eventsDetailsfront')]
    public function eventsDetailsfront(ManagerRegistry $doctrine,Request $request): Response
    {
        $event = $doctrine->getManager()->getRepository(Events::class)->find($request->get('idevent'));
        return $this->render('events/frontOffice/showDetailEvent.html.twig', [
            'event' => $event
        ]);
    }




    #[Route('/searchevents', name: 'search_events_Front')]
    public function searchevents(EventsRepository $eventsRep, Request $request): Response
    {

        $search = $request->query->get('search');
        $events = $eventsRep->searchEventByTitle($search);

        $dataToJson = $this->serializeEvents($events);
        
        return new Response($dataToJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }




    #[Route('/filterEventBydate', name: 'filterEventBydate')]
    public function filterEventBydate(EventsRepository $eventsRep, Request $request): Response
    {
       
        $filter = $request->query->get('filter'); 
        if (!$filter || !in_array($filter, ['Newest', 'Oldest','Expensive','Shipping'])) {

            return new JsonResponse(['error' => 'Invalid filter provided'], Response::HTTP_BAD_REQUEST);
        }
        $events = $eventsRep->filterByDateDesc($filter);
    
        $dataToJson = $this->serializeEvents($events);
    
   
        return new Response($dataToJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }


    

    private function serializeEvents(array $events): string
    {
        $serializer = $this->get('serializer');
        return $serializer->serialize($events, 'json');
    }

    #[Route('/showmap', name: 'showmap')]
    public function map(): Response
    {
        return $this->render('events/backOffice/map.html.twig');
    }

    
}
