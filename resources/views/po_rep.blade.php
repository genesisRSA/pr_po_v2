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
        .box-panel{
            width: 680px;
            border: 1px solid black;
            padding: 10px;
            position: relative;
            top: 30px;
            height: 100px;
        }
    </style>
    </head>
<body>
    <div class="d-flex">
        <img src="{{ public_path('images/rsa_logo.png') }}" style="height: 80px; position:relative; right: 70px;">
        <label for="fname" style="font-weight: bolder; color: #1B1878;" class='title'>RTI System Automation, Inc.</label>
    </div>
    <hr>
<form>

<h3 style="position: relative; bottom:8px;">PURCHASE ORDER</h3>
<br>
<div class="row" style="position: relative; bottom:8px;">
  <div class="column">
    <div class="row">
         <label for="fname" class='title' style="position:relative; left: 20px; font-size: 75%">TO: </label>
         <label for="fname" class='title' style="height: 25px; position:relative; left: 20px; bottom: 35px; font-size: 75%">{{ isset($pr_no) ? $pr_no : 'MISUMI SOUTH EAST ASIA PTE LTD' }}</label><br><br>
    </div>
    <div class="row" style="position: relative; bottom:13px;">
         <label for="fname" class='title' style="position:relative; left: 20px; font-size: 75%">Address: </label>
         <label for="fname" class='title' style="height: 25px; position:relative; left: 20px; bottom: 35px; font-size: 75%">{{ isset($department) ? $department : '331 NORTH BRIDGE ROAD #05-01 ODEON TOWERS SINGAPORE 188720' }}</label><br><br>
    </div>
    <div class="row" style="position: relative; bottom:24px;">
         <label for="fname" class='title' style="position:relative; left: 20px; font-size: 75%">Contact No.: </label>
         <label for="fname" class='title' style="height: 25px; position:relative; left: 20px; bottom: 35px; font-size: 75%">{{ isset($created_at) ? $created_at->toDateString() : '+65-6733 7211' }}</label><br><br>
    </div>
    <div class="row" style="position: relative; bottom:18px;">
         <label for="fname" class='title' style="position:relative; left: 20px; font-size: 75%">Attn: </label>
         <label for="fname" class='title' style="height: 25px; position:relative; left: 20px; bottom: 35px; font-size: 75%">{{ isset($created_at) ? $created_at->toDateString() : 'CS JOEY' }}</label><br><br>
    </div>
    <div class="row" style="position: relative; bottom:18px;">
         <label for="fname" class='title' style="position:relative; left: 20px; font-size: 75%">Please deliver the items to: </label>
         <textarea for="fname" class='title' style="height: 100px; width: 210px; position:relative; left: 60px; bottom: 35px; font-size: 75%; border:none; outline:none;">RTI SYSTEM AUTOMATION, INC. BLK 4 LOT 6 ONDA COMPOUND PTC COMPLEX SPECIAL ECONOMIC ZONE, BRGY. MADUYA CARMONA, CAVITE<br>TEL/FAX: 02-519-2243 / 02-519-2065<br>VAT REG. TIN: 007-307-077</textarea><br><br>
    </div>
  </div>

  <div class="column">
        <div class="row">
            <input type="text" class='title' style="position:relative; right: 11px; font-size: 75%" value='Date of Order'>
            <input type="text" class='title' style="position:relative; right: 20px; bottom: 35px; font-size: 75%" value="{{ isset($requestor) ? $requestor : '31-Jan-22' }}"><br><br>
        </div>
        <div class="row" style="position: relative; bottom:293px;">
            <input type="text" class='title' style="position:relative; right: 11px; font-size: 75%" value='Requisition No.'>
            <input type="text" class='title' style="position:relative; right: 20px; bottom: 35px; font-size: 75%" value="{{ isset($requestor) ? $requestor : 'LOG-0027' }}"><br><br>
        </div>
        <div class="row" style="position: relative; bottom:318px;">
            <input type="text" class='title' style="position:relative; right: 11px; font-size: 75%" value='Date Wanted'>
            <input type="text" class='title' style="position:relative; right: 20px; bottom: 35px; font-size: 75%" value="{{ isset($requestor) ? $requestor : '--/--' }}"><br><br>
        </div>
        <div class="row" style="position: relative; bottom:343px;">
            <input type="text" class='title' style="position:relative; right: 11px; font-size: 75%" value='Request by:'>
            <input type="text" class='title' style="position:relative; right: 20px; bottom: 35px; font-size: 75%" value="{{ isset($requestor) ? $requestor : 'LOGISTICS' }}"><br><br>
        </div>
        <div class="row" style="position: relative; bottom:368px;">
            <input type="text" class='title' style="position:relative; right: 11px; font-size: 75%" value='Ship Via'>
            <input type="text" class='title' style="position:relative; right: 20px; bottom: 35px; font-size: 75%" value="{{ isset($requestor) ? $requestor : 'AIR' }}"><br><br>
        </div>
        <div class="row" style="position: relative; bottom:393px;">
            <input type="text" class='title' style="position:relative; right: 11px; font-size: 75%" value='Payment Terms'>
            <input type="text" class='title' style="position:relative; right: 20px; bottom: 35px; font-size: 75%" value="{{ isset($requestor) ? $requestor : 'ADVANCE T/T' }}"><br><br>
        </div>
        <div class="row" style="position: relative; bottom:418px;">
            <input type="text" class='title' style="position:relative; right: 11px; font-size: 75%" value='Currency'>
            <input type="text" class='title' style="position:relative; right: 20px; bottom: 35px; font-size: 75%" value="{{ isset($requestor) ? $requestor : 'USD' }}"><br><br>
        </div>
        <div class="row" style="position: relative; bottom:443px;">
            <input type="text" class='title' style="position:relative; right: 11px; font-size: 75%" value='SO#'>
            <input type="text" class='title' style="position:relative; right: 20px; bottom: 35px; font-size: 75%" value="{{ isset($requestor) ? $requestor : 'STOCKABLE ITEMS' }}"><br><br>
        </div>
        <div class="row" style="position: relative; bottom:468px;">
            <input type="text" class='title' style="position:relative; right: 11px; font-size: 75%" value='PURCHASE ORDER NO.'>
            <input type="text" class='title' style="position:relative; right: 20px; bottom: 35px; font-size: 75%" value="{{ isset($requestor) ? $requestor : 'RSA01312022-008CJ' }}"><br><br>
        </div>
  </div>
</div>


</body>
</html>