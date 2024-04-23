<?php

namespace App\Controller;

use App\Repository\NotificationNewCourseRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotificationNewCourseController extends AbstractController
{
    // notify tutor for new enrollment
    #[Route('/notifyTutor', name: 'notify_tutor')]
    public function notifyTutor(NotificationNewCourseRepository $notifRep,Request $request): Response
    {
        $idTutor = $request->get('idTutor');
        $notifications = $notifRep->getNotCheckedNotif($idTutor);   
        $dataToJson = json_encode($notifications);
        return new Response($dataToJson);
    }
    // read notifications
    #[Route('/readNotificationCourse', name: 'read_notification_course')]
    public function readNotificationCourse(ManagerRegistry $doctrine,NotificationNewCourseRepository $notifRep,Request $request)
    {
        $idTutor = $request->get('idTutor');
        $notifications = $notifRep->getAllNotifications($idTutor);  
        $em = $doctrine->getManager();
        foreach($notifications as $notif){
            $notif->setIsChecked(true);
            $em->persist($notif);
        }
        $em->flush();
        return $this->render('notification_new_course/backOffice/readNotificationCourse.html.twig', [
            'notifications' => $notifications
        ]);
    }
}
