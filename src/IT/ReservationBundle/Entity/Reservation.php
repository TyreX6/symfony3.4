<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 26/02/2018
 * Time: 19:52
 */

namespace IT\ReservationBundle\Entity;

use IT\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Serializer\Serializer;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Reservation
 *
 * @ORM\Table(name="Reservation")
 * @ORM\Entity(repositoryClass="IT\ReservationBundle\Repository\ReservationsRepository")
 * @SWG\Definition(type="object", @SWG\Xml(name="Reservation"))
 * @ExclusionPolicy("all")
 */
class Reservation extends AbstractReservation
{

    /**
     * @ORM\ManyToOne(targetEntity="IT\DispositifBundle\Entity\Dispositif", inversedBy="reservation",)
     * @Assert\NotBlank()
     * @ORM\JoinColumn(name="dispositif_id", referencedColumnName="id",onDelete="CASCADE",nullable=false)
     * @SWG\Property(description="Le dispositif réservé.")
     * @Expose
     **/
    private $dispositif;



    public function __construct() {
        parent::__construct();
    }

    /**
     * @return mixed
     */
    public function getDispositif()
    {
        return $this->dispositif;
    }

    /**
     * @param mixed $dispositif
     */
    public function setDispositif($dispositif)
    {
        $this->dispositif = $dispositif;
    }




}