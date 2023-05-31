<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Validation\Rule;

class AdminPostController extends Controller
{
    public function index()
    {
        return view('admin.posts.index', [
            'posts' => Post::paginate(50)
        ]);
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store()
    {
        $attributes = $this->validatePost(new Post());

        $attributes['user_id'] = auth()->id();
        $fileName = time() . request()->file('thumbnail')->getClientOriginalName();
        $path = request()->file('thumbnail')->storeAs('thumbnails', $fileName, 'public');
        $attributes["thumbnail"] = $path;

        Post::create($attributes);

        return redirect('/');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.edit', [
            'post' => $post
        ]);
    }

    public function update(Post $post)
    {
        $attributes = $this->validatePost($post);

        if ($attributes['thumbnail'] ?? false) {
            $fileName = time() . request()->file('thumbnail')->getClientOriginalName();

            $path = request()->file('thumbnail')->storeAs('thumbnails', $fileName, 'public');

            $attributes["thumbnail"] = $path;
        }

        $post->update($attributes);

        return back()->with('success', 'Post Updated!');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return back()->with('success', 'Post Deleted!');
    }

    /**
     * @param Post|null $post
     * @return array
     */
    protected function validatePost(?Post $post = null): array
    {
        $post ??= new Post();

        return request()->validate([
            'title' => 'required',
            'thumbnail' => $post->exists ? ['image'] : ['required', 'image'],
            'slug' => ['required', Rule::unique('posts', 'slug')->ignore($post)],
            'excerpt' => 'required',
            'body' => 'required',
            'category_id' => ['required', Rule::exists('categories', 'id')]
        ]);
    }
}
