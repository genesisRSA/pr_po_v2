<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style type="text/css">
        * {
            font-family: Arial, sans-serif;
        }
        .title{
            position: relative;
            right: 80px;
            bottom: 35px;
            font-size: 90%;
        }
        h3{
            text-align: center;
        }
        .column {
            float: left;
            width: 50%;
            padding: 10px;
            height: 300px; /* Should be removed. Only for demonstration */
            }

            /* Clear floats after the columns */
            .row:after {
            content: "";
            display: table;
            clear: both;
            }
    </style>
    </head>
<body>
    <div class="d-flex">
        <img src="{{ public_path('images/rsa_logo.png') }}" style="height: 80px; position:relative; right: 70px;">
        <label for="fname" style="font-weight: bolder; color: #1B1878;" class='title'>RTI System Automation, Inc.</label>
    </div>
<form>

<h3 style="position: relative; bottom:8px;">PURCHASE REQUISITION FORM</h3>
<br>
<div class="row" style="position: relative; bottom:8px;">
  <div class="column">
    <div class="row">
         <label for="fname" class='title' style="position:relative; left: 20px;">PR NO.</label>
         <input type="text" id="fname" name="fname" style="height: 25px; position:relative; left: 20px; bottom: 28px;"><br><br>
    </div>
    <div class="row" style="position: relative; bottom:13px;">
         <label for="fname" class='title' style="position:relative; left: 20px;">DEPT.</label>
         <input type="text" id="fname" name="fname" style="height: 25px; position:relative; left: 27px; bottom: 28px;"><br><br>
    </div>
    <div class="row" style="position: relative; bottom:24px;">
         <label for="fname" class='title' style="position:relative; left: 20px;">DATE.</label>
         <input type="text" id="fname" name="fname" style="height: 25px; position:relative; left: 30px; bottom: 28px;"><br><br>
    </div>
    <div class="row" style="position: relative; bottom:18px;">
         <label for="fname" class='title' style="position:relative; left: 20px;">STOCKS AVAILABILITY:</label><br>
         <label for="fname" class='title' style="position:relative; left: 20px; bottom:31px;">YES</label>
         <input type="text" id="fname" name="fname" style="height: 25px; position:relative; left: 30px; bottom: 28px;"><br><br>
         <label for="fname" class='title' style="position:relative; left: 24px; bottom:48px;">NO</label>
         <input type="text" id="fname" name="fname" style="height: 25px; position:relative; left: 38px; bottom: 43px;"><br><br>
    </div>
  </div>

  <div class="column">
        <div class="row">
            <label for="fname" class='title' style="position:relative; right: 37px;">REQUESTOR</label>
            <input type="text" id="fname" name="fname" style="height: 25px; position:relative; right: 37px; bottom: 28px;"><br><br>
        </div>
        <div class="row" style="position:relative; bottom:265px;">
            <label for="fname" class='title' style="position:relative; left: 42px;">ITEM CATEGORY:</label>
        </div>
        <div class="row" style="position: relative; bottom:255px;">
            <label for="fname" class='title' style="position:relative; right: 38px;">CAPITAL</label>
            <input type="text" id="fname" name="fname" style="height: 25px; position:relative; left: 3px; bottom: 28px;"><br><br>
        </div>
        <div class="row" style="position: relative; bottom:268px;">
            <label for="fname" class='title' style="position:relative; right: 38px;">STOCKS/SPARE PARTS</label>
            <input type="text" id="fname" size="5"name="fname" style="height: 25px; position:relative; left: -21px; bottom: 28px;"><br><br>
        </div>
        <div class="row" style="position: relative; bottom:281px;">
            <label for="fname" class='title' style="position:relative; right: 38px;">CONSUMABLE</label>
            <input type="text" id="fname" name="fname" style="height: 25px; position:relative; left: -13px; bottom: 28px;" size="12"><br><br>
        </div>
        <div class="row" style="position: relative; bottom:294px;">
            <label for="fname" class='title' style="position:relative; right: 38px;">RAW MATL'S</label>
            <input type="text" id="fname" name="fname" style="height: 25px; position:relative; left: 3px; bottom: 28px;" size='12'><br><br>
        </div>
        <div class="row" style="position: relative; bottom:306px;">
            <label for="fname" class='title' style="position:relative; right: 38px;">SUBCON</label>
            <input type="text" id="fname" name="fname" style="height: 25px; position:relative; left: 37px; bottom: 28px;" size='12'><br><br>
        </div>
  </div>
</div>

<table width="100%" style="position:relative; bottom:50px;">
    <thead style="background-color: blue; color:#FFFFFF;">
      <tr class="font">
        <th>PR Code</th>
        <th>Item Code</th>
        <th>Description</th>
        <th>Quantity</th>
        <th>Unit of Measure</th>
        <th>Delivery Date</th>
      </tr>
    </thead>
    <tbody>
      <tr class="font">
          <td align="center">
          asdasd
          </td>
          <td align="center"> asdasd</td>
          <td align="center">asdasd</td>
          <td align="center">asdasd</td>
          <td align="center">asdasd</td>
          <td align="center">asdasd</td>
        </tr>
    </tbody>
</table>

</body>
</html>
