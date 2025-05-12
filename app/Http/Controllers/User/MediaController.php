<?php
namespace App\Http\Controllers\User;

use App\Models\Media;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MediaController extends Controller
{
    public function index()
    {
        $media = Media::latest()->get();
        return view('user.media.index', compact('media'));
    }

}
