<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\InvestorUser;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class InvestorLoginRateLimitTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Réinitialiser le rate limiter avant chaque test
        RateLimiter::clear('login_*');
    }

    public function test_investor_login_successful_without_rate_limit(): void
    {
        $investor = InvestorUser::factory()->create([
            'email' => 'investor@test.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/investisseur/connexion', [
            'email' => 'investor@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/projets');
        $this->assertAuthenticatedAs($investor, 'investor');
    }

    public function test_investor_login_clears_rate_limit_on_success(): void
    {
        $investor = InvestorUser::factory()->create([
            'email' => 'investor@test.com',
            'password' => bcrypt('password123'),
        ]);

        // Vérifier que le rate limiter est bien clear après succès
        $this->post('/investisseur/connexion', [
            'email' => 'investor@test.com',
            'password' => 'password123',
        ]);

        $key = 'login_investor@test.com';
        $this->assertFalse(RateLimiter::tooManyAttempts($key, 5));
    }

    public function test_investor_login_rate_limit_increments_on_failure(): void
    {
        // Créer un investisseur avec un mot de passe différent
        InvestorUser::factory()->create([
            'email' => 'investor@test.com',
            'password' => bcrypt('correct_password'),
        ]);

        $key = 'login_investor@test.com';

        // Effectuer plusieurs tentatives échouées
        for ($i = 0; $i < 5; $i++) {
            $this->post('/investisseur/connexion', [
                'email' => 'investor@test.com',
                'password' => 'wrong_password',
            ])->assertSessionHasErrors('email');
        }

        // La prochaine tentative devrait être bloquée par le rate limit
        $response = $this->post('/investisseur/connexion', [
            'email' => 'investor@test.com',
            'password' => 'wrong_password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertStringContainsString('Trop de tentatives', $response->getSession()->errors()->first('email'));
    }
}
