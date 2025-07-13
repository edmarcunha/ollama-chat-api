<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class ProcessScheduledDeletions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-scheduled-deletions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete users scheduled for deletion';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::onlyTrashed()
            ->whereNotNull('scheduled_for_deletion_at')
            ->where('scheduled_for_deletion_at', '<=', Carbon::now())
            ->get();

        foreach ($users as $user) {
            $user->forceDelete();
            $this->info("Usuário ID {$user->id} deletado permanentemente.");
        }

        $this->info("Processamento concluído.");
        return Command::SUCCESS;
    }
}
