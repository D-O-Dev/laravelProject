<?php

namespace App\Http\Controllers;

use App\Models\Articles;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;

class ArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Articles::latest()->get();
        return view('articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('articles.edit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'category' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = $request->picture->store("articles");


        Articles::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'content' => $request->content,
            'slug' => Str::slug($request->title),
            'category' => $request->category,
            'image' => $imageName,
        ]);

        return redirect()->route('articles.index')
            ->with('success', 'Article created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Articles $articles)
    {
        return view('articles.show', compact("articles"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Articles $articles)
    {
        return view('articles.edit', compact('articles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Articles $articles)
    {
        $val = [
            'title' => 'bail|required|string|max:255',
            'content' => 'bail|required',
        ];


        // Si une nouvelle image est envoyée
        if ($request->has('image')) {
            $val['image'] = 'bail|required|image|max:1024';
        }

        $request->validate($val);


        if ($request->has('image')) {
            $data = User::find($request->id);
            $image_path = public_path() . '/' . $data->filename;
            unlink($image_path);
            $data->delete();
        }


        $imageName = $request->image->store("articles");
        $slug = $request->slug;

        $articles->update([
            'title' => $request->title,
            'content' => $request->content,
            'image' =>isset($imageName) ? $imageName : $articles->image,
            'slug' => $slug,
            'category' => $request->category,
        ]);

        return redirect(route('articles.show', $articles));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Articles $articles)
    {
        $data = Articles::find($articles->id);
        $data->delete($articles->image);

        $articles->delete();

        return redirect(route('articles.index'));
    }
}
