<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleCreateRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\Tag;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'status' => 'ok',
            'articles' => ArticleResource::collection(Article::all())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleCreateRequest $request)
    {
        $title = $request->title;
        $content = $request->content;
        $tags = $request->getLowerCaseTags();

        try {
            $article = Article::create(compact('title', 'content'));
            $tagsToSync = [];

            $tags->each(function ($name) use (&$tagsToSync) {
                $tag = Tag::firstOrCreate(compact('name'));

                $tagsToSync[] = $tag->id;
            });

            $article->tags()->sync($tagsToSync);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'status' => 'ok',
            'article' => new ArticleResource($article)
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        return response()->json([
            'status' => 'ok',
            'article' => new ArticleResource($article)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleCreateRequest $request, Article $article)
    {
        $title = $request->title;
        $content = $request->content;
        $tags = $request->getLowerCaseTags();

        try {
            $article->update(compact('title', 'content'));
            $tagsToSync = [];

            $tags->each(function ($name) use (&$tagsToSync) {
                $tag = Tag::firstOrCreate(compact('name'));

                $tagsToSync[] = $tag->id;
            });

            $article->tags()->sync($tagsToSync);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }

        $article->load("tags");

        return response()->json([
            'status' => 'ok',
            'article' => new ArticleResource($article)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        $article->delete();

        return response()->json([
            'status' => 'ok',
            'article' => new ArticleResource($article)
        ]);
    }
}
