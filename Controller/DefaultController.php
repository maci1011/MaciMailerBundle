<?php

namespace Maci\MailerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Maci\MailerBundle\Entity\Subscriber;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MaciMailerBundle:Mailer:index.html.twig');
    }

    public function listAction()
    {
        $list = $this->getDoctrine()->getManager()
            ->getRepository('MaciMailerBundle:Subscriber')
            ->getList();

        return $this->render('MaciMailerBundle:Mailer:list.html.twig', array('list' => $list));
    }

    public function showAction($token)
    {
        return $this->render('MaciMailerBundle:Mailer:show.html.twig');
    }

    public function addAction($token)
    {
        return $this->render('MaciMailerBundle:Mailer:add.html.twig');
    }

    public function subscribeFormAction(Request $request)
    {
        $subscriber = new Subscriber;

        $form = $this->createForm('subscribe', $subscriber);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $subscriber->setLocale( $request->getLocale() );

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($subscriber);
            $em->flush();

            return $this->redirect($this->generateUrl('maci_page', array('path' => 'subscription_completed')));
        }

        return $this->render('MaciMailerBundle:Form:_subscribe.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
