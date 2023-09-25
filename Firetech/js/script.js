    
var debugScript = true;
    function openwin(){
        window.open("createscheme.php")
    }
    function openwinscheme(){
        window.open("schemes.php")
    }
    function msg(){
        window.alert("This shipment has been closed. No further updates can be done");
        location.reload();
    }
    function msg2(){
        window.alert("Costs have been successfully updated. Click 'Post into sage evolution' to update into sage");
        window.location.href.open();
    }
    function del(){
        window.alert("Lines will be deleted");
   
    }
    function upload(){
        alert("Hello");
        }
        function selectall(){
            var chk=document.getElementById('checkall');
            var items=document.getElementsByName('users[]');
            for(var i=0; i<items.length; i++){
                if(items[i].type=='checkbox' && chk.checked==1){
                    chk.checked=true;
                    items[i].checked=true;
                } else {
                    chk.checked=true;
                    items[i].checked=true;    
                }
                }
            }
    function selectallship(){
        var chk=document.getElementById('selectall');
        var items=document.getElementsByName('lines[]');
        for(var i=0; i<items.length; i++){
            if(items[i].type=='checkbox' && chk.checked==1){
                items[i].checked=true;
            } else {
                items[i].checked=false;
            }
        }

    }
 
    function exportData(){
        /* Get the HTML data using Element by Id */
        var table = document.getElementById("cost_table");
    
        /* Declaring array variable */
        var rows =[];
    
          //iterate through rows of table
        for(var i=0,row; row = table.rows[i];i++){
            //rows would be accessed using the "row" variable assigned in the for loop
            //Get each cell value/column from the row
            column1 = row.cells[0].innerText;
            column2 = row.cells[1].innerText;
            column3 = row.cells[2].innerText;
            column4 = row.cells[3].innerText;
            column5 = row.cells[4].innerText;
    
        /* add a new records in the array */
            rows.push(
                [
                    column1,
                    column2,
                    column3,
                    column4,
                    column5
                ]
            );
    
            }
            csvContent = "data:text/csv;charset=utf-8,";
             /* add the column delimiter as comma(,) and each row splitted by new line character (\n) */
            rows.forEach(function(rowArray){
                row = rowArray.join(",");
                csvContent += row + "\r\n";
            });
    
            /* create a hidden <a> DOM node and set its download attribute */
            var encodedUri = encodeURI(csvContent);
            var link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "Stock_Price_Report.csv");
            document.body.appendChild(link);
             /* download the data file named "Stock_Price_Report.csv" */
            link.click();
    }