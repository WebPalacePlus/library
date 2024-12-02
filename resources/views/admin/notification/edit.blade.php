@extends('layouts.master')

@section('head')
    <link rel="stylesheet" href="/css/book-table.css" />
    <link rel="stylesheet" href="/css/form.css" />
    <link rel="stylesheet" href="/css/persianDatepicker-default.css"/>
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
        <script src="/js/persianDatepicker.js"></script>
@endsection

@section('content')
    <section class="edit-container">
        <form method="post" action="auth">
            <h1 style="width:max-content;margin:1em auto;">ویرایش اعلان {{ $notification->id }}</h1>
            @csrf
            <input type="hidden" id="id" name="id" value="{{ $notification->id }}">
            <div class="error-group"></div>
            <div class="form-group">
                <label for="subject">موضوع</label>
                <input type="text" id="subject" name="subject" value="{{ $notification->subject }}" maxlength="64" required>
            </div>
            <div class="form-group">
                <label for="text">متن</label>
                <textarea id="text" name="text" required>{{ $notification->text }}</textarea>
            </div>
            <button type="submit" data-id="edit({{ $notification->id }})" class="submit-btn">اعمال</button>
        </form>
    </section>

    <script>
        document.querySelector('form').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            document.querySelector('.error-group').innerHTML = ''

            $.ajax({
                url: '/api/notifications/update',
                type: 'POST',
                data: Object.fromEntries(formData.entries()),
                success: function(response) {
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
