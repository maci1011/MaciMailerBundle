<?php

namespace Maci\MailerBundle\Entity;

/**
 * Mail
 */
class Mail
{
	/**
	 * @var integer
	 */
	private $id;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $token;

	/**
	 * @var string
	 */
	private $type;

	/**
	 * @var string
	 */
	private $sender;

	/**
	 * @var string
	 */
	private $header;

	/**
	 * @var string
	 */
	private $subject;

	/**
	 * @var string
	 */
	private $text;

	/**
	 * @var string
	 */
	private $content;

	/**
	 * @var json
	 */
	private $data;

	/**
	 * @var string
	 */
	private $locale;

	/**
	 * @var boolean
	 */
	private $public;

	/**
	 * @var boolean
	 */
	private $sended;

	/**
	 * @var bolean
	 */
	private $removed;

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 */
	private $slides;

	/**
	 * @var \Maci\UserBundle\Entity\User
	 */
	private $user;


	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->template = 'MaciMailerBundle:Templates:default.html.twig';
		$this->type = $this->getTypes()[0];
		$this->token = md5(
			'MaciMailerBundle_Entity_Mail-' . rand(10000, 99999) . '-' . 
			date('h') . date('i') . date('s') . date('m') . date('d') . date('Y')
		);
		$this->data = $this->getDefaultData();
		$this->public = false;
		$this->sended = false;
		$this->removed = false;
		$this->slides = new \Doctrine\Common\Collections\ArrayCollection();
	}


	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Set name
	 *
	 * @param string $name
	 *
	 * @return Mail
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * Get name
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Set token
	 *
	 * @param string $token
	 * @return Order
	 */
	public function setToken($token)
	{
		$this->token = $token;

		return $this;
	}

	/**
	 * Get token
	 *
	 * @return string 
	 */
	public function getToken()
	{
		return $this->token;
	}

	/**
	 * Set type
	 *
	 * @param string $type
	 *
	 * @return Mail
	 */
	public function setType($type)
	{
		$this->type = $type;

		return $this;
	}

	/**
	 * Get type
	 *
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * Get Type Array
	 */
	static public function getTypeArray()
	{
		return [
			'Unknown' => 'unknown',
			'Message' => 'message',
			'Newsletter' => 'newsletter',
			'Notify' => 'notify'
		];
	}

	public function getTypeLabel()
	{
		$array = $this->getTypeArray();
		$key = array_search($this->type, $array);
		if ($key) {
			return $key;
		}
		$str = str_replace('_', ' ', $this->type);
		return ucwords($str);
	}

	static public function getTypes()
	{
		return array_values(Mail::getTypeArray());
	}

	/**
	 * Set sender
	 *
	 * @return Mail
	 */
	public function setSender($sender, $header = false)
	{
		$this->sender = $sender;

		if ($header) {
			$this->header = $header;
		}

		return $this;
	}

	/**
	 * Get sender
	 *
	 * @return string
	 */
	public function getSender()
	{
		return $this->sender;
	}

	/**
	 * Set header
	 *
	 * @param string $header
	 *
	 * @return MailTranslation
	 */
	public function setHeader($header)
	{
		$this->header = $header;

		return $this;
	}

	/**
	 * Get header
	 *
	 * @return string
	 */
	public function getHeader()
	{
		return $this->header;
	}

	/**
	 * Set subject
	 *
	 * @param string $subject
	 *
	 * @return MailTranslation
	 */
	public function setSubject($subject)
	{
		$this->subject = $subject;

		return $this;
	}

	/**
	 * Get subject
	 *
	 * @return string
	 */
	public function getSubject()
	{
		return $this->subject;
	}

	/**
	 * Set text
	 *
	 * @param string $text
	 *
	 * @return MailTranslation
	 */
	public function setText($text)
	{
		$this->text = $text;

		return $this;
	}

	/**
	 * Get text
	 *
	 * @return string
	 */
	public function getText()
	{
		return $this->text;
	}

	/**
	 * Set content
	 *
	 * @param string $content
	 *
	 * @return MailTranslation
	 */
	public function setContent($content)
	{
		$this->content = $content;

		return $this;
	}

	/**
	 * Get content
	 *
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * Set data
	 *
	 * @param string $data
	 *
	 * @return Mail
	 */
	public function setData($data)
	{
		$this->data = $data;

		return $this;
	}

	/**
	 * Get default data
	 *
	 * @return array
	 */
	static public function getDefaultData()
	{
		return [
			'recipients' => []
		];
	}

	/**
	 * Get data
	 *
	 * @return string
	 */
	public function getData()
	{
		if (is_null($this->data)) return $this->getDefaultData();
		return $this->data;
	}

	/**
	 * Reset data
	 *
	 * @return Mail
	 */
	public function resetData()
	{
		$this->data = $this->getDefaultData();

		return $this;
	}

	public function addRecipients($list)
	{
		$recipients = $this->getRecipients();

		foreach ($list as $mail => $name) {
			array_push($recipients, [
				'name' => $name,
				'mail' => $mail,
				'sended' => false
			]);
		}

		$this->data['recipients'] = $recipients;

		return $this;
	}

	public function getRecipients()
	{
		$data = $this->getData();

		if (!is_array($data))
			$this->data = [];

		return array_key_exists('recipients', $data) ? $data['recipients'] : [];
	}

	/**
	 * Set locale
	 *
	 * @param string $locale
	 *
	 * @return Mail
	 */
	public function setLocale($locale)
	{
		$this->locale = $locale;

		return $this;
	}

	/**
	 * Get locale
	 *
	 * @return string
	 */
	public function getLocale()
	{
		return $this->locale;
	}

	/**
	 * Set public
	 *
	 * @param boolean $public
	 *
	 * @return Mail
	 */
	public function setPublic($public)
	{
		$this->public = $public;

		return $this;
	}

	/**
	 * Get public
	 *
	 * @return boolean
	 */
	public function getPublic()
	{
		return $this->public;
	}

	/**
	 * Set sended
	 *
	 * @param boolean $sended
	 *
	 * @return Mail
	 */
	public function setSended($sended)
	{
		$this->sended = $sended;

		return $this;
	}

	/**
	 * Get sended
	 *
	 * @return boolean
	 */
	public function getSended()
	{
		return $this->sended;
	}

	/**
	 * Set removed
	 *
	 * @param boolean $removed
	 *
	 * @return Mail
	 */
	public function setRemoved($removed)
	{
		$this->removed = $removed;

		return $this;
	}

	/**
	 * Get removed
	 *
	 * @return boolean
	 */
	public function getRemoved()
	{
		return $this->removed;
	}

	/**
	 * Add slide
	 *
	 * @param \Maci\MailerBundle\Entity\Slide $slide
	 * @return Mail
	 */
	public function addSlide(\Maci\MailerBundle\Entity\Slide $slide)
	{
		$this->slides[] = $slide;

		return $this;
	}

	/**
	 * Remove slide
	 *
	 * @param \Maci\MailerBundle\Entity\Slide $slide
	 */
	public function removeSlide(\Maci\MailerBundle\Entity\Slide $slide)
	{
		$this->slides->removeElement($slide);
	}

	/**
	 * Get slides
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getSlides()
	{
		return $this->slides;
	}

	/**
	 * Set user
	 *
	 * @param \Maci\UserBundle\Entity\User $user
	 *
	 * @return Subscriber
	 */
	public function setUser(\Maci\UserBundle\Entity\User $user = null)
	{
		$this->user = $user;

		return $this;
	}

	/**
	 * Get user
	 *
	 * @return \Maci\UserBundle\Entity\User
	 */
	public function getUser()
	{
		return $this->user;
	}

	// ---> Utils

	public function getSwiftMessage()
	{
		$message = (new \Swift_Message());

		if ($this->subject)
			$message->setSubject($this->subject);

		if ($this->sender)
			$message->setFrom($this->sender, $this->header);

		$recipients = $this->getRecipients();

		foreach ($recipients as $recipient)
			$message->addTo($recipient['mail'], $recipient['name']);

		if ($this->content)
		{
			$message->setBody($this->content, 'text/html');
			if ($this->text)
				$message->addPart($this->text, 'text/plain');
		}
		else if ($this->text)
			$message->setBody($this->text, 'text/plain');

		if (array_key_exists('bcc', $this->data))
			$message->setBcc($this->data['bcc']);

		return $message;
	}

	// public function getSwiftMessage()
	// {
	// 	$to = $this->getCurrentTo();

	// 	if (!$to) {
	// 		return false;
	// 	}

	// 	$message = (new \Swift_Message())
	// 		->setSubject($this->getSubject())
	// 		->setFrom($this->getFrom(), $this->getHeader())
	// 		->setTo($to[0], $to[1])
	// 		->setBcc($this->getBcc())
	// 		->setBody($this->getContent(), 'text/html')
	// 		->addPart($this->getText(), 'text/plain')
	// 	;

	// 	return $message;
	// }

	/**
	 * toString()
	 */
	public function __toString()
	{
		return 'Mail_'.$this->getToken();
	}
}
