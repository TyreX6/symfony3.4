<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 30/03/2018
 * Time: 14:21
 */

namespace IT\ResourceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table()
 * @SWG\Definition(type="object", @SWG\Xml(name="Salle"))
 */
class Salle extends Ressource
{


    /**
     * @var integer
     * @ORM\Column(name="numero_salle", type="integer")
     * @Serializer\Groups({"categories","resources"})
     * @Assert\NotBlank()
     * @SWG\Property(type="integer",description="numero salle.")
     */
    private $numero_salle;

    /**
     * Salle constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * @return int
     */
    public function getNumeroSalle()
    {
        return $this->numero_salle;
    }

    /**
     * @param int $numero_salle
     */
    public function setNumeroSalle($numero_salle)
    {
        $this->numero_salle = $numero_salle;
    }

    public function __toString()
    {
        return $this->name;
    }


}