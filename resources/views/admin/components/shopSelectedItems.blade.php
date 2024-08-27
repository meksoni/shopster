<!-- admin/components/shopSelectedItems.blade.php -->
<form action="{{ route('selectedItems.edit') }}" method="POST">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-3 mb-2">
            <div class="form-group">
                <label for="brand_id">Spisak Brendova</label>
                <select name="brand_id" id="brand_id" class="form-select">
                    <option value="">-- Izaberite Brend --</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ $selectedItems->brand_id == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }} - ({{ $brand->products_count }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-3 mb-2">
            <div class="form-group">
                <label for="category_id">Spisak Kategorija</label>
                <select name="category_id" id="category_id" class="form-select">
                    <option value="">-- Izaberite Kategoriju --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $selectedItems->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }} - ({{ $category->products_count }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-3 mb-2">
            <div class="form-group">
                <label for="sub_category_id">Spisak Pod-Kategorija</label>
                <select name="sub_category_id" id="sub_category_id" class="form-select">
                    <option value="">-- Izaberite Pod-Kategoriju --</option>
                    @foreach($sub_categories as $subcategory)
                        <option value="{{ $subcategory->id }}" {{ $selectedItems->sub_category_id == $subcategory->id ? 'selected' : '' }}>
                            {{ $subcategory->name }} - ({{ $subcategory->products_count }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="col-md-3 mb-5">
            <div class="form-group">
                <label for="sub_sub_category_id">Spisak Pod-Pod-Kategorija</label>
                <select name="sub_sub_category_id" id="sub_sub_category_id" class="form-select">
                    <option value="">-- Izaberite Pod-pod-Kategoriju --</option>
                    @foreach($sub_sub_categories as $subsubcategory)
                        <option value="{{ $subsubcategory->id }}" {{ $selectedItems->sub_sub_category_id == $subsubcategory->id ? 'selected' : '' }}>
                            {{ $subsubcategory->name }} - ({{ $subsubcategory->products_count }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

    </div>

    <button type="submit" class="btn w-100 btn-primary text-none">Sačuvaj podešavanja</button>
</form>
