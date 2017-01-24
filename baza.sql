CREATE TABLE Produkt 
(
	NumerProduktu int NOT NULL UNIQUE,
	Marka varchar(255) NULL,
	Model varchar(255) NULL,
	Rodzaj varchar(255) NULL,
	DodatkoweInformacje varchar(1000) NULL,
	PRIMARY KEY (NumerProduktu)
);

CREATE TABLE Komentarz 
(
	NumerKomentarza int NOT NULL UNIQUE,
	NumerProduktu int NOT NULL,
	Autor varchar(255) NULL,
	Podsumowanie varchar(1000) NULL,
	LiczbaGwiazdek varchar(255) NULL,
	DataWystawieniaKomentarza varchar(255) NULL,
	CzyOsobaPoleca varchar(20) NULL,
	PrzydatnoscOpinii varchar(20) NULL,
	NiePrzydatnoscOpinii varchar(20) NULL,
	Zalety varchar(2000) NULL,
	Wady varchar(2000) NULL,
	PRIMARY KEY (NumerKomentarza),    
	CONSTRAINT FOREIGN KEY (NumerProduktu) REFERENCES Produkt(NumerProduktu)
);