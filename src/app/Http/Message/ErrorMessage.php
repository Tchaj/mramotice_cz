<?php
/***
 * File: ErrorMessage.php
 * Product: aiorarpApi
 * HeroldCMS | heroldcms.eu
 * Author: Martin Horáček | martinhoracek.cz
 * Date: 17.11.2022
 * Time: 16:42
 * Codes may not be redistributed the owner's consent.
 * Copyrights (c) 2022. All right Reserved
 ***/


namespace App\Http\Message;

class ErrorMessage
{
    public const user_register_success = "email_registered_success";
    public const user_not_exists = "user_not_exists";
    public const invalid_login = "invalid_login";
    public const not_authorized = "not_authorized";
    public const user_logout = "user_logout";
}
