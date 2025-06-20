<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Sales;
use App\Models\SalesItems;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function index()
    {
        $query = Sales::with('user');

        if (!auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }
        
        $salesOrders = $query->latest()->paginate(10);
        // dd($salesOrders);
        $confirmedCount = $salesOrders->where('status', 'confirmed')->count();
        $pendingCount = $salesOrders->where('status', 'pending')->count();
        return view('sales.list', compact('salesOrders', 'confirmedCount', 'pendingCount'));
    }

    public function create()
    {
        $products = Products::where('quantity', '>', 0)->get();
        return view('sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            $totalAmount = 0;

            // Calculate total amount
            foreach ($request->products as $item) {
                $product = Products::find($item['product_id']);
                if ($product->quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}");
                }
                $totalAmount += $product->price * $item['quantity'];
            }

            // Create sales order
            $salesOrder = Sales::create([
                'order_number' => Sales::generateOrderNumber(),
                'user_id' => auth()->user()->id,
                'total_amount' => $totalAmount,
                'status' => 'pending',
            ]);

            // Create order items
            foreach ($request->products as $item) {
                $product = Products::find($item['product_id']);
                $totalPrice = $product->price * $item['quantity'];

                SalesItems::create([
                    'sales_id' => $salesOrder->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'total_price' => $totalPrice,
                ]);
            }
        });

        return redirect()->route('sales.list')
            ->with('success', 'Sales order created successfully.');
    }

    public function show(Sales $salesOrder)
    {
        $salesOrder->load('items.products', 'user');
        // dd($salesOrder);
        return view('sales.view', compact('salesOrder'));
    }

    public function confirm(Sales $salesOrder)
    {
        try {
            DB::transaction(function () use ($salesOrder) {
                if ($salesOrder->status !== 'pending') {
                    throw new \Exception('Only pending orders can be confirmed.');
                }

                // Check stock availability again
                foreach ($salesOrder->items as $item) {
                    if ($item->products->quantity < $item->quantity) {
                        throw new \Exception("Insufficient stock for {$item->products->name}");
                    }
                }

                // Reduce inventory
                foreach ($salesOrder->items as $item) {
                    $product = $item->products;
                    $product->quantity -= $item->quantity;
                    $product->save();
                }

                // Update order status
                $salesOrder->status = 'confirmed';
                $salesOrder->save();
            });

            return redirect()->back()
                ->with('success', 'Sales order confirmed successfully. Inventory has been updated.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    public function downloadPdf(Sales $salesOrder)
    {
        $salesOrder->load('items.products', 'user');
        
        $pdf = Pdf::loadView('sales.pdf', compact('salesOrder'));
        
        return $pdf->download('sales-order-' . $salesOrder->order_number . '.pdf');
    }
}
