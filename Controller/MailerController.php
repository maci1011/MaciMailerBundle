<?php

namespace Maci\MailerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

use Maci\MailerBundle\Entity\Mail;
use Maci\MailerBundle\Entity\Subscriber;

class MailerController extends Controller
{
	private $em;

	private $securityContext;

	private $user;

	public function __construct(EntityManager $doctrine, SecurityContext $securityContext)
	{
    	$this->em = $doctrine;
	    $this->securityContext = $securityContext;
	    $this->user = $securityContext->getToken()->getUser();
    }

    public function getNewMail()
    {
        return new Mail;
    }

    /**
     * get Swift Message
     */
    public function getSwiftMessage($mail)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($mail->getSubject())
            ->setFrom($mail->getFrom(), $mail->getHeader())
            ->setTo($mail->getCurrentTo(), $mail->getCurrentToHeader())
            ->setBcc($mail->getBcc())
            ->setBody($mail->getText())
            ->addPart($mail->getContent(), 'text/html')
        ;

        return $message;
    }
}
