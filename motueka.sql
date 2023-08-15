CREATE DATABASE IF NOT EXISTS motueka;
USE motueka;

-- The rooms for the bed and breakfast
DROP TABLE IF EXISTS room;
CREATE TABLE IF NOT EXISTS room (
  roomID int unsigned NOT NULL auto_increment,
  roomname varchar(100) NOT NULL,
  description text default NULL,
  roomtype character default 'D',  
  beds int,
  PRIMARY KEY (roomID)
) AUTO_INCREMENT=1;
INSERT INTO `room` (`roomID`,`roomname`,`description`,`roomtype`,`beds`) VALUES (1,"Kellie","Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sed tortor. Integer aliquam adipiscing","S",5);
INSERT INTO `room` (`roomID`,`roomname`,`description`,`roomtype`,`beds`) VALUES (2,"Herman","Lorem ipsum dolor sit amet, consectetuer","D",5);
INSERT INTO `room` (`roomID`,`roomname`,`description`,`roomtype`,`beds`) VALUES (3,"Scarlett","Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur","D",2);
INSERT INTO `room` (`roomID`,`roomname`,`description`,`roomtype`,`beds`) VALUES (4,"Jelani","Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sed tortor. Integer aliquam","S",2);
INSERT INTO `room` (`roomID`,`roomname`,`description`,`roomtype`,`beds`) VALUES (5,"Sonya","Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sed tortor. Integer aliquam adipiscing lacus.","S",5);
INSERT INTO `room` (`roomID`,`roomname`,`description`,`roomtype`,`beds`) VALUES (6,"Miranda","Lorem ipsum dolor sit amet, consectetuer adipiscing","S",4);
INSERT INTO `room` (`roomID`,`roomname`,`description`,`roomtype`,`beds`) VALUES (7,"Helen","Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sed tortor. Integer aliquam adipiscing lacus.","S",2);
INSERT INTO `room` (`roomID`,`roomname`,`description`,`roomtype`,`beds`) VALUES (8,"Octavia","Lorem ipsum dolor sit amet,","D",3);
INSERT INTO `room` (`roomID`,`roomname`,`description`,`roomtype`,`beds`) VALUES (9,"Gretchen","Lorem ipsum dolor sit","D",3);
INSERT INTO `room` (`roomID`,`roomname`,`description`,`roomtype`,`beds`) VALUES (10,"Bernard","Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sed tortor. Integer","S",5);
INSERT INTO `room` (`roomID`,`roomname`,`description`,`roomtype`,`beds`) VALUES (11,"Dacey","Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur","D",2);
INSERT INTO `room` (`roomID`,`roomname`,`description`,`roomtype`,`beds`) VALUES (12,"Preston","Lorem","D",2);
INSERT INTO `room` (`roomID`,`roomname`,`description`,`roomtype`,`beds`) VALUES (13,"Dane","Lorem ipsum dolor","S",4);
INSERT INTO `room` (`roomID`,`roomname`,`description`,`roomtype`,`beds`) VALUES (14,"Cole","Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sed tortor. Integer aliquam","S",1);

-- Customers
DROP TABLE IF EXISTS customer;
CREATE TABLE IF NOT EXISTS customer (
  customerID int unsigned NOT NULL auto_increment,
  firstname varchar(50) NOT NULL,
  lastname varchar(50) NOT NULL,
  email varchar(100) NOT NULL,
  role tinyint(1) default '1', 
  password varchar(250) NOT NULL default 'temp1234',
  PRIMARY KEY (customerID)
) AUTO_INCREMENT=1;

INSERT INTO customer (customerID,firstname,lastname,email,role) VALUES 
(2,"Desiree","Collier","Maecenas@non.co.uk","1"),
(3,"Irene","Walker","id.erat.Etiam@id.org","1"),
(4,"Forrest","Baldwin","eget.nisi.dictum@a.com","1"),
(5,"Beverly","Sellers","ultricies.sem@pharetraQuisqueac.co.uk","1"),
(6,"Glenna","Kinney","dolor@orcilobortisaugue.org","1"),
(7,"Montana","Gallagher","sapien.cursus@ultriciesdignissimlacus.edu","1"),
(8,"Harlan","Lara","Duis@aliquetodioEtiam.edu","1"),
(9,"Benjamin","King","mollis@Nullainterdum.org","1"),
(10,"Rajah","Olsen","Vestibulum.ut.eros@nequevenenatislacus.ca","1"),
(11,"Castor","Kelly","Fusce.feugiat.Lorem@porta.co.uk","1"),
(12,"Omar","Oconnor","eu.turpis@auctorvelit.co.uk","1"),
(13,"Porter","Leonard","dui.Fusce@accumsanlaoreet.net","1"),
(14,"Buckminster","Gaines","convallis.convallis.dolor@ligula.co.uk","1"),
(15,"Hunter","Rodriquez","ridiculus.mus.Donec@est.co.uk","1"),
(16,"Zahir","Harper","vel@estNunc.com","1"),
(17,"Sopoline","Warner","vestibulum.nec.euismod@sitamet.co.uk","1"),
(18,"Burton","Parrish","consequat.nec.mollis@nequenonquam.org","1"),
(19,"Abbot","Rose","non@et.ca","1"),
(20,"Barry","Burks","risus@libero.net","1"),
(21,"admin","admin","adm@in.net","9");

-- Booking Database table
DROP TABLE IF EXISTS booking;
CREATE TABLE IF NOT EXISTS booking (
    bookingID int unsigned NOT NULL auto_increment,
    customerID int unsigned DEFAULT NULL,
    roomID int unsigned DEFAULT NULL,
    checkindate DATE NOT NULL,
    checkoutdate DATE NOT NULL,
    contactnumber varchar(20) NOT NULL,
    bookingextras text(1000) DEFAULT NULL,
    roomreview text(2000) DEFAULT NULL,
    PRIMARY KEY(bookingID),
    FOREIGN KEY(customerID) REFERENCES customer(customerID),
    FOREIGN KEY(roomID) REFERENCES room(roomID)
)AUTO_INCREMENT=1;

INSERT INTO booking (bookingID, customerID, roomID, checkindate, checkoutdate, contactnumber, bookingextras, roomreview) VALUES
(1, 4,3,'2023-04-13','2023-04-23','0212334567','No extras required','NA' ),
(2, (SELECT customerID FROM customer WHERE customerID='7'),(SELECT roomID FROM room where roomID='5'),'2023-02-11','2023-02-12','0212334567','Can we ask for one extra towel?','' );