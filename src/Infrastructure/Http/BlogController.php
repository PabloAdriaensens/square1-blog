<?php

namespace App\Infrastructure\Http;

use App\Application\Service\CandidateTestApi;
use App\Application\Service\PostsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    private CandidateTestApi $api;
    private PostsService $postsService;

    public function __construct(CandidateTestApi $api, PostsService $postsService)
    {
        $this->api = $api;
        $this->postsService = $postsService;
    }

    #[Route('/posts', name: 'posts', methods: ['GET'])]
    public function getPosts(): JsonResponse
    {
        $response = $this->api->getByParameters(['_sort' => 'publishedAt', '_order' => 'asc']);
        $posts = $response['articles'];

        return $this->json(['data' => $posts]);
    }

    #[Route('/posts/{id}', name: 'post', methods: ['GET'])]
    public function getPost(int $id): JsonResponse
    {
        $response = $this->api->getByParameters(['_sort' => 'publishedAt', '_order' => 'asc']);
        $post = $this->postsService->getSpecificPost($response['articles'], $id);

        if (!$post) {
            return $this->json(['message' => 'Post not found'], 404);
        }

        return $this->json(['data' => $post]);
    }
}
