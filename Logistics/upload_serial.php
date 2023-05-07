<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/bootstrap1.css"/>
    <link rel="stylesheet" type="text/css" href="css/bootsrap2.css"/>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/bootstrap1.js"></script>
    <script src="js/bootstrap2.js.js"></script>
    <script src="js/bootstrap3.js"></script>
    <script src="js/jquery1.js"></script>
    <script src="js/jquery2.js"></script>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.min.js"></script> 
</head>
<link href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<body>

<?php include 'navbar2.php' ?>
<hr></hr>
<div id="replaceserials" style='margin-top:90px;'>
    <span class='symbol-input100' style='margin-left:15px;'>
    <i class='fa fa-user-circle' aria-hidden='true'><?php echo  $_SESSION['user'] ; ?></i></span>
    <span class='symbol-input100' style='margin-left:15px;'>
    <i class='fa fa-database' aria-hidden='true'><?php echo $_SESSION['db'] ; ?></i></span>
    <a class="nav-link" href="index.php" style='color:blue'>sign out</a>
<a>
</a>
<hr></hr>
</div>
<div class="align-content-end" >
<a style='margin-left:10px;'>
    Enter the serial numbers to replace on the replace with column and click validate to check if the serial numbers exist. If they do not exist click replace to post
</a>
<br>
<button id='validate' name='validate' type='button' class='btn-danger' style='margin-bottom:10px;' >
    Validate
</button>
<button id='replace' name='replace' type='button' class='btn-success' style='margin-bottom:10px;' >
    Replace
</button>
<h3 style='margin-left:10px;'>Upload excel file</h1>
    <!-- Input element to upload an excel file -->
    <input type="file" id="file_upload" style='margin-left:10px;'/>
    <br>
    <button onclick="upload()" style='margin-left:10px;' class='btn-success'>Upload</button>  
    <br>
    <br>
</div>
<!---table to display the excel data-->
<table id="tabletwo" name="tabletwo" class="table table-bordered table-striped table-hover" style='font-size:12px;'>
<thead class="table-success">
<tr id="thone">
    <td id="tdone">Serial Counter</td>
    <td id="tdtwo">
    Existing Serial Number
    </td>
    <td id="tdthree">
    Replace With
    </td>
</tr>
</thead>
<tbody>

<script>   
     // Method to upload a valid excel file
     function upload() {
       var files = document.getElementById('file_upload').files;
       if(files.length==0){
         alert("Please choose any file...");
         return;
       }
       var filename = files[0].name;
       var extension = filename.substring(filename.lastIndexOf(".")).toUpperCase();
       if (extension == '.XLS' || extension == '.XLSX') {
           //Here calling another method to read excel file into json
           excelFileToJSON(files[0]);
       }else{
           alert("Please select a valid excel file.");
       }
     }
      
     //Method to read excel file and convert it into JSON 
     function excelFileToJSON(file){
         try {
           var reader = new FileReader();
           reader.readAsBinaryString(file);
           reader.onload = function(e) {

               var data = e.target.result;
               var workbook = XLSX.read(data, {
                   type : 'binary'
               });
               var result = {};
               var firstSheetName = workbook.SheetNames[0];
               //reading only first sheet data
               var jsonData = XLSX.utils.sheet_to_json(workbook.Sheets[firstSheetName]);
               //displaying the json result into HTML table
               displayJsonToHtmlTable(jsonData);
               }
           }catch(e){
               console.error(e);
           }
     }
      
     //Method to display the data in HTML Table
     function displayJsonToHtmlTable(jsonData){
       var table=document.getElementById("tabletwo");
       if(jsonData.length>0){
        var htmlData='<tr class="table-success"><th >Serial Counter</th><th>Existing Serial Number</th><th>Replace With</th></tr>';
           for(var i=0;i<jsonData.length;i++){
               var row=jsonData[i];
               htmlData+='<tr><td>'+row["Serial Counter"]+'<input id="sid" name="sid[]" value='+row["Serial Counter"]+' hidden/></td><td>'+row["Existing Serial Number"] + '</td><td><input id="serial" name="serial[]" value='+row["Replace With"]+' type="text" style="form-control"/></td></tr>';
           }
           table.innerHTML=htmlData;
       }else{
           table.innerHTML='There is no data in Excel';
       }
     }
   </script>

</tbody>
</table>
<script>
    $(document).ready( function () {
        $('#tabletwo').DataTable();

    } );
</script>
<a id="link" href="CostEstimateHome.php"><<<<<< Go back</a>
</body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.js"></script>
    <script src="assets/js/script.min.js"></script>
    <script src="./assets/js/excel.js"></script>
    <script>
        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('tabletwo');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
                XLSX.writeFile(wb, fn || ('Serial template.' + (type || 'xlsx')));
            }
    </script>
<script src="js/script.js">
</script>
<!---Replace button--->
<script>
            $(document).ready(function(){
            $("#replace").click(function(){
                var counter=[];
                $('input[name^="sid"]').each(function() {
                    counter.push(this.value);
                });
                var serial=[];
                $('input[name^="serial"]').each(function() {
                    serial.push(this.value);
                });
                $.ajax({
                    url: 'update_serial.php',
                    type: 'post',
                    data: {counter:counter,serial:serial},
                    success: function(result){
                            alert('Serial numbers successfully updated');
                        },
                    error: function(result) {
                            alert('Error');
                    }
                            }); 
                        });
             });
        </script>
                <!---Validate button--->
        <script>
        $(document).ready(function(){
            $("#validate").click(function(){
                var serial=[];
                $('input[name^="serial"]').each(function() {
                    serial.push(this.value);
                });
            $.ajax({
                url: 'check_serial.php',
                type: 'post',
                data: {serial:serial},
                success: function(result){
                        alert(result);
                    },
                error: function(result) {
                        alert('Error');
                }
                        }); 
                    });
            });
        </script>

</html>