<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;

class NewPostAdded extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Holds the information about the new post.
     *
     * @var App\Post
     */
    public $post;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($post)
    {
        $this->post = $post;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->content('*@'.$this->post->profile->username.' just posted on Instagram ('.$this->post->posted_at->diffForHumans().')*')
            ->attachment(function ($attachment) {
                $attachment->content("\"{$this->post->caption}\"")
                    ->image($this->post->image_url)
                    ->color('#4392F1')
                    ->action('Comment on post', $this->post->post_url, 'primary')
                    ->action('Add more profiles', route('home'), '')
                    ->timestamp($this->post->posted_at);
            });
        ;
    }
}
