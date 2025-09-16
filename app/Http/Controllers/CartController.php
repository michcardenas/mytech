<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Cart;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    /**
     * Mostrar el carrito de compras
     */
        public function index()
    {
        try {
            $cartItems = Cart::content();
            
            // Calcular manualmente para evitar tax doble
            $cartSubtotal = 0;
            foreach ($cartItems as $item) {
                $cartSubtotal += $item->price * $item->qty;
            }
            
            $cartTax = $cartSubtotal * $this->taxRate;
            $cartTotal = $cartSubtotal + $cartTax;
            $cartCount = Cart::count();
            
            return view('cart.index', compact(
                'cartItems', 
                'cartTotal', 
                'cartCount', 
                'cartSubtotal', 
                'cartTax'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error loading cart:', ['error' => $e->getMessage()]);
            return view('cart.index')->with('error', 'Error al cargar el carrito');
        }
    }

    /**
     * Agregar producto al carrito
     */
  public function add(Request $request)
{
    \Log::info('✅ Entrando al método add del carrito', $request->all());

    // Validar la request
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'qty' => 'nullable|integer|min:1|max:100'
    ]);

    try {
        $product = Product::with('category', 'images')->findOrFail($request->product_id);
        \Log::info('🔎 Producto encontrado:', ['product_id' => $product->id, 'stock' => $product->stock]);

        // Verificar stock disponible
        $requestedQty = $request->qty ?? 1;
        if ($product->stock < $requestedQty) {
            $errorMessage = 'Stock insuficiente. Solo hay ' . $product->stock . ' unidades disponibles.';
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ]);
            }
            return back()->with('error', $errorMessage);
        }
        
        // Verificar si el producto ya está en el carrito
        $cartItem = Cart::search(function ($cartItem) use ($product) {
            return $cartItem->id === $product->id;
        })->first();
        
        if ($cartItem) {
            // Si ya existe, actualizar cantidad
            $newQty = $cartItem->qty + $requestedQty;
            if ($newQty > $product->stock) {
                $errorMessage = 'No puedes agregar más unidades. Stock máximo: ' . $product->stock;
                
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage
                    ]);
                }
                return back()->with('error', $errorMessage);
            }
            Cart::update($cartItem->rowId, $newQty);
            $message = 'Cantidad actualizada en el carrito 🔄';
        } else {
            \Log::info('🛒 Agregando al carrito', [
                'id' => $product->id,
                'name' => $product->name,
                'qty' => $requestedQty,
                'price' => ($product->price ?? 0) + ($product->interest ?? 0),
            ]);

            // Si no existe, agregarlo
              Cart::add([
                    'id'      => $product->id,
                    'name'    => $product->name,
                    'qty'     => $requestedQty,
                    'price'   => ($product->price ?? 0) + ($product->interest ?? 0),
                    'weight'  => is_numeric(str_replace(',', '.', $product->avg_weight)) 
                                ? floatval(str_replace(',', '.', $product->avg_weight)) 
                                : 0,
                    'options' => [
                        'image'         => $product->images->first()?->image ?? $product->image ?? null,
                        'avg_weight'    => $product->avg_weight ?? null,
                        'category_id'   => $product->category_id ?? null,
                        'category_name' => $product->category->name ?? 'Sin categoría',
                        'stock'         => $product->stock,
                        'base_price'    => $product->price,
                        'interest'      => $product->interest ?? 0,
                    ],
                ])->associate(Product::class);

         
            \Log::info('✅ Producto agregado al carrito. Total items: ' . Cart::count());


            $message = 'Producto añadido al carrito 🚀';
        }

        // Si es una petición AJAX, devolver JSON
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'cart_count' => Cart::count(),
                'cart_total' => Cart::total(),
                'cart_subtotal' => Cart::subtotal()
            ]);
        }

        return back()->with('success', $message);
        
    } catch (\Exception $e) {
        Log::error('Error adding to cart:', [
            'error' => $e->getMessage(),
            'product_id' => $request->product_id,
            'trace' => $e->getTraceAsString()
        ]);
        
        $errorMessage = 'Error al procesar la solicitud. Inténtalo de nuevo.';
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $errorMessage
            ]);
        }
        
        return back()->with('error', $errorMessage);
    }
}

    /**
     * Actualizar cantidad de producto en el carrito
     */
    public function update(Request $request, $rowId)
    {
        $request->validate([
            'qty' => 'required|integer|min:0|max:100'
        ]);

        try {
            // Obtener el item del carrito
            $cartItem = Cart::get($rowId);
            
            if (!$cartItem) {
                return back()->with('error', 'Producto no encontrado en el carrito');
            }

            // Si la cantidad es 0, eliminar el producto
            if ($request->qty == 0) {
                Cart::remove($rowId);
                return back()->with('success', 'Producto eliminado del carrito');
            }

            // Verificar stock disponible
            $product = Product::find($cartItem->id);
            if (!$product) {
                return back()->with('error', 'Producto no encontrado');
            }

            if ($request->qty > $product->stock) {
                return back()->with('error', "Stock insuficiente. Solo hay {$product->stock} unidades disponibles");
            }

            // Actualizar cantidad
            Cart::update($rowId, $request->qty);
            
            return back()->with('success', 'Cantidad actualizada correctamente');
            
        } catch (\Exception $e) {
            Log::error('Error updating cart item:', [
                'error' => $e->getMessage(),
                'rowId' => $rowId,
                'qty' => $request->qty
            ]);
            
            return back()->with('error', 'Error al actualizar la cantidad');
        }
    }

    /**
     * Eliminar producto del carrito
     */
    public function remove($rowId)
    {
        try {
            $cartItem = Cart::get($rowId);
            
            if (!$cartItem) {
                return back()->with('error', 'Producto no encontrado en el carrito');
            }

            Cart::remove($rowId);
            
            return back()->with('success', 'Producto eliminado del carrito');
            
        } catch (\Exception $e) {
            Log::error('Error removing cart item:', [
                'error' => $e->getMessage(),
                'rowId' => $rowId
            ]);
            
            return back()->with('error', 'Error al eliminar el producto');
        }
    }

    /**
     * Vaciar todo el carrito
     */
    public function clear()
    {
        try {
            Cart::destroy();
            return back()->with('success', 'Carrito vaciado completamente');
            
        } catch (\Exception $e) {
            Log::error('Error clearing cart:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error al vaciar el carrito');
        }
    }

    /**
     * Obtener cantidad total de productos en el carrito (AJAX)
     */
    public function count()
    {
        try {
            $count = Cart::count();
            return response()->json([
                'success' => true,
                'count' => $count
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'count' => 0
            ]);
        }
    }

    /**
     * Obtener información del carrito (AJAX)
     */
    public function info()
    {
        try {
            return response()->json([
                'success' => true,
                'count' => Cart::count(),
                'total' => Cart::total(),
                'subtotal' => Cart::subtotal(),
                'tax' => Cart::tax(),
                'items' => Cart::content()->map(function ($item) {
                    return [
                        'rowId' => $item->rowId,
                        'id' => $item->id,
                        'name' => $item->name,
                        'qty' => $item->qty,
                        'price' => $item->price,
                        'total' => $item->total,
                        'image' => $item->options->image ?? null,
                    ];
                })
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener información del carrito'
            ]);
        }
    }

    /**
     * Verificar disponibilidad de stock antes del checkout
     */
    public function checkStock()
    {
        try {
            $cartItems = Cart::content();
            $errors = [];
            
            foreach ($cartItems as $item) {
                $product = Product::find($item->id);
                
                if (!$product) {
                    $errors[] = "El producto '{$item->name}' ya no está disponible";
                    continue;
                }
                
                if ($product->stock < $item->qty) {
                    $errors[] = "Stock insuficiente para '{$item->name}'. Disponible: {$product->stock}, en carrito: {$item->qty}";
                }
            }
            
            if (!empty($errors)) {
                return response()->json([
                    'success' => false,
                    'errors' => $errors
                ]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Stock verificado correctamente'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al verificar el stock'
            ]);
        }
    }

    /**
     * Aplicar descuento al carrito
     */
    public function applyDiscount(Request $request)
    {
        $request->validate([
            'discount_code' => 'required|string|max:50'
        ]);

        try {
            // Aquí puedes implementar la lógica de descuentos
            // Por ejemplo, buscar en una tabla de cupones
            $discountCode = $request->discount_code;
            
            // Ejemplo básico de descuentos hardcodeados
            $discounts = [
                'WELCOME10' => 10, // 10% de descuento
                'SAVE20' => 20,    // 20% de descuento
                'FIRST5' => 5      // 5% de descuento
            ];
            
            if (array_key_exists($discountCode, $discounts)) {
                session(['discount_code' => $discountCode]);
                session(['discount_percentage' => $discounts[$discountCode]]);
                
                return back()->with('success', "Descuento del {$discounts[$discountCode]}% aplicado");
            }
            
            return back()->with('error', 'Código de descuento inválido');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error al aplicar el descuento');
        }
    }

    /**
     * Remover descuento del carrito
     */
    public function removeDiscount()
    {
        try {
            session()->forget(['discount_code', 'discount_percentage']);
            return back()->with('success', 'Descuento removido');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error al remover el descuento');
        }
    }
}