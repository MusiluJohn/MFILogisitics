
// CLEAR MODAL DATA AFTER SUBMITTING
function clearInputs(){
    document.getElementById('MIPONO').value='';
    document.getElementById('MIPOQuantity').value='';
    document.getElementById('PI_Num').value='';
    document.getElementById('PIDate').value='';
}


// SUBMIT ORDERS FORM DATA
function updateValues(element,column,id) {
    
         
               var value = element.value
          
          
            $.ajax(
                {
                    url:'UpdateOrders.php',
                    type:'post',
                    data:{
                        value: value,
                        column: column,
                        id: id
                    },
            success:function(php_result)
                {
                        console.log(php_result);
                        
                    
                    } 
                }
            )
            /* console.log(value+column+id) */
        }

//UPDATE ORDER
function updateOrders() {

 $.ajax(
     {
         url:'UpdateOrder.php',
         type:'post',
         data:$('#Ordering').serialize(),
 success:function(php_result)
     {
             console.log(php_result);
             
         
         } 
     }
 )
 /* console.log(value+column+id) */
}


// UPDATE ORDER DATE

function updateOrderDate(element,column,id) {
    var value = element.value
    $.ajax(
        {
            url:'UpdateOrderDate.php',
            type:'post',
            data:{
                value: value,
                column: column,
                id: id
            },
    success:function(php_result)
    
    {
        if (php_result != '')
        {

            alert(php_result);
        }
        

        
        } 
        }
    )
    /* console.log(value+column+id) */
   }


// UPDATE INVOICE DATE

function updateInvoiceDate(element,column,id) {
    var value = element.value
    $.ajax(
        {
            url:'UpdateInvoiceDate.php',
            type:'post',
            data:{
                value: value,
                column: column,
                id: id
            },
    success:function(php_result)
    
    {
        if (php_result != '')
        {

            alert(php_result);
        }
        

        
        } 
        }
    )
    /* console.log(value+column+id) */
   }

        
// UPDATE ORDER QUANTITY 
        function updateOrderQuantity(element,column,id) {
    
         
            var value = element.value
       
       
         $.ajax(
             {
                 url:'updateOrderQuantity.php',
                 type:'post',
                 data:{
                     value: value,
                     column: column,
                     id: id
                 },
         success:function(php_result)
             {
                 if (php_result != '')
                 {

                     alert(php_result);
                 }
                 

                 
                 } 
             }
         )
         /* console.log(value+column+id) */
     }

// UPDATE INVOICE QUANTITY
     function updateInvoiceQuantity(element,column,id) {
    
         
        var value = element.value
   
   
     $.ajax(
         {
             url:'updateInvoiceQuantity.php',
             type:'post',
             data:{
                 value: value,
                 column: column,
                 id: id
             },
     success:function(php_result)
     {
        if (php_result != '')
        {

            alert(php_result);
        }
        

        
        } 
    }

     )
     /* console.log(value+column+id) */
 }



        function updateInvoices(element,column,id) {
    
         
            var value = element.value
       
       
         $.ajax(
             {
                 url:'updateInvoices.php',
                 type:'post',
                 data:{
                     value: value,
                     column: column,
                     id: id
                 },
         success:function(php_result)
             {
                     console.log(php_result);
                     
                 
                 } 
             }
         )
         /* console.log(value+column+id) */
     }


        $(document).ready(function(){
            $("#ItemCode").change(function(){
                var description = $("#ItemCode").val();
                $.ajax({
                    url: 'data.php',
                    method: 'post',
                    data: 'description=' + description
                }).done(function(description){
                    var item_description  = description;
                    console.log(description);
                    description = JSON.parse(description);
                    $('#description').empty();
                
                        $('#description').append('<input type="text" class="form-control">  ${description}   </input>')
                    
                })
            })
        })



        function formsubmit() {
            var MIPONO = document.getElementById('MIPONO').value;
            var MIPOQuantity = document.getElementById('MIPOQuantity').value;
            var PI_Num = document.getElementById('PI_Num').value;
            var PIDate = document.getElementById('PIDate').value;
            var ItemCode = document.getElementById('ItemCode').value;
            var PONO = document.getElementById('PO').innerHTML;
            //store all the submitted data in astring.
            var formdata =
                'MIPONO=' + MIPONO
                + '&MIPOQuantity=' + MIPOQuantity
                + '&PI_Num=' + PI_Num
                + '&PIDate=' + PIDate
                +'&ItemCode=' + ItemCode
                + '&PONO=' + PONO;
            // validate the form input
            if(ItemCode == '') {
                alert("Please Enter Item Code");
                return false;
            }
            if (MIPONO == '' ) {
                alert("Please Enter MIPO Number");
                return false;
            }
            if (MIPOQuantity == '' ) {
                alert("Please Enter MIPO Quantity");
                return false;
            }
            
            // if(username == '') {
            //     alert("Please Enter Username");
            //     return false;
            // }
            // if(pwd == '') {
            //     alert("Please Enter Password");
            //     return false;
            // }
            
            // AJAX code to submit form.
            $.ajax({
                 type: "POST",
                 url: "insert_orders.php", //call storeemdata.php to store form data
                 data: formdata,
                 cache: false,
                 success: function(html) {
                  alert(html);
                    var html = `'<tr>';
                    
                    html += '<td  class="table-active" align="left" >${ItemCode}</td>';
                    html += '<td  class="table-active" align="left" >${ItemCode}</td>';
                    html += '<td class="table-active" align="center" ></td>';
                    html += '<td class="table-active" align="center" ></td>';
                    html += '<td align="left"><div class="col"><input type="text" class="form-control" value = ${MIPONO}></div></td>';
                    html += '<td align="left"><div class="col"><input type="text" class="form-control" value = ${MIPOQuantity}></div></td>';
                    html += '<td align="left"><div class="col"><input type="text" class="form-control" value = ${PI_Num}></div></td>';
                    html += '<td align="center"><div class="col"><input type = "date" class="form-control" value = "${PIDate}"></input></div></td>';
                    html += '</tr>'`;
                    
                    $('#orders tbody').prepend(html);
                    clearInputs();
                    
                 }
            });
            
            return false;
            //call storeemdata.php to store form data
        }

// SUBMIT INVOICE FORM
        function Invoicesubmit() {
            var CINO = document.getElementById('CINO').value;
            var CIQuantity = document.getElementById('CIQuantity').value;
            var MIPONO = document.getElementById('CIQuantity').value;
            var PickupNo = document.getElementById('PickupNo').value;
            var CIDate = document.getElementById('CIDate').value;
            var ItemCode = document.getElementById('ItemCode').value;
            var FinalItemCode = document.getElementById('FinalItemCode').value;
            var PONO = document.getElementById('PO_Invoice').innerHTML;
            //store all the submitted data in astring.
            var formdata =
                'CINO=' + CINO
                + '&CIQuantity =' + CIQuantity
                + '&PickupNo=' + PickupNo
                + '&CIDate=' + CIDate
                +'&ItemCode=' + ItemCode
                +'&FinalItemCode=' + FinalItemCode
                + '&PONO=' + PONO;
            // validate the form input
            if(ItemCode == '') {
                alert("Please Enter Item Code");
                return false;
            }
            if (CINO == '' ) {
                alert("Please Enter CI Number");
                return false;
            }
            if (CIQuantity == '' ) {
                alert("Please Enter CI Quantity");
                return false;
            }
            
          
            // AJAX code to submit form.
            $.ajax({
                 type: "POST",
                 url: "InsertInvoices.php", //call storeemdata.php to store form data
                 data: formdata,
                 cache: false,
                 success: function(html) {
                  alert('row inserted');
                    var html = `'<tr>';
                    
                    html += '<td  class="table-active" align="left" >${ItemCode}</td>';
                    html += '<td  class="table-active" align="left" >${ItemCode}</td>';
                    html += '<td class="table-active" align="center" ></td>';
                    html += '<td class="table-active" align="center" ></td>';
                    html += '<td class="table-active" align="center" ></td>';
                    html += '<td class="table-active" align="center" ></td>';
                    html += '<td class="table-active" align="center" ></td>';
                    html += '<td class="table-active" align="center" ></td>';
                    html += '<td align="left"><div class="col"><input type="text" class="form-control" value = ${CINO}></div></td>';
                    html += '<td align="left"><div class="col"><input type="text" class="form-control" value = ${CIQuantity}></div></td>';
                    html += '<td align="center"><div class="col"><input type = "date" class="form-control" value = "${CIDate}"></input></div></td>';
                    html += '<td align="left"><div class="col"><input type="text" class="form-control" value = ${FinalItemCode}></div></td>';
                    html += '<td align="center"><div class="col"><input type = "date" class="form-control" value = "${PickupNo}"></input></div></td>';
                    html += '</tr>'`;

                    $('#orders tbody').prepend(html);
                 }
            });
            
            return false;
            //call storeemdata.php to store form data
        }
        

// DROP DOWN FOR SEARCH ITEMS
        
        $(document).ready(function () {
            // Send Search Text to the server
            $("#search").keyup(function () {
              let searchText = $(this).val();
              if (searchText != "") {
                $.ajax({
                  url: "search.php",
                  method: "post",
                  data: {
                    query: searchText,
                  },
                  success: function (response) {
                    $("#show-list").html(response);
                  },
                });
              } else {
                $("#show-list").html("");
              }
            });
            // Set searched text in input field on click of search button
            $(document).on("click", "a", function () {
              $("#search").val($(this).text());
              $("#show-list").html("");
            });
          });
          