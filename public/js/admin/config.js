var table = '';
var file_name = "global_config";
var pdf_title = "Global Config";

$(document).ready(function() {
    // Initialize the DataTable

    $(document).on("click",".edit-part",function(){
        var data = $(this).attr("data-value");
        configData = JSON.parse(atob(data)); 
      
        $(".companyLogo,.signatureLogo,.regularValue").hide();
        $('#updateConfigForm input[name="display_label"]').val(configData.displayLabel);
        $('#updateConfigForm input[name="config_name"]').val(configData.config_name);
        $('#updateConfigForm input[name="configID"]').val(configData.id);
        $('#updateConfigForm textarea[name="note"]').val(configData.note);
    
        // Handling the config value field
        if (configData.config_name === "companyLogo") {
            $(".companyLogo").show();
            $('#updateConfigForm input[name="companyLogo"]').val(configData.config_value);
        }else if (configData.config_name === "SignatureImage") {
            $(".signatureLogo").show();
            $('#updateConfigForm input[name="SignatureImage"]').val(configData.config_value);
        } else {
            $(".regularValue").show();
            $('#updateConfigForm input[name="config_value"]').val(configData.config_value);
        }
    
        // Set the checkboxes
        if (configData.ARMUserOnly === "1") {
            $('#updateConfigForm input[name="forAromOnly"]').prop('checked', true);
        } else {
            $('#updateConfigForm input[name="forAromOnly"]').prop('checked', false);
        }
    
        if (configData.canModify === "1") {
            $('#updateConfigForm input[name="canCustomerModify"]').prop('checked', true);
        } else {
            $('#updateConfigForm input[name="canCustomerModify"]').prop('checked', false);
        }
        // myModal.show();
    })


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
                            values.splice(5, 1);
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
                        row.splice(5, 1);
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
        columnDefs: [{ sortable: false, targets: 5 }],
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

    $('#updateConfigForm').validate({
            rules: {
                display_label: {
                    required: true,
                    maxlength: 100
                },
                config_value: {
                    required: true
                },
                note: {
                    required: true,
                    maxlength: 255
                }
            },
            messages: {
                display_label: {
                    required: "Please enter the display name",
                    maxlength: "Display name can't exceed 100 characters"
                },
                config_value: {
                    required: "Please enter the config value"
                },
                note: {
                    required: "Please enter a note",
                    maxlength: "Note can't exceed 255 characters"
                }
            },
            submitHandler: function(form,e) {

                 e.preventDefault();
                 
                // Perform AJAX form submission
                $.ajax({
                    url: $(form).attr('action'),
                    type: 'POST',
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Handle successful response
                        if(response != '' && response != null && typeof response != 'undefined'){
                            let res = JSON.parse(response);
                            console.log(res['success'])
                            if(res['success'] == 1){
                                toastr.success(res['msg']);
                                setTimeout(() => {
                                    window.location.reload();
                                    // Optionally, refresh the table or perform other actions
                                }, 1000);
                                $(form).closest('.modal').modal('hide');
                            }else{
                                toastr.error(res['msg']);
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                        console.error('Form submission failed:', error);
                    }
                });
            }



    });

    $(".add_new_config").submit(function(e){
        e.preventDefault();
       
        var href = $(this).attr("action");
        var id = $(this).attr("id");
        let flag = formValidate(id);

        if(flag){
          return;
        }

        var formData = new FormData($('.'+id)[0]);

        $.ajax({
          type: "POST",
          url: href,
          data: formData,
          processData: false,
          contentType: false,
          success: function (response) {
            var responseObject = JSON.parse(response);
            var msg = responseObject.messages;
            var success = responseObject.success;
            if (success == 1) {
              toastr.success(msg);
              $(this).parents(".modal").modal("hide")
              setTimeout(function(){
                window.location.reload();
              },1000);

            } else {
              toastr.error(msg);
            }
          },
          error: function (error) {
            console.error("Error:", error);
          },
        });
      });

    function formValidate(form_class = ''){
        let flag = false;
        $(".custom-form."+form_class+" .required-input").each(function( index ) {
          var value = $(this).val();
          var dataMax = parseFloat($(this).attr('data-max'));
          var dataMin = parseFloat($(this).attr('data-min'));
          if(value == ''){
            flag = true;
            var label = $(this).parents(".form-group").find("label").contents().filter(function() {
              return this.nodeType === 3; // Filter out non-text nodes (nodeType 3 is Text node)
            }).text().trim();
            var exit_ele = $(this).parents(".form-group").find("label.error");
            if(exit_ele.length == 0){
              var start ="Please enter ";
              if($(this).prop("localName") == "select"){
                var start ="Please select ";
              }
              label = ((label.toLowerCase()).replace("enter", "")).replace("select", "");
              var validation_message = start+(label.toLowerCase()).replace(/[^\w\s*]/gi, '');
              var label_html = "<label class='error'>"+validation_message+"</label>";
              $(this).parents(".form-group").append(label_html)
            }
          }
          else if(dataMin !== undefined && dataMin > value){
            flag = true;
            var label = $(this).parents(".form-group").find("label").contents().filter(function() {
              return this.nodeType === 3; // Filter out non-text nodes (nodeType 3 is Text node)
            }).text().trim();
            var exit_ele = $(this).parents(".form-group").find("label.error");
            if(exit_ele.length == 0){
              var end =" must be greater than or equal "+dataMin;
              label = ((label.toLowerCase()).replace("enter", "")).replace("select", "");
              label = (label.toLowerCase()).replace(/[^\w\s*]/gi, '');
              label = label.charAt(0).toUpperCase() + label.slice(1);
              var validation_message =label +end;
              var label_html = "<label class='error'>"+validation_message+"</label>";
              $(this).parents(".form-group").append(label_html)
            }
            }else if(dataMax !== undefined && dataMax < value){
              flag = true;
              var label = $(this).parents(".form-group").find("label").contents().filter(function() {
                return this.nodeType === 3; // Filter out non-text nodes (nodeType 3 is Text node)
              }).text().trim();
              var exit_ele = $(this).parents(".form-group").find("label.error");
              if(exit_ele.length == 0){
                var end =" must be less than or equal "+dataMax;
                label = ((label.toLowerCase()).replace("enter", "")).replace("select", "");
                label = (label.toLowerCase()).replace(/[^\w\s*]/gi, '');
                label = label.charAt(0).toUpperCase() + label.slice(1)
                var validation_message =label +end;
                var label_html = "<label class='error'>"+validation_message+"</label>";
                $(this).parents(".form-group").append(label_html)
              }
          }
        });
       
        return flag;
    }
    // Custom search filter event
  
   



});
