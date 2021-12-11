
<!DOCTYPE html>
<html>
<head>
 <title>Laravel 8 Send Email Example</title>
</head>
<body>
<style type="text/css">
/* .header{
    height:300px;
    background:url("img/brand/HEADER.png");
    background-size: cover;
} */
/* .footer{
    height:499px;
    background:url("img/brand/FOOTER.png");
    background-size: cover;
    background-repeat: no-repeat;
} */
table{
    border-collapse: collapse;
}
table th{
    border:1px solid #000;
    margin:0;
    padding:0;
}
.no-border-top-bottom{
    border:none !important;
    border-left:1px solid #000 !important;
    border-right:1px solid #000 !important;
}
.align-left{
    text-align:left;
}
.body-content{
    margin-top:50px;
    min-height:400px;
}
h6{
    font-size:20px;
    margin:10px;
}
</style>
 <div class="container">
    <div class="header">
        <img src="img/brand/HEADER.png" width="100%">
        <div style="margin-right:140px; margin-top:-55px;">
            <p style="text-align:right; margin:0;">(02) 796 5092 </p>
            <p style="text-align:right; margin:0;">rakkiiautoservices@yahoo.com </p>
            <p style="text-align:right; margin:0;">71 Santol St.Niño, Quezon City, 1113 </p>
        </div>
    </div>

    <div class="body-content">
    <table style="border:1px #000 solid;" width="100%">
        <tr>
            <th colspan="4" style="text-align:center;">
                <h6>REPAIR JOB ESTIMATE</h6>
            </th>
        </tr>
        <tr>
            <th width="15%" class="align-left">
                <h6>Customer:</h6>
            </th>
            <th width="55%" style="text-align:center;">
                <h6>{{$estimate->customer->company_name}}</h6>
            </th>
            <th width="10%" class="align-left" >
                <h6>Date:</h6>
            </th>
            <th width="10%" style=" text-align:center;">
                <h6>{{$estimate->date}}</h6>
            </th>
        </tr>
        <tr>
            <th width="15%" class="align-left">
                <h6>Address:</h6>
            </th>
            <th width="55%" style="text-align:center;">
                <h6>{{$estimate->customer->address}}</h6>
            </th>
            <th width="10%">
                
            </th>
            <th width="10%">
                
            </th>
        </tr>
        <tr>
            <th width="15%" class="align-left">
                <h6>Insurance:</h6>
            </th>
            <th width="55%" style="text-align:center;">
                <h6>{{$estimate->insurance->insurance_name}}</h6>
            </th>
            <th width="10%">
                
            </th>
            <th width="10%">
                
            </th>
        </tr>
        <tr>
            <th width="20%" class="align-left">
                <h6>Vehicle:</h6>
            </th>
            <th width="50%" style="text-align:center;">
                <h6>{{$estimate->property->vehicle->vehicle_name}}</h6>
            </th>
            <th width="10%" class="align-left" >
                <h6>PLATE NO.:</h6>
            </th>
            <th width="10%" style="text-align:center;">
                <h6>{{$estimate->property->plate_no}}</h6>
            </th>
        </tr>
        <tr>
            <th colspan="4">
                &nbsp;
            </th>
        </tr>
        <tr>
            <th colspan="2" style="text-align:center;">
                <h6>SCOPE OF WORK</h6>
            </th>
            <th style="text-align:center;">
                <h6>LABOR</h6>
            </th>
            <th style="text-align:center;">
                LABOR
            </th>
        </tr>
        @php
            $total = 0;
            $total_parts = 0;
        @endphp
        @foreach($estimate->scope as $est)
        <tr class="no-border-top-bottom">
            <th colspan="2" class="align-left">
                {{$est['services']['services_name']}}
                @php
                    $total += $est['labor_fee'];
                    $total_parts += $est['parts_fee'];
                @endphp
                <ul>
                    @foreach($est['sub_services'] as $sub)
                    <li>{{$sub['sub_services']['services_name']}}</li>
                        @php
                            $total += $sub['labor_fee'];
                        @endphp
                        @php
                            $total_parts += $sub['parts_fee'];
                        @endphp
                    @endforeach
                </ul>
            </th>
            <th style="border-left:1px #000 solid;" class="align-left">
                ₱{{$est['labor_fee']}}
                <ul>
                    @foreach($est['sub_services'] as $sub)
                    <li>₱{{$sub['labor_fee']}}</li>
                    @endforeach
                </ul>
            </th>
            <th style="border-left:1px #000 solid;" class="align-left">
                ₱{{$est['parts_fee']}}
                <ul>
                    @foreach($est['sub_services'] as $sub)
                    <li>₱{{$sub['parts_fee']}}</li>
                    @endforeach
                </ul>
            </th>
        </tr>
        @endforeach
        <tr>
            <th style="text-align:right;">
                &nbsp;
            </th>
            <th style="text-align:right;">
                <h6>SUB-TOTAL:</h6>
            </th>
            <th style="border-top:1px #000 solid; border-left:1px #000 solid;">
                <h6>₱{{$total}}
                </h6>
            </th>
            <th style="border-top:1px #000 solid; border-left:1px #000 solid;">
                <h6>₱{{$total_parts}}</h6>
            </th>
        </tr>
        <tr>
            <th style="text-align:right;" colspan="2">
                <h6>12% VAT:</h6>
            </th>
            @php
                $vat = $total * 0.12;
            @endphp
            <th style="border-top:1px #000 solid; border-left:1px #000 solid;">
                <h6>₱{{$vat}}</h6>
            </th>
            <th style="border-top:1px #000 solid; border-left:1px #000 solid;">
                
            </th>
        </tr>
        <tr>
            <th style="border-top:1px #000 solid; text-align:center;" colspan="2">
                <div style="display:flex; justify-content:space-between;">
                <h6 style="margin-left:160px;">TOTAL LABOR AND PARTS</h6>
                @php
                    $total_labor_parts = $total + $total_parts + $vat;
                @endphp
                <h6 style="margin-right:30px;">₱ {{$total_labor_parts}}</h6>
                </div>
            </th>
            <th colspan="2" style="border-top:1px #000 solid; border-left:1px #000 solid; text-align:center;">
                <div style="height:30px;"></div>
                <h6>FRED MORAN</h6>
            </th>
        </tr>
        <tr>
            <th rowspan="2" style="border-top:1px #000 solid;" colspan="2">
                <h6>The above ESTIMATE is based on our inspection and does not cover/include broken or additional labor which are not evident on the first inspection. Qoutation and parts/labor are current and subject to change notice. This estimate is only good for fifteen days, and is NOT VALID FOR COURT PURPOSES. RAKKII AUTO SERVICE shall not be liable for loss/es or any damage/s to vehicle or any</h6>
            </th>
            <th colspan="2" style="border-top:1px #000 solid; border-left:1px #000 solid; text-align:center;">
                <div style="height:30px;"></div>
                <h6>APPROVED BY</h6>
                <h6>REZZA LYNN BITENG</h6>
            </th>
        </tr>
        <tr>
            <th colspan="2" style="border-top:1px #000 solid; border-left:1px #000 solid; text-align:center;">
                <h6>GENERAL MANAGER</h6>
            </th>
        </tr>
    </table>
    </div>
    <!-- <div class="footer">
        <img src="img/brand/FOOTER.png" width="100%">
    </div> -->
 </div>
</body>
</html>