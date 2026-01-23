$(document).ready(function() {
    page.init();
});

var table = '';
var file_name = "inwarding_list";
var pdf_title = "Inwarding List";

const page = {
    init: function() {
        // inwarding_grn
        this.filter();
        this.dataTable();
        this.closePo();
    },
    dataTable: function(){
        var data = this.serachParams();
        table = new DataTable("#inwarding_grn", {
            dom: "Bfrtilp",
            buttons: [
              {     
                    extend: 'csv',
                      text: '<i class="ti ti-file-type-csv"></i>',
                      init: function(api, node, config) {
                      $(node).attr('title', 'Download CSV');
                      },
                      customize: function (csv) {
                            var lines = csv.split('\n');
                            var modifiedLines = lines.map(function(line) {
                                var values = line.split(',');
                                values.splice(5, 2);
                                return values.join(',');
                            });
                            return modifiedLines.join('\n');
                        },
                        filename : file_name
                    },  
                  {
                    extend: 'pdf',
                    text: '<i class="ti ti-file-type-pdf"></i>',
                    init: function(api, node, config) {
                        $(node).attr('title', 'Download Pdf');
                    },
                    filename: file_name,
                    customize: function (doc) {
                      doc.pageMargins = [15, 15, 15, 15];
                      doc.content[0].text = pdf_title;
                      doc.content[0].color = theme_color;
                        doc.content[1].table.widths = ['21%', '36%', '13%', '15%','15%'];
                        doc.content[1].table.body[0].forEach(function(cell) {
                            cell.fillColor = theme_color;
                        });

                         // Remove the 4th and 5th columns from each row
                        doc.content[1].table.body.forEach(function(row) {
                            row.splice(5, 2); // Remove the 4th and 5th columns from each row
                        });
                        doc.content[1].table.body.forEach(function(row, rowIndex) {
                            row.forEach(function(cell, cellIndex) {
                                var alignmentClass = $('#child_part_view tbody tr:eq(' + rowIndex + ') td:eq(' + cellIndex + ')').attr('class');
                                var alignment = '';
                                if (alignmentClass && alignmentClass.includes('dt-left')) {
                                    alignment = 'left';
                                } else if (alignmentClass && alignmentClass.includes('dt-center')) {
                                    alignment = 'center';
                                } else if (alignmentClass && alignmentClass.includes('dt-right')) {
                                    alignment = 'right';
                                } else {
                                    alignment = 'left';
                                }
                                cell.alignment = alignment;
                            });
                            row.splice(5, 2);
                        });
                    }
                },
            ],
            orderCellsTop: true,
            fixedHeader: true,
            lengthMenu: page_length_arr,
            // "sDom":is_top_searching_enable,
            columns: column_details,
            processing: false,
            serverSide: is_serverSide,
            sordering: true,
            searching: is_searching_enable,
            ordering: is_ordering,
            bSort: true,
            orderMulti: false,
            pagingType: "full_numbers",
            scrollCollapse: true,
            scrollX: true,
            scrollY: true,
            paging: is_paging_enable,
            fixedHeader: false,
            info: true,
            autoWidth: true,
            lengthChange: true,
            order: sorting_column,
            // fixedColumns: {
            //     leftColumns: 2,
            //     // end: 1
            // },
            ajax: {
                data: {'search':data},    
                url: "restaurant/restaurant/get_restaurant_list",
                type: "POST",
            },
            //  columnDefs: [{ sortable: false, targets: 6 },{ sortable: false, targets: 5 }],
        });
        $('.dataTables_length').find('label').contents().filter(function() {
            return this.nodeType === 3; // Filter out text nodes
        }).remove();
        table.on('init.dt', function() {
            $(".dataTables_length select").select2({
                minimumResultsForSearch: Infinity
            });
        });
        $('#serarch-filter-input').on('keyup', function() {
            table.search(this.value).draw();
        });
    },
    closePo: function(){
        let that = this;
        // var toastMixin = Swal.mixin({
        //     toast: true,
        //     icon: 'success',
        //     title: 'General Title',
        //     animation: false,
        //     position: 'top-right',
        //     showConfirmButton: false,
        //     timer: 3000,
        //     timerProgressBar: true,
        //     didOpen: (toast) => {
        //       toast.addEventListener('mouseenter', Swal.stopTimer)
        //       toast.addEventListener('mouseleave', Swal.resumeTimer)
        //     }
        //   });
        //    toastMixin.fire({
        //     animation: true,
        //     title: 'Signed in Successfully'
        // });
       $(document).on('click', '.close-po', function(e) {
            var po_number = $(this).parents("tr").find(".po-number").text();
            var po_id =  $(this).attr("data-id");
		    Swal.fire({
            title: "Close PO",
            text: "Are you sure you want to close PO " + po_number + "?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes",
            customClass: {
                icon: 'swal-small-icon'
            }
            }).then((result) => {
            if (result.isConfirmed) {  // ✅ SweetAlert2 uses isConfirmed, not value
                console.log(po_id);
                $.ajax({
                url: base_url + "close_po",
                data: { id: po_id, po_number: po_number },
                type: "post",
                success: function(result) {
                    var data = JSON.parse(result);
                    if (data.success == 1) {
                    toastr.success(data.message);
                    table.destroy();
                    that.dataTable();
                    } else {
                    toastr.error(data.message);
                    }
                }
                });
            }
            });
		});
    },
    filter: function(){
        let that = this;
        // $('#date_range_filter').daterangepicker({
        //     singleDatePicker: false,
        //     showDropdowns: true,
        //     autoApply: true,
        //     locale: {
        //         format: 'DD/MM/YYYY' // Change this format as per your requirement
        //     }
        // });
        // dateRangePicker = $('#date_range_filter').data('daterangepicker');
        // dateRangePicker.setStartDate(start_date);
        // dateRangePicker.setEndDate(end_date);
        $(".search-filter").on("click",function(){
            table.destroy(); 
            that.dataTable();
            $(".close-filter-btn").trigger( "click" )
        })
        $(".reset-filter").on("click",function(){
            that.resetFilter();
        })
    },
    serachParams: function(){
        var date_range_filter = $("#date_range_filter").val();
        var supplier_id_search = $("#supplier_id_search").val();
        var params = {date_range_filter:date_range_filter,supplier_id:supplier_id_search};
        return params;
    },
    resetFilter: function(){
        $('#date_range_filter').data('daterangepicker').setStartDate(moment().format('YYYY-MM-DD'));
        $("#supplier_id_search").val("").trigger("change");
        table.destroy(); 
        this.dataTable();
    },
};
