<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"username"}, message="Le nom d'utilisateur est déjà utilisé.")
 * @UniqueEntity(fields={"email"}, message="L'adresse courriel est déjà utilisé.")
 */
class User implements AdvancedUserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     * @Assert\NotBlank(message="Le nom d'utilisateur ne peut pas être vide.")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     * @Assert\NotBlank(message="L'adresse courriel ne peut pas être vide.")
     * @Assert\Email(message="Le format de l'adresse courriel n'est pas valide.")
     */
    private $email;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role", inversedBy="users", cascade={"persist"})
     */
    private $roles;

    /**
     * @Assert\Length(
     *      min = 8,
     *      max = 2048,
     *      minMessage = "Votre mot de passe doit avoir un minimum de {{ limit }} caractères.",
     *      maxMessage = "Votre mot de passe doit avoir un maximum de {{ limit }} caractères."
     * )
     * @Assert\Regex(
     *     pattern="/(?=.*?[!@$%?&*-])/",
     *     message="Votre mot de passe doit posséder au moins un caractère parmis (!@$%?&*-)."
     * )
     * @Assert\Regex(
     *     pattern="/(?=.*?[0-9])/",
     *     message="Votre mot de passe doit posséder au moins un 1 chiffre."
     * )
     * @Assert\Regex(
     *     pattern="/(?=.*?[A-Z])/",
     *     message="Votre mot de passe doit posséder au moins une lettre majuscule non-accentuée."
     * )
     */
    private $plainPassword;

    public function __construct()
    {
        $this->isActive = true;
        $this->roles = new ArrayCollection();
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password){
        $this->password = $password;
    }

    public function getRoles()
    {
        return $this->roles->toArray();
    }

    public function isEnabled(){
        return $this->isActive;
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->isActive,
        ));
    }

    /** @see \Serializable::unserialize()
     * @param $serialized
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->isActive,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }

    /**
     * Checks whether the user's account has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw an AccountExpiredException and prevent login.
     *
     * @return bool true if the user's account is non expired, false otherwise
     *
     * @see AccountExpiredException
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * Checks whether the user is locked.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a LockedException and prevent login.
     *
     * @return bool true if the user is not locked, false otherwise
     *
     * @see LockedException
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * Checks whether the user's credentials (password) has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a CredentialsExpiredException and prevent login.
     *
     * @return bool true if the user's credentials are non expired, false otherwise
     *
     * @see CredentialsExpiredException
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function getId(){
        return $this->id;
    }

    /**
     * @param mixed $roles
     */
    public function setRoles($roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @param Role $role
     */
    public function addRole(Role $role){
        if ($this->roles->contains($role)) {
            return;
        }

        $this->roles[] = $role;
        // set the *owning* side!
        $role->addUser($this);
    }

    public function removeRole(Role $role){
        $this->roles->removeElement($role);
        // set the owning side to null
        $role->removeUser($this);
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return mixed
     */
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setActive($isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * @return mixed
     */
    public function getInitiales(){
        return $this->username;
    }

}

