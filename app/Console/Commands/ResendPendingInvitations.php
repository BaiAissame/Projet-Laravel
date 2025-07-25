<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserInvitation;
use App\Models\Project;
use App\Jobs\SendInvitationEmailJob;
use Carbon\Carbon;

class ResendPendingInvitations extends Command
{
  protected $signature = 'invitation:resend-pending {--days=3 : Nombre de jours avant relance}';
  protected $description = 'Relance automatiquement les invitations non acceptées après un certain nombre de jours';

  public function handle()
  {
    $days = (int) $this->option('days');
    $threshold = Carbon::now()->subDays($days);

    $pendingInvitations = UserInvitation::where('status', 'pending')
      ->where('created_at', '<=', $threshold)
      ->get();

    $count = 0;


    \Log::info('Invitations trouvées', ['count' => $pendingInvitations->count()]);

    foreach ($pendingInvitations as $invitation) {
      $project = Project::find($invitation->project_id);
      \Log::info('Traitement invitation', ['id' => $invitation->id, 'email' => $invitation->email]);

      if ($project) {
        dispatch(new SendInvitationEmailJob($invitation, $project, false));
        $count++;
      }
    }

    $this->info("{$count} invitation(s) relancées.");
  }
}
