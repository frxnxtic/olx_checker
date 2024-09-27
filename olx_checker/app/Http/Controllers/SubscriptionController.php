<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Subscription;

class SubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'ad_url' => 'required|url',
            'email' => 'required|email',
        ]);

        // Парсинг поточної ціни оголошення
        $price = $this->fetchPrice($validated['ad_url']);
        echo $price;

        // Збереження підписки
        Subscription::updateOrCreate(
            ['ad_url' => $validated['ad_url'], 'user_email' => $validated['email']],
            ['current_price' => $price]
        );

        return response()->json(['message' => 'Subscribed successfully!']);
    }

    private function fetchPrice($url)
    {
        $html = file_get_contents($url);

        // Завантажуємо HTML в DOM
        $dom = new \DOMDocument();
        @$dom->loadHTML($html);

        // Створюємо XPath для вибору елементів
        $xpath = new \DOMXPath($dom);

        // Знаходимо елемент <h3> з класом 'css-90xrc0'
        $nodes = $xpath->query('//h3[@class="css-90xrc0"]');

        if ($nodes->length > 0) {
            $priceText = $nodes->item(0)->nodeValue;

            // Видаляємо всі символи, крім цифр
            $price = preg_replace('/[^0-9]/', '', $priceText);

            return (float) $price;
        }

        return null; // Повертаємо null, якщо ціна не знайдена
    }
}
