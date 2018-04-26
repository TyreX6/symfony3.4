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
 * @ORM\Table(name="ReservationProjecteur")
 * @ORM\Entity()
 * @SWG\Definition(type="object", @SWG\Xml(name="ReservationProjecteur"))
 * @ExclusionPolicy("all")
 */
class ReservationProjecteur extends Reservation
{

    /**
     * @ORM\ManyToOne(targetEntity="IT\ResourceBundle\Entity\Projecteur", inversedBy="reservationProjecteur",)
     * @Assert\NotBlank()
     * @ORM\JoinColumn(name="projecteur_id", referencedColumnName="id",onDelete="CASCADE",nullable=false)
     * @SWG\Property(description="Le projecteur réservé.")
     * @Expose
     **/
    private $projecteur;



    public function __construct() {
        parent::__construct();
    }

    /**
     * @return mixed
     */
    public function getProjecteur()
    {
        return $this->projecteur;
    }

    /**
     * @param mixed $projecteur
     */
    public function setProjecteur($projecteur)
    {
        $this->projecteur = $projecteur;
    }






}