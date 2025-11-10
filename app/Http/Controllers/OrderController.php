<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class OrderController extends Controller
{
    /**
     * Display a listing of orders for API.
     */
    public function apiIndex()
    {
        try {
            $orders = Order::with(['customer', 'orderItems.product'])
                ->latest()
                ->get()
                ->map(function ($order) {
                    return [
                        'id' => $order->id,
                        'customer' => [
                            'id' => $order->customer->id,
                            'name' => $order->customer->name,
                            'email' => $order->customer->email
                        ],
                        'order_items' => $order->orderItems->map(function ($item) {
                            return [
                                'product_id' => $item->product_id,
                                'quantity' => $item->quantity,
                                'price' => number_format($item->price, 2),
                                'product' => [
                                    'name' => $item->product->name,
                                    'current_stock' => $item->product->stock
                                ]
                            ];
                        }),
                        'total_amount' => number_format($order->total_amount, 2),
                        'status' => $order->status,
                        'created_at' => $order->created_at->toISOString()
                    ];
                });

            return response()->json([
                'status' => 'success',
                'data' => $orders
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch orders',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with('customer', 'orderItems.product')->get();
        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        return view('orders.create', compact('customers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($data) {
            $order = Order::create([
                'customer_id' => $data['customer_id'],
                'total_amount' => 0,
                'status' => 'pending',
            ]);

            $totalAmount = 0;

            foreach ($data['products'] as $productItem) {
                $product = Product::findOrFail($productItem['product_id']);

                if ($product->stock < $productItem['quantity']) {
                    throw new \Exception("Insufficient stock for " . $product->name);
                }

                $product->decrement('stock', $productItem['quantity']);
                $price = $product->price * $productItem['quantity'];

                $order->orderItems()->create([
                    'product_id' => $product->id,
                    'quantity' => $productItem['quantity'],
                    'price' => $price,
                ]);

                $totalAmount += $price;
            }

            $order->update(['total_amount' => $totalAmount]);
        });

        return redirect()->back()->with('success', 'Order placed successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::with(['customer', 'orderItems.product'])->findOrFail($id);
        return view('orders.show', compact('order'));
    }

    public function print(string $id)
    {
        $order = Order::with(['customer', 'orderItems.product'])->findOrFail($id);
        $pdf = view('orders.print', compact('order'));

        return Response::make($pdf, 200, [
            'Content-Type' => 'text/html',
            'Content-Disposition' => 'inline; filename="order-' . $id . '.html"'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $order = Order::findOrFail($id);
        return view('orders.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $order = Order::findOrFail($id);

            $request->validate([
                'status' => 'required|in:' . implode(',', Order::getAllowedStatuses()),
            ]);

            // If cancelling an order, restore the stock
            if ($request->status === Order::STATUS_CANCELLED && $order->status !== Order::STATUS_CANCELLED) {
                DB::transaction(function () use ($order) {
                    foreach ($order->orderItems as $item) {
                        $item->product->increment('stock', $item->quantity);
                    }
                    $order->update(['status' => Order::STATUS_CANCELLED]);
                });
            } else {
                $order->update(['status' => $request->status]);
            }

            return redirect()->route('orders.index')->with('success', 'Order status updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update order status: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
