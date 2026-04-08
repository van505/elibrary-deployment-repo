@extends('errors.layout')
@section('code', '404')
@section('code-color', 'text-blue-500')
@section('title', 'Page Not Found')
@section('message', "The page you're looking for doesn't exist or has been moved.")
@section('icon-bg', 'bg-blue-50')
@section('icon')
    <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
@endsection
