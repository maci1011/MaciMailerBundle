<?php

namespace Maci\MailerBundle\Entity;

/**
 * Newsletter
 */
class Newsletter
{
	/**
	 * @var integer
	 */
	private $id;

	/**
	 * @var string
	 */
	private $token;

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
	private $html;

	/**
	 * @var boolean
	 */
	private $private;

	/**
	 * @var json
	 */
	private $data;


	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->token = md5(
			'MaciMailerBundle_Entity_Mail-' . rand(10000, 99999) . '-' . 
			date('h') . date('i') . date('s') . date('m') . date('d') . date('Y')
		);
		$this->private = false;
		$this->data = [
			'list' => [],
			'count' => 0
		];
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
	 * Set subject
	 *
	 * @param string $subject
	 *
	 * @return Newsletter
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
	 * @return Newsletter
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
	 * Set html
	 *
	 * @param string $html
	 *
	 * @return Newsletter
	 */
	public function setHtml($html)
	{
		$this->html = $html;

		return $this;
	}

	/**
	 * Get html
	 *
	 * @return string
	 */
	public function getHtml()
	{
		return $this->html;
	}

	/**
	 * Set private
	 *
	 * @param boolean $private
	 *
	 * @return Newsletter
	 */
	public function setPrivate($private)
	{
		$this->private = $private;

		return $this;
	}

	/**
	 * Get private
	 *
	 * @return boolean
	 */
	public function getPrivate()
	{
		return $this->private;
	}

	/**
	 * Set data
	 *
	 * @param string $data
	 *
	 * @return Newsletter
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
	 * _toString()
	 */
	public function __toString()
	{
		return 'Newsletter_'.$this->getToken();
	}
}
