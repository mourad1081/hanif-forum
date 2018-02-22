<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ViewMessage
 *
 * @property string|null $pseudo
 * @property int|null $total_answers
 * @property string|null $custom_grade
 * @property string|null $user_updated_at
 * @property string|null $user_created_at
 * @property int|null $is_ban
 * @property string|null $role_display_name
 * @property string $content
 * @property string|null $category_title
 * @property string|null $post_created_at
 * @property string|null $post_updated_at
 * @property int|null $likes
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ViewMessage whereCategoryTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ViewMessage whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ViewMessage whereCustomGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ViewMessage whereIsBan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ViewMessage whereLikes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ViewMessage wherePostCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ViewMessage wherePostUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ViewMessage wherePseudo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ViewMessage whereRoleDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ViewMessage whereTotalAnswers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ViewMessage whereUserCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ViewMessage whereUserUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $discussion_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ViewMessage whereDiscussionId($value)
 * @property int $post_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ViewMessage wherePostId($value)
 * @property int|null $user_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ViewMessage whereUserId($value)
 * @property string|null $grade_title
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ViewMessage whereGradeTitle($value)
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ViewMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ViewMessage whereUpdatedAt($value)
 */
class ViewMessage extends Model
{
    //

    public function makeExcerpt($cutOffLength = 128, $default_if_empty = "") {
        $charAtPosition = null;
        $titleLength = strlen($this->content);

        if($titleLength == 0)
            return $default_if_empty;

        elseif($titleLength < $cutOffLength)
            return $this->content;

        do {
            $cutOffLength++;
            $charAtPosition = substr($this->content, $cutOffLength, 1);
        } while ($cutOffLength < $titleLength && $charAtPosition != " ");

        return substr($this->content, 0, $cutOffLength) . '...';
    }
}
