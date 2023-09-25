<link rel="stylesheet" href = "style.css">
<nav class="navbar navbar-expand-lg navbar-light bg-light style=background-color: #e3f2fd;">
  <div class="container-fluid">
  <a class="navbar-brand" href="ordering.php">
      <img src="mfi logo.svg" alt="" width="70" height="40" class="d-inline-block align-text-top">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="ordering.php">ORDERING</a>
        </li>
        <li class="nav-item ">
          <a class="nav-link" href="invoices.php">INVOICING</a>
        </li>
        <li class="nav-item ">
          <a class="nav-link" id="logout" href="Logout.php">Log Out </a>
        </li>
        </ul>
        
      <form  class="d-flex input-group w-auto" action = "" method= "GET">
        <input style="float:right" type="search"   name = "search" placeholder="PO Number" id = "search" aria-label="Search" autocomplete ="off" >
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>

    </div>
    <div class="col-md-5" style="position: relative;margin-top: -38px;margin-left: 215px;">
        <div class="list-group" id="show-list">
          <!-- Here autocomplete list will be display -->
        </div>
      </div>
  </div>
</nav>
<br>
<br>
