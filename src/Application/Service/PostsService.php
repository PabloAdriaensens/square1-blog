<?php

namespace App\Application\Service;

class PostsService
{
    /**
     * @param array $posts
     * @param int $id
     * @return array
     */
    public function getSpecificPost(array $posts, int $id): array
    {
        foreach ($posts as $post) {
            if ((int) $post['id'] === $id) {
                return $post;
            }
        }

        return [];
    }
}