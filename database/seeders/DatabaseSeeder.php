<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use App\Models\CashFlow;
use App\Models\Category;
use App\Models\ReminderSetting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        AppSetting::updateOrCreate(
            ['key' => 'must_saving_cutoff_day'],
            ['value' => '25'],
        );

        AppSetting::updateOrCreate(
            ['key' => 'daily_reminder_cron_token'],
            ['value' => 'money-tracker-demo-token'],
        );

        $this->call(UserSeeder::class);

        $member = User::query()->where('email', 'member@moneytracker.test')->firstOrFail();

        $fnb = Category::updateOrCreate(
            ['user_id' => $member->id, 'name' => 'FNB'],
            ['percentage' => 20],
        );

        $transport = Category::updateOrCreate(
            ['user_id' => $member->id, 'name' => 'Transport'],
            ['percentage' => 10],
        );

        $health = Category::updateOrCreate(
            ['user_id' => $member->id, 'name' => 'Health'],
            ['percentage' => 15],
        );

        CashFlow::updateOrCreate(
            ['user_id' => $member->id, 'description' => 'Gaji Bulanan', 'transaction_date' => now()->startOfMonth()->toDateString()],
            ['type' => 'income', 'category_id' => null, 'amount' => 8500000],
        );

        CashFlow::updateOrCreate(
            ['user_id' => $member->id, 'description' => 'Freelance UI Fix', 'transaction_date' => now()->subDays(6)->toDateString()],
            ['type' => 'income', 'category_id' => null, 'amount' => 1200000],
        );

        CashFlow::updateOrCreate(
            ['user_id' => $member->id, 'description' => 'Makan Siang', 'transaction_date' => now()->subDays(2)->toDateString()],
            ['type' => 'expense', 'category_id' => $fnb->id, 'amount' => 42000],
        );

        CashFlow::updateOrCreate(
            ['user_id' => $member->id, 'description' => 'Gojek Kantor', 'transaction_date' => now()->subDays(1)->toDateString()],
            ['type' => 'expense', 'category_id' => $transport->id, 'amount' => 28000],
        );

        CashFlow::updateOrCreate(
            ['user_id' => $member->id, 'description' => 'Vitamin', 'transaction_date' => now()->subDays(4)->toDateString()],
            ['type' => 'expense', 'category_id' => $health->id, 'amount' => 125000],
        );

        ReminderSetting::updateOrCreate(
            ['user_id' => $member->id],
            [
                'whatsapp_number' => '6281234567890',
                'is_enabled' => true,
                'send_time' => '08:00:00',
                'message_template' => 'Jangan lupa catat pengeluaran hari ini.',
            ],
        );
    }
}
