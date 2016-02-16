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
    poster VARCHAR(150),
    synopsis VARCHAR(500),
    date_sortie DATE,
    duree INT,
    univers INT,
    realisateur INT,
    genre1 INT,
    genre2 INT,
    genre3 INT
);

ALTER TABLE FILMS ADD FOREIGN KEY (univers) REFERENCES UNIVERS (id);
ALTER TABLE FILMS ADD FOREIGN KEY (realisateur) REFERENCES REALISATEUR (id);
ALTER TABLE FILMS ADD FOREIGN KEY (genre1) REFERENCES GENRES (id);
ALTER TABLE FILMS ADD FOREIGN KEY (genre2) REFERENCES GENRES (id);
ALTER TABLE FILMS ADD FOREIGN KEY (genre3) REFERENCES GENRES (id);
ALTER TABLE CASTING ADD FOREIGN KEY (idFilm) REFERENCES FILMS (id);

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

INSERT INTO GENRES (id, genre) VALUES
 ( 1, 'Action'),
 ( 2, 'Adventure'),
 ( 3, 'Comedy'),
 ( 4, 'Sci-Fi'),
 ( 5, 'Fantasy');

INSERT INTO FILMS (id, nom, poster, synopsis, date_sortie, duree, univers, realisateur, genre1, genre2, genre3) VALUES
 ( 1, 'Deadpool', 'https://image.tmdb.org/t/p/original/eJyRzC5uFjQryu8Hm61yqtrzj4S.jpg', 'I want a poney !', '2016-02-12', 108, 2, 2, 1, 3 , 2),
 ( 2, 'Batman Begins', 'https://image.tmdb.org/t/p/original/zfVFOo2XCHbeA0mXbst42TAGhfC.jpg', 'BLABLABLA', '2005-06-15', 140, 1, 1, 1, 2, 5),
 ( 3, 'Ant-Man', 'https://image.tmdb.org/t/p/original/n2guSYqwSQfWJnh301xIfV8OjUm.jpg', 'BLABLA', '2015-07-17', 117, 2, 3, 1, 4, 3),
 ( 4, 'Iron Man', 'https://image.tmdb.org/t/p/original/mDTFL6zpd2y0UsqfEY4cG1NgBHI.jpg', 'TADA', '2008-05-02', 126, 2, 4, 1, 2, 5),
 ( 5, 'Guardians of the Galaxy', 'https://image.tmdb.org/t/p/original/9gm3lL8JMTTmc3W4BmNMCuRLdL8.jpg', 'I\'m Groot !', '2014-07-21', 121, 2, 5, 1, 3, 4),
 ( 6, 'Man of Steel', 'https://image.tmdb.org/t/p/original/4tS3qHfYYB6C9I831pYQyLQivp8.jpg', 'I believe I can fly !', '2013-06-14', 143, 1, 6, 1, 2, 5);

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