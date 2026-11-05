<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $setting = Pengaturan::getSetting();
        $query = User::where('role', 'customer');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(fn($sq) => $sq->where('nama_lengkap', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%"));
        }

        $customerList = $query->withCount('transaksi')->orderByDesc('id')->paginate(15)->withQueryString();
        return view('admin.customer.index', compact('setting', 'customerList'));
    }

    public function show(int $id)
    {
        $setting = Pengaturan::getSetting();
        $customer = User::where('role', 'customer')->with('transaksi')->findOrFail($id);
        return view('admin.customer.show', compact('setting', 'customer'));
    }
}

