<!DOCTYPE html>
<html dir="rtl" lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>کتابخانه دانشگاه فنی مهندسی بوئین زهرا</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/admin-style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @yield('head')
</head>

<body>
    <header class="header">
        <h1>پنل ادمین</h1>
        <button type="button" id="toggle-menu" class="toggle_menu">
            <i id="toggle-icon" class="fa fa-bars"></i>
        </button>
    </header>
    <nav class="vertical_nav closed ">
        <ul id="menu" class="menu">
            <li class="menu-item">
                <a href="/admin/book/list" class="menu-link">
                    <span class="menu-label">کتاب ها</span>
                    <i class="menu-icon  fa fa-fw fa-book"></i>
                </a>
            </li>
            <li class="menu-item">
                <a href="/admin/user/list" class="menu-link">
                    <span class="menu-label">کاربران</span>
                    <i class="menu-icon  fa fa-fw fa-user-circle"></i>
                </a>
            </li>
            <li class="menu-item">
                <a href="/admin/queue/list" class="menu-link">
                    <span class="menu-label">صف درخواست </span>
                    <i class="menu-icon  fa fa-fw fa-list"></i>
                </a>
            </li>
            <li class="menu-item">
                <a href="/admin/reservation/list" class="menu-link">
                    <span class="menu-label">رزرو ها</span>
                    <i class="menu-icon  fa fa-fw fa-check"></i>
                </a>
            </li>
            <li class="menu-item">
                <a href="/admin/notification/list" class="menu-link">
                    <span class="menu-label">اعلانات</span>
                    <i class="menu-icon  fa fa-fw fa-bullhorn"></i>
                </a>
            </li>
            <li class="menu-item">
                <a href="/admin/field/list" class="menu-link">
                    <span class="menu-label">فیلدها</span>
                    <i class="menu-icon  fa fa-fw fa-th"></i>
                </a>
            </li>
            <li class="menu-item">
                <a href="/admin/log/list" class="menu-link">
                    <span class="menu-label">تاریخچه</span>
                    <i class="menu-icon  fa fa-fw fa-history"></i>
                </a>
            </li>
            <li class="menu-item">
                <a href="/" class="menu-link">
                    <span class="menu-label">صفحه اصلی</span>
                    <i class="menu-icon  fa fa-fw fa-sign-out"></i>
                </a>
            </li>
        </ul>
        <button id="collapse_menu" class="collapse_menu">
            <i id="collapse-icon" class="fa fa-fw fa-arrow-left"></i>
        </button>
    </nav>

    <script>
        const toggle_Menu = document.querySelector("#toggle-menu");
        const toggle_Icon = document.querySelector("#toggle-icon");
        const collapse_Menu = document.querySelector("#collapse_menu");
        const collapse_Icon = document.querySelector("#collapse-icon");
        const menu = document.querySelector(".vertical_nav");
        toggle_Menu.addEventListener('click', () => {
            toggleMenu();
        });
        collapse_Menu.addEventListener('click', () => {
            toggleMenu();
        });

        function toggleMenu() {
            if (menu.classList.contains("opened")) {
                collapse_Icon.classList.remove("fa-arrow-right");
                collapse_Icon.classList.add("fa-arrow-left");
                toggle_Icon.classList.remove("fa-close");
                toggle_Icon.classList.add("fa-bars");
                menu.classList.remove("opened");
                menu.classList.add("closed")
            } else {
                collapse_Icon.classList.remove("fa-arrow-left");
                collapse_Icon.classList.add("fa-arrow-right");
                toggle_Icon.classList.remove("fa-bars");
                toggle_Icon.classList.add("fa-close");
                menu.classList.remove("closed")
                menu.classList.add("opened")
            }
        }
    </script>
    <section class="content">
        @yield('list-filter')
        @yield('content')
    </section>
    <footer>
    </footer>
</body>

</html>
