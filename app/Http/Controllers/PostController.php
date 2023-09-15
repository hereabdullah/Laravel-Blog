<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $posts = $user->posts;
        return response()->json($posts);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $postData = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
        ]);

        $post = $user->posts()->create($postData);
        return response()->json($post, 201);
    }

    public function show(Post $post)
    {

        if (Auth::id() !== $post->user_id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json($post);
    }

    public function update(Request $request, Post $post)
    {

        if (Auth::id() !== $post->user_id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $postData = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
        ]);

        $post->update($postData);

        return response()->json($post);
    }

    public function destroy(Post $post)
    {

        if (Auth::id() !== $post->user_id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted']);
    }
}
