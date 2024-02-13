$(document).ready(function () {
    function createExport(idName, title, rporttitleName, selectedColumn, tableColumns) {
        let todayDate = new Date().toLocaleDateString();
        $(idName).DataTable({
            dom: "Bfrtip",
            buttons: [{
                extend: "excelHtml5",
                title: title,
                messageTop: `Report Name : ${rporttitleName} \n Date: ` + todayDate,
                exportOptions: {
                    rows: {
                        selected: !selectedColumn
                    },
                    columns: tableColumns
                }
            }, {
                extend: "pdfHtml5",
                pageSize: "LEGAL",
                title: title,
                messageTop: `Report Name : ${rporttitleName} \n Date: ` + todayDate,
                exportOptions: {
                    rows: {
                        selected: !selectedColumn
                    },
                    columns: tableColumns
                }
            }]
        })
    }

    // Company Management
    createExport('#company_management', 'Asset Management', 'All Companies', 0, [0, 1, 2]);

    // Company Management
    createExport('#location_management', 'Asset Management', 'All Locations', 0, [0, 1, 2, 3]);

    // User Management
    createExport('#user_managment', 'Asset Management', 'All Users', 0, [0, 1, 2, 3, 4, 5, 6, 7]);

    // Device Management
    createExport('#device_management', 'Asset Management', 'All Devices', 0, [0, 1, 2, 3]);

    // Current Excel
    createExport('#current_excel', 'Asset Management', 'Current Excel', 0, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]);

    //Audit Management
    createExport('#audit_management', 'Asset Management', 'Audit Excel', 0, [0, 1, 2, 3, 4, 5]);

    //Scanned Tag
    createExport('#scanned_tags', 'Asset Management', 'Scanned Tags', 0, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]);

    //Unscanned Tags
    createExport('#unscanned_tags', 'Asset Management', 'Unscanned Tags', 0, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]);

    //Log File
    createExport('#log_file', 'Asset Management', 'Log File', 0, [0, 1, 2, 3]);

    //Moved Tag
    createExport('#moved_tag', 'Asset Management', 'Moved Tag', 0, [0, 1, 2, 3, 4]);


});