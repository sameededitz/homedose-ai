<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductEdit extends Component
{
    use WithFileUploads;

    public Product $product;

    #[Validate]
    public $image;

    #[Validate]
    public $name;

    #[Validate]
    public $link;

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->name = $product->name;
        $this->link = $product->link;
    }

    public function rules()
    {
        return [
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:20480',
            'name' => 'required|string|max:255',
            'link' => 'required|string|max:9999',
        ];
    }

    public function submit()
    {
        $this->validate();
        $this->product->update([
            'name' => $this->name,
            'link' => $this->link,
        ]);

        if ($this->image) {
            $this->product->clearMediaCollection('image');
            $this->product->addMedia($this->image->getRealPath())
                ->usingFileName(time() . '_product_' . $this->product->id . '.' . $this->image->getClientOriginalExtension())
                ->toMediaCollection('image');
        }
        return redirect()->route('all-products')->with([
            'status' => 'success',
            'message' => 'Product updated successfully!',
        ]);
    }

    public function render()
    {
        return view('livewire.product-edit');
    }
}
