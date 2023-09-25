function activate(element){

    $(element).attr('class','activate')
    echo
    
    }
function updateValues(element,column,id,fldtype) {
    
          if (fldtype = '#pidate'){
             
                var mydate = new Date($(fldtype).val())
                var day = mydate.getDate()
                var month = mydate.getMonth()
                var year = mydate.getFullYear()
              var value = $(fldtype).val()
          }
          else{
               var value = element.innerText
          }
          
            $.ajax(
                {
                    url:'update_orders.php',
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
        function formsubmit() {
            var MIPO_NO = document.getElementById('MIPO_NO').value;
            var MIPO_Quantity = document.getElementById('MIPO_Quantity').value;
            var PI_Num = document.getElementById('PI_Num').value;
            var PI_Date = document.getElementById('PI_Date').value;
            var Item_Code = document.getElementById('Item_Code').value;
            var PO_NO = document.getElementById('PO').innerHTML;
            //store all the submitted data in astring.
            var formdata =
                'MIPO_NO=' + MIPO_NO
                + '&MIPO_Quantity=' + MIPO_Quantity
                + '&PI_Num=' + PI_Num
                + '&PI_Date=' + PI_Date
                +'&Item_Code=' + Item_Code
                + '&PO_NO=' + PO_NO;
            // validate the form input
            if (MIPO_NO == '' ) {
                alert("Please Enter MIPO Number");
                return false;
            }
            if(Item_Code == '') {
                alert("Please Enter Item Code");
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
                 url: "insert.php", //call storeemdata.php to store form data
                 data: formdata,
                 cache: false,
                 success: function(html) {
                  alert(html);
                 }
            });
            
            return false;
        }
   
