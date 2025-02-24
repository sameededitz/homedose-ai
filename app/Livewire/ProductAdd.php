<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductAdd extends Component
{
    use WithFileUploads;

    #[Validate]
    public $image;

    #[Validate]
    public $name;

    #[Validate]
    public $link;

    public function rules()
    {
        return [
            'image' => 'required|image|mimes:jpg,png,jpeg|max:20480',
            'name' => 'required|string|max:255',
            'link' => 'required|string|max:9999',
        ];
    }

    public function submit()
    {
        $this->validate();
        $product = Product::create([
            'name' => $this->name,
            'link' => $this->link,
        ]);

        if ($this->image) {
            $product->addMedia($this->image->getRealPath())
                ->usingFileName(time() . '_product_' . $product->id . '.' . $this->image->getClientOriginalExtension())
                ->toMediaCollection('image');
        }
        return redirect()->route('all-products')->with([
            'status' => 'success',
            'message' => 'Product added successfully!',
        ]);
    }

    public function removeImage()
    {
        $this->image = null;
    }

    public function render()
    {
        return view('livewire.product-add');
    }
}
