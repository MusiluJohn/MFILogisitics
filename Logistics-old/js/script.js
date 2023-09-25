    
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
    function selectall(){
        var chk=document.getElementById('checkall');
        var items=document.getElementsByName('users[]');
        for(var i=0; i<items.length; i++){
      
                items[i].checked=true;
                chk.checked=true;    
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
    function computeTableColumnTotal(tableId, colNumber)
    {
    // find the table with id attribute tableId
    // return the total of the numerical elements in column colNumber
    // skip the top row (headers) and bottom row (where the total will go)
            
    var result = 0;
            
    try
    {
        var tableElem = window.document.getElementById("cost_table"); 		   
        var tableBody = tableElem.getElementsByTagName("tbody").item(0);
        var i;
        var howManyRows = tableBody.rows.length;
        for (i=0; i<(howManyRows); i++) // skip first and last row (hence i=1, and howManyRows-1)
        {
        var thisTrElem = tableBody.rows[i];
        var thisTdElem = thisTrElem.cells[colNumber];			
        var thisTextNode = thisTdElem.childNodes.item(0);
        if (debugScript)
        {
            //window.alert("text is " + thisTextNode.data);
        } // end if

        // try to convert text to numeric
        var thisNumber = parseFloat(thisTextNode.data);
        // if you didn't get back the value NaN (i.e. not a number), add into result
        if (!isNaN(thisNumber))
            result += thisNumber;
        } // end for
            
    } // end try
    catch (ex)
    {
       // window.alert("Exception in function computeTableColumnTotal()\n" + ex);
        result = 0;
    }
    finally
    {
        return result;
    }
        
    }
    function costTable(){
        
        {

                 
            var tableElemName = "cost_table";
                 
            var totalqty= computeTableColumnTotal("cost_table",5);
            var totalamount = computeTableColumnTotal("cost_table",7);  
            var tot_amount_kes= computeTableColumnTotal("cost_table",8);
            var tot_weight= computeTableColumnTotal("cost_table",9);
            var tot_tot_weight= computeTableColumnTotal("cost_table",10);
            var swift= computeTableColumnTotal("cost_table",12);
            var insurance= computeTableColumnTotal("cost_table",13);
            var duty= computeTableColumnTotal("cost_table",14);
            var railway= computeTableColumnTotal("cost_table",15);
            var gok= computeTableColumnTotal("cost_table",16);
            var customs= computeTableColumnTotal("cost_table",17);
            var frefor= computeTableColumnTotal("cost_table",18);
            var frekes= computeTableColumnTotal("cost_table",19);
            var entry= computeTableColumnTotal("cost_table",20);
            var penalty= computeTableColumnTotal("cost_table",21);
            var handling= computeTableColumnTotal("cost_table",22);
            var kebs= computeTableColumnTotal("cost_table",23);
            var ism= computeTableColumnTotal("cost_table",24);
            var storage= computeTableColumnTotal("cost_table",25);
            var customproc= computeTableColumnTotal("cost_table",26);
            var customver= computeTableColumnTotal("cost_table",27);
            var agency= computeTableColumnTotal("cost_table",28);
            var doccharges= computeTableColumnTotal("cost_table",29);
            var breakbulk= computeTableColumnTotal("cost_table",30);
            var offloading= computeTableColumnTotal("cost_table",31);
            var transport= computeTableColumnTotal("cost_table",32);
            var coc= computeTableColumnTotal("cost_table",33);
            var concession= computeTableColumnTotal("cost_table",34);
            var surcharges= computeTableColumnTotal("cost_table",35);
            var othercharges= computeTableColumnTotal("cost_table",38);
            var exciseduty= computeTableColumnTotal("cost_table",39);
            var stamps= computeTableColumnTotal("cost_table",40);
            var additionalcst= computeTableColumnTotal("cost_table",41);
            var disbcst= computeTableColumnTotal("cost_table",42);
            var vat= computeTableColumnTotal("cost_table",43);
            var totals= computeTableColumnTotal("cost_table",44);
            var unitcst= computeTableColumnTotal("cost_table",45);
            try 
            {
              var totalqtyelem = window.document.getElementById("qty");
              totalqtyelem.innerHTML = totalqty.toFixed(3);
              var totalamountelem = window.document.getElementById("amount");
              totalamountelem.innerHTML = totalamount.toFixed(3);
              var totamountkeselem = window.document.getElementById("totamount");
              totamountkeselem.innerHTML = tot_amount_kes.toFixed(3);
              var totweightelem = window.document.getElementById("totamountkes");
              totweightelem.innerHTML = tot_weight.toFixed(3);
              var tottotweightelem = window.document.getElementById("weight");
              tottotweightelem.innerHTML = tot_tot_weight.toFixed(3);
              var swiftelem = window.document.getElementById("tot_tot_weight");
              swiftelem.innerHTML = swift.toFixed(3);
              var insuranceelem = window.document.getElementById("swifttot");
              insuranceelem.innerHTML = insurance.toFixed(3);
              var dutyelem = window.document.getElementById("insurance");
              dutyelem.innerHTML = duty.toFixed(3);
              var railwayelem = window.document.getElementById("dutytot");
              railwayelem.innerHTML = railway.toFixed(3);
              var gokelem = window.document.getElementById("railwaytot");
              gokelem.innerHTML = gok.toFixed(3);
              var customselem = window.document.getElementById("goktot");
              customselem.innerHTML = customs.toFixed(3);
              var freforelem = window.document.getElementById("customstot");
              freforelem.innerHTML = frefor.toFixed(3);
              var frekeselem = window.document.getElementById("freight_for");
              frekeselem.innerHTML = frekes.toFixed(3);
              var entryelem = window.document.getElementById("freight_ksh");
              entryelem.innerHTML = entry.toFixed(3);
              var penaltyelem = window.document.getElementById("entrytot");
              penaltyelem.innerHTML = penalty.toFixed(3);
              var handlingelem = window.document.getElementById("penaltytot");
              handlingelem.innerHTML = handling.toFixed(3);
              var kebselem = window.document.getElementById("handlingtot");
              kebselem.innerHTML = kebs.toFixed(3);
              var ismelem = window.document.getElementById("kebstot");
              ismelem.innerHTML = ism.toFixed(3);
              var storageelem = window.document.getElementById("ismtot");
              storageelem.innerHTML = storage.toFixed(3);
              var custprocelem = window.document.getElementById("storagetot");
              custprocelem.innerHTML = customproc.toFixed(3);
              var custverelem = window.document.getElementById("customsproc");
              custverelem.innerHTML = customver.toFixed(3);
              var agencyelem = window.document.getElementById("customsver");
              agencyelem.innerHTML = agency.toFixed(3);
              var docchargeselem = window.document.getElementById("agencyfeetot");
              docchargeselem.innerHTML = doccharges.toFixed(3);
              var breakbulkelem = window.document.getElementById("docchargestot");
              breakbulkelem.innerHTML = breakbulk.toFixed(3);
              var offloadingelem = window.document.getElementById("breakbulktot");
              offloadingelem.innerHTML = offloading.toFixed(3);
              var transportelem = window.document.getElementById("offloadingtot");
              transportelem.innerHTML = transport.toFixed(3);
              var cocelem = window.document.getElementById("transporttot");
              cocelem.innerHTML = coc.toFixed(3);
              var concessionelem = window.document.getElementById("coc_ksh");
              concessionelem.innerHTML = concession.toFixed(3);
              var surchargeselem = window.document.getElementById("concessiontot");
              surchargeselem.innerHTML = surcharges.toFixed(3);
              var othchargeselem = window.document.getElementById("actualfactortot");
              othchargeselem.innerHTML = othercharges.toFixed(3);
              var excisedutyelem = window.document.getElementById("otherchargestot");
              excisedutyelem.innerHTML = exciseduty.toFixed(3);
              var stampselem = window.document.getElementById("excisedutytot");
              stampselem.innerHTML = stamps.toFixed(3);
              var addcstelem = window.document.getElementById("stampstot");
              addcstelem.innerHTML = additionalcst.toFixed(3);
              var disbcstelem = window.document.getElementById("addcosttot");
              disbcstelem.innerHTML = disbcst.toFixed(3);
              var vatelem = window.document.getElementById("disbcosttot");
              vatelem.innerHTML = vat.toFixed(3);
              var totelem = window.document.getElementById("vattotals");
              totelem.innerHTML = totals.toFixed(3);
              var unitcstelem = window.document.getElementById("totalstot");
              unitcstelem.innerHTML = unitcst.toFixed(3);
             }
             catch (ex)
             {
               window.alert("Exception in function finishTable()\n" + ex);
             }
          
             return;
         }}
         