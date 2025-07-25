<?php

namespace App\Services;

use App\Models\UserInvitation;
use Illuminate\Support\Facades\Session;

class InvitationService
{

    public static function storePendingInvitation(int $invitationId): void
    {
        Session::put('pending_invitation_id', $invitationId);
    }


    public static function getPendingInvitationId(): ?int
    {
        return Session::get('pending_invitation_id');
    }


    public static function clearPendingInvitation(): void
    {
        Session::forget('pending_invitation_id');
    }


    public static function hasPendingInvitation(): bool
    {
        return Session::has('pending_invitation_id');
    }

    public static function getPendingInvitation(): ?UserInvitation
    {
        $invitationId = self::getPendingInvitationId();
        if (!$invitationId) {
            return null;
        }

        return UserInvitation::with(['project', 'inviter'])->find($invitationId);
    }
}