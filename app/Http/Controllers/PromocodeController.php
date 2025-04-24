<?php

namespace App\Http\Controllers;

use App\Models\Promocode;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PromocodeController extends Controller
{
    public function showPromocodesToManager()
    {
        $promocodes = Promocode::orderBy('code')->get();
        return view('Manager_Promocodes', compact('promocodes'));
    }

    public function showRedactorPromocodes()
    {
        return view('Add_Promocode');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:promocodes',
            'discount' => 'required|numeric|min:0',
            'type' => 'required|in:percent,fixed',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after:valid_from',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'sometimes|boolean'
        ]);

        Promocode::create($validated);

        return redirect()->route('Manager_Promocodes')->with('success', 'Промокод успешно создан');
    }

    public function edit($id)
    {
        $promocode = Promocode::findOrFail($id);
        return view('Edit_Promocode', compact('promocode'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:promocodes,code,'.$id,
            'discount' => 'required|numeric|min:0',
            'type' => 'required|in:percent,fixed',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after:valid_from',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'sometimes|boolean'
        ]);

        $promocode = Promocode::findOrFail($id);
        $promocode->update($validated);

        return redirect()->route('Manager_Promocodes')->with('success', 'Промокод успешно обновлен');
    }

    public function delete($id)
    {
        $promocode = Promocode::findOrFail($id);
        $promocode->delete();

        return redirect()->route('Manager_Promocodes')->with('success', 'Промокод удален');
    }
}