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
            <input type="text" class='title' style="position:relative; left: 61px; bottom: 58px; font-size: 75%" value="{{ isset($requestor) ? $requestor : 'a' }}"><br><br>
        </div>
        <div class="row" style="position: relative; bottom:262px; left:140px;">
            <label for="fname" class='title'>ITEM CATEGORY:</label>
        </div>
        <div class="row" style="position: relative; bottom:255px;">
            <label for="fname" class='title' style="position:relative; right: 32px;">CAPITAL</label>
            <input type="text" id="fname" name="fname" style="height: 25px; position:relative; left: 1px; bottom: 28px;"><br><br>
        </div>
        <div class="row" style="position: relative; bottom:268px;">
            <label for="fname" class='title' style="position:relative; right: 32px;">STOCKS/SPARE PARTS</label>
            <input type="text" id="fname" size="5"name="fname" style="height: 25px; position:relative; left: 5px; bottom: 28px;"><br><br>
        </div>
        <div class="row" style="position: relative; bottom:281px;">
            <label for="fname" class='title' style="position:relative; right: 35px;">CONSUMABLE</label>
            <input type="text" id="fname" name="fname" style="height: 25px; position:relative; left: -4px; bottom: 28px;" size="12"><br><br>
        </div>
        <div class="row" style="position: relative; bottom:294px;">
            <label for="fname" class='title' style="position:relative; right: 35px;">RAW MATL'S</label>
            <input type="text" id="fname" name="fname" style="height: 25px; position:relative; left: 8px; bottom: 28px;" size='12'><br><br>
        </div>
        <div class="row" style="position: relative; bottom:306px;">
            <label for="fname" class='title' style="position:relative; right: 35px;">SUBCON</label>
            <input type="text" id="fname" name="fname" style="height: 25px; position:relative; left: 35px; bottom: 28px;" size='12'><br><br>
        </div>
  </div>
</div>


</body>
</html>