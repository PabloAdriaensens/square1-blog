<?php

namespace App\Application\Service;

use App\Domain\Entity\Post;
use App\Domain\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
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
     * @param string $order
     * @return array
     * @throws Exception
     */
    protected function sortByPublishedAt(array $posts, string $order): array
    {
        if ($order !== 'asc' && $order !== 'desc') {
            throw new Exception('Invalid sort order: '.$order);
        }

        usort($posts, static function($a, $b) use ($order) {
            $dateA = new \DateTime($a['publishedAt']);
            $dateB = new \DateTime($b['publishedAt']);
            if ($order === 'asc') {
                return $dateA <=> $dateB;
            }

            return $dateB <=> $dateA;
        });

        return $posts;
    }

    /**
     * @param string $order
     * @return array
     * @throws JsonException
     */
    public function getAllPosts(string $order): array
    {
        $dbPosts = $this->em->getRepository(Post::class)->findAll();
        $apiPosts = [];
        $adminUser = $this->em->getRepository(User::class)->findOneBy(['email' => 'admin@square1.com']);

        if ($adminUser) {
            $apiPostsResponse = $this->api->getByParameters([]);
            if (!isset($apiPostsResponse['articles'])) {
                throw new \Exception('API response error');
            }
            $apiPosts = $apiPostsResponse['articles'];
            $existingPostTitles = $this->getAllTitles();
            foreach ($apiPosts as $key => $apiPost) {
                if (in_array($apiPost['title'], $existingPostTitles, true)) {
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
        }

        $posts = $this->combinePosts($apiPosts, $dbPosts);

        return $this->sortByPublishedAt($posts, $order);
    }

    /**
     * @return array
     */
    private function getAllTitles(): array
    {
        $dbPosts = $this->em->getRepository(Post::class)->findAll();
        $titles = [];
        foreach ($dbPosts as $post) {
            $titles[] = $post->getTitle();
        }
        return $titles;
    }

    /**
     * @param array $apiPosts
     * @param array $dbPosts
     * @return array
     */
    public function combinePosts(array $apiPosts, array $dbPosts): array
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
