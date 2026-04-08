@extends('errors.layout')
@section('code', '419')
@section('code-color', 'text-yellow-500')
@section('title', 'Session Expired')
@section('message', 'Your session has expired for security reasons. Please log in again to continue.')
@section('icon-bg', 'bg-yellow-50')
@section('icon')
    <svg class="w-12 h-12 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
@endsection
@section('extra-actions')
    <a href="{{ route('login') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition-colors">
        Login Again
    </a>
@endsection
