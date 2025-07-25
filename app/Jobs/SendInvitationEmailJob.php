<?php

namespace App\Jobs;

use App\Models\Project;
use App\Models\UserInvitation;
use App\Mail\ProjectInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendInvitationEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $invitation;
    public $project;
    public $userExists;

    public $tries = 3;


    public $timeout = 120;

    public function __construct(UserInvitation $invitation, Project $project, bool $userExists = false)
    {
        $this->invitation = $invitation;
        $this->project = $project;
        $this->userExists = $userExists;
    }

    public function handle(): void
    {
        try {
            if ($this->invitation->status !== 'pending') {
                Log::warning('Tentative d\'envoi d\'email pour une invitation non-pending', [
                    'invitation_id' => $this->invitation->id,
                    'status' => $this->invitation->status
                ]);
                return;
            }

            Mail::to($this->invitation->email)
                ->send(new ProjectInvitation($this->project, $this->invitation, $this->userExists));

            Log::info('Email d\'invitation envoyé avec succès', [
                'invitation_id' => $this->invitation->id,
                'email' => $this->invitation->email,
                'project_id' => $this->project->id
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de l\'email d\'invitation', [
                'invitation_id' => $this->invitation->id,
                'email' => $this->invitation->email,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Échec définitif de l\'envoi de l\'email d\'invitation', [
            'invitation_id' => $this->invitation->id,
            'email' => $this->invitation->email,
            'error' => $exception->getMessage()
        ]);
    }
}