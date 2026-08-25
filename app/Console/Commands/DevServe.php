<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

#[Signature('dev:serve {--host=127.0.0.1 : Server host} {--port=8002 : Server port} {--foreground : Run the server in the foreground} {--no-build : Skip the asset build step}')]
#[Description('Build front-end assets and run the dev server')]
class DevServe extends Command
{
    public function handle(): int
    {
        $host = (string) $this->option('host');
        $port = (int) $this->option('port');

        if (! $this->option('no-build')) {
            if (! $this->buildAssets()) {
                return self::FAILURE;
            }
        }

        $this->stopPortListeners($port);

        if ($this->option('foreground')) {
            return $this->serveInForeground($host, $port);
        }

        return $this->serveInBackground($host, $port);
    }

    /**
     * Run both front-end build pipelines (legacy assets + Vite).
     */
    protected function buildAssets(): bool
    {
        foreach (['assets:build', 'build'] as $script) {
            $this->line('');
            $this->info("Running npm run {$script} ...");

            $process = new Process(['npm', 'run', $script], base_path());
            $process->setTimeout(600);

            $process->run(function (string $type, string $buffer): void {
                $this->output->write($buffer);
            });

            if (! $process->isSuccessful()) {
                $this->error("npm run {$script} failed.");

                return false;
            }
        }

        return true;
    }

    /**
     * Kill whatever is currently listening on the target port so the
     * newly started server can bind to it.
     */
    protected function stopPortListeners(int $port): void
    {
        $pids = $this->findPidsOnPort($port);

        if ($pids === []) {
            return;
        }

        $this->line('');
        $this->warn("Port {$port} is already in use, stopping: ".implode(', ', $pids));

        foreach ($pids as $pid) {
            exec('kill '.((int) $pid));
        }

        usleep(400000); // give the process time to release the port
    }

    protected function findPidsOnPort(int $port): array
    {
        // lsof -ti tcp:PORT returns the PID(s) bound to the port.
        exec("lsof -ti tcp:{$port} 2>/dev/null", $output, $code);

        if ($code === 0 && $output !== []) {
            return array_values(array_filter($output, 'is_numeric'));
        }

        // Fallback: match artisan serve processes on that port.
        exec("pgrep -f 'artisan serve --port={$port}' 2>/dev/null", $output);

        return array_values(array_filter($output, 'is_numeric'));
    }

    protected function serveInForeground(string $host, int $port): int
    {
        $this->line('');
        $this->info("Starting server at http://{$host}:{$port} (foreground)");

        return $this->call('serve', [
            '--host' => $host,
            '--port' => (string) $port,
        ]);
    }

    protected function serveInBackground(string $host, int $port): int
    {
        $log = storage_path("logs/serve-{$port}.log");

        $command = sprintf(
            'setsid nohup php artisan serve --host=%s --port=%d > %s 2>&1 < /dev/null &',
            escapeshellarg($host),
            $port,
            escapeshellarg($log)
        );

        $this->line('');
        $this->info("Starting server at http://{$host}:{$port} (background)...");
        exec($command);

        for ($i = 0; $i < 20; $i++) {
            usleep(300000);

            if ($this->isPortOpen($host, $port)) {
                $this->info("✔ Server is running: http://{$host}:{$port}");
                $this->line("Logs: {$log}");

                return self::SUCCESS;
            }
        }

        $this->error("Server failed to start. Check the log: {$log}");

        return self::FAILURE;
    }

    protected function isPortOpen(string $host, int $port): bool
    {
        $connection = @fsockopen($host, $port, $errno, $errstr, 1);

        if ($connection) {
            fclose($connection);

            return true;
        }

        return false;
    }
}
