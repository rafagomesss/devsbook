<?php

namespace src\controllers;

use \core\Controller;
use src\handlers\PostHandler;
use src\handlers\UserHandler;

class AjaxController extends Controller
{
    private $loggedUser;

    public function __construct()
    {
        $this->loggedUser = UserHandler::checkLogin();
        if ($this->loggedUser === false) {
            header('Content-type: application/json');
            echo json_encode(['error' => 'Usuário não logado!']);
            exit();
        }
    }

    public function like($atts)
    {
        $id = intval(trim($atts['id']));
        if (PostHandler::isLiked($id, $this->loggedUser->id)) {
            PostHandler::deleteLike($id, $this->loggedUser->id);
        } else {
            PostHandler::addLike($id, $this->loggedUser->id);
        }
    }
}