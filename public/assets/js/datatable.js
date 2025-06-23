function initializeDataTable({
    main_class = ".datatable",
    displayLength = 10,
    export_columns = [],
    main_route = "",
    order_route = "",
    model = "",
    table_columns = [], // Array for defining table columns
    row_reorder = true,
} = {}) {
    let row_reorder_settings = false;
    if (row_reorder) {
        row_reorder_settings = {
            selector: "td:last-child .reorder",
            update: true,
        };
    }
    $(function () {
        var table = $(main_class).DataTable({
            dom: "Bfrtip",
            colReorder: true,
            responsive: true,
            processing: true,
            serverSide: true,
            iDisplayLength: displayLength,
            rowReorder: row_reorder_settings,
            buttons: [
                // "copy",
                // {
                //     extend: "pdfHtml5",
                //     download: "open",
                //     orientation: "portrait",
                //     pageSize: "A4",
                //     exportOptions: {
                //         columns: export_columns,
                //     },
                //     customize: function (doc) {
                //         doc.defaultStyle = {
                //             font: "Roboto",
                //             fontSize: 10,
                //         };
                //         // doc.pageMargins = [30, 30, 30, 30];
                //     },
                // },
                // {
                //     extend: "print",
                //     exportOptions: {


                //         columns: export_columns, // Modify as needed
                //     },
                // },
                // {
                //     extend: "csv",
                //     exportOptions: {
                //         columns: export_columns, // Modify as needed
                //     },
                // },
                "pageLength",
            ],
            ajax: {
                url: main_route,
                type: "GET",
                data: function (d) {
                    let urlParams = new URLSearchParams(window.location.search);
                    urlParams.forEach((value, key) => {
                        d[key] = value; // Append each existing URL param to DataTables AJAX request
                    });
                },
            },
            columns: [
                {
                    data: null, // `data` set to `null` because we are not using a field from the dataset
                    name: "serial", // A unique name for the serial column
                    orderable: false, // You probably don’t want to allow sorting by this column
                    searchable: false, // No search on serial number
                    // render: function (data, type, row, meta) {
                    //     return meta.row + 1; // meta.row gives the row index (0-based), so add 1
                    // },
                    render: function (data, type, row, meta) {
                        // Calculate serial number based on page and page length
                        const pageInfo = $(main_class).DataTable().page.info(); // Get current page info

                        return pageInfo.start + meta.row + 1; // Adjust serial number
                    },
                },
                // Map the rest of the columns from `table_columns`
                ...table_columns.map(function (item) {
                    return {
                        data: item[0], // column data from the dataset
                        name: item[0], // same as above
                        orderable: item[1], // is column orderable
                        searchable: item[2], // is column searchable
                    };
                }),
            ],
        });
        if (row_reorder) {
            table.on("row-reorder", function (e, diff, edit) {
                let orderData = [];
                for (var i = 0; i < diff.length; i++) {
                    let rowData = table.row(diff[i].node).data();

                    // Collect the IDs and new order for the server
                    orderData.push({
                        id: rowData.id, // Assuming the ID is part of the data
                        newOrder: diff[i].newPosition,
                    });
                }

                // If newOrder is not empty, send it to the server
                if (orderData.length > 0) {
                    $.ajax({
                        url: order_route, // Your route for sorting update
                        type: "POST",
                        data: {
                            model: model,
                            datas: orderData,
                            _token: document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute("content"),
                        },
                        success: function (response) {
                            if (response.success) {
                                toastr.success(response.message);
                            } else {
                                handleErrors(response);
                            }
                            // table.ajax.reload(); // Reload the table to reflect changes
                        },
                        error: function (error) {
                            toastr.error(
                                "Something went wrong. Please try again."
                            );
                        },
                    });
                }
            });
        } else {
            removeRowOrderIcon(table, main_class);
        }
    });
}

function removeRowOrderIcon(table, main_class) {
    // Attach event handler to the `draw` event
    table.on("draw", function () {
        // Find and remove reorder icons in the current page
        $(main_class).find(".reorder").remove();
    });
}
