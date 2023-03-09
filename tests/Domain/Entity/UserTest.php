<?php

namespace App\Tests\Domain\Entity;

use App\Domain\Entity\User;
use App\Domain\Entity\Post;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        $this->user = new User();
    }

    public function testGetId(): void
    {
        $this->assertNull($this->user->getId());
    }

    public function testGetEmail(): void
    {
        $email = 'test@example.com';
        $this->user->setEmail($email);
        $this->assertEquals($email, $this->user->getEmail());
    }

    public function testGetUserIdentifier(): void
    {
        $email = 'test@example.com';
        $this->user->setEmail($email);
        $this->assertEquals($email, $this->user->getUserIdentifier());
    }

    public function testGetUsername(): void
    {
        $email = 'test@example.com';
        $this->user->setEmail($email);
        $this->assertEquals($email, $this->user->getUsername());
    }

    public function testGetRoles(): void
    {
        $roles = ['ROLE_USER', 'ROLE_ADMIN'];
        $this->user->setRoles($roles);
        $this->assertEquals($roles, $this->user->getRoles());
    }

    public function testSetRoles(): void
    {
        $roles = ['ROLE_USER', 'ROLE_ADMIN'];
        $this->user->setRoles($roles);
        $this->assertEquals($roles, $this->user->getRoles());
    }

    public function testGetPassword(): void
    {
        $password = 'testpassword';
        $this->user->setPassword($password);
        $this->assertEquals($password, $this->user->getPassword());
    }

    public function testSetPassword(): void
    {
        $password = 'testpassword';
        $this->user->setPassword($password);
        $this->assertEquals($password, $this->user->getPassword());
    }

    public function testGetSalt(): void
    {
        $this->assertNull($this->user->getSalt());
    }

    public function testEraseCredentials(): void
    {
        $this->assertNull($this->user->eraseCredentials());
    }

    public function testGetPosts(): void
    {
        $post = new Post();
        $this->user->addPost($post);
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $this->user->getPosts());
    }

    public function testAddPost(): void
    {
        $post = new Post();
        $this->user->addPost($post);
        $this->assertTrue($this->user->getPosts()->contains($post));
    }

    public function testRemovePost(): void
    {
        $post = new Post();
        $this->user->addPost($post);
        $this->user->removePost($post);
        $this->assertFalse($this->user->getPosts()->contains($post));
    }
}
