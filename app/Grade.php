<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Grade
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $min_posts
 * @property string $title
 * @property string|null $color
 * @property string|null $icon
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Grade whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Grade whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Grade whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Grade whereMinPosts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Grade whereTitle($value)
 */
class Grade extends Model
{

}
