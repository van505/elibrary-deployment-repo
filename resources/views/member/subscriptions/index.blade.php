@extends('layouts.member')
@section('title', 'My Subscription')

@push('breadcrumbs')
<nav class="flex items-center text-sm">
    <a href="{{ route('member.dashboard') }}" class="text-gray-400 hover:text-gray-600 transition-colors duration-150">Home</a>
    <span class="text-gray-300 mx-1.5 select-none">›</span>
    <span class="text-gray-400 mx-1.5">Profile</span>
    <span class="text-gray-300 mx-1.5 select-none">›</span>
    <span class="text-gray-700 font-medium">My Subscription</span>
</nav>
@endpush

@section('content')
<div class="space-y-6" x-data="{ showCheckoutModal: false, selectedPlanId: '', selectedPlanName: '', selectedPlanPrice: '' }">

    <div>
        <h1 class="text-2xl font-bold text-gray-800">Choose Your Plan</h1>
        <p class="text-gray-500 text-sm mt-1">Upgrade anytime. Downgrades are not permitted — plans must stay active until expiry.</p>
    </div>

    {{-- Current Plan Banner --}}
    @if($currentSubscription)
    @php $currentPlan = $currentSubscription->plan; @endphp
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div>
            <p class="text-sm font-semibold text-blue-800">You are on the {{ $currentPlan->name }} Plan</p>
            <p class="text-xs text-blue-600 mt-0.5">
                @if($currentSubscription->expires_at)
                    Expires {{ $currentSubscription->expires_at->format('M d, Y') }}.
                    You may only upgrade to a higher-tier plan.
                @else
                    No expiry. You may upgrade to a higher plan anytime.
                @endif
            </p>
        </div>
    </div>
    @endif

    {{-- Pricing Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        @foreach($plans as $plan)
        @php
            $isCurrent   = $currentSubscription && $currentSubscription->plan_id === $plan->id;
            $isDowngrade = $currentSubscription && $plan->level <= $currentSubscription->plan->level && !$isCurrent;
            $isPremium   = $plan->slug === 'premium';
            $borderClass = $isPremium ? 'border-purple-400 ring-2 ring-purple-200' : 'border-gray-200';
            $btnClass    = match($plan->slug) {
                'premium' => 'bg-purple-600 hover:bg-purple-700',
                'basic'   => 'bg-blue-600 hover:bg-blue-700',
                default   => 'bg-gray-600 hover:bg-gray-700',
            };
        @endphp
        <div class="relative bg-white rounded-2xl shadow-sm border {{ $borderClass }} p-6 flex flex-col {{ $isDowngrade ? 'opacity-60' : '' }}">

            @if($isPremium)
                <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-purple-600 text-white text-xs font-bold px-3 py-1 rounded-full">Most Popular</span>
            @endif

            @if($isCurrent)
                <span class="absolute top-4 right-4 bg-green-100 text-green-700 text-xs font-semibold px-2 py-0.5 rounded-full">Current Plan</span>
            @elseif($isDowngrade)
                <span class="absolute top-4 right-4 bg-gray-100 text-gray-400 text-xs font-semibold px-2 py-0.5 rounded-full">Unavailable</span>
            @else
                <span class="absolute top-4 right-4 bg-blue-100 text-blue-700 text-xs font-semibold px-2 py-0.5 rounded-full">Upgrade ↑</span>
            @endif

            <div class="mb-4">
                <h3 class="text-xl font-bold text-gray-800">{{ $plan->name }}</h3>
                <div class="mt-2">
                    <span class="text-3xl font-bold text-gray-900">₱{{ number_format($plan->price, 0) }}</span>
                    <span class="text-gray-500 text-sm">/month</span>
                </div>
                <p class="text-sm text-gray-500 mt-2">{{ $plan->description }}</p>
            </div>

            <ul class="space-y-2 mb-6 flex-1">
                <li class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ $plan->ebook_limit === -1 ? 'Unlimited ebooks' : 'Up to ' . $plan->ebook_limit . ' ebooks' }}
                </li>
                <li class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    @if($plan->slug === 'free') Free ebooks only
                    @elseif($plan->slug === 'basic') Free + Basic ebooks
                    @else All access levels @endif
                </li>
                <li class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    PDF, EPUB &amp; MP3 formats
                </li>
            </ul>

            @if($isCurrent)
                <div class="bg-emerald-50 text-emerald-700 border border-emerald-200 cursor-not-allowed font-semibold w-full py-2 rounded-lg text-center mt-auto">
                    ✓ Current Plan
                </div>
            @elseif($isDowngrade)
                <div class="bg-gray-50 text-gray-400 border border-gray-200 cursor-not-allowed font-semibold w-full py-2 rounded-lg text-center mt-auto">
                    Not Available
                </div>
                <p class="text-[11px] text-gray-400 text-center mt-2">
                    Wait until your {{ $currentSubscription->plan->name }} plan expires
                    @if($currentSubscription->expires_at) on {{ $currentSubscription->expires_at->format('M d, Y') }} @endif
                </p>
            @else
                <button type="button" 
                        @click="showCheckoutModal = true; selectedPlanId = '{{ $plan->id }}'; selectedPlanName = '{{ $plan->name }} Plan'; selectedPlanPrice = '{{ $plan->price == 0 ? 'Free' : '₱' . number_format($plan->price, 0) . ' / month' }}'"
                        class="w-full {{ $btnClass }} text-white py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm mt-auto">
                    {{ $plan->price == 0 ? 'Get Started Free' : 'Subscribe — ₱' . number_format($plan->price, 0) . '/mo' }}
                </button>
            @endif
        </div>
        @endforeach

    </div>

    {{-- FAQ Section --}}
    <div class="mt-12 mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Frequently Asked Questions</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="text-sm font-bold text-gray-900">How do cash payments work?</h4>
                <p class="text-sm text-gray-600 mt-1">If you select cash, your account will remain pending until you visit the librarian in person to settle your payment. Once paid, the admin will activate your access immediately.</p>
            </div>
            <div>
                <h4 class="text-sm font-bold text-gray-900">Can I switch plans mid-month?</h4>
                <p class="text-sm text-gray-600 mt-1">You can upgrade to a higher tier at any time! However, downgrading to a lower tier is not permitted until your current subscription period has officially expired.</p>
            </div>
            <div>
                <h4 class="text-sm font-bold text-gray-900">What happens to my downloads if I downgrade?</h4>
                <p class="text-sm text-gray-600 mt-1">If your Premium plan expires and you revert to Free, you will lose access to read Premium-tier ebooks, even if you had them saved to your wishlist or reading history.</p>
            </div>
            <div>
                <h4 class="text-sm font-bold text-gray-900">Are there any hidden fees?</h4>
                <p class="text-sm text-gray-600 mt-1">Absolutely not. The price you see on the plan is exactly what you pay per month. There are no additional transaction fees or hidden charges applied by the library system.</p>
            </div>
        </div>
    </div>

    {{-- Subscription History --}}
    @if($history->count())
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="font-semibold text-gray-800 mb-4">Subscription History</h2>
        <table class="w-full text-sm">
            <thead class="text-gray-500 text-xs uppercase">
                <tr>
                    <th class="text-left pb-2">Plan</th>
                    <th class="text-left pb-2">Status</th>
                    <th class="text-left pb-2">Started</th>
                    <th class="text-left pb-2">Expired / Cancelled</th>
                </tr>
            </thead>
            <tbody>
                @foreach($history as $sub)
                <tr class="border-t border-gray-100">
                    <td class="py-2 font-medium text-gray-700">{{ $sub->plan->name }}</td>
                    <td class="py-2"><span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">{{ $sub->status }}</span></td>
                    <td class="py-2 text-gray-500">{{ $sub->started_at?->format('M d, Y') ?? '—' }}</td>
                    <td class="py-2 text-gray-500">{{ $sub->expires_at?->format('M d, Y') ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Checkout Modal --}}
    <div x-show="showCheckoutModal" style="display: none;" class="fixed inset-0 z-[9999] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
         
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden" @click.away="showCheckoutModal = false"
             x-show="showCheckoutModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
             
            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">Complete Your Upgrade</h3>
                <button type="button" @click="showCheckoutModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <form action="{{ route('member.subscriptions.subscribe') }}" method="POST">
                @csrf
                <input type="hidden" name="plan_id" x-bind:value="selectedPlanId">
                
                <div class="p-6">
                    {{-- Order Summary --}}
                    <div class="bg-slate-50 p-4 rounded-xl mb-6 border border-slate-100 flex items-center justify-between">
                        <div>
                            <p class="font-bold text-gray-900" x-text="selectedPlanName"></p>
                            <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md mt-1 inline-block">Billed Monthly</span>
                        </div>
                        <p class="font-bold text-gray-900" x-text="selectedPlanPrice"></p>
                    </div>
                    
                    {{-- Payment Method Selection --}}
                    <div x-data="{ selectedPayment: 'gcash' }">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Select Payment Method</label>
                        <div class="space-y-3">
                            <label class="border rounded-lg p-3 flex items-center gap-3 cursor-pointer transition-colors" :class="{ 'border-indigo-500 bg-indigo-50': selectedPayment === 'gcash', 'border-gray-200 hover:border-indigo-500 hover:bg-indigo-50': selectedPayment !== 'gcash' }">
                                <input type="radio" name="payment_method" value="gcash" x-model="selectedPayment" class="sr-only">
                                <div class="w-5 h-5 rounded-full border flex items-center justify-center flex-shrink-0" :class="{ 'border-indigo-500': selectedPayment === 'gcash', 'border-gray-300': selectedPayment !== 'gcash' }">
                                    <div class="w-3 h-3 rounded-full bg-indigo-600" x-show="selectedPayment === 'gcash'"></div>
                                </div>
                                <div class="flex-1">
                                    <span class="block text-sm font-medium text-gray-900">GCash / E-Wallet</span>
                                </div>
                            </label>
                            
                            <label class="border rounded-lg p-3 flex items-center gap-3 cursor-pointer transition-colors" :class="{ 'border-indigo-500 bg-indigo-50': selectedPayment === 'cash', 'border-gray-200 hover:border-indigo-500 hover:bg-indigo-50': selectedPayment !== 'cash' }">
                                <input type="radio" name="payment_method" value="cash" x-model="selectedPayment" class="sr-only">
                                <div class="w-5 h-5 rounded-full border flex items-center justify-center flex-shrink-0" :class="{ 'border-indigo-500': selectedPayment === 'cash', 'border-gray-300': selectedPayment !== 'cash' }">
                                    <div class="w-3 h-3 rounded-full bg-indigo-600" x-show="selectedPayment === 'cash'"></div>
                                </div>
                                <div class="flex-1">
                                    <span class="block text-sm font-medium text-gray-900">Cash at Library</span>
                                </div>
                            </label>
                            
                            <label class="border rounded-lg p-3 flex items-center gap-3 cursor-pointer transition-colors" :class="{ 'border-indigo-500 bg-indigo-50': selectedPayment === 'credit_card', 'border-gray-200 hover:border-indigo-500 hover:bg-indigo-50': selectedPayment !== 'credit_card' }">
                                <input type="radio" name="payment_method" value="credit_card" x-model="selectedPayment" class="sr-only">
                                <div class="w-5 h-5 rounded-full border flex items-center justify-center flex-shrink-0" :class="{ 'border-indigo-500': selectedPayment === 'credit_card', 'border-gray-300': selectedPayment !== 'credit_card' }">
                                    <div class="w-3 h-3 rounded-full bg-indigo-600" x-show="selectedPayment === 'credit_card'"></div>
                                </div>
                                <div class="flex-1">
                                    <span class="block text-sm font-medium text-gray-900">Credit Card</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
                
                {{-- Modal Footer --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                    <button type="button" @click="showCheckoutModal = false" class="text-gray-500 hover:text-gray-700 font-medium px-4 py-2 text-sm transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-6 rounded-lg flex justify-center items-center gap-2 transition-colors flex-1 sm:flex-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Confirm Upgrade
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
