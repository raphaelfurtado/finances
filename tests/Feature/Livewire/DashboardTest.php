<?php

namespace Tests\Feature\Livewire;

use App\Models\Transaction;
use App\Models\User;
use Livewire\Livewire;
use App\Livewire\Dashboard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_component_renders_successfully()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Livewire::test(Dashboard::class)
            ->assertStatus(200)
            ->assertSee('Minhas Finanças');
    }

    public function test_dashboard_shows_correct_totals()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'income',
            'amount' => 1000,
            'date' => now()
        ]);

        Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'expense',
            'amount' => 400,
            'date' => now()
        ]);

        Livewire::test(Dashboard::class)
            ->assertSee('1.000,00')
            ->assertSee('400,00')
            ->assertSee('600,00');
    }
}
