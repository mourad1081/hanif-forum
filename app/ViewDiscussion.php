<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ViewDiscussion
 *
 * @property int $discussion_id
 * @property int $discussion_category_id
 * @property string $discussion_title
 * @property string|null $discussion_content
 * @property int|null $discussion_views
 * @property int|null $discussion_answers
 * @property int|null $discussion_locked
 * @property int|null $discussion_sticked_at_top
 * @property string|null $discussion_created_at
 * @property string|null $discussion_updated_at
 * @property string|null $discussion_deleted_at
 * @property int|null $discussion_likes
 * @property string|null $author_pseudo
 * @property int|null $author_id
 * @property string|null $last_post_created_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ViewDiscussion whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ViewDiscussion whereAuthorPseudo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ViewDiscussion whereDiscussionAnswers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ViewDiscussion whereDiscussionCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ViewDiscussion whereDiscussionContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ViewDiscussion whereDiscussionCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ViewDiscussion whereDiscussionDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ViewDiscussion whereDiscussionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ViewDiscussion whereDiscussionLikes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ViewDiscussion whereDiscussionLocked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ViewDiscussion whereDiscussionStickedAtTop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ViewDiscussion whereDiscussionTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ViewDiscussion whereDiscussionUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ViewDiscussion whereDiscussionViews($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ViewDiscussion whereLastPostCreatedAt($value)
 * @mixin \Eloquent
 */
class ViewDiscussion extends Model
{
    //
}
