<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebInfo extends Model
{
    use HasFactory;

    public static $keys = [
        "name",
        "state",
        "city",
        "street",
        "zip",
        "no",
        "email",
        "blog",
    ];

    public static $required = [
        "name",
    ];


    public static $omit = [
        "id"
    ];

}
