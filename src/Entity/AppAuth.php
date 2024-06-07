<?php

namespace App\Entity;

use App\Repository\AppAuthRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AppAuthRepository::class)
 */
class AppAuth extends BaseEntity
{
    /**
     * @ORM\Column(type="json")
     */
    private $login_image_file;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Length(max="255")
     */
    private $login_image_text;

    /**
     * @ORM\Column(type="string")
     * @Assert\Length(max="255")
     * @Assert\NotBlank()
     */
    private $login_title;


    /**
     * @ORM\Column(type="string")
     * @Assert\Length(max="255")
     * @Assert\NotBlank()
     */
    private $recovery_password_title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $recovery_password_text;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $recovery_password_disclaimer;


    /**
     * @ORM\Column(type="string")
     * @Assert\Length(max="255")
     * @Assert\NotBlank()
     */
    private $reset_password_title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $reset_password_text;


    /**
     * @ORM\Column(type="string")
     * @Assert\Length(max="255")
     * @Assert\NotBlank()
     */
    private $change_password_title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $change_password_text;


    /**
     * AppAuth constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function getLoginImageFile(): ?array
    {
        return $this->login_image_file;
    }

    public function setLoginImageFile(array $login_image_file): self
    {
        $this->login_image_file = $login_image_file;

        return $this;
    }

    public function getLoginImageText(): ?string
    {
        return $this->login_image_text;
    }

    public function setLoginImageText(?string $login_image_text): self
    {
        $this->login_image_text = $login_image_text;

        return $this;
    }

    public function getLoginTitle(): ?string
    {
        return $this->login_title;
    }

    public function setLoginTitle(string $login_title): self
    {
        $this->login_title = $login_title;

        return $this;
    }

    public function getRecoveryPasswordTitle(): ?string
    {
        return $this->recovery_password_title;
    }

    public function setRecoveryPasswordTitle(string $recovery_password_title): self
    {
        $this->recovery_password_title = $recovery_password_title;

        return $this;
    }

    public function getRecoveryPasswordText(): ?string
    {
        return $this->recovery_password_text;
    }

    public function setRecoveryPasswordText(string $recovery_password_text): self
    {
        $this->recovery_password_text = $recovery_password_text;

        return $this;
    }

    public function getRecoveryPasswordDisclaimer(): ?string
    {
        return $this->recovery_password_disclaimer;
    }

    public function setRecoveryPasswordDisclaimer(string $recovery_password_disclaimer): self
    {
        $this->recovery_password_disclaimer = $recovery_password_disclaimer;

        return $this;
    }

    public function getResetPasswordTitle(): ?string
    {
        return $this->reset_password_title;
    }

    public function setResetPasswordTitle(string $reset_password_title): self
    {
        $this->reset_password_title = $reset_password_title;

        return $this;
    }

    public function getResetPasswordText(): ?string
    {
        return $this->reset_password_text;
    }

    public function setResetPasswordText(string $reset_password_text): self
    {
        $this->reset_password_text = $reset_password_text;

        return $this;
    }

    public function getChangePasswordTitle(): ?string
    {
        return $this->change_password_title;
    }

    public function setChangePasswordTitle(string $change_password_title): self
    {
        $this->change_password_title = $change_password_title;

        return $this;
    }

    public function getChangePasswordText(): ?string
    {
        return $this->change_password_text;
    }

    public function setChangePasswordText(string $change_password_text): self
    {
        $this->change_password_text = $change_password_text;

        return $this;
    }
}
