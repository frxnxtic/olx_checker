<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Subscription;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_subscribe_and_parse_price()
    {
        // Готуємо дані для запиту
        $adUrl = 'https://www.olx.ua/d/uk/obyavlenie/daewoo-nexia-2008-117-tis-ne-farbovana-IDV1ZgZ.html';
        $email = 'den080820051@gmail.com';

        // Відправляємо POST-запит з використанням CSRF токену
        $response = $this->post('/subscribe', [
            'ad_url' => $adUrl,
            'email' => $email,
            '_token' => csrf_token(),  // Генеруємо CSRF токен для запиту
        ]);

        // Перевіряємо, що запит був успішним
        $response->assertStatus(200);

        // Перевіряємо, що API повертає правильну відповідь
        $response->assertJson([
            'message' => 'Subscribed successfully!'
        ]);

        // Перевіряємо, що підписка успішно збереглась в базі даних
        $this->assertDatabaseHas('subscriptions', [
            'ad_url' => $adUrl,
            'user_email' => $email,
        ]);
    }
}
