<?php

namespace App\Infrastructure\Http;

use App\Application\Service\CandidateTestApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @var CandidateTestApi
     */
    private CandidateTestApi $api;

    /**
     * @param CandidateTestApi $api
     */
    public function __construct(CandidateTestApi $api)
    {
        $this->api = $api;
    }

    #[Route('/posts', name: 'posts', methods: ['GET'])]
    public function getPosts(): JsonResponse
    {
        $response = $this->api->getByParameters(['_sort' => 'publishedAt', '_order' => 'asc']);

        return $this->json(['data' => $response]);
    }
}
