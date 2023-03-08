<?php

namespace App\Application\Service;

use App\Domain\Entity\Post;
use App\Domain\Entity\User;
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
     * @param $id
     * @return array|null
     */
    public function getSpecificPost(array $posts, $id): ?array
    {
        foreach ($posts as $post) {
            if ($post['id'] === $id) {
                return $post;
            }
        }

        return null;
    }

    /**
     * @param array $posts
     * @return array
     */
    public function sortByPublishedAtAsc(array $posts): array
    {
        $compareByPublishedAt = static function($a, $b) {
            return strcmp($a['publishedAt'], $b['publishedAt']);
        };

        usort($posts, $compareByPublishedAt);

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
        $adminUser = $this->em->getRepository(User::class)->findOneBy(['email' => 'admin@square1.com']);

        foreach ($apiPosts as $key => $apiPost) {
            $existingPost = $this->em->getRepository(Post::class)->findOneBy(['title' => $apiPost['title']]);
            if ($existingPost) {
                unset($apiPosts[$key]);
            } else {
                $newPost = new Post();
                $newPost->setAuthor($adminUser);
                $newPost->setTitle($apiPost['title']);
                $newPost->setDescription($apiPost['description']);
                $newPost->setPublicationDate(new \DateTime($apiPost['publishedAt']));

                $this->em->persist($newPost);
            }
        }

        $this->em->flush();

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
                'id' => 'bd_' . $post->getId(),
                'title' => $post->getTitle(),
                'description' => $post->getDescription(),
                'publishedAt' => $post->getPublicationDate()->format('Y-m-d H:i:s'),
            ];
        }

        return array_merge($apiPosts, $postsArray);
    }
}
