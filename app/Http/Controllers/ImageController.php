<?php

namespace App\Http\Controllers;

use App\Models\Computer;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:images.index', only: ['index']),
            new Middleware('permission:images.create', only: ['create']),
            new Middleware('permission:images.store', only: ['store']),
            new Middleware('permission:images.show', only: ['show']),
            new Middleware('permission:images.edit', only: ['edit']),
            new Middleware('permission:images.update', only: ['update']),
            new Middleware('permission:images.destroy', only: ['destroy']),
        ];
    }

    public function store(Request $request, Computer $computer): RedirectResponse
    {
    $request->validate([
                'images'   => ['required', 'array'],
                'images.*' => ['image', 'max:4096'],
            ], [], [
                'images'   => 'imágenes',
                'images.*' => 'imagen seleccionada',
            ]);

            foreach ($request->file('images', []) as $file) {
                $path = $file->store('computers/' . $computer->id, 'public');
                Image::create(['path' => $path, 'computer_id' => $computer->id]);
            }

        return redirect()->back()->with('success', 'Imágenes agregadas correctamente.');
    }

    public function destroy(Request $request, Computer $computer, Image $image): RedirectResponse
    {
        Storage::disk('public')->delete($image->path);
        $image->delete();

        $redirect = $request->input('_redirect', route('computers.show', $computer));

        return redirect($redirect)->with('success', 'Imagen eliminada correctamente.');
    }
}