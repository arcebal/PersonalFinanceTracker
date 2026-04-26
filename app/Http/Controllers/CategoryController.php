<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('user_id', auth()->id())->latest()->get();
        return view('categories.index', compact('categories'));
    }

    public function trashed()
    {
        $categories = Category::onlyTrashed()->where('user_id', auth()->id())->get();
        return view('categories.trashed', compact('categories'));
    }

    public function restore($id)
    {
        $c = Category::withTrashed()->where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $c->restore();
        return redirect()->back()->with('success', 'Category restored.');
    }

    public function forceDelete($id)
    {
        $c = Category::withTrashed()->where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $c->forceDelete();
        return redirect()->back()->with('success', 'Category permanently deleted.');
    }

    public function exportCsv()
    {
        $categories = Category::where('user_id', auth()->id())->latest()->get();

        $filename = 'categories_export_'.date('Ymd_His').'.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($categories) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Name','Type','Color']);
            foreach ($categories as $c) {
                fputcsv($handle, [
                    $c->name,
                    $c->type,
                    $c->color,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'type'  => 'required|in:income,expense',
            'color' => 'required|string',
        ]);

        Category::create([
            'user_id' => auth()->id(),
            'name'    => $request->name,
            'type'    => $request->type,
            'color'   => $request->color,
        ]);

        return redirect()->route('categories.index')->with('success', 'Category created successfully!');
    }

    public function show(Category $category)
    {
        abort_if($category->user_id !== auth()->id(), 403);
        return redirect()->route('categories.edit', $category);
    }

    public function edit(Category $category)
    {
        abort_if($category->user_id !== auth()->id(), 403);
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        abort_if($category->user_id !== auth()->id(), 403);
        $request->validate([
            'name'  => 'required|string|max:255',
            'type'  => 'required|in:income,expense',
            'color' => 'required|string',
        ]);
        $category->update($request->only('name', 'type', 'color'));
        return redirect()->route('categories.index')->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category)
    {
        abort_if($category->user_id !== auth()->id(), 403);
        $category->delete();
        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully!')
            ->with('undo', route('categories.restore', $category->id));
    }
}