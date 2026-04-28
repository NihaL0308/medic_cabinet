<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LangController extends Controller {
    public function switch(Request $request, string $locale) {
    if (!in_array($locale, ['fr', 'ar'])) abort(400);
    session(['locale' => $locale]);
    return redirect()->back();
}
}