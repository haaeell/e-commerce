<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['user', 'product'])->latest()->get();
        return view('reviews.index', compact('reviews'));
    }

    public function toggleVerify($id)
    {
        $review = Review::findOrFail($id);
        $review->update([
            'is_verified' => !$review->is_verified
        ]);

        return redirect()->back()->with('success', 'Status ulasan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();
        return redirect()->back()->with('success', 'Ulasan berhasil dihapus');
    }
}
