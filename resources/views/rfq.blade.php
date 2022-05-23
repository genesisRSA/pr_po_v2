<!DOCTYPE html>

<html lang='en'>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<head>

    <title>{{ $title }}</title>

        <style type="text/css">
        * {
            font-family: DejaVu Sans, sans-serif;
        }
        table{
            font-size: x-small;
        }
        tfoot tr td{
            font-weight: bold;
            font-size: x-small;
        }
        .gray {
            background-color: lightgray
        }
        .font{
          font-size: 15px;
        }
        .authority {
            /*text-align: center;*/
            position: relative;
            left: 518px;
            top: 50px;
        }
        .authority h5 {
            margin-top: -10px;
            color: black;
            /*text-align: center;*/
            margin-left: 35px;
        }
        .thanks p {
            color: green;;
            font-size: 16px;
            font-weight: normal;
            font-family: serif;
            margin-top: 20px;
        }
    </style>

</head>

<body>

    <table width="100%" style="background: #F7F7F7; padding:0 20px 0 20px;">
        <tr>
            <td valign="top">
              <img src="{{ public_path('images/rsa_logo.png') }}" alt="" width="250" style="position:relative; top:20px; right:80px;"/>
            </td>
            <td align="left">
                <pre class="font" >
                  RTI Systems Automation INC.
                  Email: it.support@rsa.com.ph <br>
                  Mob: +6399000000 <br>
                  Blk 4 Lot 6 Onda Compound, People's
                  Technology Complex, Special Economic
                  Zone Brgy, Maduya, Carmona, 4116 Cavite
                </pre>
            </td>
        </tr>

      </table>

      <table width="100%" style="background:white; padding:2px;""></table>
      <table width="100%" style="background: #F7F7F7; padding:0 5 0 5px;" class="font">
        <tr>
            <td>
              <p class="font" style="margin-left: 20px;">
              <strong>Name:</strong>  <br>
              <strong>Email:</strong> <br>
              <strong>Phone:</strong>  <br>
              <strong>Address:</strong> <br>
              <strong>Post Code:</strong>
            </p>
            </td>
            <td>
              <p class="font">
                Order Date:  <br>
                Delivery Date:  <br>
                Payment Type :  </span>
            </p>
            </td>
        </tr>
      </table>
      <br/>
      <h3>Request for Quotation</h3>
    <table width="100%">
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
      @foreach($rfqs as $item)
      <tr class="font">
          <td align="center">
          {{ $item->pr_code }}
          </td>
          <td align="center"> {{ $item->item_code }}</td>
          <td align="center">{{ $item->description }}</td>
          <td align="center">₱{{ $item->quantity }}</td>
          <td align="center">{{ $item->unit_of_measure }}</td>
          <td align="center">{{ $item->delivery_date }}</td>
        </tr>
      @endforeach
    </tbody>
    <!-- <div class="thanks mt-3">
      <p>Thanks For Buying Our Products..!!</p>
    </div> -->
    <div class="authority mt-5">
        <p>------------------------------------------</p>
        <h5>Authority Signature:</h5>
      </div>
</body>

</html>
