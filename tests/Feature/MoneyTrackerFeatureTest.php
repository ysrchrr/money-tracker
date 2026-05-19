<?php

namespace Tests\Feature;

use App\Models\AppSetting;
use App\Models\CashFlow;
use App\Models\Category;
use App\Models\ReminderSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MoneyTrackerFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_open_core_pages(): void
    {
        AppSetting::create(['key' => 'must_saving_cutoff_day', 'value' => '25']);
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('dashboard'))->assertOk();
        $this->actingAs($user)->get(route('categories.index'))->assertOk();
        $this->actingAs($user)->get(route('cash-flows.index'))->assertOk();
        $this->actingAs($user)->get(route('reminder.index'))->assertOk();
    }

    public function test_user_can_create_category_and_cash_flow(): void
    {
        AppSetting::create(['key' => 'must_saving_cutoff_day', 'value' => '25']);
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('categories.store'), [
            'name' => 'FNB',
            'percentage' => 20,
        ])->assertRedirect();

        $category = Category::first();

        $this->assertNotNull($category);

        $this->actingAs($user)->post(route('cash-flows.store'), [
            'transaction_date' => now()->toDateString(),
            'description' => 'Beli Kopi',
            'type' => 'expense',
            'category_id' => $category->id,
            'amount' => '25000',
        ])->assertRedirect();

        $this->assertDatabaseHas('cash_flows', [
            'user_id' => $user->id,
            'description' => 'Beli Kopi',
            'type' => 'expense',
            'category_id' => $category->id,
            'amount' => 25000,
        ]);
    }

    public function test_silver_member_cannot_enable_reminder(): void
    {
        $user = User::factory()->create([
            'member_type' => 'silver',
        ]);

        ReminderSetting::create([
            'user_id' => $user->id,
            'whatsapp_number' => null,
            'is_enabled' => false,
            'send_time' => '08:00:00',
            'message_template' => 'test',
        ]);

        $this->actingAs($user)->put(route('reminder.update'), [
            'whatsapp_number' => '6281234567890',
            'is_enabled' => 1,
            'send_time' => '08:00',
            'message_template' => 'test',
        ])->assertSessionHasErrors('reminder');
    }

    public function test_dashboard_uses_real_cash_flow_data(): void
    {
        AppSetting::create(['key' => 'must_saving_cutoff_day', 'value' => '25']);
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Transport',
            'percentage' => 10,
        ]);

        CashFlow::create([
            'user_id' => $user->id,
            'transaction_date' => now()->toDateString(),
            'description' => 'Salary',
            'type' => 'income',
            'category_id' => null,
            'amount' => 100000,
        ]);

        CashFlow::create([
            'user_id' => $user->id,
            'transaction_date' => now()->toDateString(),
            'description' => 'Bus',
            'type' => 'expense',
            'category_id' => $category->id,
            'amount' => 50000,
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertSee('Salary')
            ->assertSee('Rp100.000', false)
            ->assertSee('Rp5.000', false);
    }

    public function test_superadmin_uses_admin_menu(): void
    {
        AppSetting::create(['key' => 'must_saving_cutoff_day', 'value' => '25']);
        $admin = User::factory()->create([
            'role' => 'superadmin',
        ]);

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertRedirect(route('admin.dashboard'));

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('All Member Dashboard')
            ->assertSee('Members')
            ->assertSee('Transactions')
            ->assertSee('Reports')
            ->assertSee('Audit Log')
            ->assertDontSee('Cash Flow');
    }

    public function test_member_does_not_see_admin_menu(): void
    {
        AppSetting::create(['key' => 'must_saving_cutoff_day', 'value' => '25']);
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Cash Flow')
            ->assertSee('Category')
            ->assertSee('Reminder')
            ->assertDontSee('Members')
            ->assertDontSee('Audit Log');
    }

    public function test_superadmin_can_readonly_impersonate_member(): void
    {
        AppSetting::create(['key' => 'must_saving_cutoff_day', 'value' => '25']);
        $admin = User::factory()->create([
            'role' => 'superadmin',
        ]);
        $member = User::factory()->create();
        $category = Category::create([
            'user_id' => $member->id,
            'name' => 'Food',
            'percentage' => 10,
        ]);

        CashFlow::create([
            'user_id' => $member->id,
            'transaction_date' => now()->toDateString(),
            'description' => 'Readonly Meal',
            'type' => 'expense',
            'category_id' => $category->id,
            'amount' => 30000,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.members.impersonate', $member))
            ->assertRedirect(route('dashboard'));

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Readonly as Admin')
            ->assertSee('Readonly Meal');

        $this->get(route('cash-flows.index'))
            ->assertOk()
            ->assertSee('Readonly Meal')
            ->assertDontSee('Save Transaction')
            ->assertDontSee('Delete');

        $this->post(route('cash-flows.store'), [
            'transaction_date' => now()->toDateString(),
            'description' => 'Blocked Write',
            'type' => 'expense',
            'category_id' => $category->id,
            'amount' => '12000',
        ])->assertForbidden();

        $this->assertDatabaseMissing('cash_flows', [
            'description' => 'Blocked Write',
        ]);
        $this->assertDatabaseHas('admin_audit_logs', [
            'admin_id' => $admin->id,
            'target_user_id' => $member->id,
            'action' => 'impersonation.start',
        ]);
    }

    public function test_dashboard_uses_calendar_month_when_cutoff_is_disabled(): void
    {
        $user = User::factory()->create([
            'is_cutoff_enabled' => false,
        ]);
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Food',
            'percentage' => 10,
        ]);

        CashFlow::create([
            'user_id' => $user->id,
            'transaction_date' => '2026-04-26',
            'description' => 'Old Period Expense',
            'type' => 'expense',
            'category_id' => $category->id,
            'amount' => 40000,
        ]);

        CashFlow::create([
            'user_id' => $user->id,
            'transaction_date' => '2026-05-02',
            'description' => 'Current Month Expense',
            'type' => 'expense',
            'category_id' => $category->id,
            'amount' => 50000,
        ]);

        $this->actingAs($user)
            ->get(route('dashboard', ['month' => '2026-05']))
            ->assertOk()
            ->assertSee('Current Month Expense')
            ->assertDontSee('Old Period Expense')
            ->assertSee('Kalender 1-Akhir Bulan');
    }

    public function test_dashboard_uses_cutoff_period_when_cutoff_is_enabled(): void
    {
        $user = User::factory()->create([
            'is_cutoff_enabled' => true,
        ]);
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Food',
            'percentage' => 10,
        ]);

        CashFlow::create([
            'user_id' => $user->id,
            'transaction_date' => '2026-04-26',
            'description' => 'Cutoff Expense',
            'type' => 'expense',
            'category_id' => $category->id,
            'amount' => 40000,
        ]);

        CashFlow::create([
            'user_id' => $user->id,
            'transaction_date' => '2026-05-26',
            'description' => 'Next Period Expense',
            'type' => 'expense',
            'category_id' => $category->id,
            'amount' => 60000,
        ]);

        $this->actingAs($user)
            ->get(route('dashboard', ['month' => '2026-05']))
            ->assertOk()
            ->assertSee('Cutoff Expense')
            ->assertDontSee('Next Period Expense')
            ->assertSee('Cutoff 26-25');
    }

    public function test_cash_flow_page_defaults_to_cutoff_period_when_enabled(): void
    {
        $user = User::factory()->create([
            'is_cutoff_enabled' => true,
        ]);
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Food',
            'percentage' => 10,
        ]);

        CashFlow::create([
            'user_id' => $user->id,
            'transaction_date' => now()->startOfMonth()->subDays(3)->toDateString(),
            'description' => 'Inside Cutoff',
            'type' => 'expense',
            'category_id' => $category->id,
            'amount' => 30000,
        ]);

        CashFlow::create([
            'user_id' => $user->id,
            'transaction_date' => now()->startOfMonth()->addDays(26)->toDateString(),
            'description' => 'Outside Cutoff',
            'type' => 'expense',
            'category_id' => $category->id,
            'amount' => 35000,
        ]);

        $this->actingAs($user)
            ->get(route('cash-flows.index'))
            ->assertOk()
            ->assertSee('Inside Cutoff')
            ->assertDontSee('Outside Cutoff')
            ->assertSee('Mode Cutoff 26-25');
    }
}
