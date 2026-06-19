<?php

namespace App\Http\Controllers;

use App\Models\Computer;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    //
    public function store(Request $request, Computer $computer): RedirectResponse
    {
        $request->validate([
            'images' => ['required', 'array'],
            'images.*' => ['image', 'max:4096'],
        ]);
 
        foreach ($request->file('images', []) as $file) {
            $path = $file->store('computers/' . $computer->id, 'public');
 
            Image::create([
                'path' => $path,
                'computer_id' => $computer->id,
            ]);
        }
 
        return redirect()->route('computers.show', $computer)->with('success', 'Imágenes agregadas correctamente.');
    }
 
    public function destroy(Computer $computer, Image $image): RedirectResponse
    {
        Storage::disk('public')->delete($image->path);
 
        $image->delete();
 
        return redirect()->route('computers.show', $computer)->with('success', 'Imagen eliminada correctamente.');
    }

}
