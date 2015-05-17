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
    private $template;

    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $type;

    /**
     * @var array
     */
    private $to;

    /**
     * @var string
     */
    private $header;

    /**
     * @var integer
     */
    private $index;

    /**
     * @var string
     */
    private $from;

    /**
     * @var string
     */
    private $bcc;

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
     * @var string
     */
    private $locale;

    /**
     * @var boolean
     */
    private $public;

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
        $this->template = 'MaciMailerBundle:Templates:default.html.twig';
        $this->token = md5(
            'MaciMailerBundle_Entity_Mail-' . rand(10000, 99999) . '-' . 
            date('h') . date('i') . date('s') . date('m') . date('d') . date('Y')
        );
        $this->index = 0;
        $this->public = false;
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
     * Set template
     *
     * @param string $template
     *
     * @return Mail
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
     * Set to
     *
     * @param array $to
     *
     * @return Mail
     */
    public function setTo($to)
    {
        $this->to = $to;

        return $this;
    }

    public function addTo($to, $int = null)
    {
        if (!is_array($this->to)) {
            $this->to = array();
        }

        if (is_array($to)) {
            $this->to = array_merge($this->to, $to);
        } else {
            if (!is_null($int)) {
                $this->to[$to] = $int;
            } else {
                $this->to[$to] = null;
            }
        }

        return $this;
    }

    /**
     * Get to
     *
     * @return array
     */
    public function getTo()
    {
        return $this->to;
    }

    public function getToMails()
    {
        return array_keys($this->to);
    }

    public function getCurrentTo()
    {
        if (!$this->isFinish()) {
            $mails = $this->getToMails();
            return $mails[$this->index];
        }
        return false;
    }

    public function getCurrentToHeader()
    {
        if (!$this->isFinish()) {
            if (strlen($this->to[$this->index])) {
                return $this->to[$this->index];
            }
        }
        return null;
    }

    public function getToLength()
    {
        return count($this->to);
    }

    public function getToLeftovers()
    {
        return ( $this->getToLength() - $this->index );
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
     * Set index
     *
     * @param integer $index
     *
     * @return Mail
     */
    public function setIndex($index)
    {
        $this->index = $index;

        return $this;
    }

    public function increaseIndex($index = 1)
    {
        $this->index += $index;

        return $this;
    }

    /**
     * end()
     */
    public function end()
    {
        $this->index = $this->getToLength();

        return $this;
    }

    /**
     * Get index
     *
     * @return integer
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * Set from
     *
     * @param string $from
     *
     * @return Mail
     */
    public function setFrom($from, $header = false)
    {
        $this->from = $from;

        if ($header) {
            $this->header = $header;
        }

        return $this;
    }

    /**
     * Get from
     *
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Set bcc
     *
     * @param string $bcc
     *
     * @return Mail
     */
    public function setBcc($bcc)
    {
        $this->bcc = $bcc;

        return $this;
    }

    /**
     * Get bcc
     *
     * @return string
     */
    public function getBcc()
    {
        return $this->bcc;
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
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Mail
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
     * @return Mail
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

    /**
     * __toString()
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * isFinish()
     */
    public function isFinish()
    {
        return !( $this->index < $this->getToLength() );
    }
}
