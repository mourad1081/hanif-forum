<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Post
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $discussion_id
 * @property int $user_id
 * @property string $content
 * @property int|null $likes
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereDiscussionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereLikes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereUserId($value)
 */
class Post extends Model
{
    protected $fillable = ["discussion_id", "user_id", "content", "likes", "created_at", "updated_at"];

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
