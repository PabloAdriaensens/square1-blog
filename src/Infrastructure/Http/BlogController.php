<?php

namespace App\Infrastructure\Http;

use App\Application\Service\CandidateTestApi;
use App\Application\Service\PostsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
    public function getPosts(): Response
    {
        $response = $this->api->getByParameters([]);
        $posts = $response['articles'];

        $orderedPosts = $this->postsService->sortByPublishedAtAsc($posts);

        return $this->render('posts/index.html.twig', [
            'posts' => $orderedPosts,
        ]);
    }


    #[Route('/posts/{id}', name: 'post_show', methods: ['GET'])]
    public function getPost(int $id): Response
    {
        $response = $this->api->getByParameters([]);
        $post = $this->postsService->getSpecificPost($response['articles'], $id);

        if (!$post) {
            return $this->render('posts/404.html.twig', [
                'post' => $id,
            ]);
        }

        return $this->render('posts/show.html.twig', [
            'post' => $post,
        ]);
    }
}
