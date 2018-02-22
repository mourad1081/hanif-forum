<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Category
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int|null $parent_id
 * @property string $title
 * @property string|null $slug
 * @property string|null $color
 * @property string|null $icon
 * @property int|null $priority
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int|null $min_access_level restricted area for vip, admin, etc
 * @property string|null $description
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereMinAccessLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereUpdatedAt($value)
 * @property int|null $nb_topics
 * @property int|null $nb_discussions
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereNbDiscussions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereNbTopics($value)
 * @property int|null $nb_messages
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereNbMessages($value)
 * @property int|null $visible
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereVisible($value)
 * @property string|null $last_discussion_title
 * @property string|null $last_discussion_author
 * @property string|null $last_discussion_created_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereLastDiscussionAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereLastDiscussionCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereLastDiscussionTitle($value)
 * @property int|null $last_discussion_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereLastDiscussionId($value)
 */
class Category extends Model
{
    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected $fillable = [
        "title", "description", "min_access_level",
        "nb_messages", "nb_discussions", "visible",
        "slug", "color", "icon", "priority", "parent_id"
    ];

    public function isAdminCategory() {
        return $this->min_access_level == 99;
    }

    public function isNormalCategory() {
        return $this->min_access_level == 0;
    }

    public function isVIPCategory() {
        return $this->min_access_level == 1;
    }

    public function makeExcerpt($cutOffLength = 20, $default_if_empty = "") {
        $charAtPosition = null;
        $titleLength = strlen($this->description);

        if($titleLength == 0)
            return $default_if_empty;

        elseif($titleLength < $cutOffLength)
            return $this->description;

        do {
            $cutOffLength++;
            $charAtPosition = substr($this->description, $cutOffLength, 1);
        } while ($cutOffLength < $titleLength && $charAtPosition != " ");

        return substr($this->description, 0, $cutOffLength) . '...';
    }

}
