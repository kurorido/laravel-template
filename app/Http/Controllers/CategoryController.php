<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Category::class, 'category');
    }

    public function index()
    {
        $categories = QueryBuilder::for(Category::class)
            ->paginate();

        return JsonResource::collection($categories);
    }

    public function show(Category $category)
    {
        return new JsonResource($category);
    }

    public function store(Request $request)
    {
        $data = $request->validate([]);

        $category = Category::create($data);

        return new JsonResource($category);
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([]);

        $category->update($data);

        return new JsonResource($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return new JsonResource($category);
    }
}
