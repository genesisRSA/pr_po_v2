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
        .page-break {
            page-break-after: always;
        }
    </style>
    </head>
@foreach($data as $datas)
<body>
    <div class="d-flex">
        <img src="{{ public_path('images/rsa_logo.png') }}" style="height: 80px; position:relative; right: 70px;">
        <label for="fname" style="font-weight: bolder; color: #1B1878;" class='title'>RTI System Automation, Inc.</label>
    </div>
    <hr>
<form>

    @foreach($datas as $d)
        @if($loop->first)
            @php
                $supp_name = $d['chosen_supplier'];
                $address = $d['company_address'];
                $contact = $d['contact'];
                $contact_person = $d['contact_person'];
                $date_of_order = $d['date_of_order'];
                $pr_no = $d['pr_no'];
                $user_requestor = $d['user_requestor'];
            @endphp
        @endif
    @endforeach

<h3 style="position: relative; bottom:8px;">PURCHASE ORDER</h3>
<br>
<div class="row" style="position: relative; bottom:8px;">
  <div class="column">
    <div class="row">
         <label for="fname" class='title' style="position:relative; left: 20px; font-size: 75%">TO: </label>
         <label for="fname" class='title' style="height: 25px; position:relative; left: 20px; bottom: 35px; font-size: 75%">{{ isset($supp_name) ? $supp_name : 'N/A' }}</label><br><br>
    </div>
    <div class="row" style="position: relative; bottom:13px;">
         <label for="fname" class='title' style="position:relative; left: 20px; font-size: 75%">Address: </label>
         <label for="fname" class='title' style="height: 25px; position:relative; left: 20px; bottom: 35px; font-size: 75%">{{ $address }}</label><br><br>
    </div>
    <div class="row" style="position: relative; bottom:24px;">
         <label for="fname" class='title' style="position:relative; left: 20px; font-size: 75%">Contact No.: </label>
         <label for="fname" class='title' style="height: 25px; position:relative; left: 20px; bottom: 35px; font-size: 75%">{{ $contact }}</label><br><br>
    </div>
    <div class="row" style="position: relative; bottom:18px;">
         <label for="fname" class='title' style="position:relative; left: 20px; font-size: 75%">Attn: </label>
         <label for="fname" class='title' style="height: 25px; position:relative; left: 20px; bottom: 35px; font-size: 75%">{{ $contact_person }}</label><br><br>
    </div>
    <div class="row" style="position: relative; bottom:18px;">
         <label for="fname" class='title' style="position:relative; left: 20px; font-size: 75%">Please deliver the items to: </label>
         <textarea for="fname" class='title' style="height: 100px; width: 210px; position:relative; left: 60px; bottom: 35px; font-size: 75%; border:none; outline:none;">RTI SYSTEM AUTOMATION, INC. BLK 4 LOT 6 ONDA COMPOUND PTC COMPLEX SPECIAL ECONOMIC ZONE, BRGY. MADUYA CARMONA, CAVITE<br>TEL/FAX: 02-519-2243 / 02-519-2065<br>VAT REG. TIN: 007-307-077</textarea><br><br>
    </div>
    <div class="row" style="position: relative; bottom:36px;">
         <label for="fname" class='title' style="position:relative; left: 20px; font-size: 75%">Bill to: </label>
         <textarea for="fname" class='title' style="height: 100px; width: 660px; position:relative; left: 16px; bottom: 5px; font-size: 75%; border:none; outline:none;">Subject to the terms indicated on this Order; All Document, Papers and Packages must show the Purchase Order Number<br>Payment will be made when due Official Receipt is required.</textarea><br><br>
    </div>
  </div>

  <div class="column">
        <div class="row">
            <input type="text" class='title' style="position:relative; right: 11px; font-size: 75%" value='Date of Order'>
            <input type="text" class='title' style="position:relative; right: 20px; bottom: 35px; font-size: 75%" value="{{ $date_of_order }}"><br><br>
        </div>
        <div class="row" style="position: relative; bottom:293px;">
            <input type="text" class='title' style="position:relative; right: 11px; font-size: 75%" value='Requisition No.'>
            <input type="text" class='title' style="position:relative; right: 20px; bottom: 35px; font-size: 75%" value="{{ $pr_no }}"><br><br>
        </div>
        <div class="row" style="position: relative; bottom:318px;">
            <input type="text" class='title' style="position:relative; right: 11px; font-size: 75%" value='Date Wanted'>
            <input type="text" class='title' style="position:relative; right: 20px; bottom: 35px; font-size: 75%" value="{{ isset($requestor) ? $requestor : '--/--' }}"><br><br>
        </div>
        <div class="row" style="position: relative; bottom:343px;">
            <input type="text" class='title' style="position:relative; right: 11px; font-size: 75%" value='Request by:'>
            <input type="text" class='title' style="position:relative; right: 20px; bottom: 35px; font-size: 75%" value="{{ $user_requestor }}"><br><br>
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
<div class="row" style="position: relative; top:100px;">
    <table width="100%" style="position:relative; bottom:50px;">
        <thead style="background-color: blue; color:#FFFFFF;">
        <tr class="font">
            <th>Item No.</th>
            <th>Qty</th>
            <th>UM</th>
            <th>Description</th>
            <th>Unit Price</th>
            <th>Total Amount</th>
        </tr>
        </thead>
        <tbody>

        @foreach($datas as $key => $d)
        <tr class="font">
            <td align="center">{{ $key + 1 }}</td>
            <td align="center">{{ $d['quantity'] }}</td>
            <td align="center"></td>
            <td align="center">{{ $d['part_name'] }}</td>
            <td align="center" style="font-family: DejaVu Sans, sans-serif !important;">{{ '₱'.number_format((json_decode(str_replace(['₱',','],'',$d['chosen_supp_cost'])) / $d['quantity']),2,'.',',') }}</td>
            <td align="center" style="font-family: DejaVu Sans, sans-serif !important;">{{ $d['chosen_supp_cost'] }}</td>
        </tr>
        @endforeach


        </tbody>
    </table>
</div>

<div class='row' style="position: relative; top:28px;">
        <label for="fname" style="position:relative; top:55px; margin-right: 10px; font-size: 75%">APPROVED BY:_________________________</label>
        <label for="fname" style="position:relative; top:55px; left:43px; font-size: 75%">APPROVED BY:______________________</label>
</div>
<div class='row' style="position: relative; top:28px;">
        <label for="fname" style="position:relative; top:55px; margin-right: 10px; font-size: 75%; left:93px;">PURCHASING SUPERVISOR</label>
        <label for="fname" style="position:relative; top:55px; left:43px; font-size: 75%; left:238px;">GENERAL MANAGER</label>
</div>

<div class='row' style="position: relative; top:55px;">
        <label for="fname" style="position:relative; top:55px; margin-right: 10px; font-size: 75%">APPROVED BY:_________________________</label>
        <label for="fname" style="position:relative; top:55px; left:43px; font-size: 75%">APPROVED BY:______________________</label>
</div>
        <span style="position:relative; top:88px; left:120px; font-size: 75%">Ms. Mercy Bacanto</span>
        <span style="position:relative; top:88px; left:316px; font-size: 75%">Mr. Glorito Marasigan</span>
<div class='row' style="position: relative; top:28px;">
        <label for="fname" style="position:relative; top:60px; margin-right: 10px; font-size: 75%; left:112px;">ADMIN SUPERVISOR</label>
        <label for="fname" style="position:relative; top:60px; font-size: 75%; left:310px;">PRESIDENT</label>
</div>
</body>
@endforeach
</html>