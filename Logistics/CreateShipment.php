
<html>
<title>
    Create shipment
</title>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap1.css"/>
<link rel="stylesheet" type="text/css" href="css/bootsrap2.css"/>
<link rel="stylesheet" href="css/style.css">
<link href="//netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="js/script.js">
</script>
<script src="js/bootstrap1.js"></script>
<script src="js/bootstrap2.js.js"></script>
<script src="js/bootstrap3.js"></script>
<script src="js/jquery1.js"></script>
<script src="js/jquery2.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<body>
<?php include 'navbar2.php' ?>
<!---Get shipment numbers--->
<div class="container" style='margin-top:90px;'>
    <span class='symbol-input100' style='margin-left:15px;'>
    <i class='fa fa-user-circle' aria-hidden='true'><?php echo  $_SESSION['user'] ; ?></i></span>
    <span class='symbol-input100' style='margin-left:15px;'>
    <i class='fa fa-database' aria-hidden='true'><?php echo $_SESSION['db'] ; ?></i></span>
    <a class="nav-link" href="index.php" style='color:blue'>sign out</a>
<div style="width:3000px;height:60px;">
<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
<button class='btn btn-success' style='margin-top:10px;margin-left:5px;margin-left:400px;' id="update" name="update" type="submit">CREATE SHIPMENT</button>
<script>
$(document).ready(function(){
$('#update').click(function(){         
                    $.ajax({
                            url: 'postship.php',
                            type: 'POST',
                            data: {shipno: $('#shipno').val(),mode: $('#mode').val(), weight: $('#weight').val(),volume: $('#volume').val(), 
                            packages: $('#packages').val(), portdate: $('#portdate').val(),officedate: $('#officedate').val(),
                            arrdate: $('#arrdate').val(),customno: $('#customno').val(),customdate: $('#customdate').val()
                            ,passdate: $('#passdate').val(),idfno: $('#idfno').val(),twentyft: $('#twentyft').val(),fortyft: $('#fortyft').val()
                            ,lcl: $('#lcl').val(),clagent: $('#clagent').val(),status: $('#status').val(),awb: $('#awb').val()
                            ,coc: $('#coc').val(),etddate: $('#etddate').val(),paystatus: $('#paystatus').val(),usdrate: $('#usdrate').val()
                            ,eurrate: $('#eurrate').val(),freightusd: $('#freightusd').val(),freigheur: $('#freigheur').val()
                            ,othchgs: $('#othchgs').val(),inschgs: $('#inschgs').val(),portchgs: $('#portchgs').val()
                            ,agfees: $('#agfees').val(),kebsfees: $('#kebsfees').val(),awbno: $('#awbno').val(),mino: $('#mino').val()
                            ,pino: $('#pino').val(),pidate: $('#pidate').val(),cino: $('#cino').val(),cidate: $('#cidate').val()
                            ,pickupno: $('#pickupno').val()},
                            success: function(result){
                                alert(result);
                               // alert('Shipment successfully created');
                            }
                            })
                    })});
</script>

</div>
</div>
</br>
<hr>
<div class="content flow" id="div-left" style="width:900px;">

<div class="even-columns">
<div class="col">
<!--------Left div------>
<table style="margin-left:60px;">
    <tr><td style='width:100px;'><label >Shipment No: </label></td>
    <td style='width:100px;'><input name='shipno' id='shipno' class='form-control' type='text' /></td></td></tr>
    </form>    
    </div>
    </div>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Mode of Transport: </label></td>
    <td><select name='mode' id='mode' class='form-control' style='margin-top:10px;height:30px;' >
    <option>Air</option><option>Sea</option><option>Courier</option>
    </select></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Gross Weight (Kgs): </label></td>
    <td><input name='weight' id='weight' value=0 class='form-control' type='number'  style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Volume (CBM): </label></td><td>
    <input name='volume' value=0 id='volume' class='form-control' type='number' style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>No. of Packages: </label></td><td>
    <input name='packages' id='packages' value=0 class='form-control' type='number'  style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>ETA @ Port [NBI/MSA]: </label></td><td>
    <input name='portdate' id='portdate' type='date' class='form-control' style='margin-top:10px;' /></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>ETA @ Office*: </label></td><td>
    <input name='officedate' id='officedate' type='date'  class='form-control' style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Actual Arrival  @ Port: </label></td><td>
    <input name='arrdate' id='arrdate' type='date'  class='form-control' style='margin-top:10px;' disabled/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Custom Entry No.: </label></td><td>
    <input name='customno' id='customno' value=0 class='form-control' style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Custom Entry Date: </label></td><td>
    <input name='customdate' id='customdate' class='form-control' type='date' style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Custom Pass Date: </label></td><td>
    <input name='passdate' id='passdate' class='form-control' type='date' style='margin-top:10px;' disabled/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>IDF No.: </label></td><td>
    <input name='idfno' id='idfno' value=0 class='form-control' style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>How many 20FT Container(s): </label></td><td>
    <input name='twentyft' id='twentyft' class='form-control'  type='number' value=0 style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>How many 40FT Container(s): </label></td><td>
    <input name='fortyft' id='fortyft' class='form-control'  type='number' value=0 style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>LCL (Put 1 if yes): </label></td><td>
    <input name='lcl' id='lcl' class='form-control' value=0 type='number'  style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Clearing Agent: </label></td><td>
    <select name='clagent' id='clagent'  class='form-control' style='margin-top:10px;height:30px;'>
    <option>Admin</option><option>Grace</option>
    </select></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Status: </label></td><td>
    <select name='status' id='status' class='form-control' style='margin-top:10px;height:30px;'>
    <option>IN TRANSIT</option><option>CLOSED</option>
    </select></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>AWB/BL Date: </label></td><td>
    <input name='awb' id='awb' class='form-control' type='date' style='margin-top:10px;'/></td></tr>
</table>
</div>
<!---------right div----->
<div class="col">
<!--<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8" id="div-right">-->
<table  style="margin-left:60px;">
    <tr><td>
    <tr><td style='width:100px;'><label>COC No.: </label></td><td>
    <input name='coc' value=0 id='coc' class='form-control'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>ETD Origin (Date): </label></td><td>
    <input name='etddate' id='etddate' class='form-control' type='date' style='margin-top:10px;' disabled/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Payment Status: </label></td><td>
    <select name='paystatus' id='paystatus' class='form-control' style='margin-top:10px;height:30px;'>
    <option>UNPAID</option><option>PAID</option>
    </select></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>USD Exchange Rate: </label></td><td>
    <input name='usdrate' value=0 id='usdrate' class='form-control' type='number'  style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>EUR Exchange Rate: </label></td><td>
    <input name='eurrate' value=0 id='eurrate' class='form-control' type='number'  style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Freight Charges USD: </label></td><td>
    <input name='freightusd'  value=0 id='freightusd' class='form-control' type='number'  style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Freight Charges EUR: </label></td><td>
    <input name='freigheur' value=0 id='freigheur' class='form-control' type='number'  style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Other Charges KES: </label></td><td>
    <input name='othchgs' value=0 id='othchgs' class='form-control' type='number'  style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Insurance Charges KES: </label></td><td>
    <input name='inschgs' value=0 id='inschgs' class='form-control' type='number'  style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Freight Charges KES: </label></td><td>
    <input name='portchgs' value=0 id='portchgs' class='form-control' type='number'  style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Agency Fees KES: </label></td><td>
    <input name='agfees'  value=0 id='agfees' class='form-control' type='number'  style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>KEBS Fees KES: </label></td><td>
    <input name='kebsfees' value=0 id='kebsfees' class='form-control' type='number'   style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>AWB/BL No.: </label></td><td>
    <input name='awbno'  value=0 id='awbno' class='form-control' style='margin-top:10px;' /></td></tr>
    <tr ><td style='width:100px;'><label style='margin-top:10px;'>MI PO No.: </label></td><td>
    <input name='mino' value=0 id='mino' class='form-control' style='margin-top:10px;' disabled/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Main Supplier PI No.: </label></td><td>
    <input name='pino' value=0 id='pino' class='form-control' style='margin-top:10px;' disabled/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Main Supplier PI Date: </label></td><td>
    <input name='pidate' id='pidate' class='form-control' type='date' style='margin-top:10px;' disabled/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Main Supplier CI No.: </label></td><td>
    <input name='cino' value=0 id='cino' class='form-control' style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Main Supplier CI Date: </label></td><td>
    <input name='cidate' id='cidate' class='form-control' type='date' style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Main Supplier Pickup No.: </label></td><td>
    <input name='pickupno' value=0 id='pickupno' class='form-control' style='margin-top:10px;' disabled/></td></tr>
    </tr></td>
    </table>
    <!--</div>--->
    </div>
</div>
</div>
</body>
</html>