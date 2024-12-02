@extends('layouts.adminpanel')

@section('head')
    <link rel="stylesheet" href="/css/table.css" />
@endsection

@section('content')
    @include('layouts.list-filter')
    <div class="box">
        <table class="table" id="field-table">
            <thead>
                <tr>
                    <th>نام دانشجو</th>
                    <th>کد دانشجو</th>
                    <th>نام کتاب</th>
                    <th>کد کتاب</th>
                    <th>تاریخ درخواست</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div id="pagination"></div>

    <script>
        const sortFilter = document.querySelector("#sort-filter");
        const cppFilter = document.querySelector("#cpp-filter");
        const searchListInput = document.querySelector("#search-list");

        function fetch(page = 1) {
            if(searchListInput.value != ""){
                searchList();
                return;
            }
            $.ajax({
                url: `/admin/reservation?page=${page}`,
                type: "GET",
                data: {
                    sort: sortFilter.value,
                    count: cppFilter.value
                },
                success: function(result) {
                    console.log(result);
                    displayreservations(result.data);
                    setupPagination(result.meta);
                },
                error: function(err) {
                    console.error("Error fetching reservations:", err);
                }
            });
        }

        function displayreservations(reservations) {
            const fieldList = $('#field-table tbody');
            fieldList.empty();

            if (!reservations.length) {
                fieldList.append('<tr><td colspan="2">هیچ کتاب در حال رزروی یافت نشد.</td></tr>');
                return;
            }

            reservations.forEach(reservation => {
                var createdAt = new Date(reservation.created_at).toLocaleDateString('fa-IR');
                const row = `
                    <tr>
                        <td>${reservation.user_name}</td>
                        <td>${reservation.user_code}</td>
                        <td>${reservation.book_name}</td>
                        <td>${reservation.book_id}</td>
                        <td>${createdAt}</td>
                        <td>
                            <div class='row'>
                                <a onclick='returnBook(${reservation.id})' class='fa fa-check btn green'></a>
                            </div>
                        </td>
                    </tr>`;
                fieldList.append(row);
            });
        }

        function returnBook(id) {
            const res = confirm(`کتاب ${id} تحویل گرفته شود؟`);
            if (!res) return;

            $.ajax({
                url: `/admin/reservation/return?id=${id}`,
                type: "GET",
                success: function() {
                    fetch(1);
                },
                error: function(xhr) {
                    if (xhr.status === 401) {
                        window.location.href = '/login';
                    } else {
                        alert('خطایی در حذف فیلد رخ داد.');
                    }
                }
            });
        }

        function searchList() {
            if(searchListInput.value == ""){
                fetch(1);
                return;
            }
            $.ajax({
                url: `/api/search/reservation?q=${searchListInput.value}`,
                type: "get",
                success: function(result) {
                    console.log(result);
                    displayreservations(result.data);
                    setupPagination(result.meta);
                },
            });
        }

        function setupPagination(meta) {
            const pagination = $('#pagination');
            pagination.empty();

            if (meta.current_page > 1) {
                pagination.append(`<button onclick="fetch(${meta.current_page - 1})">&laquo;</button>`);
            }

            const startPage = Math.max(1, meta.current_page - 5);
            for (let i = startPage; i <= meta.last_page; i++) {
                const active = i === meta.current_page ? 'class="active"' : '';
                pagination.append(`<button ${active} onclick="fetch(${i})">${i}</button>`);
            }

            if (meta.current_page < meta.last_page) {
                pagination.append(`<button onclick="fetch(${meta.current_page + 1})">&raquo;</button>`);
            }
        }

        fetch();
    </script>
@endsection
