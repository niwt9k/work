@include('includes.header')

<div class="row">
    @foreach ($tours as $index => $tour)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h2 class="card-title">{{ $tour->name }}</h2>
                    <p class="card-text">Откуда: {{ $tour->from }}</p>
                    <p class="card-text">Куда: {{ $tour->to }}</p>
                    <p class="card-text">Свободных мест: {{ $tour->max_people }}</p>
                    <p class="card-text">Дата начала: {{ $tour->start_date }}</p>
                    <p class="card-text">Дата окончания: {{ $tour->end_date }}</p>
                    <p class="card-text">Цена: {{ $tour->price }} руб.</p>
                    @auth
                        <form action="{{ route('tour.buy', $tour) }}" method="POST" class="d-flex align-items-center" style="margin-left: 10px;">
                            @csrf
                            <div class="mb-3 mr-2">
                                <label for="quantity" class="form-label">Количество билетов</label>
                                <input type="number" class="form-control" id="people" name="people" min="1" max="{{ $tour->max_people }}" value="1" required>
                            </div>
                            <button type="submit" class="btn btn-success">Купить</button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>

        @if (($index + 1) % 3 == 0 && $index != count($tours) - 1)
            </div>
            <div class="row">
        @endif
    @endforeach
</div>

@include('includes.footer')