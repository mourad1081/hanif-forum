<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Discussion
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $category_id
 * @property int $user_id
 * @property string $title
 * @property string|null $content
 * @property int|null $views
 * @property int|null $answers
 * @property int|null $locked
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $created_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Discussion whereAnswers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Discussion whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Discussion whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Discussion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Discussion whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Discussion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Discussion whereLocked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Discussion whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Discussion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Discussion whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Discussion whereViews($value)
 * @property int|null $sticked_at_top
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Discussion whereStickedAtTop($value)
 * @property int|null $likes
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Discussion whereLikes($value)
 * @property int|null $last_post_user_id auteur du dernier message
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Discussion whereLastPostUserId($value)
 * @property int|null $last_post_id auteur du dernier message
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Discussion whereLastPostId($value)
 */
class Discussion extends Model
{

    protected $fillable = [
        "category_id", "user_id", "title", "content", "views", "answers", "locked", "sticked_at_top"
    ];

}
