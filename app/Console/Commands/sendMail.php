<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\WebsiteUser;
use App\Models\Post;
use App\Http\Controllers\SentStoryController;
use App\Models\SentStory;

class sendMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:send-mail';

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

        // $postId = $this->argument('post_id');


        $posts = Post::all();
        
        foreach($posts as $post){

            $postId = $post->id;
            // $this->info('post is' . $post->id);

            $post = Post::with('website')->find($postId);

            $websiteId = $post->website['id'];

            $subscribers = WebsiteUser::where([
                'website_id' => $websiteId,
            ])->get();

            $subscribers = WebsiteUser::with('user')->where('website_id', $websiteId)->get();

            $subscribersArray = [];
            foreach ($subscribers as $subscriber) {

                $sent_story = SentStory::where([
                    'user_id' => $subscriber->user['id'],
                    'post_id' => $postId,
                ])->get();

                if($sent_story->count() < 1){
                    $subscribersArray[] = $subscriber->user['email'];
                }  
            }

            $data = array('title' => $post->title,'content' => $post->description);
            $mailTitle = $post->title;

            if(count($subscribersArray) > 0){
                Mail::send(
                    [],
                    $data,
                    function($message) use ($subscribersArray, $mailTitle) {
                        $message->to($subscribersArray)->subject($mailTitle);
                    }
                );
            }
        }

        $this->info('The emails are sent successfully!');


        // return 0;
    }

    

}
