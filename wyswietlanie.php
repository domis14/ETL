<html>
<body>
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
</head>


<a href="wybor.html">Powrót do menu.</a><br>

<?php
	include("simple_html_dom.php");

	
	$nrProduktu = $_POST['nrProduktu']; 
	echo "Numer produktu: $nrProduktu<br>";
	$html = file_get_html("http://www.ceneo.pl/$nrProduktu");

	$nazwaFirmy = ($html->find("li.attr-value a", 0));
	$nazwaFirmy = strip_tags($nazwaFirmy);
	$modelUrzadzenia = ($html->find(".js_searchInGoogleTooltip", 0));
	$modelUrzadzenia = strip_tags($modelUrzadzenia);
	$dodatkoweUwagi = ($html->find(".ProductSublineTags", 0));
	$dodatkoweUwagi = strip_tags($dodatkoweUwagi);
	$rodzajUrzadzenia = (string)($html->find(".breadcrumb", 3));
	$rodzajUrzadzenia = strip_tags($rodzajUrzadzenia);
	$liczbaOpinii =(string)($html->find(".js_reviews-link span", 1));
	$liczbaStronOpinii = (floor((int)preg_replace("/[^\d]+/","",$liczbaOpinii)/10))+1;
	

	echo "Liczba opinii: $liczbaOpinii<br>";
	echo "Liczba stron z opiniami: $liczbaStronOpinii	 <br>";
	echo "Urzadzenie to: $nazwaFirmy <br>";
	echo "Jest to produkt z działu: $rodzajUrzadzenia<br>";
	echo "jego model: $modelUrzadzenia <br>";
	echo "Dodatkowe uwagi: $dodatkoweUwagi<br><br> ";

	$plik = fopen('iiiii.txt','a');
	fwrite($plik, $nazwaFirmy);
	

for($s=0;$s<$liczbaStronOpinii;$s++)
{
	$pomocnicza=$s+1;
$html = file_get_html("http://www.ceneo.pl/$nrProduktu/opinie-$pomocnicza");


$rozmiarKomentarzy = $iloscKomentarzyNaStronie[] = count($html->find(".product-review"));
for($z=0; $z < $rozmiarKomentarzy; $z++)
{
	$zalety[$z] = ($html->find(".pros-cell", $z));
	$wady[$z] = ($html->find(".cons-cell", $z));
	$podsumowanie[$z] = ($html->find(".product-review-body", $z));
	$liczbaGwiazdek[$z] = ($html->find(".review-score-count", $z));
	$dataWystawieniaOpinii[$z] = $html->find("span[time datetime]", $z)->attr['datetime'];
	$autorOpinii[$z] = ($html->find(".product-reviewer", $z));
	$czyPoleca[$z] = ($html->find(".product-recommended", $z));
	$osobUznajacychOpinieZaPrzydatna[$z] = ($html->find(".js_vote-yes span", $z));
	$osobUznajacychOpinieZaNiePrzydatna[$z] = ($html->find(".js_vote-no span", $z));
	
	


	
	

	echo "Autor opinii to: $autorOpinii[$z]";
	echo "użytkownik: $czyPoleca[$z]<br>";
	echo "Tyle osób uznało opinię za przydatną: $osobUznajacychOpinieZaPrzydatna[$z] <br>";
	echo "Tyle osób uznało opinię za nie przydatną: $osobUznajacychOpinieZaNiePrzydatna[$z] <br>";
	echo "Opinie wystawiono: $dataWystawieniaOpinii[$z]<br>";
	echo "liczba gwiazdek to: $liczbaGwiazdek[$z]";
	//wyswietlenie trescu podsumowania
	echo "Podsumowanie opinii: $podsumowanie[$z]";
	//sprawdzenie czy produkt ma przypisane zalety
	if (strpos($zalety[$z], 'Zalety') == true)
    {
	$zalety[$z] = (preg_replace("/Zalety+/","",$zalety[$z]));
	echo "Zalety to: $zalety[$z]";
	}
	else 
	{ 
		echo "Użytkownik nie wymienił zalet.<br>"; 
	}
	
	//sprawdzenie czy produkt ma przypisane wady
	if (strpos($wady[$z], 'Wady') == true)
    {
	$wady[$z]= (preg_replace("/Wady+/","",$wady[$z]));
	echo "Wady to: $wady[$z]";
	
	//	$liczbaStronOpinii = (floor((int)preg_replace("/[^\d]+/","",$liczbaOpinii)/10))+1;
	//$wady[$z]= (preg_replace("/[\W]/","",$wady[$z]));
	//echo "$wady[$z]";
	}
	else 
	{ 
		echo "Użytkownik nie wymienił wad.<br>"; 
	}
	
}
}
 ?>
</html>
</body>		