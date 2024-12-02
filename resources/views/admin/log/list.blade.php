@extends('layouts.adminpanel')

@section('head')
    <link rel="stylesheet" href="/css/table.css" />
@endsection

@section('content')
    @include('layouts.list-filter')
    <div class="box">
        <table class="table" id="log-table">
            <thead>
                <tr>
                    <th>نام دانشجو</th>
                    <th>کد دانشجو</th>
                    <th>نام کتاب</th>
                    <th>کد کتاب</th>
                    <th>تاریخ رزرو</th>
                    <th>تاریخ اعتبار</th>
                    <th>تاریخ تحویل</th>
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
            $.ajax({
                url: `/admin/log?page=${page}`,
                type: "GET",
                data: {
                    sort: sortFilter.value,
                    count: cppFilter.value
                },
                success: function(result) {
                    console.log(result);
                    displaylogs(result.data);
                    setupPagination(result.meta);
                },
                error: function(err) {
                    console.error("Error fetching logs:", err);
                }
            });
        }

        function displaylogs(logs) {
            const logList = $('#log-table tbody');
            logList.empty();

            if (!logs.length) {
                logList.append('<tr><td colspan="2">هیچ تاریخچه ای یافت نشد.</td></tr>');
                return;
            }

            logs.forEach(log => {
                var reserveDate = new Date(log.reserve_date).toLocaleDateString('fa-IR');
                var deadlineDate = new Date(log.deadline_date).toLocaleDateString('fa-IR');
                var returnDate = new Date(log.return_date).toLocaleDateString('fa-IR');
                const row = `
                    <tr>
                        <td>${log.user_name}</td>
                        <td>${log.user_code}</td>
                        <td>${log.book_name}</td>
                        <td>${log.book_id}</td>
                        <td>${reserveDate}</td>
                        <td>${deadlineDate}</td>
                        <td>${returnDate}</td>
                    </tr>`;
                logList.append(row);
            });
        }

        function searchList() {
            if(searchListInput.value == ""){
                fetch(1);
                return;
            }
            $.ajax({
                url: `/api/search/log?q=${searchListInput.value}`,
                type: "get",
                success: function(result) {
                    console.log(result);
                    displaylogs(result.data);
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
