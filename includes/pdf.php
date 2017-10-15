<?php

require_once(__DIR__.'/../vendor/autoload.php');
require_once('airports.php');
use NumberToWords\NumberToWords;

function validateForm(array $formData) : bool {
    return $formData['departure'] !== $formData['arrival']
            && $formData['timeOfStart']
            && $formData['timeOfFlight']
            && $formData['price'] > 0;
}

function findTimezoneByAirportCode(string $code) : DateTimeZone {
    global $airports;
    foreach($airports as $airport) {
        if($airport['code'] === $code) {
            return new DateTimeZone($airport['timezone']);
        }
    }
}

function findNameByAirportCode(string $code) : string {
    global $airports;
    foreach($airports as $airport) {
        if($airport['code'] === $code) {
            return $airport['name'];
        }
    }
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && validateForm($_POST)){

    $departureAirpotCode = $_POST['departure'];
    $arrivalAirportCode = $_POST['arrival'];
    $timeOfStart = $_POST['timeOfStart'];
    $timeOfFlight = $_POST['timeOfFlight'];
    $price = $_POST['price'];
    
    $departureAirportName = findNameByAirportCode($departureAirpotCode);
    $arrivalAirportName = findNameByAirportCode($arrivalAirportCode);
        
    $departureTimezone = findTimezoneByAirportCode($departureAirpotCode);
    $arrivalTimezone = findTimezoneByAirportCode($arrivalAirportCode);
    
    $departureDateTime = new DateTime($timeOfStart);
    
    $arrivalDateTime = new DateTime($timeOfStart);
    $arrivalDateTime->setTimezone($departureTimezone);
    $arrivalDateTime->modify("+ $timeOfFlight seconds");
    $arrivalDateTime->setTimezone($arrivalTimezone);
        
    $fakePerson = Faker\Factory::create();
    $fakeFirstName = $fakePerson-> firstName;
    $fakeLastName = $fakePerson-> lastName;

    $numberToWords = new NumberToWords();
    $currencyTransformer = $numberToWords->getCurrencyTransformer('en');
    $priceInWords = $currencyTransformer->toWords($price*100, 'USD');
    
    $pdf = "<table border = \"1\" style=\"width:400px\">
            <tr>
                <td colspan=\"2\" style=\"text-align:center; color:blue; font-size: 30px; font-weight:bold\">PDF AIRLINES TICKET</td> 
            </tr>
            <tr>
                <td style=\"font-size: 12px; font-weight:bold\">From</td>
                <td style=\"font-size: 12px; font-weight:bold\">To</td>
            </tr>
            <tr>
                <td style=\"font-size: 22px; font-weight:bold\">$departureAirportName</td>
                <td style=\"font-size: 22px; font-weight:bold\">$arrivalAirportName</td>
            </tr>
            <tr>
                <td>Departure (local time)</td>
                <td>Arrival (local time)</td>
            </tr>
            <tr>
                <td style=\"font-size: 12px\"><p>{$departureDateTime->format('Y-m-d H:i:s')}</p> {$departureTimezone->getName()}</td>
                <td style=\"font-size: 12px\"><p>{$arrivalDateTime->format('Y-m-d H:i:s')}</p> {$arrivalTimezone->getName()}</td>
            </tr>
            <tr>
                <td colspan=\"2\" style=\"font-size: 22px; font-weight:bold; height:50px\">Flight time</td>
            </tr>
            <tr>
                <td colspan=\"2\">$timeOfFlight seconds</td>
            </tr>
            <tr>
                <td colspan=\"2\" style=\"font-size: 22px; font-weight:bold; height:50px\">Passenger</td>
            </tr>
            <tr>
                <td colspan=\"2\" style=\"color:red; font-size: 26px; font-weight:bold\">$fakeFirstName $fakeLastName</td>
            </tr>
            <tr>
                <td colspan=\"2\" style=\"font-size: 22px; font-weight:bold; height:50px\">Price</td>
            </tr>
            <tr>
                <td colspan=\"2\"><p style=\"color:green; font-size: 26px\">$price USD</p>$priceInWords</td>
        </table>";

    $mpdf = new mPDF();
    $mpdf->WriteHTML($pdf);
    $mpdf->Output('FlyTicket.pdf','I');
       
}
else {
    die("Błędne dane");
}
?>