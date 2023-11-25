<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Image\ImageManager;

class UpdateUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function view()
    {
        return view('auth.update');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);


        $user = Auth::user();

        $user_model = User::where('id', $user->id)->find(1);
        $user_model->name = $request->name;
        $user_model->password = Hash::make($request->password);
        $user_updated = $user_model->save();

        if ($user_updated > 0) {
            $imageManager = new ImageManager();
            $imageManager->delete($user->id);

            $photo_data = $request->file('photos');

            $result = $imageManager->store($photo_data, $user->id);
        }

        return redirect(RouteServiceProvider::HOME);
    }
}
