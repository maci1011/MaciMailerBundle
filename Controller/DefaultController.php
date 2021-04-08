<?php

namespace Maci\MailerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Intl\Locales;

use Maci\MailerBundle\Entity\Subscriber;
use Maci\MailerBundle\Form\Type\SubscribeType;


class DefaultController extends AbstractController
{
	public function indexAction()
	{
		// return $this->render('@MaciMailer/Mailer/index.html.twig');
		return $this->redirect($this->generateUrl('maci_mailer_notifications'));
	}

	public function userMailsAction()
	{
		$list = $this->getDoctrine()->getManager()
			->getRepository('MaciMailerBundle:Mail')
			->getUserMails( $this->getUser() );

		return $this->render('@MaciMailer/Mailer/user_mails.html.twig', array('list' => $list));
	}

	public function showAction($token)
	{
		$mail = $this->getDoctrine()->getManager()
			->getRepository('MaciMailerBundle:Mail')
			->findOneByToken( $token );

		$user = $this->getUser();

		return $this->render('@MaciMailer/Mailer/show.html.twig', [
			'mail' => $mail,
			'user' => $user
		]);
	}

	public function subscribeAction(Request $request)
	{
		$subscriber = new Subscriber;

		$form = $this->getSubscribeForm($subscriber);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {

			$subscriber->setLocale($request->getLocale());

			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($subscriber);
			$em->flush();

			return $this->redirect($this->generateUrl('maci_page', array('path' => 'subscription_complete')));
		}

		return $this->render('@MaciMailer/Mailer/subscribe.html.twig', array(
			'form' => $form->createView()
		));
	}

	public function getSubscribeForm(&$subscriber)
	{
		if (!$subscriber) {
			$subscriber = new Subscriber;
		}
		
		$choices = [];
		$choices[ucfirst(Locales::getName('it'))] = 'it';
		$choices[ucfirst(Locales::getName('en'))] = 'en';

		return $this->createForm(SubscribeType::class, $subscriber, array(
			'action' => $this->generateUrl('maci_mailer_subscribe'),
			'method' => 'POST',
			'env' => $this->get('kernel')->getEnvironment(),
			'locales' => $choices
		));
	}

	public function templatesAction()
	{
		// $list = $this->getDoctrine()->getManager()
		//     ->getRepository('MaciMailerBundle:Mail')
		//     ->findByType( 'template' );

		return $this->render('@MaciMailer/Mailer/templates.html.twig');
	}

	public function confirmationEmailTemplateAction()
	{
		$orders = $this->getDoctrine()->getManager()
			->getRepository('MaciOrderBundle:Order')
			->findBy(['status' => ['confirm', 'complete']], ['id' => 'DESC'], ['limit' => 100]);

		return $this->render('@MaciOrder/Email/confirmation_email.html.twig', [
			'order' => $orders[rand(0,count($orders) - 1)]
		]);
	}

	// public function subscribeAction()
	// {
	// 	$form = $this->getSubscribeForm();
	// 	return $this->render('@MaciMailer/Mailer/subscribe.html.twig', array('form' => $form));
	// }
}
