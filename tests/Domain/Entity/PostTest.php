<?php

namespace Tests\Domain\Entity;

use App\Domain\Entity\Post;
use App\Domain\Entity\User;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $post = new Post();
        $title = 'Test post title';
        $description = 'Test post description';
        $publicationDate = new DateTimeImmutable('2022-01-01 00:00:00');
        $author = new User();

        // Test setters
        $post->setTitle($title);
        $post->setDescription($description);
        $post->setPublicationDate($publicationDate);
        $post->setAuthor($author);

        // Test getters
        $this->assertNull($post->getId());
        $this->assertSame($title, $post->getTitle());
        $this->assertSame($description, $post->getDescription());
        $this->assertSame($publicationDate, $post->getPublicationDate());
        $this->assertSame($author, $post->getAuthor());
    }
}
