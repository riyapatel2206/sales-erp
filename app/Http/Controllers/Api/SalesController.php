<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sales;
use App\Models\SalesItems;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SalesController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $totalAmount = 0;
            $orderItems = [];

            foreach ($request->products as $item) {
                $product = Products::find($item['product_id']);
                
                if (!$product) {
                    throw new \Exception("Product with ID {$item['product_id']} not found");
                }

                if ($product->quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product: {$product->name}. Available: {$product->quantity}, Requested: {$item['quantity']}");
                }

                $itemTotal = $product->price * $item['quantity'];
                $totalAmount += $itemTotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'price' => $product->price,
                    'quantity' => $item['quantity'],
                    'total' => $itemTotal,
                ];
            }
            $salesOrder = Sales::create([
                'order_number' => Sales::generateOrderNumber(),
                'user_id' => auth()->user()->id,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'order_date' => now(),
            ]);

            foreach ($orderItems as $item) {
                SalesItems::create([
                    'sales_id' => $salesOrder->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'price' => $item['price'],
                    'total_price' => $item['total'],
                ]);

                Products::where('id', $item['product_id'])
                    ->decrement('quantity', $item['quantity']);
            }

            DB::commit();

            $salesOrder->load(['user:id,name,email', 'items.products:id,name,sku']);

            return response()->json([
                'success' => true,
                'message' => 'Sales order created successfully',
                'data' => [
                    'id' => $salesOrder->id,
                    'total_amount' => $salesOrder->total_amount,
                    'status' => $salesOrder->status,
                    'created_date' => $salesOrder->created_at,
                    'created_by' => $salesOrder->user->name,
                    'items' => $salesOrder->items->map(function ($item) {
                        return [
                            'product_id' => $item->product_id,
                            'product_name' => $item->products->name,
                            'product_sku' => $item->products->sku,
                            'quantity' => $item->quantity,
                            'unit_price' => $item->unit_price,
                            'total_price' => $item->total_price,
                        ];
                    }),
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create sales order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $salesOrder = Sales::with(['user:id,name,email', 'items.products:id,name,sku'])
                ->find($id);

            if (!$salesOrder) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sales order not found'
                ], 404);
            }

            $user = auth()->user();
            if ($user->isSalesperson() && $salesOrder->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to view this sales order'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'message' => 'Sales order retrieved successfully',
                'data' => [
                    'id' => $salesOrder->id,
                    'total_amount' => $salesOrder->total_amount,
                    'status' => $salesOrder->status,
                    'order_number' => $salesOrder->order_number,
                    'created_at' => $salesOrder->created_at,
                    'created_by' => [
                        'id' => $salesOrder->user->id,
                        'name' => $salesOrder->user->name,
                    ],
                    'items' => $salesOrder->items->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'product_id' => $item->product_id,
                            'product_name' => $item->products->name,
                            'product_sku' => $item->products->sku,
                            'quantity' => $item->quantity,
                            'unit_price' => $item->unit_price,
                            'total_price' => $item->total_price,
                        ];
                    }),
                    'summary' => [
                        'total_items' => $salesOrder->items->count(),
                        'total_quantity' => $salesOrder->items->sum('quantity'),
                        'subtotal' => $salesOrder->total_amount,
                        'total' => $salesOrder->total_amount,
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve sales order',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}