@extends('layouts.master')

@section('head')
    <link rel="stylesheet" href="/css/table.css" />
@endsection

@section('content')
    <div class="box">
        <table id="queue-table" class="table">
            <caption>صف کتاب {{ $book->name }}</caption>
            <thead>
                <tr>
                    <th>نوبت</th>
                    <th>نام دانشجو</th>
                    <th>شماره دانشجویی</th>
                    <th>تاریخ درخواست</th>
                </tr>
                @if ($reserve)
                    <tr class='green'>
                        <td>0</td>
                        <td>{{ $reserve->user->name }}</td>
                        <td>{{ $reserve->user->code }}</td>
                        <td class="queue-date" data-date="{{ $reserve->created_at }}"></td>
                    </tr>
                @endif
            </thead>
            <tbody>
                @php $counter = 0; @endphp
                @foreach ($queues as $queue)
                    <tr class="yellow">
                        <td>{{ ++$counter }}</td>
                        <td>{{ $queue->user->name }}</td>
                        <td>{{ $queue->user->code }}</td>
                        <td class="queue-date" data-date="{{ $queue->created_at }}"></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div id="pagination">
        {{ $queues->links() }}
    </div>

    <script>
        document.querySelectorAll('.queue-date').forEach(function (cell) {
            const rawDate = cell.getAttribute('data-date');
            if (rawDate) {
                const date = new Date(rawDate);
                cell.textContent = date.toLocaleDateString('fa-IR', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit'
                });
            }
        });
    </script>    
@endsection
