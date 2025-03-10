@extends('layouts.app')

@section('title', 'View Post')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white shadow-md rounded-lg p-6">
        <!-- Post Title -->
        <h1 class="text-2xl font-bold text-gray-800 pb-2 border-b-2 border-gray-300">
            {{ $post->title }}
        </h1>

        <!-- Post Date -->
        <p class="text-gray-500 text-sm mt-1">Published on {{ $post->created_at->format('F d, Y') }}</p>

        <!-- Post Content -->
        <div class="mt-4 text-gray-700 border border-gray-300 p-4 rounded-lg">
            {!! nl2br(e($post->content)) !!}
        </div>

        <!-- Back Button -->
        <div class="mt-6">
            <a href="{{ route('posts.index') }}" class="text-blue-500 hover:underline">← Back to list</a>
        </div>
    </div>
</div>

@endsection
@section('scripts')
@endsection
