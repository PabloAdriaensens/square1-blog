<?php

use App\Application\Service\CandidateTestApi;
use App\Application\Service\PostsService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class PostsServiceTest extends TestCase
{
    private PostsService $service;
    private ReflectionMethod $sortByPublishedAtMethod;

    protected function setUp(): void
    {
        parent::setUp();
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $this->service = new PostsService($entityManager, $this->createMock(CandidateTestApi::class));
        $this->sortByPublishedAtMethod = new ReflectionMethod(PostsService::class, 'sortByPublishedAt');
    }

    public function testSortByPublishedAtThrowsExceptionWithInvalidOrder(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid sort order: invalid_order');

        $this->sortByPublishedAtMethod->invoke($this->service, [], 'invalid_order');
    }

    public function testSortByPublishedAtSortsByAscendingOrder(): void
    {
        $posts = [
            ['publishedAt' => '2022-01-01T12:00:00'],
            ['publishedAt' => '2021-01-01T12:00:00'],
            ['publishedAt' => '2023-01-01T12:00:00'],
        ];

        $sortedPosts = $this->sortByPublishedAtMethod->invoke($this->service, $posts, 'asc');

        $expectedSortedPosts = [
            ['publishedAt' => '2021-01-01T12:00:00'],
            ['publishedAt' => '2022-01-01T12:00:00'],
            ['publishedAt' => '2023-01-01T12:00:00'],
        ];

        $this->assertSame($expectedSortedPosts, $sortedPosts);
    }
}
