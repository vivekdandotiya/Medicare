<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class MedicineController extends Controller
{
    public function index(Request $request)
    {
        $query = Medicine::with(['category', 'brand']);

        // Search logic
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Brand filter
        if ($request->has('brand') && $request->brand != '') {
            $query->where('brand_id', $request->brand);
        }

        $medicines = $query->latest()->paginate(12);
        $categories = Category::all();
        $brands = Brand::all();

        return view('medicines.index', compact('medicines', 'categories', 'brands'));
    }

    public function create()
    {
        $this->authorizeAdminOrStaff();
        $categories = Category::all();
        $brands = Brand::all();

        return view('medicines.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdminOrStaff();

        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'mrp' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0|lte:mrp',
            'stock_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/medicines'), $filename);
            $imagePath = 'uploads/medicines/' . $filename;
        }

        Medicine::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . time(),
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'description' => $request->description,
            'mrp' => $request->mrp,
            'selling_price' => $request->selling_price,
            'stock_quantity' => $request->stock_quantity,
            'prescription_required' => $request->has('prescription_required'),
            'image' => $imagePath,
            'status' => $request->has('status'),
        ]);

        return redirect()
            ->route('medicines.index')
            ->with('success', 'Medicine created successfully.');
    }

    public function edit(Medicine $medicine)
    {
        $this->authorizeAdminOrStaff();
        $categories = Category::all();
        $brands = Brand::all();

        return view('medicines.edit', compact('medicine', 'categories', 'brands'));
    }

    public function update(Request $request, Medicine $medicine)
    {
        $this->authorizeAdminOrStaff();

        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'mrp' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0|lte:mrp',
            'stock_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = $medicine->image;
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($imagePath && file_exists(public_path($imagePath))) {
                @unlink(public_path($imagePath));
            }

            $file = $request->file('image');
            $filename = time() . '_' . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/medicines'), $filename);
            $imagePath = 'uploads/medicines/' . $filename;
        }

        $medicine->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . $medicine->id,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'description' => $request->description,
            'mrp' => $request->mrp,
            'selling_price' => $request->selling_price,
            'stock_quantity' => $request->stock_quantity,
            'prescription_required' => $request->has('prescription_required'),
            'image' => $imagePath,
            'status' => $request->has('status'),
        ]);

        return redirect()
            ->route('medicines.index')
            ->with('success', 'Medicine updated successfully.');
    }

    public function destroy(Medicine $medicine)
    {
        $this->authorizeAdminOrStaff();

        // Delete image if exists
        if ($medicine->image && file_exists(public_path($medicine->image))) {
            @unlink(public_path($medicine->image));
        }

        $medicine->delete();

        return redirect()
            ->route('medicines.index')
            ->with('success', 'Medicine deleted successfully.');
    }

    protected function authorizeAdminOrStaff()
    {
        $user = Auth::user();
        if (!$user->hasRole('admin') && !$user->hasRole('staff')) {
            abort(403, 'Unauthorized action.');
        }
    }
}