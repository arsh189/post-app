<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use League\Csv\Writer;
use League\Csv\Reader;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // View all posts (Everyone)
    public function index()
    {
        $posts = Post::paginate(10); // Fetch 10 posts per page
        return view('posts.index', compact('posts'));
    }

    // Show create form (Super Admin, Admin, Staff)
    public function create()
    {
        if (!Auth::user()->can('create posts')) {
            abort(403, 'Unauthorized action.');
        }
        return view('posts.create');
    }

    // Store new post (Super Admin, Admin, Staff)
    public function store(Request $request)
    {
        if (!Auth::user()->can('create posts')) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }

        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $post = Post::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Post created successfully',
            'post' => $post
        ], 201);
    }

    // Show single post (Everyone)
    public function show(Post $post)
    {
        return view('posts.view', compact('post'));
    }

    // Update post (Super Admin, Admin ONLY)
    public function update(Request $request, Post $post)
    {
        if (!Auth::user()->can('edit posts')) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }

        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $post->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Post updated successfully',
            'post' => $post
        ], 200);
    }

    public function destroy(Post $post)
    {
        if (!Auth::user()->can('delete posts')) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }

        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully'
        ], 200);
    }

    public function importCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048'
        ]);

        $file = $request->file('csv_file');
        $csv = Reader::createFromPath($file->getPathname(), 'r');
        $csv->setHeaderOffset(0); // Use the first row as headers

        $duplicates = [];
        $newRecords = [];

        foreach ($csv as $row) {
            $existingPost = Post::where('title', $row['title'])->first();

            if ($existingPost) {
                $duplicates[] = $row; // Store duplicate records
            } else {
                $newRecords[] = [
                    'title' => $row['title'],
                    'content' => $row['content'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (!empty($newRecords)) {
            Post::insert($newRecords); // Bulk insert new posts
        }

        if (!empty($duplicates)) {
            $duplicateFilePath = 'duplicates/duplicate_posts_' . time() . '.csv';
            $csvWriter = Writer::createFromString('');
            $csvWriter->insertOne(['title', 'content']); // Add CSV headers
            $csvWriter->insertAll($duplicates);
            Storage::disk('public')->put($duplicateFilePath, $csvWriter->toString());

            return response()->json([
                'success' => false,
                'duplicates' => true,
                'failedCount' => count($duplicates),
                'uploadCount' => count($newRecords),
                'duplicatesCount' => true,
                'duplicate_file' => asset('storage/' . $duplicateFilePath),
            ]);
        }

        return response()->json(['success' => true, 'uploadCount' => count($newRecords), 'failedCount' => count($duplicates)]);
    }

}

