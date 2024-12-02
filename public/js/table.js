class TableHandler {
    constructor(config) {
        this.apiUrl = config.apiUrl; // Base URL for API requests
        this.tableSelector = config.tableSelector; // Table element selector
        this.paginationSelector = config.paginationSelector; // Pagination element selector
        this.filters = config.filters || {}; // Filters for the API request
        this.columns = config.columns; // Columns to render dynamically
    }

    fetchData(page = 1) {
        // Build query parameters
        const params = {
            page,
            ...this.filters.reduce((acc, filter) => {
                const el = document.querySelector(filter.selector);
                acc[filter.param] = el ? el.value : "";
                return acc;
            }, {}),
        };

        $.ajax({
            url: this.apiUrl,
            type: "GET",
            data: params,
            success: (result) => {
                this.displayTable(result.data);
                this.setupPagination(result.meta);
            },
            error: (err) => {
                console.error("Error fetching data:", err);
            },
        });
    }

    displayTable(data) {
        const tableBody = $(`${this.tableSelector} tbody`);
        tableBody.empty();

        if (!data.length) {
            tableBody.append(
                '<tr><td colspan="100%">هیچ نتیجه‌ای یافت نشد.</td></tr>'
            );
            return;
        }

        data.forEach((row) => {
            const tableRow = this.columns
                .map(
                    (col) =>
                        `<td>${
                            col.format ? col.format(row[col.key]) : row[col.key]
                        }</td>`
                )
                .join("");
            tableBody.append(`<tr>${tableRow}</tr>`);
        });
    }

    setupPagination(meta) {
        const pagination = $(this.paginationSelector);
        pagination.empty();

        if (meta.current_page > 1) {
            pagination.append(
                `<button onclick="handler.fetchData(${
                    meta.current_page - 1
                })">&laquo;</button>`
            );
        }

        const startPage = Math.max(1, meta.current_page - 5);
        for (
            let i = startPage;
            i <= Math.min(meta.last_page, startPage + 9);
            i++
        ) {
            const active = i === meta.current_page ? 'class="active"' : "";
            pagination.append(
                `<button ${active} onclick="handler.fetchData(${i})">${i}</button>`
            );
        }

        if (meta.current_page < meta.last_page) {
            pagination.append(
                `<button onclick="handler.fetchData(${
                    meta.current_page + 1
                })">&raquo;</button>`
            );
        }
    }
}
