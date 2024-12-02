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
                    <th>نام</th>
                    <th>عملیات</th>
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
                url: `/api/fields?page=${page}`,
                type: "GET",
                data: { sort: sortFilter.value },
                success: function(result) {
                    displayfields(result.data);
                    setupPagination(result.meta);
                },
                error: function(err) {
                    console.error("Error fetching fields:", err);
                }
            });
        }

        function removefield(id) {
            const res = confirm(`فیلد ${id} حذف شود؟`);
            if (!res) return;

            $.ajax({
                url: `/api/fields/${id}`,
                type: "DELETE",
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

        function displayfields(fields) {
            const fieldList = $('#field-table tbody');
            fieldList.empty();

            if (!fields.length) {
                fieldList.append('<tr><td colspan="2">هیچ فیلدی یافت نشد.</td></tr>');
                return;
            }

            fields.forEach(field => {
                const row = `
                    <tr>
                        <td>${field.name}</td>
                        <td>
                            <div class="row">
                                <a class="btn" href="/admin/field/edit/${field.id}">ویرایش</a>
                                <a class="btn" onclick="removefield(${field.id})">حذف</a>
                            </div>
                        </td>
                    </tr>`;
                fieldList.append(row);
            });
        }

        function searchList() {
            if(searchListInput.value == ""){
                fetch(1);
                return;
            }
            $.ajax({
                url: `/api/search/field?q=${searchListInput.value}`,
                type: "get",
                success: function(result) {
                    console.log(result);
                    displayfields(result.data);
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

        // Initial fetch
        fetch();
    </script>
@endsection
