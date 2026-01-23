<div class="wrapper container-xxl flex-grow-1 container-p-y">
   <nav aria-label="breadcrumb">
      <div class="sub-header-left pull-left breadcrumb">
         <h1>
            Restaurant Management
            <a hijacked="yes" href="#stock/issue_request/index" class="backlisting-link" title="Back to Issue Request Listing" >
            <i class="ti ti-chevrons-right" ></i>
            <em >Restaurant</em></a>
         </h1>
         <br>
         <span >Listing</span>
      </div>
   </nav>
   <div class="dt-top-btn d-grid gap-2 d-md-flex justify-content-md-end mb-5">
      <button type="button" class="btn btn-seconday" data-bs-toggle="modal" data-bs-target="#addPromo">
      Add User
      </button>
      <!-- <button class="btn btn-seconday" type="button" id="downloadCSVBtn" title="Download CSV"><i class="ti ti-file-type-csv"></i></button>
      <button class="btn btn-seconday" type="button" id="downloadPDFBtn" title="Download PDF"><i class="ti ti-file-type-pdf"></i></button> -->
      <div class="dropdown grid-drop-down">
          <button class="btn btn-secondary top-btn-row btn-seconday " type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" title="Export">
            <i class=" la-list-ul ti ti-arrow-down-from-arc" ></i>
          </button>
          <ul class="dropdown-menu p-0 mt-1 export-drop-down" aria-labelledby="dropdownMenuButton1" >
            <li class="csv"  id="downloadCSVBtn" title="CSV"><label class="hide">CSV</label> <i class="ti ti-file-type-csv" style="color: black"></i></li>
            <li class="pdf " id="downloadPDFBtn" title="PDF"><label class="hide">PDF</label><i class="ti ti-file-type-pdf" style="color: black"></i></li>
          </ul>
      </div>
      <div class="dropdown grid-drop-down">
          <button class="btn btn-secondary top-btn-row btn-seconday " type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
            <i class=" la-list-ul ti ti-list-details" ></i>
          </button>
          <ul class="dropdown-menu p-0 mt-1 toggle-grid-btn" aria-labelledby="dropdownMenuButton1" >
            <li class="table active" data-value="Table"><label>Table</label> <i class="las la-stream" style="color: black"></i></li>
            <li class="grid " data-value="Grid"><label>Grid</label><i class="las la-border-all" style="color: black"></i></li>
          </ul>
      </div>
   </div>
   <div class="content-wrapper" >
      <!-- Main content -->
      <section class="content">
         <div>
            <!-- Small boxes (Stat box) -->
            <div class="row">
               
               <div class="col-lg-12">
                  <!-- Modal -->
                  <div class="w-100">
                     <input type="text" name="reason" placeholder="Filter Search" class="form-control serarch-filter-input m-3 me-0" id="serarch-filter-input" fdprocessedid="bxkoib">
                  </div>
                  <div class="card w-100 table-card">
                     <div class="table-responsive text-nowrap">
                        <table width="100%" border="1" cellspacing="0" cellpadding="0" class="table table-striped" style="border-collapse: collapse;" border-color="#e1e1e1" id="inwarding_grn">
                        <thead>
                              <tr>
                                 <%foreach from=$data key=key item=val%>
                                 <th><b>Search <%$val['title']%></b></th>
                                 <%/foreach%>
                              </tr>
                        </thead>
                        <tbody></tbody>
                     </table>
                        </div>
                                    </div>
                                    <!-- ./col -->
                                 </div>
                              </div>
                              <!-- /.row -->
                              <!-- Main row -->
                              <!-- /.row (main row) -->
                           </div>
                           <!-- /.container-fluid -->
                        </section>
                        <!-- /.content -->
                     </div>
</div>
</div>
<style type="text/css">
   input.check-box{
   width: 18px;
   height: 15px;
   cursor: pointer;
   }
   .menu-form-row {
   margin-top: 5px;
   padding-top: 5px;
   padding-bottom: 5px;
   width: 100%;
   position: relative;
   }
   .menu-form-row .form-label{
   float: left;
   width: 100% !important;
   }
   .menu-form-row .form-label lable{
   font-style: normal !important;
   display: block;
   margin-top: 3px;
   font-size: 17px;
   color: #919396;
   font-family: 'GilroySemibold', sans-serif !important;
   }
   .menu-form-row .form-right-div {
   margin: 10px 6px 10px 13px;
   float: left;
   width: 100% !important;
   }
   .menu-form-row .margin-equilize {
   float: left;
   width: 20%;
   }
   .menu-form-row .margin-equilize label{
   font-size: 17px;
   color: #000;
   margin: 0px 0px 2px 8px;
   }
   .menu-form-row .margin-equilize input{
   width: 17px;
   height: 15px;
   cursor: pointer;
   }
   #accessGroups .modal-body {
   padding: 0 20px 0 20px;
   max-height: 433px !important;
   overflow-y: scroll;
   overflow-x: clip;
   }
   .pointer-none{
   pointer-events: none;
   }
   .select2-container--default .select2-selection--multiple .select2-selection__choice {
   background-color: var(--bs-theme-light4-color) !important;
   }
</style>
 <script>
    var column_details =  <%$data|json_encode%>;
    var page_length_arr = <%$page_length_arr|json_encode%>;
    var is_searching_enable = <%$is_searching_enable|json_encode%>;
    var is_top_searching_enable =  <%$is_top_searching_enable|json_encode%>;
    var is_paging_enable =  <%$is_paging_enable|json_encode%>;
    var is_serverSide =  <%$is_serverSide|json_encode%>;
    var no_data_message =  <%$no_data_message|json_encode%>;
    var is_ordering =  <%$is_ordering|json_encode%>;
    var sorting_column = <%$sorting_column%>;
    var api_name =  <%$api_name|json_encode%>;
    var base_url = <%$base_url|json_encode%>;
    var start_date = <%$start_date|json_encode%>;
    var end_date = <%$end_date|json_encode%>;
</script>

  <script src="<%$base_url%>public/js/restaurant/restaurant_list.js"></script>