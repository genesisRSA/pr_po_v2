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
<form>

<h3 style="position: relative; bottom:8px;">PURCHASE REQUISITION FORM</h3>
<br>
<div class="row" style="position: relative; bottom:8px;">
  <div class="column">
    <div class="row">
         <label for="fname" class='title' style="position:relative; left: 20px;">PR NO.</label>
         <input type="text" id="fname" name="fname" style="height: 25px; position:relative; left: 20px; bottom: 28px;" value="{{ $pr_no }}"><br><br>
    </div>
    <div class="row" style="position: relative; bottom:13px;">
         <label for="fname" class='title' style="position:relative; left: 20px;">DEPT.</label>
         <input type="text" id="fname" name="fname" style="height: 25px; position:relative; left: 27px; bottom: 28px;" value="{{ $department }}"><br><br>
    </div>
    <div class="row" style="position: relative; bottom:24px;">
         <label for="fname" class='title' style="position:relative; left: 20px;">DATE</label>
         <input type="text" id="fname" name="fname" style="height: 25px; position:relative; left: 30px; bottom: 28px;" value="{{ $created_at->toDateString() }}"><br><br>
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
            <label for="fname" class='title' style="position:relative; right: 31px;">REQUESTOR</label>
            <input type="text" id="fname" name="fname" style="height: 25px; position:relative; right: 31px; bottom: 28px;" value="{{ $requestor }}"><br><br>
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

<table width="100%" style="position:relative; bottom:50px;">
    <thead style="background-color: blue; color:#FFFFFF;">
      <tr class="font">
        <th>Quantity</th>
        <th>Description</th>
        <th>Unit Cost</th>
        <th>Amount</th>
      </tr>
    </thead>
    <tbody>

    @foreach($pr_items as $item)
      <tr class="font">
          <td align="center">{{ $item->quantity }}</td>
          <td align="center">{{ $item->part_name.' '.($item->material != '' ? '('.$item->material.')' : '').($item->dimension != '' ? '('.$item->dimension.')' : '') }}</td>
          <td align="center" style="font-family: DejaVu Sans, sans-serif !important;">{{ '₱'.number_format((json_decode(str_replace(['₱',','],'',$item->target_cost)) / $item->quantity),2,'.',',') }}</td>
          <td align="center" style="font-family: DejaVu Sans, sans-serif !important;">{{ $item->target_cost }}</td>
        </tr>
    @endforeach

    </tbody>
</table>

{{-- ///'₱'.number_format(array_sum($arr),2, '.', ',') --}}
    <div class="row">
        <label for="fname"><span>Please check if:</span><span style="position:relative; left:10px;">    LOCAL __________ FOREIGN __________</span></label>
    </div>

    <div class="box-panel">
        <div class="row">
                    <label for="fname">SUPPLIER NAME:___________________________________________________</label>
        </div>
        <span style="position:relative; bottom:20px; left:140px;">SAMPLE SUPPLIER</span>

                    <div class="row">
                        <label for="fname">ADDRESS:_________________________________________________________</label>
                    </div>

                        <span style="position:relative; bottom:20px; left:90px;">SAMPLE ADRESS</span>


        <div class="row">
                    <label for="fname">TERMS OF PAYMENTS:______________________________________________</label>
        </div>
                <span style="position:relative; bottom:20px; left:185px;">CASH</span>
    </div>

    <div class='row'>
        <label for="fname" style="position:relative; top:55px; margin-right: 10px; font-size: 75%">PURPOSE:_________________________</label>
        <label for="fname" style="position:relative; top:55px; left:43px; font-size: 75%">REQUESTING DEPT.:______________________</label>
    </div>
        <span style="position:relative; top:35px; left:80px; font-size: 75%">SAMPLE PURPOSE</span>
        <span style="position:relative; top:35px; left:300px; font-size: 75%">SAMPLE DEPARTMENT</span>
    <div class='row'>
        <label for="fname" style="position:relative; top:50px; margin-right: 53px; font-size: 75%">DATE NEEDED:_____________________</label>
        <label for="fname" style="position:relative; top:50px; font-size: 75%">DEPT MNGR.:_____________________________</label>
    </div>
        <span style="position:relative; top:30px; left:110px; font-size: 75%">SAMPLE PURPOSE</span>
        <span style="position:relative; top:30px; left:288px; font-size: 75%">SAMPLE MANAGER</span>
        <span style="position:relative; top:45px; left:175px; font-size: 50%">SIGNATURE OVER PRINTED</span>
    <div class='row'>
        <label for="fname" style="position:relative; top:55px; margin-right: 145px; font-size: 75%">AUTHORIZED BY:</label>
        <label for="fname" style="position:relative; top:55px; left:36px; font-size: 75%">APPROVED BY:</label>
    </div>
    <div class='row'>
        <label for="fname" style="position:relative; top:70px; margin-right: 143px; font-size: 75%">_____________________</label>
        <label for="fname" style="position:relative; top:70px; font-size: 75%">_____________________________</label>
    </div>
        <span style="position:relative; top:70px; left:18px; font-size: 75%">DEPT. MANAGER</span>
        <span style="position:relative; top:70px; left:240px; font-size: 75%">PRESIDENT</span>
        <span style="position:relative; top:50px; right:159px; font-size: 75%">DEPT. MANAGER</span>
        <span style="position:relative; top:50px; left:24px; font-size: 75%">SAMPLE PRESIDENT</span>
    <div class='row'>
        <label for="fname" style="position:relative; top:85px; margin-right: 35px; font-size: 75%">DATE:_________________________</label>
        <label for="fname" style="position:relative; top:85px; left:43px; font-size: 75%">DATE:______________________</label>
    </div>
        <span style="position:relative; top:65px; left:50px; font-size: 75%">SAMPLE DATE</span>
        <span style="position:relative; top:65px; left:245px; font-size: 75%">SAMPLE DATE</span>
</body>
</html>
