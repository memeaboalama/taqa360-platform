<?php

namespace App\Application\Ecommerce\Controllers;

use App\Domain\AppBase\Models\CustomerTestimonial;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CustomerTestimonialController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'position' => ['nullable', 'string', 'max:100'],
            'content' => ['required', 'string', 'min:10', 'max:2000'],
            'website' => ['prohibited'],
        ]);

        $position = $validated['position'] ?? null;

        CustomerTestimonial::query()->create([
            'name' => $validated['name'],
            'position' => [
                'ar' => $position,
                'en' => $position,
            ],
            'content' => [
                'ar' => $validated['content'],
                'en' => $validated['content'],
            ],
            'is_active' => false,
            'status' => 'pending',
            'sort_order' => min(((int) CustomerTestimonial::query()->max('sort_order')) + 1, 65535),
        ]);

        return redirect()->back()->with('testimonial_submitted', true);
    }
}
