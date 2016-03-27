
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
    background VARCHAR(150),
    date_creation VARCHAR(150),
    date_last_modif VARCHAR(150),
    genre1 INT,
    genre2 INT,
    genre3 INT,
    idUser INT
);

ALTER TABLE FILMS ADD FOREIGN KEY (univers) REFERENCES UNIVERS (id);
ALTER TABLE FILMS ADD FOREIGN KEY (realisateur) REFERENCES REALISATEUR (id);
ALTER TABLE FILMS ADD FOREIGN KEY (genre1) REFERENCES GENRES (id);
ALTER TABLE FILMS ADD FOREIGN KEY (genre2) REFERENCES GENRES (id);
ALTER TABLE FILMS ADD FOREIGN KEY (genre3) REFERENCES GENRES (id);
ALTER TABLE FILMS ADD FOREIGN KEY (idUser) REFERENCES USERS (id);
ALTER TABLE CASTING ADD FOREIGN KEY (idFilm) REFERENCES FILMS (id);



INSERT INTO USERS (id, pseudo, email, password) VALUES
 (0, "AstIirDarkham", "thomas.picard666@gmail.com", "$2y$10$akErPOiRzBWTM7VFfUppzOUQ.PCXyom58nIrDwHlboxsUUxaNxcs2"),
 (1, "Senayan", "seb.gamblin@hotmail.fr", "$2y$10$5tur0JlgRJsU1DfUUSAMOublTIl8/8Vfzt9905dkbTg4cvekSHwLC");

INSERT INTO UNIVERS (id, univers) VALUES
 ( 1, 'DC'),
 ( 2, 'MARVEL');

INSERT INTO REALISATEUR (id, direc) VALUES
 ( 0, 'Zack Snyder'),
 ( 1, 'Tim Miller'),
 ( 2, 'Christopher Nolan'),
 ( 3, 'Peyton Reed'),
 ( 4, 'Jon Favreau'),
 ( 5, 'James Gunn');


INSERT INTO GENRES (id, genre) VALUES
 ( 1, 'Action'),
 ( 2, 'Adventure'),
 ( 3, 'Comedy'),
 ( 4, 'Sci-Fi'),
 ( 5, 'Fantasy');

INSERT INTO FILMS (id, nom, poster, synopsis, date_sortie, duree, univers, realisateur, background, date_creation, date_last_modif, genre1, genre2, genre3, idUser) VALUES
 ( 0, 'Man of Steel', 'https://image.tmdb.org/t/p/original/4tS3qHfYYB6C9I831pYQyLQivp8.jpg', 'I believe I can fly !', '2013-06-14', 143, 1, 0, "https://image.tmdb.org/t/p/w780/aJRmXwMP8YtYzkZzHH5YL8Nrdc7.jpg", '1970-01-01', '1970-01-01', 1, 2, 5, 1),
 ( 1, 'Deadpool', 'https://image.tmdb.org/t/p/original/eJyRzC5uFjQryu8Hm61yqtrzj4S.jpg', 'I want a poney !', '2016-02-12', 108, 2, 2, "https://image.tmdb.org/t/p/w780/n1y094tVDFATSzkTnFxoGZ1qNsG.jpg", '1970-01-01', '1970-01-01', 1, 3 , 2, 0),
 ( 2, 'Batman Begins', 'https://image.tmdb.org/t/p/original/zfVFOo2XCHbeA0mXbst42TAGhfC.jpg', 'BLABLABLA', '2005-06-15', 140, 1, 1, "https://image.tmdb.org/t/p/original/9myrRcegWGGp24mpVfkD4zhUfhi.jpg", '1970-01-01', '1970-01-01', 1, 2, 5, 1),
 ( 3, 'Ant-Man', 'https://image.tmdb.org/t/p/original/n2guSYqwSQfWJnh301xIfV8OjUm.jpg', 'BLABLA', '2015-07-17', 117, 2, 3, "https://image.tmdb.org/t/p/w780/cB5O1uoUpJdAcGgAN1lJcP3tfuA.jpg", '1970-01-01', '1970-01-01', 1, 4, 3, 0),
 ( 4, 'Iron Man', 'https://image.tmdb.org/t/p/original/mDTFL6zpd2y0UsqfEY4cG1NgBHI.jpg', 'TADA', '2008-05-02', 126, 2, 4, "https://image.tmdb.org/t/p/w780/ZQixhAZx6fH1VNafFXsqa1B8QI.jpg", '1970-01-01', '1970-01-01', 1, 2, 5, 0),
 ( 5, 'Guardians of the Galaxy', 'https://image.tmdb.org/t/p/original/9gm3lL8JMTTmc3W4BmNMCuRLdL8.jpg', 'I\'m Groot !', '2014-07-21', 121, 2, 5, "https://image.tmdb.org/t/p/w780/bHarw8xrmQeqf3t8HpuMY7zoK4x.jpg", '1970-01-01', '1970-01-01', 1, 3, 4, 0);

 INSERT INTO CASTING (idFilm, cast) VALUES
 ( 0, 'Henry Cavill'),
 ( 0, 'Amy Adams'),
 ( 0, 'Michael Shannon'),
 ( 0, 'Diane Lane'),
 ( 0, 'Russell Crowe'),
 ( 0, 'Laurence Fishburne'),
 ( 0, 'Kevin Costner'),
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
 ( 5, 'Bradley Cooper');

