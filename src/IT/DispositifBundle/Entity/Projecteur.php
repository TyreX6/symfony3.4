<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 30/03/2018
 * Time: 14:28
 */

namespace IT\DispositifBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table()
 * @ExclusionPolicy("all")
 * @SWG\Definition(type="object", @SWG\Xml(name="Projecteur"))
 */
class Projecteur extends Ressource
{

    /**
     * @var string
     * @ORM\Column(name="modele", type="string", length=50)
     * @Expose
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 5,
     *      max = 80,
     *      minMessage = "Your model must be at least {{ limit }} characters long",
     *      maxMessage = "Your model cannot be longer than {{ limit }} characters"
     * )
     * @SWG\Property(property="modele",type="string",description="modele du dispositif.")
     */
    private $modele;

    /**
     * @var string
     * @ORM\Column(name="resolution", type="string" ,length=20)
     * @Expose
     * @Assert\NotBlank()
     * @Assert\Regex("/(^[0-9]{4}+)(x|X)([0-9]{4}$)/",
     *     message="This is not a valid resolution")
     * @SWG\Property(type="string",description="Résolution du projecteur.")
     */
    private $resolution;

    /**
     * @ORM\OneToMany(targetEntity="IT\ReservationBundle\Entity\ReservationProjecteur", mappedBy="projecteur",cascade="persist")
     **/
    private $reservationProjecteur;

    /**
     * Projecteur constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->reservationProjecteur = new ArrayCollection();
    }


    /**
     * @return string
     */
    public function getModele()
    {
        return $this->modele;
    }

    /**
     * @param string $modele
     */
    public function setModele($modele)
    {
        $this->modele = $modele;
    }

    /**
     * @return string
     */
    public function getResolution()
    {
        return $this->resolution;
    }

    /**
     * @param string $resolution
     */
    public function setResolution($resolution)
    {
        $this->resolution = $resolution;
    }

    /**
     * @return mixed
     */
    public function getReservationProjecteur()
    {
        return $this->reservationProjecteur;
    }

    /**
     * @param mixed $reservationProjecteur
     */
    public function setReservationProjecteur($reservationProjecteur)
    {
        $this->reservationProjecteur = $reservationProjecteur;
    }





}