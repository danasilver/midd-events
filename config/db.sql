CREATE DATABASE EventsCalendar;

CREATE TABLE Users (
     username CHAR(20) NOT NULL,
     full_name CHAR(40) NOT NULL,
     is_admin BOOLEAN NOT NULL DEFAULT 0,
     joined DATETIME NOT NULL,
     PRIMARY KEY (username)
)

CREATE TABLE Categories (
     name CHAR(40),
     PRIMARY KEY (name)
)

CREATE TABLE follow_cat (
     cat CHAR(40),
     user CHAR(20),
     FOREIGN KEY (cat_name) REFERENCES Categories,
     FOREIGN KEY (username) REFERENCES Users,
     PRIMARY KEY (cat_name, username)
)

CREATE TABLE Events (
     id INT NOT NULL AUTO_INCREMENT,
     title CHAR(30) NOT NULL,
     description TEXT NOT NULL,
     is_approved BOOLEAN NOT NULL DEFAULT 0,
     photo_url CHAR(100) NULL,
     location CHAR(100) NOT NULL,
     event_date DATETIME NOT NULL,
     created_date DATETIME NOT NULL
     host CHAR(20) NOT NULL,
     FOREIGN KEY (host) REFERENCES Users
     ON DELETE CASCADE ON UPDATE RESTRICT ,
     PRIMARY KEY (id)
)

CREATE TABLE attend (
     user CHAR(20),
     event INT,
     FOREIGN KEY (user) REFERENCES Users,
     FOREIGN KEY (event) REFERENCES Events,
     PRIMARY KEY (user, event)
)

CREATE TABLE Organizations (
     name CHAR(40),
     PRIMARY KEY (name)
)

CREATE TABLE follow_org (
     user CHAR(20),
     org CHAR(40),
     FOREIGN KEY (user) REFERENCES Users,
     FOREIGN KEY (org_name) REFERENCES Organizations,
     PRIMARY KEY (user, org_name)
)

CREATE TABLE organizer (
     org CHAR(40),
     event INT,
     FOREIGN KEY (org) REFERENCES Organizations,
     FOREIGN KEY (event) REFERENCES Events,
     PRIMARY KEY (org, event)
)