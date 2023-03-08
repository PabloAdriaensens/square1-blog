<?php

namespace App\Infrastructure\Http;

use App\Application\Service\CandidateTestApi;
use App\Application\Service\PostsService;
use App\Domain\Entity\Post;
use App\Infrastructure\Form\PostFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BlogController extends AbstractController
{
    private CandidateTestApi $api;
    private PostsService $postsService;
    private EntityManagerInterface $em;
    private Security $security;

    public function __construct(
        CandidateTestApi $api,
        PostsService $postsService,
        EntityManagerInterface $em,
        Security $security
    ) {
        $this->api = $api;
        $this->postsService = $postsService;
        $this->em = $em;
        $this->security = $security;
    }

    #[Route('/posts', name: 'posts', methods: ['GET'])]
    public function getPosts(Request $request, SessionInterface $session): Response
    {
        $order = $request->query->get('order', 'asc');

        $posts = $this->postsService->getAllPosts($order);

        $session->set('post_order', $order);

        return $this->render('posts/index.html.twig', [
            'posts' => $posts,
            'order' => $order,
        ]);
    }

    #[Route('/private_area', name: 'private_area', methods: ['GET'])]
    public function getPrivateArea(): Response
    {
        $user = $this->security->getUser();
        $posts = $this->em->getRepository(Post::class)->findBy(['author' => $user->getId()]);

        return $this->render('posts/private_area.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/posts/create', name: 'post_create')]
    public function create(Request $request): Response
    {
        $post = new Post();
        $post->setAuthor($this->security->getUser());
        $post->setPublicationDate(new \DateTime());
        $form = $this->createForm(PostFormType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPost = $form->getData();

            $this->em->persist($newPost);
            $this->em->flush();

            return $this->redirectToRoute('posts');
        }

        return $this->render('posts/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/posts/{id}', name: 'post_show', methods: ['GET'])]
    public function getPost(int $id): Response
    {
        $post = $this->em->getRepository(Post::class)->find($id);

        if (!$post) {
            return new Response(null, 404);
        }

        return $this->render('posts/show.html.twig', [
            'post' => $post,
        ]);
    }
}
