<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Role\Role as RoleInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoleRepository")
 * @UniqueEntity(fields={"name"}, message="Ce role existe déjà.")
 * @UniqueEntity(fields={"role"}, message="Ce role existe déjà.")
 */
class Role extends RoleInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=30)
     * @Assert\NotBlank(message="Le nom du rôle ne peut pas être vide.")
     * @Assert\Length(
     *      min = 1,
     *      max = 30,
     *      minMessage = "Le nom du rôle doit avoir un minimum de {{ limit }} caractères.",
     *      maxMessage = "Le nom du rôle doit avoir un maximum de {{ limit }} caractères."
     * )
     */
    private $name;

    /**
     * @ORM\Column(name="role", type="string", length=20, unique=true)
     * @Assert\NotBlank(message="Le role ne peut pas être vide.")
     * @Assert\Length(
     *      min = 5,
     *      max = 20,
     *      minMessage = "Le role doit avoir un minimum de {{ limit }} caractères.",
     *      maxMessage = "Le role doit avoir un maximum de {{ limit }} caractères."
     * )
     * @Assert\Regex(
     *     pattern="/^ROLE_[A-Z_]+$/",
     *     message="Le role doit débuter par (ROLE_) et doit être en majuscule."
     * )
     */
    private $role;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="roles", cascade={"persist"})
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param mixed $users
     */
    public function setUsers($users): void
    {
        $this->users = $users;
    }

    public function addUser(User $user){
        if ($this->users->contains($user)) {
            return;
        }

        $this->users[] = $user;
    }

    public function removeUser(User $user){
        $this->users->removeElement($user);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role): void
    {
        $this->role = strtoupper($role);
    }
}
