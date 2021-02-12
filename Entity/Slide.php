<?php

namespace Maci\MailerBundle\Entity;

/**
 * Slide
 */
class Slide
{
	/**
	 * @var integer
	 */
	private $id;

	/**
	 * @var string
	 */
	private $title;

	/**
	 * @var string
	 */
	private $content;

	/**
	 * @var string
	 */
	private $template;

	/**
	 * @var int
	 */
	private $position;

	/**
	 * @var \Maci\MailerBundle\Entity\Mail
	 */
	private $mail;


	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->title = 'A New Slide';
		$this->template = '@MaciMailer/Slide/default.html.twig';
		$this->position = 0;
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
	 * Set title
	 *
	 * @param string $title
	 *
	 * @return Slide
	 */
	public function setTitle($title)
	{
		$this->title = $title;

		return $this;
	}

	/**
	 * Get title
	 *
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * Set content
	 *
	 * @param string $content
	 *
	 * @return Slide
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
	 * Set template
	 *
	 * @param string $template
	 *
	 * @return Slide
	 */
	public function setTemplate($template)
	{
		$this->template = $template;

		return $this;
	}

	/**
	 * Get template
	 *
	 * @return string
	 */
	public function getTemplate()
	{
		return $this->template;
	}

	/**
	 * Get Template Array
	 */
	public function getTemplateArray()
	{
		return array(
			'Default' => '@MaciMailer/Slide/default.html.twig',
			'Foo' => '@MaciMailer/Slide/foo.html.twig'
		);
	}

	public function getTemplateLabel()
	{
		$array = $this->getTemplateArray();
		$key = array_search($this->template, $array);
		if ($key) {
			return $key;
		}
		$str = str_replace('_', ' ', $this->template);
		return ucwords($str);
	}

	/**
	 * Set position
	 *
	 * @param int $position
	 *
	 * @return Slide
	 */
	public function setPosition($position)
	{
		$this->position = $position;

		return $this;
	}

	/**
	 * Get position
	 *
	 * @return int
	 */
	public function getPosition()
	{
		return $this->position;
	}

	/**
	 * Set mail
	 *
	 * @param string $mail
	 *
	 * @return Slide
	 */
	public function setMail($mail)
	{
		$this->mail = $mail;

		return $this;
	}

	/**
	 * Get mail
	 *
	 * @return string
	 */
	public function getMail()
	{
		return $this->mail;
	}

	/**
	 * _toString()
	 */
	public function __toString()
	{
		return 'Slide_'.$this->getId();
	}
}
