CREATE TABLE AdminTable(
UserID INT(10) PRIMARY KEY NOT NULL AUTO_INCREMENT,
Email VARCHAR(100) NOT NULL UNIQUE,
Phone VARCHAR(20) NOT NULL,
PassHash VARCHAR(255) NOT NULL
);

CREATE TABLE UserTable(
UserID INT(10) PRIMARY KEY NOT NULL AUTO_INCREMENT,
Retailer ENUM('yes','no') NOT NULL,
Email VARCHAR(100) NOT NULL UNIQUE,
Phone VARCHAR(20) NOT NULL,
PassHash VARCHAR(255) NOT NULL
);

CREATE TABLE MusicTable (
ProductID INT(13) PRIMARY KEY NOT NULL AUTO_INCREMENT,
SellerID INT(10) NOT NULL,
ImagePath VARCHAR(255) NOT NULL UNIQUE,
Title VARCHAR(100) NOT NULL,
Price DECIMAL(6,2) NOT NULL,
Artist VARCHAR(100) NOT NULL,
Publisher VARCHAR(100) NOT NULL,
ReleaseDate DATE NOT NULL,
RunningTime INT(3) NOT NULL, 
Description VARCHAR(255) NOT NULL, 
FOREIGN KEY (SellerID) REFERENCES UserTable(UserID));

INSERT INTO AdminTable(Email, Phone, PassHash) /*password123, 12345678910*/
VALUES ("adminguy@gmail.com", "+447420248275", "$2y$10$W.LWs4LOEfIy8lO/T16pJOmlejUGx0D/QQKyGIzn1NRUp/NViKaHq"), ("theboss@gmail.com", "+447420248276", "$2y$10$66qXglZUiVsYamaIvbgaie7dopfAlv.67IVcRc7yQITO5fEVKbMSO");

INSERT INTO UserTable(Retailer, Email, Phone, PassHash) /*musicOnlinePassword, 675tu5r435e4W!r, qwerty*/
VALUES ("no", "dovakin@gmail.com", "+447420248277", "$2y$10$TLZyAtch75wjkbT9I0P3WO51Tiz1nFFP70P1HnNX0xFYbHtpvPNxm"), 
("yes", "tardigrade@hotmail.com", "+447420248278", "$2y$10$BkdYIIkyY1YBWZv2Zj/V2ubIh4Ed89GQM90LbS0MtrvUD4H2DwJoK"),
("yes", "shadydude@rambler.ru", "+447420248279", "$2y$10$qneMmnhYNbEVfK8B7ZzAYuk2lUd9YHcR5xn9P3wZ78UgMYP3VnhQW");    

INSERT INTO MusicTable(SellerID, ImagePath, Title, Price, Artist, Publisher, ReleaseDate, RunningTime, Description)
VALUES ("0000000001", "images/disk1.jpg", "Hollow Knight", "19.99", "Christopher Larkin", "Ghost Ramp", "2019-11-11", "120", "This is an amazing soundtrack and you should buy it!"),
("0000000001", "images/disk2.jpg", "Rumors", "17.99", "Fleetwood Mac", "Random House", "2019-11-10", "99", "Available now!"),
("0000000001", "images/disk3.jpg", "What's the Story Morning Glory", "9.99", "Oasis", "Vynil Productions", "2019-11-09", "112", "Buy, buy, buy!"),  
("0000000001", "images/disk4.jpg", "Back to Black", "22.99", "Amy Winehouse", "Sleeping Dragon", "2019-11-08", "108", "'I haven't listened to it yet but a friend told me it's awesome. Five stars!'"),
("0000000001", "images/disk5.jpg", "Bleach", "21.99", "Nirvana", "Publisher Supreme", "1980-11-07", "119", "Soundtrack of the year 1980!"),
    
("0000000002", "images/disk6.jpg", "The White Stripes Greatest Hits", "10.99", "The White Stripes", "The Cash Cow", "2019-11-06", "45", "Nam quis nulla. Integer malesuada. In in enim a arcu imperdiet malesuada. Sed vel lectus. Donec odio urna, tempus molestie, porttitor ut, iaculis quis, sem. Phasellus rhoncus. Aenean id metus id velit ullamcorper pulvinar. Vestibulum fermentum tortor id m"),
("0000000002", "images/disk7.jpg", "Fine Line", "19.99", "Harry Styles", "Wooly Mammoth", "2019-11-05", "93", "'So good I use it as my alarm clock!'"),
("0000000002", "images/disk8.jpg", "Disco", "19.99", "Kylie Minogue", "Audiophyle", "2019-11-04", "96", "Worth every penny."),
("0000000002", "images/disk9.jpg", "Greatest Hits", "19.99", "Queen", "White Noise", "2019-11-03", "116", "Guaranteed to drive you insane!"),
("0000000002", "images/disk10.jpg", "Power Up", "19.99", "AC/DC", "VPH", "2019-11-02", "120", "This music gives you superpowers."),
("0000000002", "images/disk11.jpg", "Live at the Royal Albert Hall", "7.99", "Arctic Monkeys", "Vynil Productions", "2019-11-01", "94", "A must have."),
("0000000002", "images/disk12.jpg", "Ultra Mono", "5.99", "Idles", "Random House", "2019-10-25", "60", "If you are reading this, send help."),
("0000000002", "images/disk13.jpg", "MTV Unplugged", "6.99", "Liam Gallagher", "Sleeping Dragon", "2019-10-24", "71", "Good music, please buy etc."),
("0000000002", "images/disk14.jpg", "Legend", "19.99", "Bob Marley & the Wailers", "White Noise", "2019-10-23", "155", "Description goes here."),
("0000000002", "images/disk15.jpg", "Live at Reading", "19.99", "Nirvana", "Freedom Group", "2019-10-22", "90", "Does anyone even read this?");