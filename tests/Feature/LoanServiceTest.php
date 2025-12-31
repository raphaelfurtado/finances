<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\LoanService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_loan_service_calculates_simple_interest_correctly()
    {
        $service = new LoanService();

        // Test with 0% interest
        $preview = $service->calculatePreview(1000, 0, 12);
        $this->assertEquals(1000, $preview['total_amount']);
        $this->assertEquals(83.33, $preview['installment_amount']);

        // Test with 5% monthly interest for 12 months
        $preview = $service->calculatePreview(1000, 5, 12);
        $this->assertEquals(1600, $preview['total_amount']); // 1000 * (1 + (5/100 * 12))
        $this->assertEquals(133.33, $preview['installment_amount']);
    }

    public function test_loan_creation_generates_correct_installments()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $service = new LoanService();
        $loan = $service->createLoan([
            'type' => 'receivable',
            'counterparty_name' => 'Test Person',
            'counterparty_phone' => '11999999999',
            'principal_amount' => 1000,
            'interest_rate' => 5,
            'installments_count' => 12,
            'start_date' => now()->format('Y-m-d'),
        ]);

        $this->assertDatabaseHas('loans', [
            'id' => $loan->id,
            'user_id' => $user->id,
            'type' => 'receivable',
            'principal_amount' => 1000,
            'interest_rate' => 5,
        ]);

        $this->assertEquals(12, $loan->installments()->count());
        $this->assertEquals('pending', $loan->installments()->first()->status);
    }
}
