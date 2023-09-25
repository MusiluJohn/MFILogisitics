
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
* {box-sizing: border-box;}



.topnav {
  overflow: hidden;
  background-color: #e9e9e9;
 
  position: fixed;
  top: 0;
  width: 100%;
  
 
  
  height: 60px; /* Used in this example to enable scrolling */
}

.topnav a {
  float: left;
  display: block;
  color: black;
  text-align: center;
  font-weight: bold;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
  font-family: 'Numans', sans-serif;
}

.topnav a:hover {
  background-color: #ddd;
  color: black;
}

.topnav a.active {
  background-color: #2196F3;
  color: #1eeb33;
}

.topnav .search-container {
  float: right;
}

.topnav input[type=text] {
  padding: 6px;
  margin-top: 8px;
  font-size: 17px;
 
  border-radius: 4px;
}
.topnav input[type=text]:focus {
  border: 3px Solid #1bcb0a;
}

.topnav .search-container button {
  float: right;
  padding: 6px;
  margin-top: 8px;
  margin-right: 12px;
  margin-left: 12px;
  background: #1bcb0a;
  font-size: 17px;
  border: none;
  cursor: pointer;
}

.topnav .search-container button:hover {
  background-color: #1bcb0a;
}

@media screen and (max-width: 600px) {
  .topnav .search-container {
    float: none;
  }
  .topnav a, .topnav input[type=text], .topnav .search-container button {
    float: none;
    display: block;
    text-align: left;
    width: 100%;
    margin: 0;
    padding: 14px;
    border-radius: 25px;
  }
  .topnav input[type=text] {
    border: 3px Solid #1bcb0a;  

}
</style>
</head>
<body>
<?php 
if(isset($_SESSION['user'])) {
?>


<div class="topnav">
<a class="navbar-brand" href="ordering.php">
      <img src="mfi logo.svg" alt="" width="70" height="40" class="d-inline-block align-text-top">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <a class="nav-link" aria-current="page" href="ordering.php">MV-ORDERING</a>
    <a class="nav-link" href="invoices.php">MV-INVOICING</a>
    
    <a class="nav-link" href="logout.php">REPORTS</a>
    <a class="nav-link" href="logout.php">LOGOUT</a>
    
  <div class="search-container">
    <form action = "" method= "GET">
      <input type="text" placeholder="PO Number.." name="search" id = "search" aria-label="Search" autocomplete = "off">
      <button class="btn btn-outline-success" type="submit">Search</button>

      
    </form>
    
  </div>

      </div>
      
<br>
<br>
<br>
<div  style="float: right;margin-top: 0px; margin-right: 80px; width: 240px;">
        <div class="list-group" id="show-list" >
          <!-- Here autocomplete list will be display -->
        </div>
</div>
<?php
}
else{
  header("location:index.php");
}
?>
