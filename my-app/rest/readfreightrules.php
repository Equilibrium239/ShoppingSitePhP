<?php
//Läs filen frakt.csv och ta en regel i taget och synka mot databasen
//Stefan visar en rad i taget i html

// använd leauge/csv för att läsa csv filen

require_once(__DIR__.'/../../vendor/autoload.php');
require_once(__DIR__.'/../Models/Database.php');

$csv = League\Csv\Reader::createFromPath(__DIR__.'/frakt.csv', 'r');
$csv->setHeaderOffset(0); // Första raden är header
$records = $csv->getRecords();
$database = new Database();





echo "<h1>Fraktregler</h1>";
echo "<ul>";
foreach ($records as $record){
    $database->updateFreightRule(
        $record['zon_kod'],
        $record['zon_namn'],
        $record['basavgift_sek'],
        $record['vikt_multiplikator_sek_per_kg'],
        $record['fri_frakt_grans_sek']
    );
// Ni tar bort alla echo och ersätter med kod som synkar mot databasen istället
  // $database->updateFreightRule()
    echo "<li>Zonkod: ";
    echo  $record['zon_kod'] ;
    echo  " - Namn";
    echo  $record['zon_namn'] ;
     echo  " - basavgift";
    echo  $record['basavgift_sek'] ;
     echo  " - Kr per kg";
    echo  $record['vikt_multiplikator_sek_per_kg'] ;
     echo  " - Fri frakt över";
    echo  $record['fri_frakt_grans_sek'] ;
    
 
    echo "</li>";

}
echo "</ul>";






?>