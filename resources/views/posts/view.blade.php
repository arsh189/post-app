@extends('layouts.app')

@section('title', 'View Post')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-2xl overflow-hidden p-6">
        <!-- Post Title -->
        <h1 class="text-3xl font-bold text-gray-800">{{ $post->title }}</h1>
        
        <!-- Post Meta -->
        <p class="text-gray-500 mt-2 text-sm">Published on {{ $post->created_at->format('F d, Y') }}</p>
        
        <!-- Post Content -->
        <div class="mt-4 text-gray-700 leading-relaxed">
            {!! nl2br(e($post->content)) !!}
        </div>

        <!-- Buttons -->
        <div class="mt-6 flex justify-between items-center">
            <a href="{{ route('posts.index') }}" class="text-blue-500 hover:text-blue-700 font-semibold">
                ← Back to list
            </a>

            <div class="flex space-x-3">
                @if(Auth::user()->can('update posts'))
                    <a href="{{ route('posts.edit', $post->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        ✏ Edit
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this post?");
    }
</script>
@endsection
