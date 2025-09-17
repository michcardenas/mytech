<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\ProductController; // <-- NUEVO
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\WholesaleController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PagesController;
use App\Http\Controllers\ServiciosController;


/* ---------- Landing y páginas públicas ---------- */
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/servicios', [ServiciosController::class, 'index'])->name('servicios.index');
Route::get('/proyectos', [App\Http\Controllers\ServiciosController::class, 'indexproyectos'])->name('proyectos.index');
Route::get('/sobre-nosotros', [App\Http\Controllers\ServiciosController::class, 'indexsobreNosotros'])->name('sobre_nosotros.index');
Route::get('/contacto', [App\Http\Controllers\ServiciosController::class, 'indexcontacto'])->name('contacto.index');

// Route::get('/products', [ProductController::class, 'index'])->name('products.index');
// Route::get('/product/{product}', [ProductController::class, 'show'])->name('product.show');
// Route::view('/about',   'about')->name('about');
// Route::view('/insiders','insiders')->name('insiders');//
// Route::get('/servicios', function () {
//     return view('servicios');
// })->name('servicios');

// Route::get('/recipes', [App\Http\Controllers\Admin\PageController::class, 'servicios'])->name('recipes');
// //los de dentro de recipes
// Route::get('/wholesale', [WholesaleController::class, 'index'])->name('wholesale.form');
// Route::post('/wholesale', [WholesaleController::class, 'submit'])->name('wholesale.submit');
// Route::put('/admin/pages/{page}/sections/{section}', [App\Http\Controllers\Admin\PageController::class, 'updateSection'])->name('admin.pages.sections.update');
// Route::view('/chefs',   'chefs')->name('chefs');
// Route::view('/wholesale','wholesale')->name('wholesale');
// Route::get('/admin/seo/{page}/edit', [App\Http\Controllers\Admin\SeoController::class, 'edit'])->name('admin.seo.edit');
// Route::put('/admin/seo/{page}', [App\Http\Controllers\Admin\SeoController::class, 'update'])->name('admin.seo.update');
// /* ---------- Catálogo y carrito ---------- */
// Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
// Route::delete('/admin/products/images/{id}', [App\Http\Controllers\Admin\ProductImageController::class, 'destroy'])->name('admin.products.images.destroy');
// Route::get('/checkout', [ShopController::class, 'checkout'])->name('checkout.index');
// Route::post('/checkout/calculate', [ShopController::class, 'calculateShippingAndTax'])->name('checkout.calculate');
// Route::post('/order/process', [ShopController::class, 'processOrder'])->name('order.process');

// // Ruta para la pasarela de pago
// Route::get('/payment/gateway/{order}', [App\Http\Controllers\ShopController::class, 'paymentGateway'])->name('payment.gateway');
// Route::post('/payment/process/{order}', [App\Http\Controllers\ShopController::class, 'processPayment'])->name('payment.process');
// Route::get('/payment/success/{order}', [App\Http\Controllers\ShopController::class, 'paymentSuccess'])->name('payment.success');
// Route::get('/contacto', [ContactController::class, 'index'])->name('contact.index');
// Route::post('/contacto', [ContactController::class, 'submit'])->name('contact.submit');

// Route::post('/cart',           [CartController::class, 'add'])->name('cart.add');
// Route::get('/cart',            [CartController::class, 'index'])->name('cart.index');
// Route::patch('/cart/{rowId}',  [CartController::class, 'update'])->name('cart.update');
// Route::delete('/cart/{rowId}', [CartController::class, 'remove'])->name('cart.remove');
// Route::get('/about', [HomeController::class, 'about'])->name('about');
// Route::get('/partner-chefs', [HomeController::class, 'partnerChefs'])->name('partner.chefs');
// Route::post('/partner-chefs', [HomeController::class, 'submitPartnerChefs'])->name('partner.chefs.submit');
// Route::get('/shipping-policy', function () {
//     return view('policies.shipping');
// })->name('shipping.policy');

// Route::get('/return-policy', function () {
//     return view('policies.return');
// })->name('return.policy');

// Route::get('/refund-policy', function () {
//     return view('policies.refund');
// })->name('refund.policy');

// Route::get('/terms-conditions', function () {
//     return view('policies.terms');
// })->name('terms.conditions');
// /* ---------- Dashboard y perfil ---------- */
// Route::middleware(['auth', 'verified'])->group(function () {
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

//     Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
//     Route::prefix('admin')->group(function () {
//     Route::get('/categorias', [CategoryController::class, 'index'])->name('categories.index');
//     Route::get('/categorias/crear', [CategoryController::class, 'create'])->name('categories.create');
//     Route::post('/categorias', [CategoryController::class, 'store'])->name('categories.store');
// });

// Route::resource('categories', CategoryController::class);


// // routes/web.php - SOLO cambia las rutas de páginas

// Route::prefix('admin')->group(function () {
//     // Countries (NO TOCAR - YA FUNCIONAN)
//     Route::get('/countries', [LocationController::class, 'countriesIndex'])->name('admin.countries.index');
//     Route::post('/countries', [LocationController::class, 'countriesStore'])->name('admin.countries.store');
//     Route::delete('/countries/{id}', [LocationController::class, 'countriesDestroy'])->name('admin.countries.destroy');

//     // Cities (NO TOCAR - YA FUNCIONAN)
//     Route::get('/cities', [LocationController::class, 'citiesIndex'])->name('admin.cities.index');
//     Route::post('/cities', [LocationController::class, 'citiesStore'])->name('admin.cities.store');
//     Route::delete('/cities/{id}', [LocationController::class, 'citiesDestroy'])->name('admin.cities.destroy');

//     // === RUTAS ESPECÍFICAS PARA PÁGINAS ===
    
//     // Lista general de páginas - COMENTADO PARA NUEVA IMPLEMENTACIÓN
    // Route::get('pages', [App\Http\Controllers\Admin\PageController::class, 'index'])->name('admin.pages.index');

    // === NUEVAS RUTAS PARA GESTIÓN DE PÁGINAS ===
    Route::resource('pages', PagesController::class, ['as' => 'admin']);

    // Rutas adicionales para secciones
    Route::get('pages/{page}/sections', [PagesController::class, 'sections'])->name('admin.pages.sections');
    Route::post('pages/{page}/sections', [PagesController::class, 'storeSection'])->name('admin.pages.sections.store');
    Route::put('pages/{page}/sections/{section}', [PagesController::class, 'updateSection'])->name('admin.pages.sections.update');
    Route::delete('pages/{page}/sections/{section}', [PagesController::class, 'destroySection'])->name('admin.pages.sections.destroy');
    Route::patch('pages/{page}/sections/{section}/toggle', [PagesController::class, 'toggleSection'])->name('admin.pages.sections.toggle');
    
//     // Página INICIO
//     Route::get('pages/inicio/edit', [App\Http\Controllers\Admin\PageController::class, 'editInicio'])->name('admin.pages.edit-inicio');
//     Route::put('pages/inicio', [App\Http\Controllers\Admin\PageController::class, 'updateInicio'])->name('admin.pages.update-inicio');
    
//     // Página QUIÉNES SOMOS
//     Route::get('pages/quienes-somos/edit', [App\Http\Controllers\Admin\PageController::class, 'editQuienesSomos'])->name('admin.pages.edit-quienes-somos');
//     Route::put('pages/quienes-somos', [App\Http\Controllers\Admin\PageController::class, 'updateQuienesSomos'])->name('admin.pages.update-quienes-somos');
    
//     // Página SERVICIOS
//     Route::get('pages/servicios/edit', [App\Http\Controllers\Admin\PageController::class, 'editServicios'])->name('admin.pages.edit-servicios');
//     Route::put('pages/servicios', [App\Http\Controllers\Admin\PageController::class, 'updateServicios'])->name('admin.pages.update-servicios');
    
//     // Página CONTACTO
//     Route::get('pages/contacto/edit', [App\Http\Controllers\Admin\PageController::class, 'editContacto'])->name('admin.pages.edit-contacto');
//     Route::put('pages/contacto', [App\Http\Controllers\Admin\PageController::class, 'updateContacto'])->name('admin.pages.update-contacto');
    
//     // Eliminar imágenes (funciona para todas las páginas)
//     Route::delete('pages/{page}/image', [App\Http\Controllers\Admin\PageController::class, 'deleteImage'])->name('admin.pages.delete-image');

//     Route::delete('pages/{page}/sections/{section}/images', [App\Http\Controllers\Admin\PageController::class, 'deleteSectionImage'])
//     ->name('admin.pages.sections.delete-image');
// });
// });

// /* ---------- CRUD de productos (solo usuarios logueados) ---------- */
// Route::middleware(['auth'])
//       ->prefix('admin')
//       ->name('admin.')
//       ->group(function () {
//           Route::resource('products', ProductController::class)->except(['show']);
// });
// // Rutas del carrito
// Route::prefix('cart')->name('cart.')->group(function () {
//     Route::get('/', [CartController::class, 'index'])->name('index');
//     Route::post('/', [CartController::class, 'add'])->name('add');
//     Route::patch('/{rowId}', [CartController::class, 'update'])->name('update');
//     Route::delete('/{rowId}', [CartController::class, 'remove'])->name('remove');
//     Route::delete('/', [CartController::class, 'clear'])->name('clear');
    
//     // Rutas AJAX
//     Route::get('/count', [CartController::class, 'count'])->name('count');
//     Route::get('/info', [CartController::class, 'info'])->name('info');
//     Route::post('/check-stock', [CartController::class, 'checkStock'])->name('check-stock');
    
//     // Rutas de descuentos
//     Route::post('/discount', [CartController::class, 'applyDiscount'])->name('apply-discount');
//     Route::delete('/discount', [CartController::class, 'removeDiscount'])->name('remove-discount');
// });

// Route::prefix('admin/pages')->name('admin.pages.')->group(function () {
//     Route::get('/', [App\Http\Controllers\Admin\PageController::class, 'index'])->name('index');
    
//     // Rutas que redirigen a secciones
//     Route::get('/inicio/edit', [App\Http\Controllers\Admin\PageController::class, 'editInicio'])->name('edit-inicio');
//     Route::get('/quienes-somos/edit', [App\Http\Controllers\Admin\PageController::class, 'editQuienesSomos'])->name('edit-quienes-somos');
//     Route::get('/servicios/edit', [App\Http\Controllers\Admin\PageController::class, 'editServicios'])->name('edit-servicios');
//     Route::get('/contacto/edit', [App\Http\Controllers\Admin\PageController::class, 'editContacto'])->name('edit-contacto');
    
//     // NUEVAS RUTAS PARA SECCIONES
//     Route::get('/{page}/sections', [App\Http\Controllers\Admin\PageController::class, 'manageSections'])->name('sections');
//     // Route::put('/{page}/sections/{section}', [App\Http\Controllers\Admin\PageController::class, 'updateSection'])->name('sections.update');
//     Route::delete('/{page}/sections/{section}/images', [App\Http\Controllers\Admin\PageController::class, 'deleteSectionImage'])->name('sections.delete-image');
// });



// Route::prefix('admin')->name('admin.')->group(function () {
//     // Vista de edición
//     Route::get('/paginas/contacto', [PageController::class, 'editContacto'])
//         ->name('pages.contacto.edit');

//     // Guardar cambios
//     Route::post('/paginas/contacto', [PageController::class, 'updateContacto'])
//         ->name('pages.contacto.update');
// });



require __DIR__.'/auth.php';
