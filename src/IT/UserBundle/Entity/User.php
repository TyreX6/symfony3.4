<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 08/02/2018
 * Time: 10:22
 */
namespace IT\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use FR3D\LdapBundle\Model\LdapUserInterface;
/**
 * @ORM\Entity(repositoryClass="IT\UserBundle\Repository\UsersRepository")
 * @ExclusionPolicy("all")
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser implements LdapUserInterface,\JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    protected $id;
    /**
     * @ORM\Column(type="string")
     */
    protected $dn;

    /**
     * @ORM\OneToMany(targetEntity="IT\ReservationBundle\Entity\Reservation", mappedBy="user",cascade="persist")
     **/
    private $reservation;


    public function __construct()
    {
        parent::__construct();
        $this->reservation = new ArrayCollection();
        if (empty($this->roles)) {
            $this->roles[] = 'ROLE_USER';
        }
    }
    /**
     * {@inheritDoc}
     */
    public function setDn($dn)
    { $this->dn = $dn; }

    /**
     * {@inheritDoc}
     */
    public function getDn()
    { return $this->dn; }

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

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            'email' => $this->getEmail(),
        ];
    }
}