<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sliders = \App\Models\Slider::orderBy('type')->orderBy('sort_order')->get();
        return view('admin.sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.sliders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image_path' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type' => 'required|in:hero,about',
            'sort_order' => 'integer',
        ]);

        $data = $request->all();

        if ($request->hasFile('image_path')) {
            $image = $request->file('image_path');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/sliders'), $imageName);
            $data['image_path'] = 'images/sliders/' . $imageName;
        }

        \App\Models\Slider::create($data);

        return redirect()->route('sliders.index')->with('success', 'Slider created successfully.');
    }

    public function edit(\App\Models\Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    public function update(Request $request, \App\Models\Slider $slider)
    {
        $request->validate([
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type' => 'required|in:hero,about',
            'sort_order' => 'integer',
        ]);

        $data = $request->all();

        if ($request->hasFile('image_path')) {
            // Delete old image
            if (file_exists(public_path($slider->image_path))) {
                @unlink(public_path($slider->image_path));
            }

            $image = $request->file('image_path');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/sliders'), $imageName);
            $data['image_path'] = 'images/sliders/' . $imageName;
        } else {
            unset($data['image_path']);
        }
        
        // Handle checkbox for is_active which might not be sent if unchecked
        $data['is_active'] = $request->has('is_active');

        $slider->update($data);

        return redirect()->route('sliders.index')->with('success', 'Slider updated successfully.');
    }

    public function destroy(\App\Models\Slider $slider)
    {
        if (file_exists(public_path($slider->image_path))) {
            @unlink(public_path($slider->image_path));
        }
        
        $slider->delete();

        return redirect()->route('sliders.index')->with('success', 'Slider deleted successfully.');
    }
}
