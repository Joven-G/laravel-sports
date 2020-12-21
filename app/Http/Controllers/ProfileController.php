<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
  public function __construct()
  {
     $this->middleware('auth');
  }

  public function edit(Request $request)
  {
    return view('profiles.edit', [
      'user' => $request->user()
    ]);
  }

  public function update(ProfileRequest $request)
  {
    return DB::transaction(function () use ($request) {
      $user = $request->user();

      $user->fill($request->validated());

      if ($user->isDirty('email')) {
        $user->email_verified_at = null;
        // $user->sendEmailVerificationNotification();
      }

      $user->save();

      // dd($request->file('image'));

      // if ($request->hasFile('image')) {
      //   if ($user->image != null) {
      //     Storage::disk('images')->delete($user->image->path);
      //     $user->image->delete();
      //   }

      //   $user->image()->create([
      //     'path' => $request->image->store('users', 'images'),
      //   ]);
      // }

      return back()->with('flash', 'Su perfil fue editado con éxito!');    
    }, 5);

  }
}
