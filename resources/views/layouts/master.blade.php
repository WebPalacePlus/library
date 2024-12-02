<!DOCTYPE html>
<html dir="rtl" lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>کتابخانه دانشگاه فنی مهندسی بوئین زهرا</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @yield('head')
</head>

<body>
    <header>
        <div class="header-container">
            <h1 class="title">کتابخانه بوئین زهرا</h1>
            <div class="col">
                <div class="search-bar">
                    <div class="row">
                        <button class="search-btn"><i class="fa fa-search"></i></button>
                        <input type="search" placeholder="جست و جو ..." name="search" id="search-input"
                            onkeyup="search()" />
                    </div>
                    <div class="search-result">
                    </div>
                </div>
                <a class="btn" href="login">ورود</a>
            </div>
        </div>
        <nav class="menu">
            <a class="item" href="#">
                <i class="fa fa-home"></i>
                <span>خانه</span>
            </a>
            <a class="item" href="#">
                <i class="fa fa-user"></i>
                <span>پنل کاربری</span>
            </a>
            <a class="item" href="/library">
                <i class="fa fa-book"></i>
                <span>کتابخانه</span>
            </a>
            <a class="item" href="#">
                <i class="fa fa-phone"></i>
                <span>ارتباط با ما</span>
            </a>
            @yield('menu')
        </nav>
    </header>
    @yield('list-filter')
    @yield('content')
    <footer>
    </footer>
    <script>
        const searchInput = document.querySelector("#search-input");
        const searchResult = document.querySelector(".search-result");

        function search() {
            if (searchInput.value == "") {
                searchResult.innerHTML = "";
                return;
            }
            $.ajax({
                url: `/api/search/book?q=${searchInput.value}`,
                type: "get",
                success: function(result) {
                    searchResult.innerHTML = "";
                    if (result.data && result.data.length > 0) {
                        var counter = 0;
                        result.data.forEach((item) => {
                            if(counter == 10)return;
                            counter++;
                            const searchItem = `
                                    <div class="search-item">
                                        <div class="search-item-info">
                                            <span>${item.name}</span>
                                            <span>${item.barcode}</span>
                                        </div>
                                        <div class="col">
                                            <a href="/book/${item.id}" class="btn">بازدید</a>
                                        </div>
                                    </div>
                                `;
                            searchResult.innerHTML += searchItem;
                        });

                    } else {
                        searchResult.innerHTML = `
                            <div class="search-item no-results">
                                <span>نتیجه ای یافت نشد</span>
                            </div>
                        `;
                    }
                    searchResult.style.display = "block";
                },
                error: function() {
                    searchResult.innerHTML = `
                        <div class="search-item no-results">
                            <span>خطا در جستجو</span>
                        </div>
                    `;
                    searchResult.style.display = "block";
                },
            });
        }
    </script>
</body>

</html>
