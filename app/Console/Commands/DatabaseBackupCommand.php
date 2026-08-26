<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class DatabaseBackupCommand extends Command
{
    protected $signature = 'db:backup {--keep=20 : Number of latest backups to keep}';
    protected $description = 'Creates a compressed SQL backup of the current database before deployments.';

    public function handle(): int
    {
        $backupDir = storage_path('backups');
        if (!File::isDirectory($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        $connection = config('database.default');
        $config = config("database.connections.{$connection}");

        $timestamp = now()->format('Y-m-d_His');
        $filename = "db_backup_{$connection}_{$timestamp}.sql.gz";
        $filepath = "{$backupDir}/{$filename}";

        $this->info("Creating backup for [{$connection}] database...");

        try {
            $success = match ($config['driver'] ?? $connection) {
                'pgsql' => $this->backupPostgreSql($config, $filepath),
                'mysql', 'mariadb' => $this->backupMySql($config, $filepath),
                'sqlite' => $this->backupSqlite($config, $filepath),
                default => throw new \RuntimeException("Unsupported database driver: " . ($config['driver'] ?? $connection)),
            };

            if ($success && File::exists($filepath)) {
                $size = round(File::size($filepath) / 1024 / 1024, 2);
                $this->info("✅ Database backup created: {$filepath} ({$size} MB)");
                $this->pruneOldBackups($backupDir, (int) $this->option('keep'));
                return Command::SUCCESS;
            }

            $this->error("❌ Backup file was not created.");
            return Command::FAILURE;
        } catch (\Throwable $e) {
            $this->error("❌ Database backup failed: " . $e->getMessage());
            return Command::FAILURE;
        }
    }

    protected function backupPostgreSql(array $config, string $filepath): bool
    {
        $host = $config['host'] ?? '127.0.0.1';
        $port = $config['port'] ?? 5432;
        $database = $config['database'];
        $username = $config['username'];
        $password = $config['password'] ?? '';

        $cmd = sprintf(
            'PGPASSWORD=%s pg_dump -h %s -p %s -U %s -d %s | gzip > %s',
            escapeshellarg($password),
            escapeshellarg($host),
            escapeshellarg($port),
            escapeshellarg($username),
            escapeshellarg($database),
            escapeshellarg($filepath)
        );

        $process = Process::fromShellCommandline($cmd);
        $process->setTimeout(300);
        $process->run();

        return $process->isSuccessful();
    }

    protected function backupMySql(array $config, string $filepath): bool
    {
        $host = $config['host'] ?? '127.0.0.1';
        $port = $config['port'] ?? 3306;
        $database = $config['database'];
        $username = $config['username'];
        $password = $config['password'] ?? '';

        $cmd = sprintf(
            'mysqldump --host=%s --port=%s --user=%s --password=%s %s | gzip > %s',
            escapeshellarg($host),
            escapeshellarg($port),
            escapeshellarg($username),
            escapeshellarg($password),
            escapeshellarg($database),
            escapeshellarg($filepath)
        );

        $process = Process::fromShellCommandline($cmd);
        $process->setTimeout(300);
        $process->run();

        return $process->isSuccessful();
    }

    protected function backupSqlite(array $config, string $filepath): bool
    {
        $dbPath = $config['database'];
        if (!File::exists($dbPath)) {
            return false;
        }

        $cmd = sprintf('gzip -c %s > %s', escapeshellarg($dbPath), escapeshellarg($filepath));
        $process = Process::fromShellCommandline($cmd);
        $process->setTimeout(120);
        $process->run();

        return $process->isSuccessful();
    }

    protected function pruneOldBackups(string $backupDir, int $keep): void
    {
        $files = File::files($backupDir);
        
        // Sort files by modification time descending (newest first)
        usort($files, fn($a, $b) => $b->getMTime() <=> $a->getMTime());

        if (count($files) > $keep) {
            $toDelete = array_slice($files, $keep);
            foreach ($toDelete as $file) {
                File::delete($file->getRealPath());
                $this->line("   🗑️ Pruned old backup: " . $file->getFilename());
            }
        }
    }
}
