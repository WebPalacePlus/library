@extends('layouts.master')

@section('head')
    <link rel="stylesheet" href="/css/book-table.css" />
    <link rel="stylesheet" href="/css/form.css" />
    <style>
        .edit-container {
            margin: 1em auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 50%;
            border: 2px solid var(--dark-red);
        }

        .edit-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: var(--dark-red);
        }

        .submit-btn {
            width: 100%;
            padding: 10px;
            background-color: var(--dark-red);
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .submit:ho ver {
            background-color: var(--hover-dark-red);
        }

        @media (max-width: 768px) {
            .edit-container {
                width: 80%;
            }
        }
    </style>
@endsection

@section('content')
    <section class="edit-container">
        <form method="post">
            <h1 style="width:max-content;margin:1em auto;">ایجاد فیلد</h1>
            @csrf
            <div class="error-group"></div>
            <div class="form-group">
                <label for="name">نام فیلد</label>
                <input type="text" id="name" name="name" maxlength="64" required>
            </div>
            <button type="submit" class="submit-btn">ثبت</button>
        </form>
    </section>
    <script>
        document.querySelector('form').addEventListener('submit', function (event) {
            event.preventDefault();
            const formData = new FormData(this);
            
            $.ajax({
                url: '/api/fields/store',
                type: 'POST',
                data: Object.fromEntries(formData.entries()),
                success: function (response) {
                    window.location.href = '/admin/field/list';  
                },
                error: function (xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        let errorHtml = '<ul>';
                        for (let field in errors) {
                            const errorMessages = errors[field];
                            errorHtml += `<li>${errorMessages.join('</li><li>')}</li>`;
                        }
                        errorHtml += '</ul>';
                        document.querySelector('.error-group').innerHTML = errorHtml;
                    }
                }
            });
        });
    </script>    
@endsection
