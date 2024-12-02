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
        <h1>کاربر <b>امیر</b></h1>
        <button type="button" id="toggle-menu" class="toggle_menu">
            <i id="toggle-icon" class="fa fa-bars"></i>
        </button>
    </header>
    <nav class="vertical_nav closed ">
        <ul id="menu" class="menu">
            <li class="menu-item">
                <a href="/user/notification/list" class="menu-link">
                    <span class="menu-label" id="notification-label">اعلانات</span>
                    <i class="menu-icon  fa fa-fw fa-bullhorn"></i>
                </a>
            </li>
        </ul>
        <button id="collapse_menu" class="collapse_menu">
            <i id="collapse-icon" class="fa fa-fw fa-arrow-right"></i>
        </button>
    </nav>

    <script>
        const toggle_Menu = document.querySelector("#toggle-menu");
        const toggle_Icon = document.querySelector("#toggle-icon");
        const collapse_Menu = document.querySelector("#collapse_menu");
        const collapse_Icon = document.querySelector("#collapse-icon");
        const notification_Label = document.querySelector("#notification-label");
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

        $.ajax({
                url: `/user/notification`,
                type: "GET",
                success: function(result) {
                    const n = result.data.length;
                    if(n > 0){
                        notification_Label.textContent += ` +${n}`;
                    }
                },
                error: function(err) {
                    console.error("Error fetching notifications:", err);
                }
            });
    </script>
    <section class="content">
        @yield('list-filter')
        @yield('content')
    </section>
    <footer>
    </footer>
</body>

</html>
