@extends('layouts.admin')
@section('title', 'New Borrowing')

@section('content')
<div class="mb-6"><a href="{{ route('admin.borrowings.index') }}" class="text-blue-600 hover:underline text-sm">← Back</a></div>

<div class="bg-white rounded-xl shadow-sm p-6 max-w-lg">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Create New Borrowing</h2>
    <form action="{{ route('admin.borrowings.store') }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Member <span class="text-red-500">*</span></label>
                <select name="member_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Select Member</option>
                    @foreach($members as $member)
                    <option value="{{ $member->id }}">{{ $member->user->name }} ({{ $member->member_code }})</option>
                    @endforeach
                </select>
                @error('member_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ebook <span class="text-red-500">*</span></label>
                <select name="ebook_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Select Ebook</option>
                    @foreach($ebooks as $ebook)
                    <option value="{{ $ebook->id }}">{{ $ebook->title }} ({{ $ebook->available_copies }} available)</option>
                    @endforeach
                </select>
                @error('ebook_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Borrow Date <span class="text-red-500">*</span></label>
                <input type="date" name="borrow_date" value="{{ old('borrow_date', now()->format('Y-m-d')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @error('borrow_date')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Due Date <span class="text-red-500">*</span></label>
                <input type="date" name="due_date" value="{{ old('due_date', now()->addDays(7)->format('Y-m-d')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @error('due_date')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
        <div class="flex gap-3 mt-6">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">Create Borrowing</button>
            <a href="{{ route('admin.borrowings.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm">Cancel</a>
        </div>
    </form>
</div>
@endsection
