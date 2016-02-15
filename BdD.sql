CREATE TABLE USERS
(
    id INT PRIMARY KEY NOT NULL,
    pseudo VARCHAR(100),
    email VARCHAR(255),
    password VARCHAR(255)
);

CREATE TABLE UNIVERS
(
    id INT PRIMARY KEY NOT NULL,
    univers VARCHAR(25)
);

CREATE TABLE REALISATEUR
(
    id INT PRIMARY KEY NOT NULL,
    direc VARCHAR(225)
);

CREATE TABLE CASTING
(
    idFilm INT,
    cast VARCHAR(250)
);

CREATE TABLE GENRES
(
    id INT PRIMARY KEY NOT NULL,
    genre VARCHAR(100)
);

CREATE TABLE FILMS
(
    id INT PRIMARY KEY NOT NULL,
    nom VARCHAR(150),
    date_sortie DATE,
    year INT,
    duree INT,
    univers INT,
    realisateur INT,
    genre INT
);

ALTER TABLE FILMS ADD FOREIGN KEY (UNIVERS_ID) REFERENCES UNIVERS (id);
ALTER TABLE FILMS ADD FOREIGN KEY (REALISATEUR_ID) REFERENCES REALISATEUR (id);
ALTER TABLE FILMS ADD FOREIGN KEY (GENRES_ID) REFERENCES GENRES (id);
ALTER TABLE CASTING ADD FOREIGN KEY (FILMS_ID) REFERENCES FILMS (id);

INSERT INTO UNIVERS (id, univers) VALUES
 ( 1, 'DC'),
 ( 2, 'MARVEL');

INSERT INTO REALISATEUR (id, direc) VALUES
 ( 1, 'Christopher Nolan'),
 ( 2, 'Tim Miller'),
 ( 3, 'Peyton Reed'),
 ( 4, 'Jon Favreau'),
 ( 5, 'James Gunn'),
 ( 6, 'Zack Snyder');

INSERT INTO CASTING (idFilm, cast) VALUES
 ( 1, 'Ryan Reynolds'),
 ( 1, 'Karan Soni'),
 ( 1, 'Ed Skrein'),
 ( 1, 'Morena Baccarin'),
 ( 1, 'Brianna Hildebrand'),
 ( 2, 'Christian Bale'),
 ( 2, 'Michael Caine'),
 ( 2, 'Liam Neeson'),
 ( 2, 'Katie Holmes'),
 ( 2, 'Gary Oldman'),
 ( 3, 'Paul Rudd'),
 ( 3, 'Michael Douglas'),
 ( 3, 'Evangeline Lilly'),
 ( 3, 'Corey Stoll'),
 ( 4, 'Robert Downey Jr.'),
 ( 4, 'Terrence Howard'),
 ( 4, 'Jeff Bridges'),
 ( 4, 'Gwyneth Paltrow'),
 ( 5, 'Chris Pratt'),
 ( 5, 'Zoe Saldana'),
 ( 5, 'Dave Bautista'),
 ( 5, 'Vin Diesel'),
 ( 5, 'Bradley Cooper'),
 ( 6, 'Henry Cavill'),
 ( 6, 'Amy Adams'),
 ( 6, 'Michael Shannon'),
 ( 6, 'Diane Lane'),
 ( 6, 'Russell Crowe'),
 ( 6, 'Laurence Fishburne'),
 ( 6, 'Kevin Costner');

INSERT INTO GENRES (id, genre) VALUES
 ( 1, 'Action'),
 ( 2, 'Adventure'),
 ( 3, 'Comedy'),
 ( 4, 'Sci-Fi'),
 ( 5, 'Fantasy');

INSERT INTO FILMS (id, nom, date_sortie, year, duree, univers, realisateur, genre) VALUES
 ( 1, 'Deadpool', '2016-02-12', 2016, 108, 2, 2, XXX)
 ( 2, 'Batman Begins', '2005-06-15', 2005, 140, 1, 1, XXX)
 ( 3, 'Ant-Man', '2015-07-17', 2015, 117, 2, 3, XXX)
 ( 4, 'Iron Man', '2008-05-02', 2008, 126, 2, 4, XXX)
 ( 5, 'Guardians of the Galaxy', '2014-07-21', 121, 2, 5, XXX)
 ( 6, 'Man of Steel', '2013-06-14', 2013, 1, 6, XXX)