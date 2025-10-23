<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::latest()->paginate(15);
        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('suppliers.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'          => 'required|string|max:255',
            'contact_person'=> 'nullable|string|max:255',
            'email'         => 'nullable|email|max:255',
            'phone'         => 'nullable|string|max:20',
            'address'       => 'nullable|string',
            'categories'    => 'nullable|array',
            'categories.*'  => 'exists:categories,id'
        ]);

        // Supplier::create($validatedData);
        $supplier = null;

        try {
            DB::transaction(function () use ($validatedData, &$supplier, $request) {
                $supplier = Supplier::create($validatedData);

                if (!empty($validatedData['categories'])) {
                    $supplier->categories()->sync($validatedData['categories']);
                }

                auth()->user()->activityLogs()->create([
                    'activity'     => "Menambahkan supplier baru: {$supplier->name}",
                    'ip_address'   => $request->ip(),
                    'user_agent'   => $request->header('User-Agent')
                ]);
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan supplier: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('suppliers.index')->with('success', 'Supplier baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        $supplier->load('products.categories');

        return view('suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        $categories = Category::orderBy('name')->get();
        $supplier->load('categories');
        return view('suppliers.edit', compact('supplier', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validatedData = $request->validate([
            'name'          => 'required|string|max:255',
            'contact_person'=> 'nullable|string|max:255',
            'email'         => ['nullable', 'email', 'max:255', Rule::unique('suppliers')->ignore($supplier->id)],
            'phone'         => 'nullable|string|max:20',
            'address'       => 'nullable|string',
        ]);

        $supplier->update($validatedData);
        $supplier->categories()->sync($request->categories ?? []);

        // LOGGING
        auth()->user()->activityLogs()->create([
            'activity'      => "Mengedit supplier: {$supplier->name}",
            'ip_address'    => $request->ip(),
            'user_agent'    => $request->header('user-Agent')
        ]);

        return redirect()->route('suppliers.index')->with('success', 'Data supplier berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Supplier $supplier)
    {
        try {
            $supplierName = $supplier->name;
            $supplier->delete();

            auth()->user()->activityLogs()->create([
                'activity'      => "Menghapus supplier: {$supplierName}",
                'ip_address'    => $request->ip(),
                'user_agent'    => $request->header('User-Agent')
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus supplier: ' . $e->getMessage());
        }

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil dihapus.');
    }
}
