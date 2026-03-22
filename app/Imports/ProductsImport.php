<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Product([
            'name'        => $row['name'] ?? $row['product_name'] ?? null,
            'description' => $row['description'] ?? null,
            'cost_price'  => $row['cost_price'] ?? $row['cost'] ?? 0,
            'sell_price'  => $row['sell_price'] ?? $row['price'] ?? 0,
            'category_id' => $row['category_id'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'cost_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'name.required' => 'Product name is required',
            'cost_price.required' => 'Cost price is required',
            'sell_price.required' => 'Sell price is required',
        ];
    }
}
