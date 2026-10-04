<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Lead;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        $businessInfo = [
            'name' => Setting::get('business.name', 'The Drive Clinic'),
            'address' => Setting::get('business.address', 'Nanak Nagar, Jammu, J&K 180004'),
            'phone' => Setting::get('business.phone', '+91 94191 00000'),
            'whatsapp' => Setting::get('business.whatsapp', '919419100000'),
            'email' => Setting::get('business.email', 'contact@thedriveclinic.in'),
            'google_review_url' => Setting::get('business.google_review_url', 'https://g.page/r/thedriveclinic/review'),
            'opening_hours' => Setting::get('capacity.opening_hours', []),
        ];

        return view('pages.contact', compact('businessInfo'));
    }

    public function submit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'mobile'  => ['required', 'regex:/^[6-9][0-9]{9}$/'],
            'email'   => 'nullable|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        $branch = Branch::where('is_active', true)->first();

        Lead::create([
            'branch_id' => $branch?->id,
            'source'    => 'contact_form',
            'name'      => $validated['name'],
            'mobile'    => $validated['mobile'],
            'email'     => $validated['email'] ?? null,
            'subject'   => $validated['subject'] ?? 'General Enquiry',
            'message'   => $validated['message'],
            'status'    => 'new',
        ]);

        return back()->with('success', 'Thank you! Your message has been received. Our studio team will call or WhatsApp you shortly.');
    }
}
