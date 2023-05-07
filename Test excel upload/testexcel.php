<! DOCTYPE html>  
<html lang = "en">  
<head>  
<title> Import EXcel Sheet into HTML structure using jQuery </title>  
        <meta charset = "utf-8">  
    <meta name = "viewport" content = "width=device-width, initial-scale=1,  
        shrink-to-fit = no">  
    <script src = "https://code.jquery.com/jquery-3.5.1.slim.min.js">  
    </script>  
    <script src = "https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"> </script>  
    <script src = "https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"> </script>  
    <script src = "https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js">  
    </script>  
    <link rel = "stylesheet" href = "https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">  
    <script src = "https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js">  
    </script>  
    <link rel = "stylesheet" href = "https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css">  
    <script src = "https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js">  
    </script>  
<style>  
.table td {  
            padding: 3px;  
        }  
        .table tbody tr td .btn-success {  
                padding: 2px 10px;  
                margin: 2px;  
                font-size: 0.8em;  
    color: white;  
        }  
  
        .table tbody tr td .btn-danger {  
                padding: 2px 10px;  
                margin: 5px;  
                font-size :0.8em;  
}  
table td {  
    padding: 3px;  
    color: #ced4da;  
    font-weight: bold;  
}  
table>:not(:last-child)>:last-child>* {  
    border-bottom-color: currentColor;  
    color: white;  
    background: #857664;  
}  
table tr:nth-child(even) {  
color: #f0f0f0;  
}  
.table tr:nth-child(odd) {  
color: #FFF;  
}  
* {  
    padding: 0;  
    margin: 0;  
    box-sizing: border-box;  
    font-family: sans-serif;  
    line-height: 1.68em;  
}  
h1 {  
  text-align: center;  
  font-weight: bold;  
  color: #e32aaa;  
  text-transform: uppercase;  
  font-size: 1em;  
  white-space: nowrap;  
  font-size: 3vw;  
  z-index: 1000;  
  font-family: 'Bangers', cursive;  
  text-shadow: 5px 5px 0 rgba(0, 0, 0, 0.7);  
  @include skew(0, -6.7deg, false);  
  @include transition-property(font-size);  
  @include transition-duration(0.5s);  
}  
h2 {  
  text-align: center;  
  font-weight: bold;  
  color: #e32aaa;  
  text-transform: uppercase;  
  font-size: 1em;  
  white-space: nowrap;  
  font-size: 3vw;  
  z-index: 1000;  
  font-family: 'Bangers', cursive;  
  text-shadow: 5px 5px 0 rgba(0, 0, 0, 0.7);  
  @include skew(0, -6.7deg, false);  
  @include transition-property(font-size);  
  @include transition-duration(0.5s);  
}  
body {  
   display: flex;  
   flex-direction: column;  
   justify-content: center;  
   align-items: center;  
    background: #2c3e50;  
  font-family: "Montserrat", sans-serif;  
  color: white;  
}  
button  
{  
  height: 6vh;  
  width: 12vw;    
  min-width: 160px;  
  min-height: 50px;    
  margin: 1vw;    
  cursor: pointer;    
  font-size: 1.4rem;  
  font-weight: 700;  
  letter-spacing: 0.07rem;    
  position: relative;  
  overflow: hidden;  
}  
  
button:nth-of-type(1)  
{  
  transition: color .4s ease-in-out, background-color 1.5s ease-in-out;  
}  
button:nth-of-type(1):hover  
{  
  color: #ec1111;  
}  
button:nth-of-type(1)::after  
{  
  content: ' ';  
  position: absolute;  
  bottom: 0;  
  left: -100%;  
  background-color: red;  
  height:10%;  
  width: 100%;  
  transition: all .4s ease-in-out;  
}  
button:nth-of-type(1):hover::after  
{  
  left: 0;  
}  
button:nth-of-type(2) {  
  border-color: #f1c40f;  
  color: #fff;  
  background-image: linear-gradient(45deg, red 50%, transparent 50%);  
  background-position: 100%;  
  background-size: 400%;  
  transition: background 300ms ease-in-out;  
}  
button:nth-of-type(2):hover {  
  background-position: 0;  
}  
</style>  
<body style = 'padding: 25px;'>  
<h2> Example </h2>  
  <h3> Import EXcel Sheet into HTML structure using jQuery </h3>      
<br />  
<br />  
  
    <form enctype = "multipart/form-data">          
        <input id = "upload" type = file name = "files[]">  
      <br> <br>  
    </form>  
        <table class = "table table-bordered">  
            <thead>  
                <tr>  
                    <th scope = "col"> Sr. No </th>  
                    <th scope = "col"> First Name </th>  
                    <th scope = "col"> Last Name </th>    
                    <th scope = "col"> Gender</th>  
                   <th scope = "col"> Country </th>  
                    <th scope = "col"> Age </th>          
                   <th scope = "col"> Date </th>          
                   <th scope = "col"> Id </th>  
                   <th scope = "col" colspan="2"> Operations </th>  
                </tr>  
            </thead>  
            <tbody>  
                <tr>  
                    <td class = 'txtcode'> </td>  
                    <td class = 'txtdesc'> </td>  
                    <td class = 'txtprice'> </td>  
                    <td class = 'txtqty'> </td>  
        <td class = 'code'> </td>  
                    <td class = 'desc'> </td>  
                    <td class = 'price'> </td>  
                    <td class = 'qty'> </td>  
                    <td>  
<button type = 'button' class = 'btn btn-success'> Add </button>  
 <button class = 'btn btn-danger' type = 'button'> Remove </button> </td>  
                </tr>  
                                <tr>  
                    <td class = 'txtcode'> </td>  
                    <td class = 'txtdesc'> </td>  
                    <td class = 'txtprice'> </td>  
                    <td class = 'txtqty'> </td>  
        <td class = 'code'> </td>  
                    <td class = 'desc'> </td>  
                    <td class = 'price'> </td>  
                    <td class = 'qty'> </td>  
                    <td>  
<button type = 'button' class = 'btn btn-success'> Add </button>  
 <button class = 'btn btn-danger' type = 'button'> Remove </button> </td>  
                </tr>  
                                <tr>  
                    <td class = 'txtcode'> </td>  
                    <td class = 'txtdesc'> </td>  
                    <td class = 'txtprice'> </td>  
                    <td class = 'txtqty'> </td>  
        <td class = 'code'> </td>  
                    <td class = 'desc'> </td>  
                    <td class = 'price'> </td>  
                    <td class = 'qty'> </td>  
                    <td>  
<button type = 'button' class = 'btn btn-success'> Add </button>  
 <button class = 'btn btn-danger' type = 'button'> Remove </button> </td>  
                </tr>  
                                <tr>  
                    <td class = 'txtcode'> </td>  
                    <td class = 'txtdesc'> </td>  
                    <td class = 'txtprice'> </td>  
                    <td class = 'txtqty'> </td>  
        <td class = 'code'> </td>  
                    <td class = 'desc'> </td>  
                    <td class = 'price'> </td>  
                    <td class = 'qty'> </td>  
                    <td>  
<button type = 'button' class = 'btn btn-success'> Add </button>  
 <button class = 'btn btn-danger' type = 'button'> Remove </button> </td>  
                </tr>  
                <tr>  
                    <td class = 'txtcode'> </td>  
                    <td class = 'txtdesc'> </td>  
                    <td class = 'txtprice'> </td>  
                    <td class = 'txtqty'> </td>  
        <td class = 'code'> </td>  
                    <td class = 'desc'> </td>  
                    <td class = 'price'> </td>  
                    <td class = 'qty'> </td>  
                    <td>  
<button type = 'button' class = 'btn btn-success'> Add </button>  
 <button class = 'btn btn-danger' type = 'button'> Remove </button> </td>  
                </tr>  
                <tr>  
                    <td class = 'txtcode'> </td>  
                    <td class = 'txtdesc'> </td>  
                    <td class = 'txtprice'> </td>  
                    <td class = 'txtqty'> </td>  
        <td class = 'code'> </td>  
                    <td class = 'desc'> </td>  
                    <td class = 'price'> </td>  
                    <td class = 'qty'> </td>  
                    <td>  
<button type = 'button' class = 'btn btn-success'> Add </button>  
 <button class = 'btn btn-danger' type = 'button'> Remove </button> </td>  
                </tr>  
                <tr>  
                    <td class = 'txtcode'> </td>  
                    <td class = 'txtdesc'> </td>  
                    <td class = 'txtprice'> </td>  
                    <td class = 'txtqty'> </td>  
                     <td class = 'code'> </td>  
                    <td class = 'desc'> </td>  
                    <td class = 'price'> </td>  
                    <td class = 'qty'> </td>  
                    <td>  
<button type = 'button' class = 'btn btn-success'> Add </button>  
 <button class = 'btn btn-danger' type = 'button'> Remove </button> </td>  
                </tr>  
                <tr>  
                    <td class = 'txtcode'> </td>  
                    <td class = 'txtdesc'> </td>  
                    <td class = 'txtprice'> </td>  
                    <td class = 'txtqty'> </td>  
            <td class = 'code'> </td>  
                    <td class = 'desc'> </td>  
                    <td class = 'price'> </td>  
                    <td class = 'qty'> </td>  
                    <td>  
<button type = 'button' class = 'btn btn-success'> Add </button>  
 <button class = 'btn btn-danger' type = 'button'> Remove </button> </td>  
                </tr>  
                <tr>  
                    <td class = 'txtcode'> </td>  
                    <td class = 'txtdesc'> </td>  
                    <td class = 'txtprice'> </td>  
                    <td class = 'txtqty'> </td>  
        <td class = 'code'> </td>  
                    <td class = 'desc'> </td>  
                    <td class = 'price'> </td>  
                    <td class = 'qty'> </td>  
                    <td>  
<button type = 'button' class = 'btn btn-success'> Add </button>  
 <button class = 'btn btn-danger' type = 'button'> Remove </button> </td>  
                </tr>  
            </tbody>  
        </table>  
    <script>  
$(document).on('click', '.table tbody tr td .btn-success', function() {  
        var html = '';  
        html += "<tr> <td class = 'txtcode'> </td>"  
        html += "<td class = 'txtdesc'> </td>"  
        html += "<td class = 'txtprice'> </td>"  
        html += "<td class = 'txtqty' > </td>"  
        html +=  "<td class = 'code'> </td>"  
        html +=  "<td class = 'desc'> </td>"  
        html +=  "<td class = 'price'> </td>"  
        html += "<td class = 'qty' > </td>"  
        html +=  "<td> <button type = 'button' class = 'btn btn-success'> Add </button> <button class = 'btn btn-danger' type = 'button'> Remove </button> </td>"  
        html += "</tr>"  
        $(this).parent().parent().after(html)  
    })  
    $(document).on('click', '.table tbody tr td .btn-danger', function() {  
        $(this).parent().parent().remove()  
    })  
    var ExcelToJSON = function() {  
  
    this.parseExcel = function(file) {  
        var reader = new FileReader();  
        reader.onload = function(e) {  
            var data = e.target.result;  
            var workbook = XLSX.read(data, {  
                type: 'binary'  
            });  
            workbook.SheetNames.forEach(function(sheetName) {  
                var XL_row_object = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);  
                var json_object = JSON.stringify(XL_row_object);  
                productList = JSON.parse(json_object);  
  
                var rows = $('.table tbody tr', );  
                console.log(productList)  
                for (i = 0; i < productList.length; i++) {  
                    var columns = Object.values(productList[i])  
                    rows.eq(i).find('td.txtcode').text(columns[0]);  
                    rows.eq(i).find('td.txtdesc').text(columns[1]);  
                    rows.eq(i).find('td.txtprice').text(columns[2]);  
                    rows.eq(i).find('td.txtqty').text(columns[3]);  
                    rows.eq(i).find('td.code').text(columns[4]);  
                    rows.eq(i).find('td.desc').text(columns[5]);  
                    rows.eq(i).find('td.price').text(columns[6]);  
                    rows.eq(i).find('td.qty').text(columns[7]);  
                }  
            })  
        };  
        reader.onerror = function(ex) {  
            console.log(ex);  
        };  
        reader.readAsBinaryString(file);  
    };  
};  
  
function handleFileSelect(evt) {  
    var files = evt.target.files;   
    var xl2json = new ExcelToJSON();  
    xl2json.parseExcel(files[0]);  
}  
document.getElementById('upload').addEventListener('change', handleFileSelect, false);  
</script>  
</body>  
</html>  