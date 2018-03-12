<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 08/03/2018
 * Time: 15:39
 */

namespace IT\UserBundle\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use JMS\Serializer\Annotation\Expose;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ORM\Entity
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="IT\UserBundle\Repository\MessageRepository")
 * @ExclusionPolicy("all")
 */
class Message
{
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    private $id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="vu", type="boolean", length=20)
     * @Expose
     */
    private $vu;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_envoi", type="datetime" ,nullable=false)
     * @Expose
     */
    private $dateEnvoi;

    /**
     * @ORM\OneToOne(targetEntity="IT\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id",onDelete="CASCADE",nullable=false)
     **/
    private $user;

    /**
     * @var string
     * @ORM\Column(name="message", type="string", length=255)
     */
    private $message ;


    /**
     * Message constructor.
     */
    public function __construct()
    {
        $this->setDateEnvoi(new \DateTime(null, new \DateTimeZone("Africa/Tunis"))) ;
        $this->setVu(false) ;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return bool
     */
    public function isVu()
    {
        return $this->vu;
    }

    /**
     * @param bool $vu
     */
    public function setVu($vu)
    {
        $this->vu = $vu;
    }

    /**
     * @return \DateTime
     */
    public function getDateEnvoi()
    {
        return $this->dateEnvoi;
    }

    /**
     * @param \DateTime $dateEnvoi
     */
    public function setDateEnvoi($dateEnvoi)
    {
        $this->dateEnvoi = $dateEnvoi;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }


}