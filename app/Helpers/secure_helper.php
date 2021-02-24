<?php

function regex($user, $pass)
{
    if (preg_match('/^[a-zA-Z0-9]', $user) && preg_match('/^[a-zA-Z0-9]', $pass)) {
        return true;
    }
}

function hashPass($pass)
{
    if (!empty($pass)) {
        return password_hash($pass, PASSWORD_BCRYPT);
    } else {
        return null;
    }
}

function verifyPass($plainText, $hash)
{
    return password_verify($plainText, $hash);
}
