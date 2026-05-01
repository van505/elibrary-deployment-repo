@extends('layouts.admin')
@section('title', 'Member Profile')

@push('breadcrumbs')
<nav class="flex items-center text-sm" aria-label="Breadcrumb">
    <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-gray-600 transition-colors duration-150">Dashboard</a>
    <span class="text-gray-300 mx-1.5 select-none">›</span>
    <a href="{{ route('admin.members.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors duration-150">Community › Members</a>
    <span class="text-gray-300 mx-1.5 select-none">›</span>
    <span class="text-gray-700 font-medium">{{ $member->full_name ?? $member->name ?? 'Member Detail' }}</span>
</nav>
@endpush

@section('content')
<div class="h-full space-y-6" x-data="{ activeTab: 'overview' }">

    {{-- ── Header Area ────────────────────────────────────────────────── --}}
    <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
        {{-- Profile Info --}}
        <div class="flex items-center gap-4">
            {{-- Avatar Placeholder --}}
            <div class="w-16 h-16 rounded-2xl bg-indigo-100 flex items-center justify-center border border-indigo-200 shadow-sm flex-shrink-0">
                <span class="text-2xl font-bold text-indigo-600">{{ strtoupper(substr($member->first_name ?: $member->user?->email ?: 'M', 0, 1)) }}</span>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 leading-tight">{{ $member->full_name ?: 'Unknown Member' }}</h1>
                <div class="flex items-center gap-3 mt-1 text-sm text-gray-500">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        {{ $member->user?->email }}
                    </span>
                    <span>&bull;</span>
                    <span class="font-mono text-xs bg-gray-100 px-1.5 py-0.5 rounded">{{ $member->member_code }}</span>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.members.edit', $member) }}"
               class="px-4 py-2 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-lg text-sm font-medium transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                Edit Profile
            </a>
            
            @if($member->status === 'active')
                <div x-data="{ suspendOpen: false }" class="relative">
                    <button @click="suspendOpen = !suspendOpen" @click.outside="suspendOpen = false"
                            class="px-4 py-2 bg-white border border-red-200 text-red-600 hover:bg-red-50 hover:text-red-700 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center gap-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                        Suspend
                    </button>
                    <div x-show="suspendOpen" style="display:none;"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         class="absolute right-0 top-full mt-2 w-72 bg-white rounded-xl shadow-xl border border-gray-100 z-50 p-4">
                        <form action="{{ route('admin.members.toggle-status', $member->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="suspended">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Reason for suspension <span class="text-red-500">*</span></label>
                            <textarea name="suspension_reason" rows="2" required placeholder="Type reason here..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none mb-3 resize-none"></textarea>
                            <button type="submit" onclick="return confirm('Suspend this member?')" class="w-full bg-red-600 hover:bg-red-700 text-white text-sm font-medium py-2 rounded-lg transition-colors">Confirm Suspension</button>
                        </form>
                    </div>
                </div>
            @else
                <form action="{{ route('admin.members.toggle-status', $member->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="active">
                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center gap-2 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Activate
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- ── Horizontal Tabs ──────────────────────────────────────────────── --}}
    <div class="border-b border-gray-200">
        <nav class="flex space-x-8" aria-label="Tabs">
            <button @click="activeTab = 'overview'"
                    :class="activeTab === 'overview' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors focus:outline-none">
                Overview
            </button>
            <button @click="activeTab = 'subscriptions'"
                    :class="activeTab === 'subscriptions' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors focus:outline-none">
                Subscriptions
            </button>
            <button @click="activeTab = 'reading'"
                    :class="activeTab === 'reading' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors focus:outline-none flex items-center gap-2">
                Reading History
                <span class="bg-gray-100 text-gray-600 py-0.5 px-2 rounded-full text-xs">{{ $member->ebookAccess->count() }}</span>
            </button>
            <button @click="activeTab = 'reviews'"
                    :class="activeTab === 'reviews' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors focus:outline-none flex items-center gap-2">
                Reviews
                <span class="bg-gray-100 text-gray-600 py-0.5 px-2 rounded-full text-xs">{{ $member->reviews->count() }}</span>
            </button>
        </nav>
    </div>

    {{-- ── Tab Contents ─────────────────────────────────────────────────── --}}
    <div class="mt-6">
        
        {{-- Tab: Overview --}}
        <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Member Info Card --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 lg:col-span-2">
                    <h3 class="font-semibold text-gray-900 mb-5 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Personal Details
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-6">
                        <div><dt class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Status</dt><dd>
                            @php $sc=['active'=>'bg-emerald-100 text-emerald-700 border-emerald-200','suspended'=>'bg-red-100 text-red-700 border-red-200','expired'=>'bg-gray-100 text-gray-600 border-gray-200']; @endphp
                            <span class="px-2.5 py-1 rounded-md text-xs font-semibold border {{ $sc[$member->status] ?? '' }}">{{ ucfirst($member->status) }}</span>
                        </dd></div>
                        <div><dt class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Joined</dt><dd class="text-gray-900 text-sm font-medium">{{ $member->created_at->format('M d, Y') }}</dd></div>
                        <div><dt class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Phone</dt><dd class="text-gray-900 text-sm">{{ $member->phone ?? '—' }}</dd></div>
                        <div><dt class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Address</dt><dd class="text-gray-900 text-sm">{{ $member->address ?? '—' }}</dd></div>
                        <div><dt class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Onboarding</dt><dd>
                            @if($member->onboarding_completed)
                                <span class="text-emerald-600 text-sm font-medium flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Completed
                                </span>
                            @else
                                <span class="text-amber-600 text-sm font-medium flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Pending (Step {{ $member->onboarding_step }})
                                </span>
                            @endif
                        </dd></div>
                    </div>
                    
                    @if(!empty($member->preferred_categories))
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-3">Preferred Interests</dt>
                            <dd class="flex flex-wrap gap-2">
                                @foreach(\App\Models\Category::whereIn('id', $member->preferred_categories)->get() as $cat)
                                    <span class="px-3 py-1 bg-indigo-50 text-indigo-700 border border-indigo-100 rounded-full text-xs font-medium">{{ $cat->name }}</span>
                                @endforeach
                            </dd>
                        </div>
                    @endif
                </div>

                {{-- Active Subscription Card --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col">
                    <h3 class="font-semibold text-gray-900 mb-5 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Current Plan
                    </h3>
                    @php $activeSub = $member->activeSubscription(); @endphp
                    @if($activeSub)
                        <div class="flex-1">
                            <div class="inline-flex items-center justify-center p-3 bg-indigo-50 rounded-xl mb-4">
                                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                            </div>
                            <h4 class="text-xl font-bold text-gray-900 mb-1">{{ $activeSub->plan->name }}</h4>
                            <p class="text-sm text-gray-500 mb-6">Active Subscription</p>
                            
                            <dl class="space-y-3">
                                <div class="flex justify-between items-center"><dt class="text-sm text-gray-500">Status</dt><dd class="text-sm font-semibold text-emerald-600">{{ ucfirst($activeSub->status) }}</dd></div>
                                <div class="flex justify-between items-center"><dt class="text-sm text-gray-500">Started</dt><dd class="text-sm font-medium text-gray-900">{{ $activeSub->started_at?->format('M d, Y') ?? '—' }}</dd></div>
                                <div class="flex justify-between items-center"><dt class="text-sm text-gray-500">Expires</dt><dd class="text-sm font-medium text-gray-900">{{ $activeSub->expires_at?->format('M d, Y') ?? 'Never' }}</dd></div>
                            </dl>
                        </div>
                    @else
                        <div class="flex-1 flex flex-col items-center justify-center text-center py-8">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <p class="text-gray-900 font-medium">No Active Plan</p>
                            <p class="text-sm text-gray-500 mt-1">This member does not have an active subscription.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Tab: Subscriptions --}}
        <div x-show="activeTab === 'subscriptions'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50/50 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Plan Details</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Duration</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($member->subscriptions as $sub)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $sub->plan->name }}</div>
                                    <div class="text-gray-500 text-xs mt-0.5">Plan ID: #{{ $sub->plan_id }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @php $sc=['active'=>'bg-emerald-50 text-emerald-700 ring-emerald-600/20','expired'=>'bg-gray-50 text-gray-600 ring-gray-500/10','cancelled'=>'bg-red-50 text-red-700 ring-red-600/10']; @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium ring-1 ring-inset {{ $sc[$sub->status] ?? '' }}">
                                        {{ ucfirst($sub->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    <div>{{ $sub->started_at?->format('M d, Y') ?? '—' }} <span class="text-gray-400 mx-1">→</span> {{ $sub->expires_at?->format('M d, Y') ?? 'Never' }}</div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="px-6 py-12 text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-50 mb-3"><svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></div>
                                <p class="text-gray-900 font-medium">No subscription history</p>
                                <p class="text-gray-500 text-sm">This member hasn't subscribed to any plans yet.</p>
                            </td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Tab: Reading History --}}
        <div x-show="activeTab === 'reading'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50/50 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Book Information</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Last Accessed</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($member->ebookAccess as $access)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.ebooks.show', $access->ebook->id) }}" class="font-medium text-indigo-600 hover:text-indigo-900">
                                        {{ $access->ebook->title ?? '—' }}
                                    </a>
                                    <div class="text-gray-500 text-xs mt-0.5">By {{ $access->ebook->authors->pluck('full_name')->join(', ') ?: 'Unknown Author' }}</div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $access->ebook->category->name ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $access->accessed_at?->format('M d, Y h:i A') ?? $access->created_at->format('M d, Y') }}
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="px-6 py-12 text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-50 mb-3"><svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg></div>
                                <p class="text-gray-900 font-medium">No reading history</p>
                                <p class="text-gray-500 text-sm">This member hasn't opened any books yet.</p>
                            </td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Tab: Reviews --}}
        <div x-show="activeTab === 'reviews'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($member->reviews as $review)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h4 class="font-medium text-gray-900 line-clamp-1">{{ $review->ebook->title ?? '—' }}</h4>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $review->created_at->format('M d, Y') }}</div>
                            </div>
                            @php $sc=['pending'=>'bg-amber-50 text-amber-700 ring-amber-600/20','approved'=>'bg-emerald-50 text-emerald-700 ring-emerald-600/20','rejected'=>'bg-red-50 text-red-700 ring-red-600/10']; @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ring-1 ring-inset {{ $sc[$review->status] ?? '' }}">
                                {{ ucfirst($review->status) }}
                            </span>
                        </div>
                        
                        <div class="flex items-center gap-1 mb-3">
                            @for($i=1; $i<=5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-amber-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        
                        <p class="text-sm text-gray-600 italic line-clamp-3">"{{ $review->comment ?? 'No comment provided.' }}"</p>
                        
                        <div class="mt-4 pt-4 border-t border-gray-50 flex justify-end">
                            <a href="{{ route('admin.reviews.index', ['search' => $member->user->email]) }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-1">
                                Manage Review
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-50 mb-3"><svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg></div>
                        <p class="text-gray-900 font-medium">No reviews written</p>
                        <p class="text-gray-500 text-sm">This member hasn't left any reviews yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
        
    </div>
</div>
@endsection
