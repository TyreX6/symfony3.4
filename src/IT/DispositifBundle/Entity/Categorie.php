<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 29/03/2018
 * Time: 16:24
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
 * @SWG\Definition(type="object", @SWG\Xml(name="Categorie"))
 */
class Categorie
{
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     * @SWG\Property(description="Identifiant du categorie.")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string",nullable=false)
     * @Expose
     * @SWG\Property(property="name",type="string",description="Nom de l'application.")
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="IT\DispositifBundle\Entity\Ressource", mappedBy="categorie",cascade="persist")
     * @Expose
     **/
    private $ressource;


    public function __construct()
    {
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
     * @return mixed
     */
    public function getRessource()
    {
        return $this->ressource;
    }

    /**
     * @param mixed $ressource
     */
    public function setRessource($ressource)
    {
        $this->ressource = $ressource;
    }

    public function __toString()
    {
        return $this->name;
    }



}