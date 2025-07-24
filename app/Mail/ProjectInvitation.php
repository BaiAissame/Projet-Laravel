<?php

namespace App\Mail;

use App\Models\Project;
use App\Models\UserInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class ProjectInvitation extends Mailable
{
    use Queueable, SerializesModels;


    public function __construct(
        public Project $project,
        public UserInvitation $invitation,
        public bool $userExists
    ) {}


    public function build()
    {
        $acceptUrl = URL::signedRoute('project.invitation.accept', [
            'invitation' => $this->invitation->id,
        ]);

        $registerUrl = route('register') . '?invitation=' . $this->invitation->id;

        return $this->view('emails.projects.invitation')
            ->with([
                'project' => $this->project,
                'invitation' => $this->invitation,
                'acceptUrl' => $acceptUrl,
                'registerUrl' => $registerUrl,
                'userExists' => $this->userExists
            ])
            ->subject('Invitation pour collaborer sur un projet : ' . $this->project->name);
    }
} 