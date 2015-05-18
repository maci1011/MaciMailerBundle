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
        $to = $mail->getCurrentTo();

        if (!$to) {
            return false;
        }

        $message = \Swift_Message::newInstance()
            ->setSubject($mail->getSubject())
            ->setFrom($mail->getFrom(), $mail->getHeader())
            ->setTo($to[0], $to[1])
            ->setBcc($mail->getBcc())
            ->setBody($mail->getContent(), 'text/html')
            ->addPart($mail->getText(), 'text/plain')
        ;

        return $message;
    }
}
