<div class="box">

    <div class="full-search-bar">
        <input type="search" id="search-list" placeholder="جستجو ..." oninput="searchList()" />
        </di v>


        <div class="filter-box">
            <div class="col">
                <label>مرتب سازی</label>
                <select id="sort-filter" onchange="fetch(1)" class="filter-select">
                    <option value="name-asc">حروف الفبا (A-Z)</option>
                    <option value="name-desc">حروف الفبا (Z-A)</option>
                    <option value="-asc" selected>قدیمی‌ترین</option>
                    <option value="-desc">جدیدترین</option>
                </select>
            </div>
            <div class="col">
                <label>تعداد نتایج در هر صفحه</label>
                <select id="cpp-filter" onchange="fetch(1)">
                    <option value="25" selected>25</option>
                    <option value="50">50</option>
                    <option value="75">75</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>

    </div>
</div>
