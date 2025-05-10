<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BrandingController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'nullable|string|max:100',
            'company_address' => 'nullable|string|max:500',
            'company_logo' => 'nullable|image|max:2048', // max 2MB
        ]);

        $user = $request->user();

        Log::info('Company name from request:', [$request->company_name]);
        Log::info('Company address from request:', [$request->company_address]);
        if ($request->hasFile('company_logo')) {
            // Delete old logo if exists
            if ($user->company_logo) {
                Storage::disk('public')->delete($user->company_logo);
            }

            $logoPath = $request->file('company_logo')->store('logos', 'public');
            $user->company_logo = 'storage/' . $logoPath;
        }

        $user->company_name = $request->input('company_name', $user->company_name);
        $user->company_address = $request->input('company_address', $user->company_address);

        $user->save();

        return response()->json($user);
    }

}
