@extends('layouts.admin')
@section('title', 'Subscription Plans')

@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold text-gray-800">Subscription Plans</h1>
        <a href="{{ route('admin.subscription-plans.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">+ Add Plan</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left font-semibold text-gray-600 px-6 py-3">Name</th>
                    <th class="text-left font-semibold text-gray-600 px-6 py-3">Price</th>
                    <th class="text-left font-semibold text-gray-600 px-6 py-3">Ebook Limit</th>
                    <th class="text-left font-semibold text-gray-600 px-6 py-3">Status</th>
                    <th class="text-left font-semibold text-gray-600 px-6 py-3">Subscribers</th>
                    <th class="text-left font-semibold text-gray-600 px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($plans as $plan)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-800">
                        {{ $plan->name }}
                        <div class="text-xs text-gray-400">{{ $plan->slug }}</div>
                    </td>
                    <td class="px-6 py-4 text-gray-700">₱{{ number_format($plan->price, 2) }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $plan->ebook_limit === -1 ? 'Unlimited' : $plan->ebook_limit }}</td>
                    <td class="px-6 py-4">
                        @if($plan->is_active)
                            <span class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-0.5 rounded-full">Active</span>
                        @else
                            <span class="bg-red-100 text-red-700 text-xs font-semibold px-2 py-0.5 rounded-full">Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-700">{{ $plan->subscriptions_count }}</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.subscription-plans.edit', $plan) }}" class="text-blue-600 hover:underline text-xs">Edit</a>
                            <form action="{{ route('admin.subscription-plans.destroy', $plan) }}" method="POST" onsubmit="return confirm('Delete this plan?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline text-xs">Delete</button>
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
