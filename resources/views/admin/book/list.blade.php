@extends('layouts.adminpanel')

@section('head')
    <link rel="stylesheet" href="/css/table.css" />
@endsection

@section('content')
    @include('layouts.list-filter')
    <div class="box">
        <table id="book-table" class="table">
            <thead>
                <tr>
                    <th>نام</th>
                    <th>نویسنده</th>
                    <th>فیلد</th>
                    <th>انتشارات</th>
                    <th>تاریخ انتشار</th>
                    <th>بارکد</th>
                    <th>تعداد</th>
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
                url: `/api/books?page=${page}`,
                type: "GET",
                data: {
                    sort: sortFilter.value
                },
                success: function(result) {
                    displayBooks(result.data);
                    setupPagination(result.meta);
                },
                error: function(err) {
                    console.error("Error fetching books:", err);
                }
            });
        }

        function removeBook(id) {
            const res = confirm(`کتاب ${id} حذف شود؟`);
            if (res == 0) {
                return;
            }
            $.ajax({
                url: `/api/books/${id}`,
                type: "DELETE",
                success: function(result) {
                    console.log(result);
                    fetch();
                },
                error: function(xhr, status, error) {
                    if (xhr.status === 401) {
                        window.location.href = '/login';
                    } else {
                        // Handle other error statuses if needed
                        console.error('Error removing book:', error);
                        alert('An error occurred while removing the book.');
                    }
                }
            });
        }

        function displayBooks(books) {
            const bookList = $('#book-table tbody');
            bookList.empty();

            if (books.length === 0) {
                bookList.append('<tr><td colspan="2">هیچ کتابی یافت نشد.</td></tr>');
                return;
            }

            books.forEach(book => {
                var row = `
                <tr>
                    <td>${book.name}</td>    
                    <td>${book.author}</td>
                    <td>${book.field}</td>
                    <td>${book.publisher}</td>
                    <td>${book.publish_date}</td>
                    <td>${book.barcode}</td>
                    <td>${book.available_count}/${book.amount}</td>
                    <td>
                        <div class='row'>
                            <a class='btn' href='/admin/book/edit/${book.id}'>ویرایش</a>    
                            <a class='btn' onclick="removeBook(${book.id})">حذف</a>
                        </div>    
                    </td>
                </tr>`;
                bookList.append(row);
            });
        }

        function searchList() {
            if (searchListInput.value == "") {
                fetch();
                return;
            }
            $.ajax({
                url: `/api/search/book?q=${searchListInput.value}`,
                type: "get",
                success: function(result) {
                    displayBooks(result.data);
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
