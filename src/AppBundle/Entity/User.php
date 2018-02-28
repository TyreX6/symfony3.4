<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 08/02/2018
 * Time: 10:22
 */
namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use FR3D\LdapBundle\Model\LdapUserInterface;
/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UsersRepository")
 * @ORM\Table(name="fos_user")
 * @ORM\InheritanceType("SINGLE_TABLE")
 */
class User extends BaseUser implements LdapUserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string")
     */
    protected $dn;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Reservation", mappedBy="reservation",cascade="persist")
     **/
    private $reservation;


    public function __construct()
    {
        parent::__construct();
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
}