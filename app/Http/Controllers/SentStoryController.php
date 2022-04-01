<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\SentStory;


class SentStoryController extends Controller
{
    //

    public function create($options)
    {
        $user_id = $options['user_id'];
        $post_id = $options['post_id'];

        $sent_story = new SentStory();
        $sent_story->user_id = $user_id;
        $sent_story->post_id = $post_id;
        $sent_story->save();

        return $sent_story;
    }

    public function hasSentStory($options)
    {
        $user_id = $options['user_id'];
        $post_id = $options['post_id'];

        $sent_story = SentStory::where([
            'user_id' => $user_id,
            'post_id' => $post_id,
        ])->get();

        return ($sent_story->count() > 0);

    }

}
