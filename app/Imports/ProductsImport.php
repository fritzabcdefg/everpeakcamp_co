<?php

namespace App\Imports;

use App\Models\Category;
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
        $categoryId = null;

        if (!empty($row['category_id'])) {
            $category = Category::find($row['category_id']);
            $categoryId = $category ? $category->category_id : null;
        }

        if (empty($categoryId) && !empty($row['category_name'])) {
            $category = Category::firstOrCreate([
                'name' => trim($row['category_name']),
            ]);
            $categoryId = $category->category_id;
        }

        return new Product([
            'name'        => $row['name'] ?? $row['product_name'] ?? null,
            'description' => $row['description'] ?? null,
            'cost_price'  => $row['cost_price'] ?? $row['cost'] ?? 0,
            'sell_price'  => $row['sell_price'] ?? $row['price'] ?? 0,
            'category_id' => $categoryId,
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'cost_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'category_name' => 'nullable|string',
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
