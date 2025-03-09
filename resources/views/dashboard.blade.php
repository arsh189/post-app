@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h2>Dashboard</h2>
    <p>Hi <strong>{{auth()->user()->name}}</strong>, Welcome to your dashboard!</p>
@endsection
