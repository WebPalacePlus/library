@extends('layouts.userpanel')

@section('head')
    <link rel="stylesheet" href="/css/table.css" />
@endsection

@section('content')
    @include('layouts.list-filter')
    <div class="box">
        <table class="table" id="notification-table">
            <thead>
                <tr>
                    <th>متن</th>
                    <th>تاریخ</th>
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
            $.ajax({
                url: `/user/notification?page=${page}`,
                type: "GET",
                data: {
                    sort: sortFilter.value,
                    count: cppFilter.value
                },
                success: function(result) {
                    console.log(result);
                    displaynotifications(result.data);
                    setupPagination(result.meta);
                },
                error: function(err) {
                    console.error("Error fetching notifications:", err);
                }
            });
        }

        function read(id) {
            $.ajax({
                url: `/user/notification/read?id=${id}`,
                type: "GET",
                success: function(result) {
                    console.log(result);
                    fetch();
                },
                error: function(xhr, status, error) {
                    if (xhr.status === 401) {
                        window.location.href = '/login';
                    } else {
                        console.error('Error removing book:', error);
                        alert('An error occurred while removing the book.');
                    }
                }
            });
        }

        function displaynotifications(notifications) {
            const notificationList = $('#notification-table tbody');
            notificationList.empty();

            if (!notifications.length) {
                notificationList.append('<tr><td colspan="2">هیچ اعلانی یافت نشد.</td></tr>');
                return;
            }

            notifications.forEach(notification => {
                var sendDate = new Date(notification.created_at).toLocaleDateString('fa-IR');
                const row = `
                    <tr>
                        <td class='col'><p>${notification.subject}</p><p>${notification.text}</p></td>
                        <td>${sendDate}</td>
                        <td>
                            <div class='row'>
                                <a class='btn' onclick='read(${notification.id})'>خوانده شد</a>    
                            </div>    
                        </td>
                    </tr>`;
                notificationList.append(row);
            });
        }

        function searchList() {
            if (searchListInput.value == "") {
                fetch(1);
                return;
            }
            $.ajax({
                url: `/api/search/notification?q=${searchListInput.value}`,
                type: "get",
                success: function(result) {
                    console.log(result);
                    displaynotifications(result.data);
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
