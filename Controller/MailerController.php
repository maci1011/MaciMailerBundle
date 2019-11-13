<?php

namespace Maci\MailerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Maci\MailerBundle\Entity\Mail;
use Maci\MailerBundle\Entity\Subscriber;

class MailerController extends Controller
{
	private $user;

	public function __construct()
	{
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

        $message = (new \Swift_Message())
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
