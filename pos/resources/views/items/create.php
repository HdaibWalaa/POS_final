<div class=" form-item-creat">

    <h1 class="table-title m-2" id="creatitem">Create Item</h1>
    <form action="/items/store" method="POST" class="col-xl-6 col-md-12 col-sm-12 col-xs-12 box p-4 m-auto" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="item-image" class="form-label">
                <image id="img-item" src="https://via.placeholder.com/300x300.png?text=UPLOAD" style="pointer-events: none" />
            </label>
            <input type="file" class="form-control" id="item-image" name="image">
        </div>
        <div class="mb-3">
            <label for="item-name" class="form-label">Item Name</label>
            <input type="text" class="form-control" id="item-name" name="item_name" required>
        </div>
        <div class="mb-3">
            <label for="item-quantity" class="form-label">Quantity </label>
            <input type="number" class="form-control" id="item-quantity" name="quantity" min="0" step="any" required>
        </div>
        <div class="mb-3">
            <label for="item-cost" class="form-label">Cost </label>
            <input type="number" class="form-control" id="item-cost" name="cost" min="0" step="any" required>
        </div>
        <div class="mb-3">
            <label for="item-selling-price" class="form-label">Selling Price </label>
            <input type="number" class="form-control" id="item-selling-price" name="selling_price" min="0" step="any" required>
        </div>
        <div class="mb-3">
            <label for="item-category" class="form-label">category</label>
            <select class="form-control mt-2" id="category" name="category">
                <option>choose category</option>
                <option>Coffee</option>
                <option>Milk Passed</option>
                <option>Jucie</option>
                <option>Sandwish</option>
                <option>Desert</option>
            </select>
        </div>
        <button type="submit" class="btn btn-outline-success mt-4">Create</button>
    </form>
</div>