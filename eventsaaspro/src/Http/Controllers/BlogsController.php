<?php

namespace Eventsaaspro\Http\Controllers;
use App\Http\Controllers\Controller;
use Facades\Eventsaaspro\EventSaaSPro;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Eventsaaspro\Models\Post;

class BlogsController extends Controller
{

    public function __construct()
    {
        // language change
        $this->middleware('common');

        $this->post   = new Post;

    }

    // particulara post view
    public function view(Request $request, $slug = null,  $view = 'eventsaaspro::blogs.show', $extra = [])
    {
        $post  = $this->post->view($request->segment(2));
        if(!$post)
            abort('404');

        return EventSaaSPro::view($view, compact('post', 'extra'));
    }

    // particulara post view
    public function get_posts(Request $request, $view = 'eventsaaspro::blogs.index', $extra = [])
    {
        $posts  = $this->post->get_posts();

        $links = $posts->appends(['sort' => 'id'])->links();

        return EventSaaSPro::view($view, compact('posts', 'extra'));
    }
}
