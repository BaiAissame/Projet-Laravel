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

    /**
     * Le nombre de tentatives pour ce job
     */
    public $tries = 3;

    /**
     * Le timeout en secondes pour ce job
     */
    public $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct(UserInvitation $invitation, Project $project, bool $userExists = false)
    {
        $this->invitation = $invitation;
        $this->project = $project;
        $this->userExists = $userExists;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Vérifier que l'invitation est toujours valide
            if ($this->invitation->status !== 'pending') {
                Log::warning('Tentative d\'envoi d\'email pour une invitation non-pending', [
                    'invitation_id' => $this->invitation->id,
                    'status' => $this->invitation->status
                ]);
                return;
            }

            // Envoyer l'email d'invitation
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

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Échec définitif de l\'envoi de l\'email d\'invitation', [
            'invitation_id' => $this->invitation->id,
            'email' => $this->invitation->email,
            'error' => $exception->getMessage()
        ]);
    }
}