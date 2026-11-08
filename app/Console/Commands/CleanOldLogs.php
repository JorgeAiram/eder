<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;

class CleanOldLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitoring:clean-logs {--days= : Número de días de antigüedad para conservar los logs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Elimina automáticamente archivos de logs antiguos que exceden el período de retención configurado.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = (int) ($this->option('days') ?: config('monitoring.retention_days', 14));
        $cutoffDate = Carbon::now()->subDays($days)->startOfDay();

        $this->info('Iniciando rotación y limpieza de logs anteriores a: '.$cutoffDate->toDateString()." ({$days} días de retención)");

        $logPath = storage_path('logs');
        $files = File::glob("{$logPath}/laravel-*.log");

        $deletedCount = 0;
        $retainedCount = 0;

        foreach ($files as $file) {
            $filename = basename($file);

            // Extract date if daily log
            $fileDate = null;
            if (preg_match('/laravel-(?:json-)?(\d{4}-\d{2}-\d{2})\.log$/', $filename, $matches)) {
                $fileDate = Carbon::createFromFormat('Y-m-d', $matches[1])->startOfDay();
            } else {
                // Use file modification time
                $fileDate = Carbon::createFromTimestamp(filemtime($file))->startOfDay();
            }

            if ($fileDate && $fileDate->lt($cutoffDate)) {
                $fileSize = round(filesize($file) / 1024, 2);
                File::delete($file);
                $this->warn("Eliminado log antiguo: {$filename} ({$fileSize} KB, fecha: {$fileDate->toDateString()})");
                $deletedCount++;
            } else {
                $retainedCount++;
            }
        }

        $this->info("Limpieza finalizada con éxito. Eliminados: {$deletedCount} archivo(s), Conservados: {$retainedCount} archivo(s).");

        return Command::SUCCESS;
    }
}
