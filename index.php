<html>
<body>

<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
</head>

<a href="wybor.html">Powrót do menu.</a><br>

<?php
	include("simple_html_dom.php");

	$nrProduktu = $_POST['nrProduktu']; 
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
	$liczbaDodanych = 0;
	
	
		
	$connection = @mysql_connect('sbazy.uek.krakow.pl', 's179647', 'U3FDptu9')
	// w przypadku niepowodznia wyświetlamy komunikat
	or die('Brak połączenia z serwerem MySQL.<br/>Błąd: '.mysql_error());
	mysql_set_charset('utf8',$connection); 
	mysql_query("SET CHARSET utf8");
	mysql_query("SET NAMES `utf8` COLLATE `utf8_polish_ci`"); 
	
for($s=0;$s<$liczbaStronOpinii;$s++)
{
	$pomocnicza=$s+1;
	$html = file_get_html("http://www.ceneo.pl/$nrProduktu/opinie-$pomocnicza");

	$rozmiarKomentarzy = $iloscKomentarzyNaStronie[] = count($html->find(".product-review"));
	for($z=0; $z < $rozmiarKomentarzy; $z++)
	{
		$pomocniczaDoNrKomentarza = $z*2;
		$numerKomentarza[$z] = $html->find("button[data-review-id]", $pomocniczaDoNrKomentarza)->attr['data-review-id'];
	
		$zalety[$z] = ($html->find(".pros-cell", $z));
		$zalety[$z] = (preg_replace("/<\/li>/",", ",$zalety[$z]));
		$zalety[$z] = (preg_replace("/<li>/","",$zalety[$z]));
		$zalety[$z] = (preg_replace("/[ ]{2,200}/"," ",$zalety[$z]));
		$zalety[$z] = strip_tags($zalety[$z]);
		$zalety[$z] = (preg_replace("/[ ]{2,200}/","",$zalety[$z]));
		$zaletyPomocniczo[$z] = (preg_replace("/Zalety/","",$zalety[$z]));
		
		$wady[$z] = ($html->find(".cons-cell", $z));
		$wady[$z] = (preg_replace("/<\/li>/",", ",$wady[$z]));
		$wady[$z] = (preg_replace("/<li>/","",$wady[$z]));
		$wady[$z] = (preg_replace("/[ ]{2,200}/"," ",$wady[$z]));
		$wady[$z] = strip_tags($wady[$z]);
		$wady[$z] = (preg_replace("/[ ]{2,200}/","",$wady[$z]));
		$wadyPomocniczo[$z] = (preg_replace("/Wady/","",$wady[$z]));
		
		$podsumowanie[$z] = ($html->find(".product-review-body", $z));
		$podsumowanie[$z] = strip_tags($podsumowanie[$z]);
		
		$liczbaGwiazdek[$z] = ($html->find(".review-score-count", $z));
		$liczbaGwiazdek[$z] = strip_tags($liczbaGwiazdek[$z]);
		
		
		$dataWystawieniaOpinii[$z] = $html->find("span[time datetime]", $z)->attr['datetime'];
		$dataWystawieniaOpinii[$z] = (preg_replace("/[ ]{2,200}/"," ",$dataWystawieniaOpinii[$z]));
		$dataWystawieniaOpinii[$z] = strip_tags($dataWystawieniaOpinii[$z]);
		$dataWystawieniaOpinii[$z] = (preg_replace("/[ ]{2,200}/","",$dataWystawieniaOpinii[$z]));
		
		$autorOpinii[$z] = ($html->find(".product-reviewer", $z));
		$autorOpinii[$z] = strip_tags($autorOpinii[$z]);
		 
		
		$czyPoleca[$z] = ($html->find(".product-recommended", $z));
		$czyPoleca[$z] = strip_tags($czyPoleca[$z]);
		 
		$osobUznajacychOpinieZaPrzydatna[$z] = ($html->find(".js_vote-yes span", $z));
		$osobUznajacychOpinieZaPrzydatna[$z] = strip_tags($osobUznajacychOpinieZaPrzydatna[$z]);
		
		$osobUznajacychOpinieZaNiePrzydatna[$z] = ($html->find(".js_vote-no span", $z));
		$osobUznajacychOpinieZaNiePrzydatna[$z] = strip_tags($osobUznajacychOpinieZaNiePrzydatna[$z]);
		
		//sprawdzenie czy produkt ma przypisane zalety
		if (strpos($zalety[$z], 'Zalety') == true)
		{
		$zalety[$z] = (preg_replace("/Zalety+/","",$zalety[$z]));
		}
		else 
		{ 
			$zalety[$z] = ""; 
		}
		
		//sprawdzenie czy produkt ma przypisane wady
		if (strpos($wady[$z], 'Wady') == true)
		{
		$wady[$z]= (preg_replace("/Wady+/","",$wady[$z]));
		}
		else 
		{ 
			$wady[$z] = ""; 
		}
		
	$db = @mysql_select_db('s179647', $connection)
    or die('Nie mogę połączyć się z bazą danych'); 
		
	$ins = @mysql_query("INSERT INTO Produkt SET NumerProduktu='$nrProduktu',
	Marka='$nazwaFirmy', Model='$modelUrzadzenia', Rodzaj='$rodzajUrzadzenia',
	DodatkoweInformacje='$dodatkoweUwagi'");
	
		
		
	$rd = @mysql_query("INSERT INTO Komentarz SET NumerKomentarza='$numerKomentarza[$z]', NumerProduktu='$nrProduktu', Autor='$autorOpinii[$z]',
	Podsumowanie='$podsumowanie[$z]', LiczbaGwiazdek='$liczbaGwiazdek[$z]', DataWystawieniaKomentarza='$dataWystawieniaOpinii[$z]',
	CzyOsobaPoleca='$czyPoleca[$z]', PrzydatnoscOpinii='$osobUznajacychOpinieZaPrzydatna[$z]',
	NiePrzydatnoscOpinii='$osobUznajacychOpinieZaNiePrzydatna[$z]', Zalety='$zalety[$z]', Wady='$wady[$z]'");
	

	if($rd)
	{	
		$liczbaDodanych++;
	}
	}
}
	if($liczbaDodanych == 0)
	{
		echo"Nie dodano komentarzy do bazy danych.";
	}
	if($liczbaDodanych == 1)
	{
		echo "Dodano $liczbaDodanych komentarz.";
	}
	if($liczbaDodanych > 1)
	{
		echo "Dodano $liczbaDodanych komentarzy.";
	}
	// w przypadku niepowodznia wyświetlamy komunikat
	
 ?>
 
</html>
</body>		