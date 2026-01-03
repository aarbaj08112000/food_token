var table = '';
var file_name = "item_master";
var pdf_title = "Item Master";

$(document).ready(function() {

    // Initialize the DataTable
    table = $("#child_parts").DataTable({
        dom: "Bfrtilp",
        buttons: [
            {
                extend: "csv",
                text: '<i class="ti ti-file-type-csv"></i>',
                init: function (api, node, config) {
                    $(node).attr("title", "Download CSV");
                },
                customize: function (csv) {
                        var lines = csv.split('\n');
                        var modifiedLines = lines.map(function(line) {
                            var values = line.split(',');
                            values.splice(3, 1);
                            return values.join(',');
                        });
                        return modifiedLines.join('\n');
                    },
                    filename : file_name
                },
          
            {
                extend: "pdf",
                text: '<i class="ti ti-file-type-pdf"></i>',
                init: function (api, node, config) {
                    $(node).attr("title", "Download Pdf");
                },
                filename: file_name,
                customize: function (doc) {
                    doc.pageMargins = [15, 15, 15, 15];
                    doc.content[0].text = pdf_title;
                    doc.content[0].color = theme_color;
                    // doc.content[1].table.widths = ["19%", "19%", "13%", "13%", "15%", "15%"];
                    doc.content[1].table.body[0].forEach(function (cell) {
                        cell.fillColor = theme_color;
                    });
                    doc.content[1].table.body.forEach(function (row, index) {
                        row.splice(3, 1);
                        row.forEach(function (cell) {
                            // Set alignment for each cell
                            cell.alignment = "center"; // Change to 'left' or 'right' as needed
                        });
                    });
                },
            },
        ],
        searching: true,
        // scrollX: true,
        scrollY: true,
        bScrollCollapse: true,
        columnDefs: [{ sortable: false, targets: 3 }],
        pagingType: "full_numbers",
       
        
        });
        $('#serarch-filter-input').on('keyup', function() {
            table.search(this.value).draw();
        });
        $('.dataTables_length').find('label').contents().filter(function() {
                return this.nodeType === 3; // Filter out text nodes
        }).remove();
        setTimeout(function(){
            $(".dataTables_length select").select2({
                minimumResultsForSearch: Infinity
            });
        },1000)

    // Custom search filter event
    $('.search-filter').on('click', function(e) {
        let part_val = $('#part_id_selected').val();
        // Ensure that the table and column exist before applying the search
        if (table && part_val) {
            table.column(1).search(part_val).draw();
        }
        $('.close-filter-btn').trigger('click');
    });

    $('.reset-filter').on('click',function(e){
        $('#part_id_selected').val('');
        table.column(1).search('').draw();
        $('.close-filter-btn').trigger('click');
    })
    $('#serarch-filter-input').on('keyup', function() {
            table.search(this.value).draw();
        });
   

    // Form validation and submission
    $('.approve-price').validate({
        rules: {
            upart_number: {
                required: true
            },
            upart_desc: {
                required: true
            },
            id: {
                required: true
            }
        },
        messages: {
            upart_number: {
                required: "Part Number is required"
            },
            upart_desc: {
                required: "Please enter part price."
            },
            id: {
                required: "ID is required"
            }
        },
        submitHandler: function(form) {
            $.ajax({
                url: $(form).attr('action'),
                type: 'POST',
                data: new FormData(form),
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response) {
                        let res = JSON.parse(response);
                        if (res.success == 1) {
                            toastr.success(res.msg);
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            toastr.error(res.msg);
                        }
                    }
                    $(form)[0].reset();
                    $('.modal').modal('hide');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('An error occurred: ' + errorThrown);
                }
            });
            return false; // Prevent default form submit
        }
    });

});
