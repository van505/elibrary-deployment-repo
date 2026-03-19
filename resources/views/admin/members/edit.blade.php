@extends('layouts.admin')
@section('title', 'Edit Member')

@section('content')
<div class="mb-6"><a href="{{ route('admin.members.index') }}" class="text-blue-600 hover:underline text-sm">← Back</a></div>

<div class="bg-white rounded-xl shadow-sm p-6 max-w-lg">
    <h2 class="text-xl font-bold text-gray-800 mb-2">Edit Member</h2>
    <p class="text-sm text-gray-500 mb-6">{{ $member->user->name }} — {{ $member->member_code }}</p>
    <form action="{{ route('admin.members.update', $member) }}" method="POST">
        @csrf @method('PUT')
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach(['active','suspended','expired'] as $s)
                    <option value="{{ $s }}" {{ old('status', $member->status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                @error('status')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $member->phone) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Membership Expiry</label>
                <input type="date" name="membership_expiry" value="{{ old('membership_expiry', $member->membership_expiry?->format('Y-m-d')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <textarea name="address" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('address', $member->address) }}</textarea>
            </div>
        </div>
        <div class="flex gap-3 mt-6">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">Save Changes</button>
            <a href="{{ route('admin.members.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm">Cancel</a>
        </div>
    </form>
</div>
@endsection
