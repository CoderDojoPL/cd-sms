<?php

namespace Entity;

/**
 * @Entity()
 * @Table(name="queue_emails")
 **/
class QueueEmail{

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     **/
    protected $id;

    /**
     * @Column(name="email_to",type="string")
     **/
    protected $to;

    /**
     * @Column(type="string")
     **/
    protected $subject;

    /**
     * @Column(type="text")
     **/
    protected $content;

    /**
     * @Column(name="sended_at",type="datetime",nullable=true)
     **/
    protected $sendedAt;

    /**
     * @Column(name="created_at",type="datetime")
     **/
    protected $createdAt;

    public function __construct(){
        $this->setCreatedAt(new \DateTime());
    }

    public function getId()
    {
        return $this->id;
    }

    public function setTo($to)
    {
        $this->to = $to;
        return $this;
    }

    public function getTo()
    {
        return $this->to;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setSendedAt($sendedAt)
    {
        $this->sendedAt = $sendedAt;
        return $this;
    }

    public function getSendedAt()
    {
        return $this->sendedAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }



}