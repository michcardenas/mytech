<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductImage;  // ← AGREGAR ESTE IMPORT
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    public function destroy($id)
    {
        try {
            $image = ProductImage::findOrFail($id);
            
            // Eliminar archivo físico
            if (Storage::disk('public')->exists($image->image)) {
                Storage::disk('public')->delete($image->image);
            }
            
            // Eliminar registro de la base de datos
            $image->delete();
            
            return response()->json([
                'success' => true, 
                'message' => 'Image deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Error deleting image: ' . $e->getMessage()
            ], 500);
        }
    }
}