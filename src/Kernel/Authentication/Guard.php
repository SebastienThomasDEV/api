<?php
namespace Mvc\Framework\Kernel\Authentication;

use Mvc\Framework\App\Repository\UtilisateurRepository;


class Guard {
    public static function check(): bool
    {
        // TODO: Implement check() method it should return a boolean if the token is valid or not
        $token = self::getToken();
        return false;
    }

    public static function getToken() : string
    {
        $headers = getallheaders();
        $authorizationHeader = $headers["Authorization"];
        $token = str_replace("Bearer ", "", $authorizationHeader);
        return trim($token);
    }
}