@extends('layouts.adminpanel')

@section('head')
    <link rel="stylesheet" href="/css/table.css" />
@endsection

@section('content')
    @include('layouts.list-filter')
    <div class="box">
        <table class="table" id="queue-table">
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

        let current_page = 1;

        function fetch() {
            $.ajax({
                url: `/admin/queue?page=${current_page}`,
                type: "GET",
                data: {
                    sort: sortFilter.value
                },
                success: function(result) {
                    console.log(result);
                    displayqueues(result.data);
                    setupPagination(result.meta);
                },
                error: function(err) {
                    console.error("Error fetching queues:", err);
                }
            });
        }

        function displayqueues(queues) {
            const queueList = $('#queue-table tbody');
            queueList.empty();

            if (!queues.length) {
                queueList.append('<tr><td colspan="2">هیچ درخواست رزروی یافت نشد.</td></tr>');
                return;
            }

            queues.forEach(queue => {
                var createdAt = new Date(queue.created_at).toLocaleDateString('fa-IR');
                const row = `
                    <tr>
                        <td>${queue.username}</td>
                        <td>${queue.usercode}</td>
                        <td>${queue.bookname}</td>
                        <td>${queue.bookcode}</td>
                        <td>${createdAt}</td>
                        <td>
                            <div class='row'>
                                <a onclick='accept(${queue.id})' class='fa fa-check btn green'></a>
                                <a onclick='reject(${queue.id})' class='fa fa-close btn red'></a>
                            </div>
                        </td>
                    </tr>`;
                queueList.append(row);
            });
        }

        function accept(id) {
            const res = confirm(`درخواست ${id} قبول شود؟`);
            if (!res) return;

            $.ajax({
                url: `/admin/queue/accept?id=${id}`,
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

        function reject(id) {
            const res = confirm(`درخواست ${id} رد شود؟`);
            if (!res) return;

            $.ajax({
                url: `/admin/queue/reject?id=${id}`,
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
                url: `/api/search/queue?q=${searchListInput.value}`,
                type: "get",
                success: function(result) {
                    console.log(result);
                    displayqueues(result.data);
                    setupPagination(result.meta);
                },
            });
        }

        function setupPagination(meta) {
            const pagination = $('#pagination');
            pagination.empty();

            if (meta.current_page > 1) {
                pagination.append(`<button onclick="updatePage(${meta.current_page - 1})">&laquo;</button>`);
            }

            const startPage = Math.max(1, meta.current_page - 5);
            for (let i = startPage; i <= meta.last_page; i++) {
                const active = i === meta.current_page ? 'class="active"' : '';
                pagination.append(`<button ${active} onclick="updatePage(${i})">${i}</button>`);
            }

            if (meta.current_page < meta.last_page) {
                pagination.append(`<button onclick="updatePage(${meta.current_page + 1})">&raquo;</button>`);
            }
        }

        function updatePage(page) {
            current_page = page;
            fetch();
        }

        fetch();
    </script>
@endsection
