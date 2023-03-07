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
}