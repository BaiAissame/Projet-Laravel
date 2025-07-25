<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Exports\ProjectsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ExportProjectsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;
    protected $filename;

    public function __construct($userId, $filename)
    {
        $this->userId = $userId;
        $this->filename = $filename;
    }

    public function handle()
    {
        // Génère le fichier Excel
        $export = new ProjectsExport();
        
        // Stocke le fichier dans le storage public
        Excel::store($export, 'exports/' . $this->filename, 'public');
    }
}