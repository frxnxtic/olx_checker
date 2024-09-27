<?php

namespace App\Jobs;

use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Subscription;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\PriceChanged;

class PriceCheckJob
{
    use Dispatchable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $subscriptions = Subscription::all();

        foreach ($subscriptions as $subscription) {
            $newPrice = $this->fetchPrice($subscription->ad_url);

            if ($newPrice != $subscription->current_price) {
                // Оновлення ціни
                $subscription->update(['current_price' => $newPrice]);

                // Надсилання повідомлення
                Mail::to($subscription->user_email)->send(new PriceChanged($subscription, $newPrice));
            }
        }
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
            Log::info('Parsed price: ' . $price);

            return (float) $price;
        }

        return null; // Повертаємо null, якщо ціна не знайдена
    }
}
