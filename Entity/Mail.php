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
	 * @var array
	 */
	private $recipient;

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
		$this->data = [
			'toIndex' => 0,
			'sendList' => []
		];
		$this->public = false;
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
		$list = [];
		foreach (Mail::getTypeArray() as $key => $value) {
			$list[] = $value;
		}
		return $list;
	}

	/**
	 * Set recipient
	 *
	 * @param array $recipient
	 *
	 * @return Mail
	 */
	public function setRecipient($recipient)
	{
		$this->recipient = $recipient;

		return $this;
	}

	public function addRecipient($recipient)
	{
		if (!is_array($this->recipient)) {
			$this->recipient = array();
		}

		if (is_array($recipient)) {
			$this->recipient = array_merge($this->recipient, $recipient);
		}

		return $this;
	}

	/**
	 * Set sender
	 *
	 * @return Mail
	 */
	public function setSender($sender, $header = false)
	{
		$this->sender = $_sender;

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
	 * Get recipient
	 *
	 * @return array
	 */
	public function getRecipient()
	{
		return $this->recipient;
	}

	public function getRecipientMails()
	{
		return array_keys($this->recipient);
	}

	public function getCurrentRecipient()
	{
		if (!$this->isFinish()) {
			$i = 0;
			$recipient = false;
			foreach ($this->recipient as $key => $value) {
				if ( $i === $this->getIndex() ) {
					$recipient = array($key, $value);
					break;
				}
				$i++;
			}
			return $recipient;
		}
		return false;
	}

	public function getRecipientLength()
	{
		return count($this->recipient);
	}

	public function getRecipientLeftovers()
	{
		return ( $this->getRecipientLength() - $this->getIndex() );
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
	 * Get data
	 *
	 * @return string
	 */
	public function getData()
	{
		return $this->data;
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

	/**
	 * Get index
	 *
	 * @return integer
	 */
	public function getIndex()
	{
		return $this->data['index'];
	}

	public function setIndex($index)
	{
		$this->data['index'] = $index;
	}

	public function increaseIndex($index = 1)
	{
		$this->data['index'] += $index;
	}

	public function end()
	{
		$this->data['index'] = $this->getRecipientLength();

		return $this;
	}

	/**
	 * isFinish()
	 */
	public function isFinish()
	{
		return !( $this->getIndex() < $this->getRecipientLength() );
	}

	/**
	 * toString()
	 */
	public function __toString()
	{
		return 'Mail_'.$this->getToken();
	}
}
