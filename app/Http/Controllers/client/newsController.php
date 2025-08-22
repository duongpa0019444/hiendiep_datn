<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use App\Models\news;
use App\Models\topics;
use Illuminate\Support\Facades\DB;

class newsController extends Controller
{
    public function index()
    {

        $news = news::where('event_type', 'news')
            ->orderBy('created_at', 'desc')
            ->get();

        $events = news::where('event_type', 'event')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('client.news.news-list', compact('news', 'events'));
    }

    public function newsDetail($id, $slug)
    {
        $news = news::findOrFail($id);
        $news->increment('views');

        $topics = topics::withCount('news')->get();
        $popularNews = News::orderBy('views', 'desc')->take(3)->get();
        return view('client.news.news-details', compact('news', 'topics', 'popularNews'));
    }

    public function newsCategory($id){
        $topic = topics::find($id);
        $newsCategorys = news::where('topic_id', $id)->orderBy('created_at', 'desc')->get();
        return view('client.news.news-category', compact('newsCategorys', 'topic'));
    }
}
