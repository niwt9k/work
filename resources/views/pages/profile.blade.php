@include('includes.header')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="my-4">Профиль</h1>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h2>{{ Auth::user()->name }}</h2>
                            <p>Email: {{ Auth::user()->email }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <h2 class="my-4">История покупок</h2>
            @foreach($bookings as $booking)
                <div class="card my-2">
                    <div class="card-body">
                        <h5 class="card-title">Покупка #{{ $booking->id }}</h5>
                        <p class="card-text">Название: {{ $booking->tour->name }}</p>
                        <p class="card-text">Количество людей: {{ $booking->count_people }}</p>
                        <p class="card-text">Дата начала: {{ $booking->tour->start_date }}</p>
                        <p class="card-text">Дата окончания: {{ $booking->tour->end_date }}</p>
                        <p class="card-text">Цена: {{ $booking->tour->price * $booking->count_people }} руб.</p>
                        @if(empty($booking->rating))
                            <form action="{{ route('booking.review', $booking->id) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="comment">Оставьте отзыв:</label>
                                    <textarea class="form-control" id="comment" name="comment"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Оценка:</label>
                                    <div>
                                        @for($i = 1; $i <= 5; $i++)
                                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }}>
                                            <label for="star{{ $i }}">{{ $i }}</label>
                                        @endfor
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Отправить отзыв</button>
                            </form>
                        @else
                            <p>Ваш отзыв: {{ $booking->comment }}</p>
                            <p>Ваша оценка: {{ $booking->rating }} из 5</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@include('includes.footer')