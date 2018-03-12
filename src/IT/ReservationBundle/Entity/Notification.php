<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 08/03/2018
 * Time: 00:07
 */

namespace IT\ReservationBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * @Entity
 * @Table()
 * @ORM\Entity(repositoryClass="IT\ReservationBundle\Repository\NotificationRepository")
 * @ExclusionPolicy("all")
 */
class Notification
{

    /**
     * @var integer
     * @Column(name="id", type="integer")
     * @Id
     * @GeneratedValue(strategy="AUTO")
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
     * @ORM\Column(name="date_res", type="datetime" ,nullable=false)
     * @Expose
     */
    private $dateRes;

    /**
     * @OneToOne(targetEntity="IT\ReservationBundle\Entity\Reservation")
     * @JoinColumn(name="reservation_id", referencedColumnName="id",onDelete="CASCADE",nullable=false)
     **/
    private $reservation;

    /**
     * Notification constructor.
     */
    public function __construct()
    {
        $this->setDateRes(new \DateTime(null, new \DateTimeZone("Africa/Tunis"))) ;
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
    public function getDateRes()
    {
        return $this->dateRes;
    }

    /**
     * @param \DateTime $dateRes
     */
    public function setDateRes($dateRes)
    {
        $this->dateRes = $dateRes;
    }

    /**
     * @return mixed
     */
    public function getReservation()
    {
        return $this->reservation;
    }

    /**
     * @param mixed $reservation
     */
    public function setReservation($reservation)
    {
        $this->reservation = $reservation;
    }
}