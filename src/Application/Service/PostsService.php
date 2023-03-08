<?php

namespace App\Application\Service;

use App\Domain\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use JsonException;

class PostsService
{
    private EntityManagerInterface $em;
    private CandidateTestApi $api;

    public function __construct(EntityManagerInterface $em, CandidateTestApi $api)
    {
        $this->em = $em;
        $this->api = $api;
    }

    /**
     * @param array $posts
     * @param int $id
     * @return array
     */
    public function getSpecificPost(array $posts, int $id): array
    {
        foreach ($posts as $post) {
            if ((int)$post['id'] === $id) {
                return $post;
            }
        }

        return [];
    }

    /**
     * @param array $posts
     * @return array
     */
    public function sortByPublishedAtAsc(array $posts): array
    {
        usort($posts, static function ($a, $b) {
            return strcmp($a['publishedAt'], $b['publishedAt']);
        });

        return $posts;
    }

    /**
     * @return array
     * @throws JsonException
     */
    public function getAllPosts(): array
    {
        $dbPosts = $this->em->getRepository(Post::class)->findAll();
        $apiPosts = $this->api->getByParameters([])['articles'];

        $posts = $this->combinePosts($apiPosts, $dbPosts);

        return $this->sortByPublishedAtAsc($posts);
    }

    /**
     * @param array $apiPosts
     * @param array $dbPosts
     * @return array
     */
    private function combinePosts(array $apiPosts, array $dbPosts): array
    {
        $postsArray = [];

        foreach ($dbPosts as $post) {
            $postsArray[] = [
                'id' => $post->getId(),
                'title' => $post->getTitle(),
                'description' => $post->getDescription(),
                'publishedAt' => $post->getPublicationDate()->format('Y-m-d H:i:s'),
            ];
        }

        return array_merge($apiPosts, $postsArray);
    }
}
