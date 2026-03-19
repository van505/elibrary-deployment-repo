@extends('layouts.admin')
@section('title', 'Payment Details')

@section('content')
<div class="mb-6"><a href="{{ route('admin.payments.index') }}" class="text-blue-600 hover:underline text-sm">← Back</a></div>

<div class="bg-white rounded-xl shadow-sm p-6 max-w-lg">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Payment #{{ $payment->id }}</h2>
    <dl class="space-y-4 text-sm">
        <div><dt class="text-gray-500">Member</dt><dd class="font-medium">{{ $payment->member->user->name }}</dd></div>
        <div><dt class="text-gray-500">Ebook</dt><dd>{{ $payment->borrowing->ebook->title ?? '—' }}</dd></div>
        <div><dt class="text-gray-500">Amount</dt><dd class="font-bold text-lg text-green-600">₱{{ number_format($payment->amount, 2) }}</dd></div>
        <div><dt class="text-gray-500">Payment Type</dt><dd class="capitalize">{{ $payment->payment_type }}</dd></div>
        <div><dt class="text-gray-500">Status</dt>
            <dd>
                @php $pc = ['paid'=>'bg-green-100 text-green-700','pending'=>'bg-yellow-100 text-yellow-700','waived'=>'bg-gray-100 text-gray-500']; @endphp
                <span class="px-2 py-1 rounded text-xs font-medium {{ $pc[$payment->payment_status] ?? '' }}">{{ $payment->payment_status }}</span>
            </dd>
        </div>
        <div><dt class="text-gray-500">Reference No.</dt><dd>{{ $payment->reference_no ?? '—' }}</dd></div>
        <div><dt class="text-gray-500">Paid At</dt><dd>{{ $payment->paid_at?->format('M d, Y H:i') ?? '—' }}</dd></div>
    </dl>
</div>
@endsection
