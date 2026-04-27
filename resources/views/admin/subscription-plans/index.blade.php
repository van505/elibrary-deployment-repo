@extends('layouts.admin')
@section('title', 'Subscription Plans')

@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Subscription Plans</h1>
            <p class="text-gray-500 text-sm mt-1">Manage available membership plans and pricing</p>
        </div>
    </div>
    <x-admin.filter-bar :action="route('admin.subscription-plans.index')" searchPlaceholder="Search plan name..." :sortable="['created_at' => 'Date Added', 'name' => 'Name', 'price' => 'Price']" :createRoute="route('admin.subscription-plans.create')" createLabel="Add Plan">
    </x-admin.filter-bar>
    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Ebook Limit</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Subscribers</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($plans as $plan)
                @php
                    $isFree    = stripos($plan->name, 'free') !== false;
                    $isPremium = stripos($plan->name, 'premium') !== false;
                @endphp
                <tr class="hover:bg-gray-50/60 transition-colors duration-200">
                    <td class="px-4 py-4">
                        <div class="flex items-center gap-3">
                            @if($isFree)
                                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                </div>
                            @elseif($isPremium)
                                <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a1 1 0 00-1.832.514L1.077 11H3a1 1 0 01.832.445l1.5 2.25 2.25-4.5a1 1 0 011.789.051l1.5 3 1.118-2.236A1 1 0 0113 10h2.5L13.832 4.514A1 1 0 0012 4a1 1 0 00-.832.445L10 7.118 8.832 4.445A1 1 0 008 4H5z"/></svg>
                                </div>
                            @else
                                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                </div>
                            @endif
                            <span class="font-bold text-gray-900">{{ $plan->name }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-4"><span class="text-gray-400 text-xs">₱</span><span class="font-medium text-gray-900">{{ number_format($plan->price, 2) }}</span></td>
                    <td class="px-4 py-4 text-gray-500">{{ $plan->ebook_limit === -1 ? 'Unlimited' : $plan->ebook_limit }}</td>
                    <td class="px-4 py-4">
                        @if($plan->is_active)
                            <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 px-2.5 py-0.5 rounded-full text-xs font-medium">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span> Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-500 px-2.5 py-0.5 rounded-full text-xs font-medium">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400 inline-block"></span> Inactive
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-4"><span class="bg-gray-100 text-gray-700 rounded-md px-2 py-1 text-xs font-semibold">{{ $plan->subscriptions_count }}</span></td>
                    <td class="px-4 py-4">
                        <div class="flex items-center gap-1">
                            <a href="{{ route('admin.subscription-plans.edit', $plan) }}"
                               class="p-1.5 text-indigo-500 hover:text-indigo-700 rounded-lg transition-colors duration-200" title="Edit">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form action="{{ route('admin.subscription-plans.destroy', $plan) }}" method="POST" onsubmit="return confirm('Delete this plan?')" class="inline-block">
                                @csrf @method('DELETE')
                                <button type="submit" title="Delete" class="p-1.5 text-rose-500 hover:text-rose-700 rounded-lg transition-colors duration-200">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-10 text-center text-gray-400">No plans found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">{{ $plans->links() }}</div>
    </div>
</div>
@endsection
