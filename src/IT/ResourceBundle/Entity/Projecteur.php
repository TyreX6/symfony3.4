<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 30/03/2018
 * Time: 14:28
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
 * @ORM\Entity
 * @ORM\Table()
 * @SWG\Definition(type="object", @SWG\Xml(name="Projecteur"))
 */
class Projecteur extends Ressource
{

    /**
     * @var string
     * @ORM\Column(name="model", type="string", length=50)
     * @Serializer\Groups({"categories","resources"})
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 5,
     *      max = 80,
     *      minMessage = "Your model must be at least {{ limit }} characters long",
     *      maxMessage = "Your model cannot be longer than {{ limit }} characters"
     * )
     * @SWG\Property(property="model",type="string",description="modele du projecteur.")
     */
    private $model;

    /**
     * @var string
     * @ORM\Column(name="resolution", type="string" ,length=20)
     * @Serializer\Groups({"categories","resources"})
     * @Assert\NotBlank()
     * @Assert\Regex("/(^[0-9]{4}+)(x|X)([0-9]{4}$)/",
     *     message="This is not a valid resolution")
     * @SWG\Property(type="string",description="Résolution du projecteur.")
     */
    private $resolution;


    /**
     * Projecteur constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param string $modele
     */
    public function setModel($model)
    {
        $this->model = $model;
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


    public function __toString()
    {
        return $this->model;
    }


}