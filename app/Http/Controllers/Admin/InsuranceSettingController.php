<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateInsuranceSettingRequest;
use App\Models\InsuranceSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * The 3 insurance packages are a fixed set (InsuranceOption enum) — this
 * intentionally only offers index/update, never create/destroy. Admins
 * retune label/price/coverage per package; they can't add or remove one.
 */
class InsuranceSettingController extends Controller
{
    /**
     * Display the 3 insurance packages.
     */
    public function index(): View
    {
        $insuranceSettings = InsuranceSetting::query()->orderBy('daily_rate')->get();

        return view('admin.insurance-settings.index', compact('insuranceSettings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInsuranceSettingRequest $request, InsuranceSetting $insuranceSetting): RedirectResponse
    {
        $insuranceSetting->update($request->validated());

        return redirect()
            ->route('admin.insurance-settings.index')
            ->with('status', 'Insurance package updated.');
    }
}
