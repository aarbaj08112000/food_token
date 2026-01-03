var table = '';
var file_name = "child_part_supplier_admin";
var pdf_title = "child_part_supplier_admin";
$(document).ready(function(){
$('.approve-price').validate({
        rules: {
            // Define your validation rules here if needed
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
            // Perform AJAX submit or any other logic here
            $.ajax({
                url: $(form).attr('action'), // Form action URL
                type: 'POST',
                data: new FormData(form), // Form data
                processData: false,
                contentType: false,
                success: function(response) {
                    // Handle the response here
                    
                if(response != '' && response != null && typeof response != 'undefined'){
                    let res = JSON.parse(response);
                    if(res['success'] == 1){
                        toastr.success(res['msg']);
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    }else{
                        toastr.error(res['msg']);
                    }
                }
                    // You can close the modal, reset the form, etc.
                    $(form)[0].reset(); // Reset the form
                    // Close the modal if needed
                    $('.modal').modal('hide');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle errors here
                    alert('An error occurred: ' + errorThrown);
                }
            });
            return false; // Prevent the default form submit
        }
    });
   var data = {};
        table = $("#example1").DataTable({
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
                            values.splice(0, 1);
                            values.splice(10, 1);
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
                        row.splice(0, 1);
                        row.splice(10, 1);
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
        columnDefs: [{ sortable: false, targets: 0 },{ sortable: false, targets: 10 }],
        pagingType: "full_numbers",
       
        
        });
        $('.dataTables_length').find('label').contents().filter(function() {
                return this.nodeType === 3; // Filter out text nodes
        }).remove();
        setTimeout(function(){
            $(".dataTables_length select").select2({
                minimumResultsForSearch: Infinity
            });
        },1000)

        // global searching for datable 
        $('#serarch-filter-input').on('keyup', function() {
            table.search(this.value).draw();
        });

    

})


