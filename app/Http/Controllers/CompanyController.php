<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CompanyController extends Controller
{
    public function index(): View
    {
        $companies = Company::latest()->paginate(10); // Mengambil data terbaru dengan pagination
        return view('companies.index', compact('companies'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'                => 'required|string|max:255',
            'registration_number' => 'nullable|string|unique:companies,registration_number',
            'tax_id'              => 'nullable|string',
            'email'               => 'required|email|unique:companies,email',
            'phone'               => 'nullable|string|max:20',
            'website'             => 'nullable|url',
            'logo'                => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
            'address'             => 'nullable|string',
            'city'                => 'nullable|string',
            'province'            => 'nullable|string',
            'postal_code'         => 'nullable|string|max:10',
            'country'             => 'nullable|string',
            'description'         => 'nullable|string',
            'industry'            => 'nullable|string',
            'is_active'           => 'boolean',
        ]);

        $validated['slug'] = Str::slug($request->name) . '-' . Str::random(5);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $path;
        }

        $validated['is_active'] = $request->has('is_active');

        Company::create($validated);

        return redirect()->back()->with('success', 'Perusahaan berhasil didaftarkan!');
    }


    public function show(Company $company): View
    {
        return view('companies.show', compact('company'));
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
