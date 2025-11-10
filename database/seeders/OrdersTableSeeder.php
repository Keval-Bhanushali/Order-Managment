<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::all();
        $products = Product::all();

        foreach ($customers as $customer) {
            for ($i = 0; $i < 2; $i++) {
                DB::transaction(function () use ($customer, $products) {
                    $order = Order::create([
                        'customer_id' => $customer->id,
                        'total_amount' => 0,
                        'status' => collect(['pending', 'completed', 'cancelled'])->random(),
                    ]);

                    $total = 0;
                    $selectedProducts = $products->random(3);

                    foreach ($selectedProducts as $product) {
                        $quantity = rand(1, 5);

                        if ($product->stock < $quantity) {
                            $quantity = $product->stock;
                        }

                        $price = $product->price * $quantity;

                        $order->orderItems()->create([
                            'product_id' => $product->id,
                            'quantity' => $quantity,
                            'price' => $price,
                        ]);

                        // Decrease stock
                        $product->decrement('stock', $quantity);

                        $total += $price;
                    }

                    $order->update(['total_amount' => $total]);
                });
            }
        }
    }
}
