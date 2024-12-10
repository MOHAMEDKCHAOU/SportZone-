<?php

namespace App\Entity;

use App\Repository\AdminRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdminRepository::class)]
class Admin extends User
{
    public function __construct()
    {
        parent::__construct(); // Call to the parent User constructor
    }

    /**
     * Assigns the ROLE_ADMIN to this user.
     *
     * @return string[]
     */
    public function getRoles(): array
    {
        $roles = parent::getRoles();
        $roles[] = 'ROLE_ADMIN'; // Ensure the admin role is added
        return array_unique($roles); // Remove duplicates
    }


}
