<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/** 
 * @Entity()
 * @Table(name="logs")
 */
class Log
{
    /** @Id @Column(type="integer") 
     * @GeneratedValue 
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     **/
    private $user;

    /**
     * @ManyToOne(targetEntity="LogAction")
     * @JoinColumn(name="log_action_id", referencedColumnName="id",nullable=false)
     **/
    private $action;

    /** @Column(type="text",nullable=true) */
    private $arguments;

    /** @Column(type="text",nullable=true) */
    private $result;

    /** @Column(name="ip_address",type="text") */
    private $ipAddress;

    /** @Column(name="user_agent",type="text",nullable=true) */
    private $userAgent;

    /** @Column(name="is_success",type="boolean") */
    private $isSuccess;

    /** @Column(name="count_modified_entities",type="integer") */
    private $countModifiedEntities;

    /** @Column(name="fail_message",type="text",nullable=true) */
    private $failMessage;


    /** @Column(name="created_at",type="datetime") */
    private $createdAt;

    public function __construct(){
        $this->setCreatedAt(new \DateTime("now"));
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
     * Set user
     *
     * @param text $user
     * @return Users
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return text 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set action
     *
     * @param text $action
     * @return actions
     */
    public function setAction(LogAction $action)
    {
        $this->action = $action;
        return $this;
    }

    /**
     * Get action
     *
     * @return text 
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set arguments
     *
     * @param text $arguments
     * @return arguments
     */
    public function setArguments($arguments)
    {
        $this->arguments = $arguments;
        return $this;
    }


    /**
     * Get arguments
     *
     * @return text 
     */
    public function getArguments()
    {
        return json_encode(json_decode($this->arguments,true),\JSON_PRETTY_PRINT);
    }

    /**
     * Set result
     *
     * @param text $result
     * @return result
     */
    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }


    /**
     * Get result
     *
     * @return text 
     */
    public function getResult()
    {
        return json_encode(json_decode($this->result,true),\JSON_PRETTY_PRINT);
    }

    /**
     * Set ipAddress
     *
     * @param text $ipAddress
     * @return ipAddress
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;
        return $this;
    }


    /**
     * Get ipAddress
     *
     * @return text 
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * Set userAgent
     *
     * @param text $userAgent
     * @return userAgent
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
        return $this;
    }


    /**
     * Get userAgent
     *
     * @return text 
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Set isSuccess
     *
     * @param text $isSuccess
     * @return isSuccesss
     */
    public function setIsSuccess($isSuccess)
    {
        $this->isSuccess = $isSuccess;
        return $this;
    }

    /**
     * Get isSuccess
     *
     * @return text 
     */
    public function getIsSuccess()
    {
        return $this->isSuccess;
    }

    /**
     * Set countModifiedEntities
     *
     * @param text $countModifiedEntities
     * @return countModifiedEntitiess
     */
    public function setCountModifiedEntities($countModifiedEntities)
    {
        $this->countModifiedEntities = $countModifiedEntities;
        return $this;
    }

    /**
     * Get countModifiedEntities
     *
     * @return text 
     */
    public function getCountModifiedEntities()
    {
        return $this->countModifiedEntities;
    }

    /**
     * Set failMessage
     *
     * @param text $failMessage
     * @return failMessages
     */
    public function setFailMessage($failMessage)
    {
        $this->failMessage = $failMessage;
        return $this;
    }

    /**
     * Get failMessage
     *
     * @return text 
     */
    public function getFailMessage()
    {
        return $this->failMessage;
    }

    /**
     * Set createdAt
     *
     * @param text $createdAt
     * @return Users
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return text 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

}