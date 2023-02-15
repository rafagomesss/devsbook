<?php

namespace src\handlers;

use src\models\Post;
use src\models\User;
use src\models\UserRelation;
use src\models\PostLike;
use src\models\PostComment;

class PostHandler
{
    public static function addPost($idUser, $type, $body): void
    {
        if (!empty($idUser) && !empty(trim($body))) {
            Post::insert([
                'id_user' => $idUser,
                'type' => $type,
                'body' => $body,
            ])->execute();
        }
    }

    public function _postListToObject($postList, $loggedUserId): array
    {
        $posts = [];
        foreach ($postList as $postItem) {
            $newPost = new Post();
            $newPost->id = $postItem['id'];
            $newPost->type = $postItem['type'];
            $newPost->body = $postItem['body'];
            $newPost->createdAt = $postItem['created_at'];
            $newPost->mine = $postItem['id_user'] == $loggedUserId ? true : false;

            $newUser = User::select()->where('id', $postItem['id_user'])->one();
            $newPost->user = new User();
            $newPost->user->id = $newUser['id'];
            $newPost->user->name = $newUser['name'];
            $newPost->user->avatar = $newUser['avatar'];

            $likes = PostLike::select()->where('id_post', $postItem['id'])->get();

            $newPost->likeCount = count($likes);
            $newPost->liked = self::isLiked($postItem['id'], $loggedUserId);

            $newPost->comments = PostComment::select()
                ->where('id_post', $postItem['id'])
            ->get();

            foreach ($newPost->comments as $key => $comment) {
                $newPost->comments[$key]['user'] = User::select()->where('id', $comment['id_user'])->one();
            }

            $posts[] = $newPost;
        }

        return $posts;
    }

    public static function isLiked($postId, $loggedUserId)
    {
        $myLike = PostLike::select()
            ->where('id_post', $postId)
            ->where('id_user', $loggedUserId)
            ->get();

        if (count($myLike) > 0 ) {
            return true;
        }

        return false;
    }

    public static function deleteLike(int $idPost, int $loggedUserId)
    {
        PostLike::delete()
            ->where('id_post', $idPost)
            ->where('id_user', $loggedUserId)
        ->execute();
    }

    public static function addLike(int $idPost, int $loggedUserId)
    {
        PostLike::insert([
            'id_post' => $idPost,
            'id_user' => $loggedUserId,
        ])->execute();
    }

    public static function getUserFeed(int $idUser, int $loggedUserId, int $page)
    {
        $perPage = 2;

        $postList = Post::select()
            ->where('id_user', $idUser)
            ->orderBy('created_at', 'desc')
            ->page($page, $perPage)
            ->get();

        $total = Post::select()
            ->where('id_user', $idUser)
            ->count();

        $pageCount = ceil($total / $perPage);

        $posts = self::_postListToObject($postList, $loggedUserId);

        return [
            'posts' => $posts,
            'pageCount' => $pageCount,
            'currentPage' => $page,
        ];
    }

    public static function getHomeFeed($idUser, $page): array
    {
        $perPage = 2;

        //* 1. Pegar lista dos usuários que eu sigo
        $userList = UserRelation::select()->where('user_from', $idUser)->get();
        $users = [];
        foreach ($userList as $userItem) {
            $users[] = $userItem['user_to'];
        }
        $users[] = $idUser;

        //* 2. pegar os posts dessa galera ordenado pela data
        $postList = Post::select()
            ->where('id_user', 'in', $users)
            ->orderBy('created_at', 'desc')
            ->page($page, $perPage)
            ->get();

        $total = Post::select()
            ->where('id_user', 'in', $users)
            ->count();

        $pageCount = ceil($total / $perPage);

        //* 3. transformar o resultado em objetos dos Models
        $posts = self::_postListToObject($postList, $idUser);

        //* 5. retornar o resultado
        return [
            'posts' => $posts,
            'pageCount' => $pageCount,
            'currentPage' => $page,
        ];
    }

    public static function getPhotosFrom(int $idUser): array
    {
        $photosData = Post::select()
            ->where('id_user', $idUser)
            ->where('type', 'photo')
            ->get();

        $photos = [];

        foreach ($photosData as $photo) {
            $newPost = new Post();
            $newPost->id = $photo['id'];
            $newPost->type = $photo['type'];
            $newPost->createdAt = $photo['created_at'];
            $newPost->body = $photo['body'];

            $photos[] = $newPost;
        }

        return $photos;
    }

    public static function addComment(int $idComment, $body, $idUser)
    {
        PostComment::insert([
            'id_post' => $idComment,
            'body' => $body,
            'id_user' => $idUser,
        ])->execute();
    }

    public static function delete($idPost, $loggedUserId)
    {
        $post = Post::select()
            ->where('id', $idPost)
            ->where('id_user', $loggedUserId)
        ->get();

        if (count($post) > 0) {
            $post = $post[0];

            PostLike::delete()->where('id_post', $idPost)->execute();
            PostComment::delete()->where('id_post', $idPost)->execute();

            if($post['type'] === 'photo') {
                $img = __DIR__ . '/../../media/uploads/' . $post['body'];
                if (file_exists($img)) {
                    unlink($img);
                }
            }

            Post::delete()->where('id', $idPost)->execute();
        }
    }
}
