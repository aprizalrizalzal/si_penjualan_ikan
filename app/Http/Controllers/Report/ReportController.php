<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Seller\Seller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function show()
    {
        // Mengambil semua data yang terkait
        $sellers = Seller::with('products', 'products.orderItems', 'products.orderItems.order', 'products.orderItems.order.payment')->get();

        return Inertia::render('Reports/Reports', [
            'sellers' => $sellers
        ]);
    }
}
