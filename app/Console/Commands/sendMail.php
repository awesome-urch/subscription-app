<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\WebsiteUser;
use App\Models\Post;

class sendMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:send-mail {post_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email to the subscribers.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $postId = $this->argument('post_id');

        $post = Post::with('website')->find($postId);

        $websiteId = $post->website['id'];

        $subscribers = WebsiteUser::where([
            'website_id' => $websiteId,
        ])->get();

        $subscribers = WebsiteUser::with('user')->where('website_id', $websiteId)->get();

        $subscribersArray = [];
        foreach ($subscribers as $subscriber) {
            $subscribersArray[] = $subscriber->user['email'];
            // error_log( $subscriber->user['email']);
        }

        // error_log( $subscribersArray[0]);

        $data = array('title' => $post->title,'content' => $post->description);
        $mailTitle = $post->title;

        Mail::send(
            [],
            $data,
            function($message) use ($subscribersArray, $mailTitle) {
                $message->to($subscribersArray)->subject($mailTitle);
            }
        );

        // Mail::send('emails.normal', $data, function ($message) {

        //     global $mailTitle;
        //     global $subscribers;
        //     foreach ($subscribers as $subscriber) {

        //         $message->from('our-website@gmail.com');
        //         $message->to($subscriber->user['email'])->subject($mailTitle);
        //         // $subscribersArray[] = $subscriber->user['email'];
        //         // error_log( $subscriber->user['email']);
        //     }  

        // });
        $this->info('The emails are send successfully!');
        // return 0;
    }

    

}
