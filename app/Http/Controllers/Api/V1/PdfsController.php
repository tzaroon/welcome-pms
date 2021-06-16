<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PdfsController extends Controller
{
    public function showVoucher(Request $request , Booking $booking){            
       
        if(!$booking){
            return response()->json(['message' => 'Booking not found']);  
        }
        
        $yIncremenent = 6;
        $fontSize = 8;

        $rooms = $booking->rooms;
       
        $pdf = app('Fpdf');
        $pdf->SetDrawColor(220,220,220);
        $pdf->SetTextColor(105,105,105);
        $pdf->SetFont('Arial','',10);
        $pdf->AddPage();
       
        $x = 95;
        $y = 20;
        $pdf->SetXY($x, $y); 
        $pdf->Cell(20,10,'CHIC');
        $y += $yIncremenent;
        $pdf->SetXY($x, $y);
        $pdf->Cell(20,10,'STAYS');

       // $x =  $x - 60;
        $x = 30;
        $y += $yIncremenent;

        $fontSize = 8;
        $pdf->SetFont('Arial','',8);

        $pdf->SetXY($x, $y+2); 
        $pdf->Cell(20, $fontSize,'Casa Boutique Barcelona - Tel. +34.615.966.839 - Carrer Pau Claris , 145, 08009 Barcelona, Barcelona');
       // $x =  $x - 5;
        $y += $yIncremenent;
        $pdf->SetXY($x, $y+2);
        $pdf->SetFont('Arial','B',8); 
        $pdf->Cell(20, $fontSize, 'Arrival');
        $x =  $x + 60;
        $pdf->SetXY($x, $y+2);
        $pdf->Cell(20, $fontSize, 'Departure');
        $x =  $x + 65;
        $pdf->SetXY($x, $y+2);
        $pdf->Cell(20, $fontSize, 'Nights');

        $pdf->SetFont('Arial','',8);

        $y += $yIncremenent;
        $x =  $x ;
        $pdf->SetXY($x-125, $y);
        $pdf->Cell(20, $fontSize, $booking->reservation_from);

         $x =  $x - 65;        
         $pdf->SetXY($x, $y);
         $pdf->Cell(20, $fontSize, $booking->reservation_to);

         $startDate = Carbon::parse($booking->reservation_from);
         $endDate = Carbon::parse($booking->reservation_to);       
        
        $days = $endDate->diffInDays($startDate);

         $x =  $x + 68; 
         $pdf->SetXY($x, $y);
         $pdf->Cell(20, $fontSize, $days);

         //$x = $x-125;
         $x = 30;
         $y += $yIncremenent;
         $yIncremenent =8;         

         $pdf->SetXY($x, $y);
         $pdf->SetFont('Arial','B',8);
         $pdf->Cell(20, $fontSize, 'ITEM');

         $x = $x + 126;         
         $pdf->SetXY($x, $y);
         $pdf->SetFont('Arial','B',8);
         $pdf->Cell(20, $fontSize, 'TOTAL');

        // $x = $x - 130; 

         $x = 30;


         $pdf->SetFont('Arial','',8);

         $y += $yIncremenent;

         foreach($rooms as $room)
            {            
                $pdf->SetXY($x, $y);  
                $pdf->Cell(20,10, $room->name . $room->roomType->roomTypeDetail->name);               
            }        

         $y += $yIncremenent;
         $pdf->SetXY($x, $y);        
         $pdf->Cell(20, $fontSize, 'Room night ' . $booking->reservation_from .' To ' . $booking->reservation_to); 

         $pdf->SetXY($x+128, $y);  

         $pdf->Cell(20, $fontSize, number_format($booking['price']['price']  + $booking['price']['vat'] , 2));

         $y += $yIncremenent;
         $pdf->SetXY($x, $y);
         $pdf->Cell(20, $fontSize, $booking->adult_count . '  Adults ' .'x Tourist tax');

         $pdf->SetXY($x+130, $y);        
         $pdf->Cell(20, $fontSize, number_format($booking['price']['tax'], 2));
         $y += $yIncremenent;

         $pdf->Rect($x, $y, 70, 15);

         $pdf->Rect($x + 71, $y, 70, 15);

         $y += 3;
         $pdf->SetXY($x+ 45, $y-3); 
         $pdf->SetFont('Arial','B',8);
         $pdf->Cell(20, $fontSize, number_format($booking['price']['price'] + $booking['price']['vat'] + $booking['price']['tax'] , 2));
         
         //$y += $yIncremenent;
         $pdf->SetXY($x+ 45, $y+1); 
         $pdf->SetFont('Arial','',8);
         $pdf->Cell(20, $fontSize, 'Taxes Inc.');

         $pdf->SetXY($x+ 75, $y-3); 
         $pdf->SetFont('Arial','B',8);
         $pdf->Cell(20, $fontSize, 'PAID.');

         $pdf->SetXY($x+ 75, $y+1); 
         $pdf->SetFont('Arial','', 8);
         $pdf->Cell(20, $fontSize, number_format($booking['price']['price'] + $booking['price']['vat'] + $booking['price']['tax'] - $booking['price']['total'] , 2));

         
         $y += $yIncremenent;
         $pdf->SetXY($x, $y+4); 
         $pdf->SetFont('Arial','B',8);
         $pdf->Cell(20, $fontSize, 'RESERVATION DETAILS');

         $y += $yIncremenent;
         $y += $yIncremenent;
        
         $pdf->Rect($x , $y-3, 141, 60);

         $pdf->SetXY($x+2, $y+1); 
         $pdf->SetFont('Arial','B', 8);
         $pdf->Cell(20, $fontSize, 'Name');

         $pdf->SetXY($x+70, $y+1); 
         $pdf->SetFont('Arial','B', 8);
         $pdf->Cell(20, $fontSize, 'Address');

         $y += $yIncremenent;
         $pdf->SetXY($x+2, $y); 
         $pdf->SetFont('Arial','', 8);
         $pdf->Cell(20, $fontSize,  $booking->booker->user->first_name . ' ' . $booking->booker->user->last_name );

         $y += $yIncremenent;        
        
         $pdf->Line($x, $y,170,$y);

         $y += 1;
         $pdf->SetXY($x+2, $y); 
         $pdf->SetFont('Arial','B', 8);
         $pdf->Cell(20, $fontSize, 'Phone');

        
         $pdf->SetXY($x+70, $y); 
         $pdf->SetFont('Arial','B', 8);
         $pdf->Cell(20, $fontSize, 'Country');

         $y += 5;
         $pdf->SetXY($x+2, $y); 
         $pdf->SetFont('Arial','', 8);
         $pdf->Cell(20, $fontSize, $booking->booker->user->phone_number);

         //$y += 5;
         $pdf->SetXY($x+70, $y); 
         $pdf->SetFont('Arial','', 8);         
         $pdf->Cell(20, $fontSize, $booking->booker->user->country->name);

         $y +=$yIncremenent;
         $pdf->Line($x, $y,170,$y);

         $y += 1;
         $pdf->SetXY($x+2, $y); 
         $pdf->SetFont('Arial','B', 8);
         $pdf->Cell(20, $fontSize, 'E-mail');

         $pdf->SetXY($x+70, $y); 
         $pdf->SetFont('Arial','B', 8);
         $pdf->Cell(20, $fontSize, 'Comments');

         $y += 5;
         $pdf->SetXY($x+2, $y); 
         $pdf->SetFont('Arial','', 8);
         
         $pdf->Cell(20, $fontSize, $booking->booker->user->email);
         
         $pdf->SetXY($x+70, $y); 
         $pdf->SetFont('Arial','', 8);
         $pdf->Cell(20, $fontSize, $booking->comment);

         $y +=$yIncremenent;
         $pdf->Line($x, $y,170,$y);

         $y += 1;
         $pdf->SetXY($x+2, $y); 
         $pdf->SetFont('Arial','B', 8);
         $pdf->Cell(20, $fontSize, 'Source');

         $y +=3;

         $pdf->SetXY($x+2, $y); 
         $pdf->SetFont('Arial','', 8);
         $pdf->Cell(20, $fontSize, $booking->source);

         $y +=$yIncremenent;
         $pdf->SetXY($x+2, $y+6); 
         $pdf->SetFont('Arial','B', 8);
         $pdf->Cell(20, $fontSize, 'ADDITIONAL INFORMATION');

         $y +=$yIncremenent;
         $y +=$yIncremenent;
         $pdf->Line($x+3, $y,170,$y);

         $y +=3;
         $pdf->SetXY($x+2, $y); 
         $pdf->SetFont('Arial','', 8);
         $pdf->Cell(20, $fontSize, 'Thank you for booking at Casa Boutique. My name is Eduardo, and I am the hotel manager');
         $y +=$yIncremenent;
         $pdf->SetXY($x+2, $y); 
         $pdf->SetFont('Arial','', 8);
         $pdf->Cell(20, $fontSize, 'Boutique House is located on Calle Pau Claris, 145, Barcelona, ​right on the corner with Calle Valencia.');
         $y +=$yIncremenent;
         $pdf->SetXY($x+2, $y); 
         $pdf->SetFont('Arial','', 8);
         $pdf->Cell(20, $fontSize, 'The day before check-in, once 100% of the reservation has been paid through the link that you will receive');
         $y +=$yIncremenent;
         $pdf->SetXY($x+2, $y); 
         $pdf->SetFont('Arial','', 8);
         $pdf->Cell(20, $fontSize, 'by email, you will receive a unique code, which  is used to access the hotel This code is used on the building');
         $y +=$yIncremenent;
         $pdf->SetXY($x+2, $y); 
         $pdf->SetFont('Arial','', 8);
         $pdf->Cell(20, $fontSize, 'portal. Once inside, you must go up some stairs to the first floor. At the hotel door, the SAME code is used.');
         $y +=$yIncremenent;
         $pdf->SetXY($x+2, $y); 
         $pdf->SetFont('Arial','', 8);
         $pdf->Cell(20, $fontSize, ',Once inside the hotel, the SAME code is used in the room');
         $y +=$yIncremenent;
         $pdf->SetXY($x+2, $y); 
         $pdf->SetFont('Arial','', 8);
         $pdf->Cell(20, $fontSize, 'If you have any questions, you can contact us on this phone (whatsapp available): +34.685.160.394');

         $y +=$yIncremenent;
         $pdf->SetXY($x+2, $y); 
         $pdf->SetFont('Arial','', 8);
         $pdf->Cell(20, $fontSize, 'If you have any questions, you can contact us on this phone (whatsapp available): +34.685.160.394');

         $y +=$yIncremenent;
         $y +=3;
         $pdf->Line($x+3, $y,170,$y);
         
         $y +=$yIncremenent;
         $pdf->SetXY($x+2, $y); 
         $pdf->SetFont('Arial','B', 8);
         $pdf->Cell(20, $fontSize, 'Date');

         $pdf->SetXY($x+70, $y); 
         $pdf->SetFont('Arial','B', 8);
         $pdf->Cell(20, $fontSize, 'Signature'); 
         
         $y +=$yIncremenent;
         $pdf->SetXY($x+2, $y); 
         $pdf->SetFont('Arial','', 8);
         $date = Carbon::now();
         $pdf->Cell(20, $fontSize, $date);
        
        $pdf->Output('I');
       exit;
    }

    public function showReceipt(Request $request , Payment $payment  , $detailed) {
      

        if(!$payment){
            return response()->json(['message' => 'Payment not found']);  
        }        
       
        $pdf = app('Fpdf');
        $pdf->SetDrawColor(208,208,208);
        $pdf->SetTextColor(105,105,105);
        $pdf->SetFont('Arial','',15);
        $pdf->AddPage();
       
        $x = 25;
        $y = 20;
        $pdf->SetXY($x, $y); 
        $pdf->Cell(20,10,'CHIC');        
        $y += 8;       
        $pdf->Line($x, $y,40,$y);
        $pdf->SetFont('Arial','',15);
        $pdf->SetXY($x, $y-2);
        $pdf->Cell(20,10,'STAYS');

       // $x =  $x - 20;
       $x =  15;
        $y += 20;
        $yIncremenent = 6;
        $fontSize = 8;
        $pdf->SetFont('Arial','',10);

        $pdf->SetXY($x, $y); 
        $pdf->Cell(20,$fontSize,'Chicstays S.L');

        $pdf->SetXY(150, $y);
        $pdf->Cell(20,10, $payment->booking->booker->user->first_name . ' ' . $payment->booking->booker->user->last_name );

        $y += $yIncremenent;
        $pdf->SetXY($x, $y);
        $pdf->Cell(20,$fontSize,'Ali Bei 15');
        $y += $yIncremenent ;
        $pdf->SetXY($x, $y);
        $pdf->Cell(20,10,'08010');
        $y += $yIncremenent ;
        $pdf->SetXY($x, $y);
        $pdf->Cell(20,$fontSize,'Barcelona (Espana)');
        $y += $yIncremenent ;
        $pdf->SetXY($x, $y);
        $pdf->Cell(20,$fontSize,'VAT : (B65121618)');
        $y += $yIncremenent ;

        $pdf->SetXY($x, $y+1);
        $pdf->SetFont('Arial','B', $fontSize+5); 
        
        $pdf->SetTextColor(150,150,150);
        $pdf->Cell(20,10,'Receipt ' . '#' . $payment->id);
        $y += $yIncremenent ;

        $pdf->SetFont('Arial','B', $fontSize); 
        
        $pdf->SetXY($x, $y);
        $pdf->Cell(20,10, date('d/m/ Y', strtotime($payment->payment_date)));

        $y += $yIncremenent ; 
        $y += $yIncremenent ; 
        $i = 15;
        $rooms = $payment->booking->rooms;
             
        if($detailed){
            $i = 0;
            $pdf->SetXY($x, $y); 
            $pdf->SetFont('Arial','', $fontSize); 
            $pdf->Cell(20,10,'ITEM');
            $pdf->SetXY(150, $y); 
            $pdf->Cell(20,10,'TOTAL');
            $y += $yIncremenent ;

            foreach($rooms as $room)
            {            
                $pdf->SetXY($x, $y);  
                $pdf->Cell(20,10, $room->name . $room->roomType->roomTypeDetail->name); 
                $y += $yIncremenent ;
            }       
            
            $pdf->SetFont('Arial','B', $fontSize);
            $pdf->SetXY($x, $y);
            $pdf->Cell(50,10, 'Room night ' . $payment->booking->reservation_from .' To ' . $payment->booking->reservation_to); 
            $pdf->SetFont('Arial','', $fontSize);  

            $pdf->SetXY(150, $y);   
        
            $pdf->Cell(20,10, number_format($payment->booking['price']['price'] + $payment->booking['price']['vat'] , 2)); 
            $y += $yIncremenent ;
            $pdf->SetXY($x, $y); 
            $pdf->Cell(20,10, $payment->booking->adult_count. ' Adults '. '* ' . 'Tourist Tax Adultos '); 
            $pdf->SetXY(150, $y);
            $pdf->Cell(20,10, number_format($payment->booking['price']['tax'] , 2));
        
        }
       
        $pdf->SetFont('Arial','', $fontSize);
        //$pdf->SetTextColor(255,255,255);
        $pdf->SetDrawColor(51,51,51);       
        $pdf->Rect(21, 125 - $i, 150, 30);
        $y += $yIncremenent ;
        $y += $yIncremenent ;
            
         
        //$pdf->SetTextColor(51,51,51); 

        $pdf->SetFont('Arial','B', $fontSize); 
        $pdf->SetXY(140, $y+4);
        $pdf->Cell(20,10,'PAID ON ACCOUNT'); 
        $y += $yIncremenent ;
        $pdf->SetXY(147, $y+4);
        $pdf->SetFont('Arial','B', $fontSize +3);  
       
        $pdf->Cell(20,10, number_format($payment->booking['price']['price'] + $payment->booking['price']['vat'] + $payment->booking['price']['tax'] - $payment->booking['price']['total'] , 2) ,0,0,'R');
       
        $y += $yIncremenent ;
        $pdf->SetXY(155, $y+4);
        $pdf->SetFont('Arial','B', $fontSize);  
        $pdf->Cell(20,10,'Taxes Inc'); 
        $y += $yIncremenent ;
        $pdf->SetXY(135, $y+4);
        $pdf->SetFont('Arial','', $fontSize);
        $pdf->Cell(20,10,'Payment Method : ' . $payment->payment_method);

        $y += $yIncremenent ;
        $y += $yIncremenent ;
        $pdf->SetXY(0, $y);        
        $pdf->Cell(208,10,'Casa  Boutique Barcelona | info@chicstays.com | +34615967283',0,0,'C'); 
        $y += $yIncremenent ;
        

        $pdf->SetXY(0, $y); 
        $pdf->Cell(208,10,'Nota a pie de factura que sa  pone on Ajustes abajo del todo',0,0,'C'); 

        
       $pdf->Output('I');
       exit;

        $fileName =  $payment->id . '-' . time() . '.pdf';
        //save file
        Storage::put('/public/' . $fileName, $pdf->Output('S'));
        //$pdf->Output($$fileName, 'D');

        return response()->json(['file' => 'storage/' . $fileName]);
    }

    
}
