<?php

namespace Tests\Feature\Livewire;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Livewire\Livewire;
use App\Livewire\Dashboard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PodioDaVergonhaTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_ranking_computed_property_returns_correct_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $cat1 = Category::factory()->create(['name' => 'Alimentação', 'user_id' => $user->id]);
        $cat2 = Category::factory()->create(['name' => 'Transporte', 'user_id' => $user->id]);

        Transaction::factory()->create([
            'user_id' => $user->id,
            'category_id' => $cat1->id,
            'type' => 'expense',
            'amount' => 500,
            'date' => now()
        ]);

        Transaction::factory()->create([
            'user_id' => $user->id,
            'category_id' => $cat2->id,
            'type' => 'expense',
            'amount' => 200,
            'date' => now()
        ]);

        Livewire::test(Dashboard::class)
            ->assertSet('categoryRanking', function ($ranking) use ($cat1, $cat2) {
                return $ranking->count() === 2 &&
                    $ranking[0]->category_id == $cat1->id &&
                    $ranking[0]->total_amount == 500 &&
                    $ranking[1]->category_id == $cat2->id &&
                    $ranking[1]->total_amount == 200;
            });
    }

    public function test_consultor_comment_logic()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $cat = Category::factory()->create(['name' => 'iFood', 'user_id' => $user->id]);

        Transaction::factory()->create([
            'user_id' => $user->id,
            'category_id' => $cat->id,
            'type' => 'expense',
            'amount' => 100,
            'date' => now()
        ]);

        Livewire::test(Dashboard::class)
            ->assertSet('consultorComment', 'Patrocinador oficial do Delivery.');
    }

    public function test_component_renders_podio_da_vergonha_card()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(Dashboard::class)
            ->assertSee('Pódio da Vergonha');
    }
}
