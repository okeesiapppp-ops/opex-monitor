<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\TbBacklogDaily;
use App\Models\User;
use App\Mail\ReminderEtaMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
class SendReminderEta extends Command
{
    protected $signature = 'send:reminder-eta';
    protected $description = 'Kirim 1 email reminder H-3 sampai H-5 sebelum Plan Date';
    public function handle()
    {
        $today = Carbon::today();
        $items = TbBacklogDaily::whereNotNull('plan_date')
            ->whereIn('status', ['outstanding', 'pending', 'on progress'])
            ->get();
        $unitList = [];
        foreach ($items as $item) {
            $planDate = Carbon::parse($item->plan_date);
            $sisaHari = $today->diffInDays($planDate, false);
            if ($sisaHari >= 3 && $sisaHari <= 5) {
                $unit = $item->unit;
                if (!isset($unitList[$unit])) {
                    $unitList[$unit] = ['sisaHari' => $sisaHari, 'count' => 0, 'plan_date' => $item->plan_date];
                }
                $unitList[$unit]['count']++;
            }
        }
        if (empty($unitList)) {
            $this->info("Tidak ada unit yang mendekati Plan Date.");
            return;
        }
        $penerima = User::where('role', 'planner')->get();
        $count = 0;
        foreach ($penerima as $user) {
            Mail::to($user->email)->send(new ReminderEtaMail($unitList, $user->name));
            $count++;
        }
        $this->info("Berhasil kirim {$count} email reminder!");
    }
}