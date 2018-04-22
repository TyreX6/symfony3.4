<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 30/03/2018
 * Time: 14:21
 */
namespace IT\DispositifBundle\Entity;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table()
 * @ExclusionPolicy("all")
 * @SWG\Definition(type="object", @SWG\Xml(name="Salle"))
 */
class Salle extends Ressource
{
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     * @SWG\Property(description="Identifiant de la salle.")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string",nullable=false)
     * @Expose
     * @SWG\Property(property="name",type="string",description="Nom de la salle.")
     */
    private $name;

    /**
     * @var integer
     * @ORM\Column(name="numero_salle", type="integer")
     * @Expose
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
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
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