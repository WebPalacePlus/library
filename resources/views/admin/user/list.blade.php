@extends('layouts.adminpanel')

@section('head')
    <link rel="stylesheet" href="/css/table.css" />
@endsection

@section('content')
    @include('layouts.list-filter')
    <div class="box">
        <table id="user-table" class="table">
            <thead>
                <tr>
                    <th>نام</th>
                    <th>کد</th>
                    <th>نقش</th>
                    <th>تاریخ عضویت</th>
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
                url: `/api/users?page=${page}`,
                type: "GET",
                data: {
                    sort: sortFilter.value
                },
                success: function(result) {
                    console.log(result)
                    displayUsers(result.data);
                    setupPagination(result.meta);
                },
                error: function(err) {
                    console.error("Error fetching users:", err);
                }
            });
        }

        function removeUser(id) {
            const res = confirm(`یوزر ${id} حذف شود؟`);
            if (res == 0) {
                return;
            }
            $.ajax({
                url: `/api/users/${id}`,
                type: "DELETE",
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

        function displayUsers(users) {
            const userList = $('#user-table tbody');
            userList.empty();

            if (users.length === 0) {
                userList.append('<tr><td colspan="2">هیچ کاربری یافت نشد.</td></tr>');
                return;
            }

            users.forEach(user => {
                var createdAt = new Date(user.created_at).toLocaleDateString('fa-IR');
                var role = user.role == "student" ? "دانشجو" : "ادمین";
                var row = `
                    <tr>
                        <td>${user.name}</td>    
                        <td>${user.code}</td>
                        <td>${role}</td>
                        <td>${createdAt}</td>
                        <td>
                            <div class='row'>
                                <a class='btn' href='/admin/user/edit/${user.id}'>ویرایش</a>    
                                <a class='btn' onclick="removeUser(${user.id})">حذف</a>
                            </div>    
                        </td>
                    </tr>`;
                userList.append(row);
            });

        }

        function searchList() {
            if(searchListInput.value == ""){
                fetch(1);
                return;
            }
            $.ajax({
                url: `/api/search/user?q=${searchListInput.value}`,
                type: "get",
                success: function(result) {
                    console.log(result);
                    displayUsers(result.data);
                    setupPagination(result.meta);
                },
            });
        }

        function setupPagination(meta) {
            const pagination = $('#pagination');
            pagination.empty();

            if (meta.current_page > 1) {
                pagination.append(`<button onclick="fetch(${meta.current_page - 1})"><</button>`);
            }
            const startpage = Math.max(1, meta.current_page - 5);
            for (let i = startpage; i <= meta.last_page; i++) {
                const active = i === meta.current_page ? 'class="active"' : '';
                pagination.append(`<button ${active} onclick="fetch(${i})">${i}</button>`);
            }

            if (meta.current_page < meta.last_page) {
                pagination.append(`<button onclick="fetch(${meta.current_page + 1})">></button>`);
            }
        }

        fetch();
    </script>
@endsection
