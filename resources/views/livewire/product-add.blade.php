<div>
    @if (session('message'))
        <div class="row py-3">
            <div class="col-6">
                <x-alert type="success" :message="session('message', 'Operation completed successfully.')" />
            </div>
        </div>
    @endif

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0"></h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('admin-home') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Products</li>
        </ul>
    </div>

    <div class="row gy-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Add Product</h5>
                </div>
                <div class="card-body">
                    <div class="py-2">
                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <x-alert type="danger" :message="$error" />
                            @endforeach
                        @endif
                    </div>
                    <form wire:submit.prevent="submit" enctype="multipart/form-data">
                        <div class="row gy-3">
                            <div class="col-12">
                                <label class="form-label">Product Image</label>
                                <div class="upload-image-wrapper d-flex align-items-center gap-3">
                                    @if ($image)
                                        <div
                                            class="uploaded-img position-relative h-120-px w-120-px border input-form-light radius-8 overflow-hidden border-dashed bg-neutral-50">
                                            <button type="button" wire:click="removeImage"
                                                class="uploaded-img__remove position-absolute top-0 end-0 z-1 text-2xxl line-height-1 me-8 mt-8 d-flex">
                                                <iconify-icon icon="radix-icons:cross-2"
                                                    class="text-xl text-danger-600"></iconify-icon>
                                            </button>
                                            <img id="uploaded-img__preview" class="w-100 h-100 object-fit-cover"
                                                src="{{ $image->temporaryUrl() }}" alt="server_image">
                                        </div>
                                    @else
                                        <label
                                            class="upload-file h-120-px w-120-px border input-form-light radius-8 overflow-hidden border-dashed bg-neutral-50 bg-hover-neutral-200 d-flex align-items-center flex-column justify-content-center gap-1"
                                            for="upload-file">
                                            <iconify-icon icon="solar:camera-outline"
                                                class="text-xl text-secondary-light"></iconify-icon>
                                            <span class="fw-semibold text-secondary-light">Upload</span>
                                            <input id="upload-file" wire:model.live="image" type="file" hidden>
                                            <div wire:loading wire:target="image">Uploading...</div>
                                        </label>
                                    @endif
                                </div>
                                <p class="text-sm mt-1 mb-0">The Image Should be Less than 20MB.</p>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Name</label>
                                <input type="text" wire:model.blur="name" class="form-control"
                                    placeholder="Product Name">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Link</label>
                                <input type="text" wire:model.blur="link" class="form-control"
                                    placeholder="Product Name">
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary-600">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div><!-- card end -->
        </div>
    </div>
</div>
