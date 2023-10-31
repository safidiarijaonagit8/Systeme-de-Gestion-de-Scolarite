CREATE SCHEMA scolarite1;

CREATE TABLE scolarite1.admin ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	username             varchar(180)  NOT NULL    ,
	roles                longtext  NOT NULL    ,
	password             varchar(255)  NOT NULL    ,
	CONSTRAINT `UNIQ_880E0D76F85E0677` UNIQUE ( username ) 
 );

ALTER TABLE scolarite1.admin MODIFY roles longtext  NOT NULL   COMMENT '(DC2Type:json)';

CREATE TABLE scolarite1.candidats ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	nom                  varchar(255)  NOT NULL    ,
	prenom               varchar(255)  NOT NULL    ,
	datedenaissance      date  NOT NULL    ,
	moyennebacc          double  NOT NULL    ,
	estdejaadmis         varchar(50)      ,
	imagefichiername     varchar(255)  NOT NULL    
 );

CREATE TABLE scolarite1.doctrine_migration_versions ( 
	version              varchar(191)  NOT NULL    PRIMARY KEY,
	executed_at          datetime      ,
	execution_time       int      
 );

CREATE TABLE scolarite1.etudiants ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	idcandidat           int  NOT NULL    ,
	CONSTRAINT fk_etudiants_candidats FOREIGN KEY ( idcandidat ) REFERENCES scolarite1.candidats( id ) ON DELETE RESTRICT ON UPDATE RESTRICT
 );

CREATE INDEX fk_etudiants_candidats ON scolarite1.etudiants ( idcandidat );

CREATE TABLE scolarite1.moyenne ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	semestre             int  NOT NULL    ,
	idetudiant           int  NOT NULL    ,
	moyennegenerale      double  NOT NULL    ,
	CONSTRAINT fk_moyenne_etudiants FOREIGN KEY ( idetudiant ) REFERENCES scolarite1.etudiants( id ) ON DELETE RESTRICT ON UPDATE RESTRICT
 );

CREATE INDEX fk_moyenne_etudiants ON scolarite1.moyenne ( idetudiant );

CREATE TABLE scolarite1.paiement ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	semestre             int  NOT NULL    ,
	idetudiant           int  NOT NULL    ,
	montantpaye          int  NOT NULL    ,
	CONSTRAINT fk_paiement_etudiants FOREIGN KEY ( idetudiant ) REFERENCES scolarite1.etudiants( id ) ON DELETE RESTRICT ON UPDATE RESTRICT
 );

CREATE INDEX fk_paiement_etudiants ON scolarite1.paiement ( idetudiant );

CREATE TABLE scolarite1.parametre ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	semestre             int  NOT NULL    ,
	montantecolage       int  NOT NULL    ,
	nbrplacedispo        int      
 );

CREATE TABLE scolarite1.reste ( 
	id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	semestre             int  NOT NULL    ,
	idetudiant           int  NOT NULL    ,
	montant              int  NOT NULL    ,
	CONSTRAINT fk_reste_etudiants FOREIGN KEY ( idetudiant ) REFERENCES scolarite1.etudiants( id ) ON DELETE RESTRICT ON UPDATE RESTRICT
 );

CREATE INDEX fk_reste_etudiants ON scolarite1.reste ( idetudiant );

CREATE VIEW scolarite1.vfiche_etudiant AS select `e`.`id` AS `id`,`c`.`nom` AS `nom`,`c`.`prenom` AS `prenom`,`c`.`datedenaissance` AS `datedenaissance`,`c`.`moyennebacc` AS `moyennebacc`,`c`.`imagefichiername` AS `imagefichiername` from (`scolarite1`.`etudiants` `e` join `scolarite1`.`candidats` `c` on(`e`.`idcandidat` = `c`.`id`));

CREATE VIEW scolarite1.vueresultatparsemestre AS select `m`.`id` AS `id`,`m`.`semestre` AS `semestre`,`m`.`moyennegenerale` AS `moyennegenerale`,`e`.`id` AS `numetudiant`,`c`.`nom` AS `nom`,`c`.`prenom` AS `prenom` from ((`scolarite1`.`moyenne` `m` join `scolarite1`.`etudiants` `e` on(`m`.`idetudiant` = `e`.`id`)) join `scolarite1`.`candidats` `c` on(`e`.`idcandidat` = `c`.`id`));

