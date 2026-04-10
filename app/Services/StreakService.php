<?php

namespace App\Services;

use App\Models\Member;
use Carbon\Carbon;

class StreakService
{
    /**
     * Update the continuous reading streak for a member.
     *
     * @param Member $member
     * @return void
     */
    public static function updateStreak(Member $member)
    {
        $today = Carbon::today();
        
        if ($member->last_read_date) {
            $lastRead = Carbon::parse($member->last_read_date)->startOfDay();
            
            if ($lastRead->isToday()) {
                // Already read today, do nothing
                return;
            } elseif ($lastRead->isYesterday()) {
                // Sequential day, increment streak
                $member->current_streak += 1;
            } else {
                // Streak broken (more than 1 day ago)
                $member->current_streak = 1;
            }
        } else {
            // First time reading
            $member->current_streak = 1;
        }

        // Update the last read date to today
        $member->last_read_date = $today->toDateString();

        // Update longest streak
        if ($member->current_streak > $member->longest_streak) {
            $member->longest_streak = $member->current_streak;
        }

        $member->save();
    }
}
