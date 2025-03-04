<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * OrderController constructor.
     * Note: Don't use middleware here, it's already applied in the routes file
     */
    public function __construct()
    {
        // Auth middleware is applied in the routes file
    }

    /**
     * Display a listing of the user's orders.
     */
    public function index()
    {
        $orders = Auth::user()->order()->latest()->paginate(10);
        
        return view('orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        // Check if the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Load the order with its relationships
        $order->load(['orderItems', 'outlet']);
        
        return view('orders.show', compact('order'));
    }
}