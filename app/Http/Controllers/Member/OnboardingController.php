<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Ebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OnboardingController extends Controller
{
    private function getMember()
    {
        $user = auth()->user();
        return $user->member;
    }

    public function show()
    {
        $member = $this->getMember();

        if ($member && $member->onboarding_completed) {
            return redirect()->route('member.dashboard');
        }

        $step = $member ? ($member->onboarding_step ?? 1) : 1;
        $categories = Category::orderBy('name')->get();

        // For step 3: show recommended ebooks based on preferences
        $recommendedEbooks = collect();
        if ($step === 3 && $member && !empty($member->preferred_categories)) {
            $recommendedEbooks = Ebook::with('authors')
                ->whereIn('category_id', $member->preferred_categories)
                ->latest()
                ->take(4)
                ->get();
        }

        return view('member.onboarding.index', compact('member', 'step', 'categories', 'recommendedEbooks'));
    }

    public function saveStep1(Request $request)
    {
        $member = $this->getMember();

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'phone'      => 'nullable|string|max:20',
            'avatar'     => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            // Delete old avatar if present
            if ($member->avatar) {
                Storage::disk('public')->delete($member->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $member->update(array_merge($validated, ['onboarding_step' => 2]));

        return redirect()->route('member.onboarding.show');
    }

    public function saveStep2(Request $request)
    {
        $member = $this->getMember();

        $request->validate([
            'preferred_categories'   => 'required|array|min:1',
            'preferred_categories.*' => 'exists:categories,id',
        ]);

        $member->update([
            'preferred_categories' => $request->preferred_categories,
            'onboarding_step'      => 3,
        ]);

        return redirect()->route('member.onboarding.show');
    }

    public function saveStep3(Request $request)
    {
        $member = $this->getMember();

        $member->update(['onboarding_completed' => true]);

        return redirect()->route('member.dashboard')->with('welcome_onboard', true);
    }

    public function skip(Request $request)
    {
        $member = $this->getMember();
        $member->update(['onboarding_completed' => true]);

        return redirect()->route('member.dashboard')->with('welcome_onboard', true);
    }
}
