@extends('layouts.member')

@section('header')
    <div class="flex items-center gap-3">
        <a href="{{ route('member.collections.index') }}" class="text-gray-500 hover:text-gray-900 transition flex items-center pr-2 border-r border-gray-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            <span class="sr-only">Back</span>
        </a>
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ $collection->name }}
        </h2>
    </div>
@endsection

@section('content')
@php
    $member = auth()->user()->member;
    $plan = $member ? $member->currentPlan() : null;
    $levelMap = ['free' => 0, 'basic' => 1, 'premium' => 2];
    $userLevel = $plan ? ($levelMap[$plan->slug] ?? 0) : -1;
@endphp

<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        
        {{-- Collection Header Info --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
            <div class="flex flex-col md:flex-row">
                {{-- Cover --}}
                <div class="w-full md:w-1/3 bg-gray-100 flex-shrink-0">
                    @if($collection->cover_image)
                        <img src="{{ Storage::url($collection->cover_image) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full aspect-[2/3] md:aspect-auto bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center p-6 text-center text-white">
                            <span class="font-bold text-2xl opacity-90 leading-tight shadow-sm p-2">{{ $collection->name }}</span>
                        </div>
                    @endif
                </div>
                
                {{-- Details --}}
                <div class="p-6 md:p-8 flex-1 flex flex-col justify-center">
                    <span class="text-xs font-bold tracking-wider text-blue-600 uppercase mb-2">Book Series</span>
                    <h1 class="text-3xl font-extrabold text-gray-900 mb-4 leading-tight">{{ $collection->name }}</h1>
                    
                    @if($collection->description)
                        <p class="text-gray-600 mb-6 leading-relaxed">{{ $collection->description }}</p>
                    @endif
                    
                    <div class="flex items-center gap-4 text-sm font-medium text-gray-500 mb-6">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            {{ $ebooks->count() }} Books in Series
                        </div>
                    </div>

                    @if($ebooks->isNotEmpty())
                        <div>
                            <a href="{{ route('member.ebooks.show', $ebooks->first()->id) }}" class="inline-flex items-center gap-2 bg-gray-900 hover:bg-gray-800 text-white font-bold py-3 px-6 rounded-xl transition-colors shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Start Series from Book 1
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Ordered Book List --}}
        <h3 class="text-xl font-bold text-gray-900 mb-4 pl-1">Books inside {{ $collection->name }}</h3>
        
        <div class="space-y-4">
            @forelse($ebooks as $ebook)
                @php
                    $ebookLevel = $levelMap[$ebook->access_level] ?? 0;
                    $canExtract = $userLevel >= $ebookLevel;
                    // Determine styling based on access
                    $bgClass = $canExtract ? 'bg-white hover:border-blue-300' : 'bg-gray-50';
                    $opacityClass = $canExtract ? 'opacity-100' : 'opacity-80';
                @endphp
                
                <a href="{{ route('member.ebooks.show', $ebook->id) }}" class="block {{ $bgClass }} rounded-xl shadow-sm border border-gray-200 overflow-hidden transition hover:shadow-md group">
                    <div class="flex items-stretch {{ $opacityClass }}">
                        
                        {{-- Book Number --}}
                        <div class="w-16 flex-shrink-0 bg-gray-100/50 flex flex-col items-center justify-center border-r border-gray-100">
                            <span class="text-xs font-bold text-gray-400 tracking-wider">BOOK</span>
                            <span class="text-2xl font-black text-gray-800">{{ $ebook->pivot->order_number }}</span>
                        </div>
                        
                        {{-- Book Info --}}
                        <div class="flex-1 p-4 md:p-6 flex flex-col sm:flex-row sm:items-center gap-4">
                            {{-- Cover Thumbnail --}}
                            @if($ebook->cover_image)
                                <img src="{{ Storage::url($ebook->cover_image) }}" class="w-16 h-24 object-cover rounded shadow-sm border border-gray-200">
                            @else
                                <div class="w-16 h-24 bg-gray-200 rounded flex items-center justify-center border border-gray-200">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                </div>
                            @endif
                            
                            <div class="flex-1">
                                <h4 class="font-bold text-lg text-gray-900 group-hover:text-blue-600 transition-colors">{{ $ebook->title }}</h4>
                                <p class="text-sm text-gray-500 mb-2">{{ $ebook->authors->pluck('name')->join(', ') }}</p>
                                
                                {{-- Access Badge --}}
                                @if($ebook->access_level === 'free')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Free</span>
                                @elseif($ebook->access_level === 'basic')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">Basic Plan</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200 z-10 shadow-sm relative">Premium Plan</span>
                                @endif
                                
                                <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-xs ml-2">{{ strtoupper($ebook->file_type) }}</span>
                            </div>
                            
                            {{-- Availability Marker --}}
                            <div class="mt-4 sm:mt-0 flex items-center justify-end">
                                @if($canExtract)
                                    <div class="flex items-center text-sm font-bold text-gray-400 group-hover:text-blue-600 transition-colors">
                                        View details 
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </div>
                                @else
                                    <div class="flex items-center gap-2 bg-gray-200/60 px-3 py-1.5 rounded-lg text-sm font-semibold text-gray-600 border border-gray-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        Upgrade to Unlock
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="text-center p-8 bg-white rounded-xl border border-gray-100">
                    <p class="text-gray-500">There are no books in this collection yet.</p>
                </div>
            @endforelse
        </div>
        
    </div>
</div>
@endsection
