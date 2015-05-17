<?php

namespace Maci\MailerBundle\Entity;

/**
 * Subscriber
 */
class Subscriber
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
    private $mail;

    /**
     * @var string
     */
    private $locale;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \DateTime
     */
    private $updated;

    /**
     * @var boolean
     */
    private $removed;

    /**
     * @var \Maci\UserBundle\Entity\User
     */
    private $user;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->removed = false;
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
     * @return Subscriber
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
     * Set mail
     *
     * @param string $mail
     *
     * @return Subscriber
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

    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Subscriber
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return Subscriber
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set removed
     *
     * @param boolean $removed
     *
     * @return Subscriber
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

    public function setUpdatedValue()
    {
        $this->updated = new \DateTime();
    }

    public function setCreatedValue()
    {
        $this->created = new \DateTime();
    }
}
