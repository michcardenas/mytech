<?php

use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PagesController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\BlogController; // <-- NUEVO
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiciosController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\WholesaleController;
use Illuminate\Support\Facades\Route;

/* ---------- SEO Routes ---------- */
Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [App\Http\Controllers\SitemapController::class, 'robots'])->name('robots');

/* ---------- Portal público (developers / gestores) ---------- */
Route::prefix('portal')->group(function () {
    // Desarrolladores
    Route::get('desarrollador', [App\Http\Controllers\Portal\PortalDeveloperController::class, 'showLogin'])->name('portal.developer.login.show');
    Route::post('desarrollador', [App\Http\Controllers\Portal\PortalDeveloperController::class, 'login'])->name('portal.developer.login');
    Route::get('desarrollador/dashboard', [App\Http\Controllers\Portal\PortalDeveloperController::class, 'dashboard'])->name('portal.developer.dashboard');
    Route::post('desarrollador/logout', [App\Http\Controllers\Portal\PortalDeveloperController::class, 'logout'])->name('portal.developer.logout');

    // Gestores / vendedores
    Route::get('gestor', [App\Http\Controllers\Portal\PortalVendedorController::class, 'showLogin'])->name('portal.vendedor.login.show');
    Route::post('gestor', [App\Http\Controllers\Portal\PortalVendedorController::class, 'login'])->name('portal.vendedor.login');
    Route::get('gestor/dashboard', [App\Http\Controllers\Portal\PortalVendedorController::class, 'dashboard'])->name('portal.vendedor.dashboard');
    Route::post('gestor/logout', [App\Http\Controllers\Portal\PortalVendedorController::class, 'logout'])->name('portal.vendedor.logout');
});

/* ---------- Landing Pages (debe ir ANTES de otras rutas dinámicas) ---------- */
Route::get('/landings', [LandingController::class, 'index'])->name('landings.index'); // Opcional: índice de landings

/* ---------- Landing y páginas públicas ---------- */
Route::get('/', [HomeController::class, 'index'])->name('home');
// Redirect 301 legacy /home-v2 → / por si quedó cacheado en historial o links externos
Route::permanentRedirect('/home-v2', '/');
// Redirects 301 legacy e-commerce (proyecto era una tienda antes — Google aún indexó esas URLs)
Route::permanentRedirect('/inicio', '/');
Route::permanentRedirect('/home', '/');
Route::permanentRedirect('/categories/{any}', '/proyectos')->where('any', '.*');
Route::permanentRedirect('/products/{any}', '/proyectos')->where('any', '.*');
Route::get('/servicios', [ServiciosController::class, 'index'])->name('servicios.index');
Route::get('/proyectos', [App\Http\Controllers\ServiciosController::class, 'indexproyectos'])->name('proyectos.index');
Route::get('/proyectos/{slug}', [App\Http\Controllers\ProyectoPublicController::class, 'show'])->name('proyectos.show');
Route::get('/sobre-nosotros', [App\Http\Controllers\ServiciosController::class, 'indexsobreNosotros'])->name('sobre_nosotros.index');
Route::get('/contacto', [App\Http\Controllers\ServiciosController::class, 'indexcontacto'])->name('contacto.index');
Route::post('/contacto', [App\Http\Controllers\ServiciosController::class, 'storeContacto'])->name('contacto.store');
Route::get('/gracias', [ServiciosController::class, 'gracias'])->name('contacto.gracias');

/* ---------- Blog Routes ---------- */
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
// Redirect 301: post renombrado - preserva el ranking (4014 impresiones / pos 5 en Google)
Route::permanentRedirect('/blog/cuanto-cuesta-contratar-agencia-desarrollo-software-colombia-2026', '/blog/cuanto-cuesta-una-agencia-de-software-en-colombia-2026');
Route::get('/blog/categoria/{category}', [BlogController::class, 'category'])->name('blog.category');
Route::get('/blog/tag/{tag}', [BlogController::class, 'tag'])->name('blog.tag');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('admin/pages/servicios/edit', [PageController::class, 'editServicios'])->name('admin.pages.servicios.edit');
Route::put('admin/pages/servicios/update', [PageController::class, 'updateServicios'])->name('admin.pages.servicios.update');
Route::get('/pages/proyectos/edit', [PageController::class, 'editProyectos'])->name('admin.pages.proyectos.edit');
Route::put('/pages/proyectos/update', [PageController::class, 'updateProyectos'])->name('admin.pages.proyectos.update');
Route::get('/pages/contacto/edit', [PageController::class, 'editContacto'])->name('admin.pages.contacto.edit');
Route::put('/pages/contacto/update', [PageController::class, 'updateContacto'])->name('admin.pages.contacto.update');
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
Route::get('/admin/seo/{page}/edit', [App\Http\Controllers\Admin\SeoController::class, 'edit'])->name('admin.seo.edit');
Route::put('/admin/seo/{page}', [App\Http\Controllers\Admin\SeoController::class, 'update'])->name('admin.seo.update');
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
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

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

// === RUTAS PARA GESTIÓN DE PROYECTOS ===
Route::resource('admin-proyectos', App\Http\Controllers\Admin\ProyectosController::class)
    ->parameters(['admin-proyectos' => 'proyecto'])
    ->names([
        'index' => 'admin.proyectos.index',
        'create' => 'admin.proyectos.create',
        'store' => 'admin.proyectos.store',
        'show' => 'admin.proyectos.show',
        'edit' => 'admin.proyectos.edit',
        'update' => 'admin.proyectos.update',
        'destroy' => 'admin.proyectos.destroy',
    ]);
Route::patch('admin-proyectos/{proyecto}/toggle', [App\Http\Controllers\Admin\ProyectosController::class, 'toggleActivo'])->name('admin.proyectos.toggle');

// === CLIENTES (usados desde proyectos internos) ===
Route::post('clients', [App\Http\Controllers\Admin\ClientController::class, 'store'])->name('admin.clients.store');

// === DESARROLLADORES (usados desde proyectos internos) ===
Route::post('developers', [App\Http\Controllers\Admin\DeveloperController::class, 'store'])->name('admin.developers.store');

// === VENDEDORES / GESTORES ===
Route::post('vendedores', [App\Http\Controllers\Admin\VendedorController::class, 'store'])->name('admin.vendedores.store');

// === PROYECTOS INTERNOS (GESTION INTERNA) ===
Route::middleware('auth')->group(function () {
    Route::get('internal-projects/stats/export', [App\Http\Controllers\Admin\InternalProjectController::class, 'statsExport'])->name('admin.internal-projects.stats.export');
    Route::get('internal-projects/stats', [App\Http\Controllers\Admin\InternalProjectController::class, 'stats'])->name('admin.internal-projects.stats');
    Route::get('internal-projects/detalle', [App\Http\Controllers\Admin\InternalProjectController::class, 'detalle'])->name('admin.internal-projects.detalle');
    Route::get('internal-projects/todos', [App\Http\Controllers\Admin\InternalProjectController::class, 'todos'])->name('admin.internal-projects.todos');
    Route::resource('internal-projects', App\Http\Controllers\Admin\InternalProjectController::class)
        ->names('admin.internal-projects');
    Route::post('internal-projects/{internal_project}/payments', [App\Http\Controllers\Admin\InternalProjectController::class, 'storePayment'])->name('admin.internal-projects.payments.store');
    Route::delete('internal-projects/{internal_project}/payments/{payment}', [App\Http\Controllers\Admin\InternalProjectController::class, 'destroyPayment'])->name('admin.internal-projects.payments.destroy');
    Route::post('internal-projects/{internal_project}/developer-payments', [App\Http\Controllers\Admin\InternalProjectController::class, 'storeDeveloperPayment'])->name('admin.internal-projects.developer-payments.store');
    Route::delete('internal-projects/{internal_project}/developer-payments/{developerPayment}', [App\Http\Controllers\Admin\InternalProjectController::class, 'destroyDeveloperPayment'])->name('admin.internal-projects.developer-payments.destroy');
    Route::post('internal-projects/{internal_project}/gestion-payments', [App\Http\Controllers\Admin\InternalProjectController::class, 'storeGestionPayment'])->name('admin.internal-projects.gestion-payments.store');
    Route::delete('internal-projects/{internal_project}/gestion-payments/{gestionPayment}', [App\Http\Controllers\Admin\InternalProjectController::class, 'destroyGestionPayment'])->name('admin.internal-projects.gestion-payments.destroy');
    Route::post('internal-projects/{internal_project}/expenses', [App\Http\Controllers\Admin\InternalProjectController::class, 'storeExpense'])->name('admin.internal-projects.expenses.store');
    Route::delete('internal-projects/{internal_project}/expenses/{expense}', [App\Http\Controllers\Admin\InternalProjectController::class, 'destroyExpense'])->name('admin.internal-projects.expenses.destroy');
    Route::post('internal-projects/{internal_project}/files', [App\Http\Controllers\Admin\InternalProjectController::class, 'storeFile'])->name('admin.internal-projects.files.store');
    Route::delete('internal-projects/{internal_project}/files/{file}', [App\Http\Controllers\Admin\InternalProjectController::class, 'destroyFile'])->name('admin.internal-projects.files.destroy');
});

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

/* ====================================================================
   PIPELINE COMERCIAL (CRM-lite)
   - comercial: solo sus propios leads/propuestas/reuniones (scoped)
   - admin: visibilidad total + conversión + comisiones
   ==================================================================== */
Route::middleware(['auth', 'role:admin|comercial'])->prefix('pipeline')->name('pipeline.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Pipeline\PipelineController::class, 'index'])->name('index');
    Route::post('leads/{lead}/stage', [\App\Http\Controllers\Pipeline\PipelineController::class, 'updateStage'])->name('leads.stage');

    Route::get('pendientes', [\App\Http\Controllers\Pipeline\LeadController::class, 'pendientes'])->name('pendientes');
    Route::get('perdidos', [\App\Http\Controllers\Pipeline\LeadController::class, 'perdidos'])->name('perdidos');
    Route::post('leads', [\App\Http\Controllers\Pipeline\LeadController::class, 'store'])->name('leads.store');
    Route::get('leads/{lead}', [\App\Http\Controllers\Pipeline\LeadController::class, 'show'])->name('leads.show');
    Route::put('leads/{lead}', [\App\Http\Controllers\Pipeline\LeadController::class, 'update'])->name('leads.update');
    Route::delete('leads/{lead}', [\App\Http\Controllers\Pipeline\LeadController::class, 'destroy'])->name('leads.destroy');
    Route::post('leads/{lead}/ganado', [\App\Http\Controllers\Pipeline\LeadController::class, 'marcarGanado'])->name('leads.ganado');
    Route::post('leads/{lead}/perdido', [\App\Http\Controllers\Pipeline\LeadController::class, 'marcarPerdido'])->name('leads.perdido');

    Route::post('leads/{lead}/activities', [\App\Http\Controllers\Pipeline\LeadActivityController::class, 'store'])->name('activities.store');

    Route::post('leads/{lead}/proposals', [\App\Http\Controllers\Pipeline\ProposalController::class, 'store'])->name('proposals.store');
    Route::put('proposals/{proposal}', [\App\Http\Controllers\Pipeline\ProposalController::class, 'update'])->name('proposals.update');
    Route::delete('proposals/{proposal}', [\App\Http\Controllers\Pipeline\ProposalController::class, 'destroy'])->name('proposals.destroy');

    Route::get('reuniones', [\App\Http\Controllers\Pipeline\MeetingController::class, 'index'])->name('meetings.index');
    Route::post('leads/{lead}/meetings', [\App\Http\Controllers\Pipeline\MeetingController::class, 'store'])->name('meetings.store');
    Route::put('meetings/{meeting}', [\App\Http\Controllers\Pipeline\MeetingController::class, 'update'])->name('meetings.update');
    Route::delete('meetings/{meeting}', [\App\Http\Controllers\Pipeline\MeetingController::class, 'destroy'])->name('meetings.destroy');

    Route::get('mis-resultados', [\App\Http\Controllers\Pipeline\MyResultsController::class, 'index'])->name('my-results');

    // Agendamiento contra el calendario del admin
    Route::get('disponibilidad', [\App\Http\Controllers\Pipeline\SchedulingController::class, 'availability'])->name('availability');
    Route::post('leads/{lead}/agendar-cierre', [\App\Http\Controllers\Pipeline\SchedulingController::class, 'book'])->name('leads.book');

    // Correos (mail marketing) — enviar + bandeja
    Route::get('correos', [\App\Http\Controllers\Pipeline\CorreosController::class, 'index'])->name('correos.index');
    Route::post('correos', [\App\Http\Controllers\Pipeline\CorreosController::class, 'send'])->name('correos.send');
    Route::get('correos/bandeja', [\App\Http\Controllers\Pipeline\CorreosController::class, 'bandeja'])->name('correos.bandeja');
    Route::post('correos/sincronizar', [\App\Http\Controllers\Pipeline\CorreosController::class, 'sincronizar'])->name('correos.sincronizar');
    Route::get('correos/bandeja/{uid}', [\App\Http\Controllers\Pipeline\CorreosController::class, 'leer'])->whereNumber('uid')->name('correos.leer');
    Route::post('correos/responder', [\App\Http\Controllers\Pipeline\CorreosController::class, 'responder'])->name('correos.responder');
});

Route::middleware(['auth', 'role:admin'])->prefix('pipeline')->name('pipeline.')->group(function () {
    Route::post('leads/{lead}/convertir', [\App\Http\Controllers\Pipeline\ConversionController::class, 'convert'])->name('leads.convert');
    Route::get('dashboard', [\App\Http\Controllers\Pipeline\CommercialDashboardController::class, 'index'])->name('dashboard');
    Route::get('comisiones', [\App\Http\Controllers\Pipeline\CommissionController::class, 'index'])->name('commissions');
    Route::put('comisiones', [\App\Http\Controllers\Pipeline\CommissionController::class, 'update'])->name('commissions.update');

    // Reporte de correos enviados por comercial (auditoría)
    Route::get('correos/reporte', [\App\Http\Controllers\Pipeline\CorreosController::class, 'reporte'])->name('correos.reporte');

    // Importación masiva de clientes + reparto aleatorio a comerciales
    Route::get('clientes/importar', [\App\Http\Controllers\Pipeline\ClientesImportController::class, 'index'])->name('clientes.importar');
    Route::get('clientes/plantilla', [\App\Http\Controllers\Pipeline\ClientesImportController::class, 'plantilla'])->name('clientes.plantilla');
    Route::post('clientes/cargar', [\App\Http\Controllers\Pipeline\ClientesImportController::class, 'importar'])->name('clientes.cargar');
    Route::post('clientes/repartir', [\App\Http\Controllers\Pipeline\ClientesImportController::class, 'repartir'])->name('clientes.repartir');

    // Google Calendar (conexión del admin)
    Route::get('calendar', [\App\Http\Controllers\Pipeline\CalendarController::class, 'index'])->name('calendar');
    Route::get('calendar/connect', [\App\Http\Controllers\Pipeline\CalendarController::class, 'connect'])->name('calendar.connect');
    Route::get('calendar/callback', [\App\Http\Controllers\Pipeline\CalendarController::class, 'callback'])->name('calendar.callback');
    Route::delete('calendar/disconnect', [\App\Http\Controllers\Pipeline\CalendarController::class, 'disconnect'])->name('calendar.disconnect');
});

/* ---------- Gestión de usuarios / equipo (solo admin) ---------- */
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('usuarios', \App\Http\Controllers\Admin\UserController::class)
        ->parameters(['usuarios' => 'user'])
        ->names('admin.users')
        ->except('show');
});

require __DIR__.'/auth.php';

/* ---------- Ruta catch-all para Landing Pages (DEBE IR AL FINAL ABSOLUTO) ---------- */
// Esta ruta captura cualquier slug que no haya coincidido con las rutas anteriores
// Debe estar DESPUÉS de auth.php para no interferir con login/register/etc
Route::get('/{slug}', [LandingController::class, 'show'])
    ->where('slug', '[a-z0-9\-]+')
    ->name('landing.show');
