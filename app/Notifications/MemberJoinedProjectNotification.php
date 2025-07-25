<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Project;
use App\Models\User;

class MemberJoinedProjectNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $project;
    protected $newMember;

    public $tries = 3;
    public $timeout = 120;

    public function __construct(Project $project, User $newMember)
    {
        $this->project = $project;
        $this->newMember = $newMember;

        $this->onQueue('notifications');
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Nouveau membre ajouté : {$this->project->name}")
            ->greeting("Bonjour {$notifiable->name} !")
            ->line("Un nouveau membre a rejoint votre projet :")
            ->line("**Projet :** {$this->project->name}")
            ->line("**Nouveau membre :** {$this->newMember->name} ({$this->newMember->email})")
            ->line("**Date d'adhésion :** " . now()->format('j F Y \à G:i'))
            ->action('Voir le projet', url("/projects/{$this->project->id}"))
            ->line('Souhaitez la bienvenue au nouveau membre de l\'équipe !');
    }


    public function backoff(): array
    {
        return [1, 5, 10];
    }
}