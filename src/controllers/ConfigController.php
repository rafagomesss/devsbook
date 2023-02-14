<?php

namespace src\controllers;

use core\Controller;
use DateTime;
use src\handlers\FlashHandler;
use src\handlers\UserHandler;

class ConfigController extends Controller
{
    private $loggedUser;

    public function __construct()
    {
        $this->loggedUser = UserHandler::checkLogin();
        if ($this->loggedUser === false) {
            $this->redirect('/login');
        }
    }

    public function index($atts)
    {
        $id = !empty($atts['id']) ? $atts['id'] : $this->loggedUser->id;

        $user = UserHandler::getUser($id, true);

        if (!$user) {
            $this->redirect('/');
        }

        $flash = FlashHandler::get('flash') ?? null;

        $this->render('config', [
            'loggedUser' => $this->loggedUser,
            'user' => $user,
            'flash' => $flash,
        ]);
    }

    public function update()
    {
        //* 1. Validar data de nascimento
        //* 2. Validar se o novo e-mail já existe na base
        //* 3. Senha e confirmar senha (ambas deve ser preenchidas e devem conter o mesmo valor)

        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $birthdate = filter_input(INPUT_POST, 'birthdate');
        $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_SPECIAL_CHARS);
        $work = filter_input(INPUT_POST, 'work', FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'password');
        $confirmPassword = filter_input(INPUT_POST, 'password_confirm');

        $fieldsUpdate = [];

        if (empty($name)) {
            FlashHandler::set('Nome inválido');
            $this->redirect('/config');
        }

        if ($name && $email) {
            $fieldsUpdate['name'] = $name;

            $user = UserHandler::getUser($this->loggedUser->id, true);

            if (!$user) {
                $this->redirect('/');
            }

            if ($user->email != $email) {
                if (!UserHandler::emailExists($email)) {
                    $fieldsUpdate['email'] = $email;
                } else {
                    FlashHandler::set('E-mail já existe!');
                    $this->redirect('/config');
                }
            }

            $birthdate = explode('/', $birthdate);
            if (
                count($birthdate) < 3 ||
                checkdate($birthdate[1], $birthdate[0], $birthdate[2]) === false
            ) {
                FlashHandler::set('Data De Nascimento Inválida');
                $this->redirect('/config');
            }

            $birthdate = DateTime::createFromFormat('d/m/Y', implode('/', $birthdate))->format('Y-m-d');


            $fieldsUpdate['birthdate'] = $birthdate;

            if (!empty($password)) {
                if ($password !== $confirmPassword) {
                    FlashHandler::set('As senhas não são são iguais!');
                    $this->redirect('/config');
                }
                $fieldsUpdate['password'] = $password;
            }

            $fieldsUpdate['city'] = $city;
            $fieldsUpdate['work'] = $work;

            if (isset($_FILES['avatar']) && !empty($_FILES['avatar']['tmp_name'])) {
                $newAvatar = $_FILES['avatar'];

                if (in_array($newAvatar['type'], ['image/jpg', 'image/jpeg', 'image/png'])) {
                    $avatarName = $this->cutImage($newAvatar, 200, 200, 'media/avatars');
                    $fieldsUpdate['avatar'] = $avatarName;
                }
            }

            if (isset($_FILES['cover']) && !empty($_FILES['cover']['tmp_name'])) {
                $newCover = $_FILES['cover'];

                if (in_array($newCover['type'], ['image/jpg', 'image/jpeg', 'image/png'])) {
                    $coverName = $this->cutImage($newCover, 850, 310, 'media/covers');
                    $fieldsUpdate['cover'] = $coverName;
                }
            }

            UserHandler::updateProfile($fieldsUpdate, $this->loggedUser->id);
        }

        $this->redirect('/config');
    }

    private function cutImage($file, $width, $height, $folder)
    {
        list($widthOrig, $heightOrig) = getimagesize($file['tmp_name']);
        $ratio = $widthOrig / $heightOrig;

        $newWidth = $width;
        $newHeight = $newWidth / $ratio;

        if ($newHeight < $height) {
            $newHeight = $height;
            $newWidth = $newHeight * $ratio;
        }

        $x = $width - $newWidth;
        $y = $height - $newHeight;

        $x = $x < 0 ? $x / 2 : $x;
        $y = $y < 0 ? $y / 2 : $y;

        $finalImage = imagecreatetruecolor($width, $height);
        switch ($file['type']) {
            case 'image/jpeg':
            case 'image/jpg':
                $image = imagecreatefromjpeg($file['tmp_name']);
                break;
            case 'image/png':
                $image = imagecreatefrompng($file['tmp_name']);
                break;
        }

        imagecopyresampled(
            $finalImage, $image,
            $x, $y, 0, 0,
            $newWidth, $newHeight, $widthOrig, $heightOrig
        );

        $fileName = md5(time() . rand(0, 9999)) . '.jpg';

        imagejpeg($finalImage, $folder . '/' . $fileName);

        return $fileName;
    }
}
