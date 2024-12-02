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

        .submit-btn:hover {
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
            <h1 style="width:max-content;margin:1em auto;">ویرایش فیلد {{ $field->id }}</h1>
            @csrf
            <input type="hidden" id="id" name="id" value="{{ $field->id }}">
            <div class="error-group"></div>
            <div class="form-group">
                <label for="name">نام فیلد</label>
                <input type="text" id="name" name="name" value="{{ $field->name }}" maxlength="64" required>
            </div>
            <button type="submit" class="submit-btn">اعمال</button>
        </form>
    </section>

    <script>
        document.querySelector('form').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            document.querySelector('.error-group').innerHTML = ''

            $.ajax({
                url: '/api/fields/update',
                type: 'POST',
                data: Object.fromEntries(formData.entries()),
                success: function(response) {
                    console.log(response);
                    alert('با موفقیت ادیت شد.');
                },
                error: function(xhr) {
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
