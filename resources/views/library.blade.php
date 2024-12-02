@extends('layouts.master')

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

        function fetchBooks(page = 1) {
            $.ajax({
                url: `/lib?page=${page}`,
                type: "GET",
                data: {
                    sort: sortFilter.value,
                    count: cppFilter.value
                },
                success: function(result) {
                    console.log(result);
                    displayBooks(result.data);
                    setupPagination(result.meta);
                },
                error: function(err) {
                    console.error("Error fetching books:", err);
                }
            });
        }

        function displayBooks(books) {
            const bookList = $('#book-table tbody');
            bookList.empty();
            
            if (books.length == 0) {
                bookList.append('<p>No books found.</p>');
                return;
            }
            
            
            books.forEach(book => {
                var publishDate = new Date(book.publish_date).toLocaleDateString('fa-IR');
                var color, btn = '';
                switch (book.status) {
                    case 1:
                        color = "yellow";
                        btn =
                            `<a class='btn' href='/queue?id=${book.id}'>مشاهده صف</a><a class='btn' onclick='cancel(${book.id})'>لغو درخواست</a>`;
                        break;
                    case 2:
                        color = "green";
                        btn = `<a class='btn' href='/queue?id=${book.id}'>مشاهده صف</a>`;
                        break;
                    case 0:
                        color = "white";
                        btn = `<a class='btn' onclick='reserve(${book.id})'>رزرو</a>`;
                        break;
                }

                var row = `
                <tr class='${color}''>
                    <td>${book.name}</td>    
                    <td>${book.author}</td>
                    <td>${book.field}</td>
                    <td>${book.publisher}</td>
                    <td>${book.publish_date}</td>
                    <td>${book.barcode}</td>
                    <td>
                        <div class='row'>
                        ${btn}
                        </div>
                    </td>
                </tr>`;
                bookList.append(row);
            });
        }

        function reserve(id) {
            $.ajax({
                url: `/reserve?id=${id}`,
                type: "GET",
                success: function(res) {
                    alert(res.message);
                    fetchBooks();
                }
            })
        }

        function cancel(id) {
            $.ajax({
                url: `/cancel?id=${id}`,
                type: "GET",
                success: function(res) {
                    //alert(res.message);
                    fetchBooks();
                }
            })
        }

        function setupPagination(meta) {
            const pagination = $('#pagination');
            pagination.empty();

            if (meta.current_page > 1) {
                pagination.append(`<button onclick="fetchBooks(${meta.current_page - 1})"><</button>`);
            }
            const startpage = Math.max(1, meta.current_page - 5);
            for (let i = startpage; i <= meta.last_page; i++) {
                const active = i === meta.current_page ? 'class="active"' : '';
                pagination.append(`<button ${active} onclick="fetchBooks(${i})">${i}</button>`);
            }

            if (meta.current_page < meta.last_page) {
                pagination.append(`<button onclick="fetchBooks(${meta.current_page + 1})">></button>`);
            }
        }

        fetchBooks();
    </script>
@endsection
