@csrf

<style>
/* Fondo principal para evitar el blanco */
body, .container, .container-fluid {
    background: #101820 !important;
    color: #FCFAF1;
}

/* Asegurar que el contenedor del formulario tenga fondo */
.form-container, .main-content {
    background: #1a252f;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #00A9E0;
}

/* Dark mode amigable dentro del editor */
.ck-content {
  background: #111 !important;
  color: #e6e6e6 !important;
}
.ck.ck-editor__main>.ck-editor__editable {
  border-color: #444 !important;
}
.ck.ck-toolbar {
  background: #121212 !important;
  border-color: #444 !important;
}
.ck.ck-button, .ck.ck-toolbar__separator {
  filter: brightness(0.9);
}
</style>

<div class="form-container">

<div class="mb-4">
    <label class="form-label fw-bold text-light">Product Name *</label>
    <input type="text" name="name" class="form-control bg-dark text-light border-secondary"
           value="{{ old('name', $product->name ?? '') }}" required>
</div>

<div class="mb-4">
  <label class="form-label fw-bold text-light">Description</label>
  <textarea id="description" name="description" rows="6" class="form-control bg-dark text-light border-secondary">{{ old('description', $product->description ?? '') }}</textarea>
</div>


<div class="row">
    

    <div class="col-md-4 mb-4">
        <label class="form-label fw-bold text-light">Stock *</label>
        <input type="number" name="stock" class="form-control bg-dark text-light border-secondary"
               value="{{ old('stock', $product->stock ?? 0) }}" required>
    </div>

    <div class="col-md-4 mb-4">
        <label class="form-label fw-bold text-light">Info adicional tarjeta</label>
        <input type="text" name="avg_weight" class="form-control bg-dark text-light border-secondary"
               value="{{ old('avg_weight', $product->avg_weight ?? '') }}" placeholder="e.g. 7 Lbs or 3.2 Kg">
    </div>
</div>

<div class="mb-4">
    <label class="form-label fw-bold text-light">Product Images</label>
    <input type="file" name="images[]" class="form-control bg-dark text-light border-secondary" multiple>
    
    @if(isset($product) && $product->images && $product->images->count() > 0)
        <small class="text-muted d-block mt-2 mb-3">
            Currently has {{ $product->images->count() }} image{{ $product->images->count() > 1 ? 's' : '' }}
        </small>
        
        <!-- Grid de imágenes existentes -->
        <div class="row mt-3" id="images-container">
            @foreach($product->images as $image)
                <div class="col-md-3 col-sm-4 col-6 mb-3" id="image-{{ $image->id }}">
                    <div class="position-relative border border-secondary rounded p-2" style="background-color: #1a1a1a;">
                        <!-- Imagen -->
                        <img src="{{ Storage::url($image->image) }}" 
                             class="img-fluid rounded mb-2" 
                             style="height: 120px; width: 100%; object-fit: cover;" 
                             alt="Product image">
                        
                        <!-- Botón de eliminar -->
                        <button type="button" 
                                class="btn btn-danger btn-sm w-100 d-flex align-items-center justify-content-center gap-1"
                                onclick="deleteImageAjax({{ $image->id }})">
                            <span>🗑️</span> Delete
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
<div class="mb-4">
    <label class="form-label fw-bold text-light">Category *</label>
  <select name="category_id" class="form-select bg-dark text-light border-secondary" required>
    <option value="">-- Select a category --</option>
    @foreach($categories as $cat)
        <option value="{{ $cat->id }}"
            {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
            {{ $cat->name }} - {{ $cat->country }}
        </option>
    @endforeach
</select>
</div>
<div class="mb-4">
    <label class="form-label fw-bold text-light">Impuestos y Envío por País/Ciudad</label>

    <div id="price-location-container">
        @if(isset($product) && $product->prices->count() > 0)
            {{-- Si hay configuraciones existentes, mostrarlas --}}
            @foreach($product->prices as $i => $price)
                <div class="row border rounded p-3 mb-3 bg-dark">
                    <div class="col-md-3 mb-2">
                        <label class="form-label text-light">Countrys</label>
                        <select name="prices[{{ $i }}][country_id]" class="form-select bg-dark text-light" onchange="loadCities(this)">
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ $price->country_id == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 mb-2">
                        <label class="form-label text-light">Cities</label>
                        <select name="prices[{{ $i }}][city_id]" class="form-select bg-dark text-light">
                            @php
                                $countryWithCities = $countries->firstWhere('id', $price->country_id);
                            @endphp

                            @if($countryWithCities && $countryWithCities->cities)
                                @foreach($countryWithCities->cities as $city)
                                    <option value="{{ $city->id }}" {{ $price->city_id == $city->id ? 'selected' : '' }}>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-md-2 mb-2">
                        <label class="form-label text-light">Impuesto</label>
                        <input type="number" 
                               name="prices[{{ $i }}][interest]" 
                               class="form-control bg-dark text-light" 
                               value="{{ $price->interest }}"
                               step="0.01"
                               placeholder="15.5">
                    </div>

                    <div class="col-md-2 mb-2">
                        <label class="form-label text-light">Costo Envío</label>
                        <input type="number" 
                               name="prices[{{ $i }}][shipping]" 
                               class="form-control bg-dark text-light" 
                               value="{{ $price->shipping }}"
                               step="0.01">
                    </div>

                    <div class="col-md-2 mb-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm w-100" onclick="removePriceBlock(this)">
                            🗑️ Eliminar
                        </button>
                    </div>
                </div>
            @endforeach
        @else
            {{-- Si no hay configuraciones, mostrar un bloque vacío --}}
            <div class="row border rounded p-3 mb-3 bg-dark">
                <div class="col-md-3 mb-2">
                    <label class="form-label text-light">País</label>
                    <select name="prices[0][country_id]" class="form-select bg-dark text-light" onchange="loadCities(this)">
                        <option value="">-- Selecciona país --</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 mb-2">
                    <label class="form-label text-light">Ciudad</label>
                    <select name="prices[0][city_id]" class="form-select bg-dark text-light">
                        <option value="">-- Selecciona país primero --</option>
                    </select>
                </div>

                <div class="col-md-2 mb-2">
                    <label class="form-label text-light">Impuesto (%)</label>
                    <input type="number" 
                           name="prices[0][interest]" 
                           class="form-control bg-dark text-light" 
                           value="0"
                           step="0.01"
                           placeholder="15.5">
                </div>

                <div class="col-md-2 mb-2">
                    <label class="form-label text-light">Costo Envío</label>
                    <input type="number" 
                           name="prices[0][shipping]" 
                           class="form-control bg-dark text-light" 
                           value="0"
                           step="0.01">
                </div>

                <div class="col-md-2 mb-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm w-100" onclick="removePriceBlock(this)">
                        🗑️ Eliminar
                    </button>
                </div>
            </div>
        @endif
    </div>

    <button type="button" class="btn btn-outline-light btn-sm" onclick="addPriceBlock()">
        ➕ Añadir configuración por ubicación
    </button>
</div>

{{-- El precio base del producto se mantiene separado --}}
<div class="col-md-4 mb-4">
    <label class="form-label fw-bold text-light">Precio Base *</label>
    <input type="number" name="price" step="0.01" class="form-control bg-dark text-light border-secondary"
           value="{{ old('price', $product->price ?? 0) }}" required>
    <small class="text-muted">Este es el precio base del producto</small>
</div>

<div class="d-flex justify-content-between mt-4">
    <button class="btn btn-success px-4">💾 Save Product</button>
    <!-- <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a> -->
</div>

</div>

<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>


<script>

    ClassicEditor
  .create(document.querySelector('#description'), {
    toolbar: [
      'heading', '|',
      'bold','italic','underline','link','bulletedList','numberedList','blockQuote','undo','redo'
    ],
    link: { addTargetToExternalLinks: true },
    removePlugins: [
      'CKBox','CKFinder','EasyImage','ImageUpload','MediaEmbed','Table','TableToolbar'
    ]
  })
  .then(editor => {
    // Ajuste visual para dark mode
    const editable = editor.ui.getEditableElement();
    editable.style.backgroundColor = '#111';
    editable.style.color = '#e6e6e6';
    editable.style.minHeight = '180px';
    editable.style.border = '1px solid #444';
    editable.style.borderRadius = '6px';
  })
  .catch(console.error);
let priceIndex = {{ isset($product) && $product->prices->count() > 0 ? $product->prices->count() : 1 }};
const countries = @json($countries);

function addPriceBlock() {
    let block = `
        <div class="row border rounded p-3 mb-3 bg-dark">
            <div class="col-md-3 mb-2">
                <label class="form-label text-light">País</label>
                <select name="prices[\${priceIndex}][country_id]" class="form-select bg-dark text-light" onchange="loadCities(this)">
                    <option value="">-- Selecciona país --</option>
                    ${countries.map(c => `<option value="${c.id}">${c.name}</option>`).join('')}
                </select>
            </div>

            <div class="col-md-3 mb-2">
                <label class="form-label text-light">Ciudad</label>
                <select name="prices[\${priceIndex}][city_id]" class="form-select bg-dark text-light">
                    <option value="">-- Selecciona país primero --</option>
                </select>
            </div>

            <div class="col-md-2 mb-2">
                <label class="form-label text-light">Impuesto (%)</label>
                <input type="number" 
                       name="prices[\${priceIndex}][interest]" 
                       class="form-control bg-dark text-light" 
                       value="0"
                       step="0.01"
                       placeholder="15.5">
            </div>

            <div class="col-md-2 mb-2">
                <label class="form-label text-light">Costo Envío</label>
                <input type="number" 
                       name="prices[\${priceIndex}][shipping]" 
                       class="form-control bg-dark text-light" 
                       value="0"
                       step="0.01">
            </div>

            <div class="col-md-2 mb-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm w-100" onclick="removePriceBlock(this)">
                    🗑️ Eliminar
                </button>
            </div>
        </div>
    `;
    document.getElementById('price-location-container').insertAdjacentHTML('beforeend', block);
    priceIndex++;
}

function loadCities(select) {
    const countryId = parseInt(select.value);
    const cities = countries.find(c => c.id === countryId)?.cities || [];
    const citySelect = select.parentElement.nextElementSibling.querySelector('select');

    citySelect.innerHTML = `<option value="">-- Selecciona ciudad --</option>`;
    citySelect.innerHTML += cities.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
}

function removePriceBlock(button) {
    button.closest('.row').remove();
}
</script>