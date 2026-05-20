<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of blog posts.
     */
    public function index(Request $request)
    {
        $query = Page::published()->orderBy('published_at', 'desc');

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->byCategory($request->category);
        }

        // Filter by tag
        if ($request->has('tag') && $request->tag) {
            $query->where('tags', 'like', '%' . $request->tag . '%');
        }

        $posts = $query->paginate(9);
        $categories = Page::$blogCategories;

        // Get popular tags
        $allTags = Page::blogs()
            ->active()
            ->whereNotNull('tags')
            ->pluck('tags')
            ->flatMap(fn($tags) => array_map('trim', explode(',', $tags)))
            ->countBy()
            ->sortDesc()
            ->take(10);

        // Página "blog" (settings) con copy editable + SEO desde BD
        $page = Page::where('slug', 'blog')->where('type', 'page')->with('seo')->first();
        $seo  = $page?->seo;

        return view('blog.index', compact('posts', 'categories', 'allTags', 'page', 'seo'));
    }

    /**
     * Display a single blog post.
     */
    public function show($slug)
    {
        $post = Page::where('slug', $slug)
            ->where('type', 'blog')
            ->where('is_active', true)
            ->where(function($q) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', now());
            })
            ->firstOrFail();

        // Get related posts (same category or similar tags)
        $relatedPosts = Page::published()
            ->where('id', '!=', $post->id)
            ->where(function($q) use ($post) {
                if ($post->category) {
                    $q->where('category', $post->category);
                }
                if ($post->tags) {
                    foreach ($post->getTagsArray() as $tag) {
                        $q->orWhere('tags', 'like', '%' . $tag . '%');
                    }
                }
            })
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        return view('blog.show', compact('post', 'relatedPosts'));
    }

    /**
     * Display posts by category.
     */
    public function category($category)
    {
        $posts = Page::published()
            ->byCategory($category)
            ->orderBy('published_at', 'desc')
            ->paginate(9);

        $categoryName = Page::$blogCategories[$category] ?? $category;
        $categories = Page::$blogCategories;

        return view('blog.category', compact('posts', 'category', 'categoryName', 'categories'));
    }

    /**
     * Display posts by tag.
     */
    public function tag($tag)
    {
        $posts = Page::published()
            ->where('tags', 'like', '%' . $tag . '%')
            ->orderBy('published_at', 'desc')
            ->paginate(9);

        $categories = Page::$blogCategories;

        return view('blog.tag', compact('posts', 'tag', 'categories'));
    }
}
