<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'title',
    'subtitle',
    'description',
    'image',
    'button_text',
    'button_url',
    'status',
    'sort_order',
])]
class Banner extends Model
{
    //
}