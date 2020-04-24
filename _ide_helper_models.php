<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * App\Settings
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $slack_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Settings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Settings newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Settings query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Settings whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Settings whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Settings whereSlackUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Settings whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Settings whereUserId($value)
 */
	class Settings extends \Eloquent {}
}

namespace App{
/**
 * App\Post
 *
 * @property int $id
 * @property int $profile_id
 * @property int $ig_post_id
 * @property string|null $caption
 * @property string $type
 * @property string $post_url
 * @property \Illuminate\Support\Carbon|null $posted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $image_url
 * @property-read \App\Profile $profile
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereCaption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereIgPostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post wherePostUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post wherePostedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereUpdatedAt($value)
 */
	class Post extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @property int $id
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\UserDetails|null $details
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Profile[] $profiles
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read int|null $profiles_count
 * @property-read \App\Settings|null $settings
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 */
	class User extends \Eloquent implements \Illuminate\Contracts\Auth\MustVerifyEmail {}
}

namespace App{
/**
 * App\UserDetails
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $name
 * @property string|null $address
 * @property string|null $postal
 * @property string|null $city
 * @property string|null $country
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserDetails newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserDetails newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserDetails query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserDetails whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserDetails whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserDetails whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserDetails whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserDetails whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserDetails whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserDetails wherePostal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserDetails whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserDetails whereUserId($value)
 */
	class UserDetails extends \Eloquent {}
}

namespace App{
/**
 * App\Profile
 *
 * @property int $id
 * @property string $username
 * @property string|null $avatar
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $followers
 * @property-read int|null $followers_count
 * @property-read string $url
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile whereUsername($value)
 */
	class Profile extends \Eloquent {}
}

