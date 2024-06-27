<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class ActionsController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'user.name' => 'required',
            'user.email' => 'required|email|unique:users,email',
            'user.password' => 'required|min:8|alpha_dash|confirmed',
        ], [
            'user.name.required' => 'Поле "Имя" обязательно для заполнения',
            'user.email.reqired' => 'Поле "Электронная почта" обязательно для заполнения',
            'user.email.email'=> 'Поле "Электронная почта" должно быть предоставлено в виде валидного адреса электронной почты',
            'user.password.required'=> 'Поле "Пароль" обязательно для заполнения',
            'user.password.min'=> 'Поле "Пароль" должно быть не менее, чем 8 символов',
            'user.password.alpha_dash'=> 'Поле "Пароль" должно содержать только строчные и прописные символы латиницы, цифры, а также символы "-" и "_"',
            'user.password.confirmed'=> 'Поле "Пароль" и "Повторите пароль" не совпадает',
        ]);

        $user = User::create($request -> input('user'));
        Auth::login($user);
        return redirect('/');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function login(Request $request)
    {
        $request->validate([
            'user.email'=> 'required|email',
            'user.password'=> 'required|min:8|alpha_dash',
        ], [
            'user.email.reqired' => 'Поле "Электронная почта" обязательно для заполнения',
            'user.email.email'=> 'Поле "Электронная почта" должно быть предоставлено в виде валидного адреса электронной почты',
            'user.password.required'=> 'Поле "Пароль" обязательно для заполнения',
            'user.password.min'=> 'Поле "Пароль" должно быть не менее, чем 8 символов',
            'user.password.alpha_dash'=> 'Поле "Пароль" должно содержать только строчные и прописные символы латиницы, цифры, а также символы "-" и "_"',
        ]);
        if(Auth::attempt($request -> input('user'))) {
            return redirect('/');
        } else {
            return back() -> withErrors([
                'user.email' => 'Предоставленная почта или пароль не подходят'
            ]);
        }
    }

    public function tour_buy(Request $request, Tour $tour)
    {
        $request->validate([
            'people' => 'required|integer|min:1',
        ], [
            'people.required' => 'Поле "Количество человек" обязательно для заполнения',
            'people.integer' => 'Поле "Количество человек" должно быть предоставлено в виде числа',
            'people.min' => 'Поле "Количество человек" должно быть не менее 1',
        ]);

        if($tour->max_people < $request->input('people')) {
            return back()->with('error', 'Количество человек не должно превышать максимальное количество');
        }

        $tour->max_people -= $request->input('people');
        $tour->save();

        $booking = new Booking();
        $booking->count_people = $request->input('people');
        $booking->tour_id = $tour->id;
        $booking->user_id = Auth::id();
        $booking->save();

        return redirect()->route('profile');
    }

    public function booking_review(Request $request, Booking $booking)
    {
        $request->validate([
            'comment' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ], [
            'comment.required' => 'Поле "Отзыв" обязательно для заполнения',
            'comment.string' => 'Поле "Отзыв" должно быть предоставлено в виде строки',
            'rating.required' => 'Поле "Рейтинг" обязательно для заполнения',
            'rating.integer' => 'Поле "Рейтинг" должно быть предоставлено в виде числа',
            'rating.min' => 'Поле "Рейтинг" должно быть не менее 1',
            'rating.max' => 'Поле "Рейтинг" должно быть не более 5',
        ]);

        if($booking->user_id != Auth::id()) {
            return back()->with('error', 'Вы не можете оставить отзыв на этот тур');
        }

        $booking->comment = $request->input('comment');
        $booking->rating = $request->input('rating');
        $booking->save();

        return back();
    }
}
