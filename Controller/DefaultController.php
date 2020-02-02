<?php

namespace Maci\MailerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use Maci\MailerBundle\Entity\Subscriber;

class DefaultController extends AbstractController
{
    public function indexAction()
    {
        // return $this->render('MaciMailerBundle:Mailer:index.html.twig');
        return $this->redirect($this->generateUrl('maci_mailer_notifications'));
    }

    public function notificationsAction()
    {
        $list = $this->getDoctrine()->getManager()
            ->getRepository('MaciMailerBundle:Mail')
            ->findByType( 'notify' );

        return $this->render('MaciMailerBundle:Mailer:notifications.html.twig', array('list' => $list));
    }

    public function subscribersAction()
    {
        $list = $this->getDoctrine()->getManager()
            ->getRepository('MaciMailerBundle:Subscriber')
            ->getList();

        return $this->render('MaciMailerBundle:Mailer:subscribers.html.twig', array('list' => $list));
    }

    public function userMailsAction()
    {
        $list = $this->getDoctrine()->getManager()
            ->getRepository('MaciMailerBundle:Mail')
            ->getUserMails( $this->getUser() );

        return $this->render('MaciMailerBundle:Mailer:user_mails.html.twig', array('list' => $list));
    }

    public function showAction($token)
    {
        $mail = $this->getDoctrine()->getManager()
            ->getRepository('MaciMailerBundle:Mail')
            ->findOneByToken( $token );

        return $this->render('MaciMailerBundle:Mailer:show.html.twig', array('mail' => $mail));
    }

    public function templatesAction()
    {
        // $list = $this->getDoctrine()->getManager()
        //     ->getRepository('MaciMailerBundle:Mail')
        //     ->findByType( 'template' );

        return $this->render('MaciMailerBundle:Mailer:templates.html.twig');
    }

    public function confirmationEmailTemplateAction()
    {
        $mail = $this->getDoctrine()->getManager()
            ->getRepository('MaciMailerBundle:Mail')
            ->findOneById( 1 );

        $cart = $this->getDoctrine()->getManager()
            ->getRepository('MaciOrderBundle:Order')
            ->findOneById( 1 );

        return $this->render('MaciOrderBundle:Email:confirmation_email.html.twig', array('mail' => $mail, 'order' => $cart));
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
