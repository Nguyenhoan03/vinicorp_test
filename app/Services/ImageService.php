<?php

namespace App\Services;

use Illuminate\Http\Request;

class ImageService
{
    public function handleImageUpload(Request $request)
    {
        if ($request->hasFile('img')) {
            $imageName = time() . '.' . $request->img->getClientOriginalExtension();
            $request->img->move(public_path('upload/images'), $imageName);
            return $imageName;
        }
        return null;
    }
}
