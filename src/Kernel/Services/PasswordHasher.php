<?php

namespace Api\Framework\Kernel\Services;

class PasswordHasher
{
    public final function hash(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public final function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

}