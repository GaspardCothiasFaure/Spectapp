# Spectapp

Spectapp is an online ticketing application to book show in a theatre.
It has been developed for an academic project during the 3rd year of engineering school at ENSG in the TSI stream.

## Install

1. Download the [master branch](https://gitlab.com/gascothiasfaure/tsi-personal-project-spectapp/-/tree/master) of this repository on your computer : ([click here to download](https://gitlab.com/gascothiasfaure/tsi-personal-project-spectapp/-/archive/master/tsi-personal-project-spectapp-master.zip)).

2. Add the `spectapp-src` folder in the root folder of your local server (*MAMP* or *WAMP* for example).

3. Create a new database named **spectapp-db** in your local database (*phpMyAdmin* for example) and import the Spectapp database in dumping `spectapp-db/spectapp-db.sql` in this database.

>**Note :**
>
>spectapp-db.sql is a phpMyAdmin SQL Dump version 4.9.2, encoding *utf8*.

4. Define the database connection in Spectapp source code : open `spectapp-src/link-db-client/connect-db.php` and define the connection with your local database in the folowing variables :
- *$host_port* for the host and port of your database connection (ex : "localhost:3306")
- *$user* for the user name of your database connection (ex : "root")
- *$password* for the password of your database connection (ex : "")
- *$db_name* for the database name (ex : "spectapp-db")

>**Note :**
>
>By default, the connection settings has been made to connect the application with the local DBMS of **MAMP** : *phpMyAdmin* connected to **localhost** on port **3306** and a database named **spectapp-db**, with default user : **"root"**, and default password : **""**.
>
>Server version used for development : 10.4.10-MariaDB
>
>PHP version used for development :  7.3.12

5. Lunch your local server (*MAMP* or *WAMP*).

4. Access to spectapp home in typing `http://localhost/spectapp-src` in your URL bar of your navigator.

## Quick start

### Book one or several seats for a show
Follow the instructions in the *Réservation* tab of Spectapp.

At the end, you will receive a reservation code. Link to your email, it's the proof of your reservation.

### Cancel a reservation
Follow the instructions in the *Annulation* tab of Spectapp.

You will have to connect with your email and your reservation code received when you made your reservation.

You can use an email and reservation code of a precedent reservation made in Spectapp.
Or you case use these existing reservation identifiers :

| Email          | Reservation code |
| :-----------:  |:---------------: |
| resa@test.com  | 0000000z         |
| resa@test.com  | 0000000a         |

Reservation code *0000000z* corresponds to this reservation :

| Show name            | Show artist       | Performance date      | Number of reserved seats | Reserved seats numbers | COVID-19 procotol |
| :-------------:      |:---------------:  | :---------------:     | :---------------:        | :---------------:      | :---------------: |
| Le monde à l'envers  | Corentin Chamoux  |  2020-11-15T21:30:00  |  3                       |  27,28,29              |  no               |

Reservation code *0000000z* corresponds to this reservation :

| Show name                      | Show artist    | Performance date      | Number of reserved seats | Reserved seats numbers | COVID-19 procotol |
| :-------------:                |:------------:  | :---------------:     | :---------------:        | :---------------:      | :---------------: |
| De la plus belle des manières  | Astrid Landry  |  2020-11-24T20:30:00  |  2                       |  39,50                 |  yes              |


### Choose a seat on a map
A user can choose one or several seats on the theatre map in `spectapp-src/booking-seat.php`. The seats have to be bookable.

>**NOTE :**
>
>A user can't book more than 5 seats.
>
>The theatre is a 77 seats theatre (49 under COVID-19 protocol).

### Deal with different performances on several dates
All performances of a show are displayed on the calendar (in `spectapp-src/booking-date.php`). The performances are grouped in November 2020.

To test the behaviour of Spectapp, 10 shows and 4 performances by show have been added to the database. Each show have 3 normal performances and one under COVID-19 protocol.

#### Among these 40 performances, 4 performances have reserved seats :

A performance with several reserved seats with no COVID-19 protocol :

| Show name            | Show artist       | Performance date      | Number of reserved seats | Reserved seats numbers    | COVID-19 procotol |
| :-------------:      |:------------:     | :---------------:     | :---------------:        | :---------------:         | :---------------: |
| Le monde à l'envers  | Corentin Chamoux  |  2020-11-15T21:30:00  |  9                       |  4,5,6,27,28,29,75,76,77  |  no               |

A full performance with no COVID-19 protocol :

| Show name            | Show artist       | Performance date      | Number of reserved seats | Reserved seats numbers  | COVID-19 procotol |
| :-------------:      |:------------:     | :---------------:     | :---------------:        | :---------------:       | :---------------: |
| Le monde à l'envers  | Corentin Chamoux  |  2020-11-08T21:30:00  |  77                      |  all                    |  no               |

A performance with several reserved seats under COVID-19 protocol :

| Show name                      | Show artist    | Performance date      | Number of reserved seats | Reserved seats numbers  | COVID-19 procotol |
| :-------------:                |:------------:  | :---------------:     | :---------------:        | :---------------:       | :---------------: |
| De la plus belle des manières  | Astrid Landry  |  2020-11-24T20:30:00  |  6                       |  14,15,30,31,39,50      |  yes              |

A full performance under COVID-19 protocol :

| Show name            | Show artist       | Performance date      | Number of reserved seats | Reserved seats numbers  | COVID-19 procotol |
| :-------------:      |:------------:     | :---------------:     | :---------------:        | :---------------:       | :---------------: |
| Le monde à l'envers  | Corentin Chamoux  |  2020-11-23T20:30:00  |  49                      |  all                    |  yes              |


### Stop sales if a performance is full
If a performance is full, it's displayed on the calendar (in `spectapp-src/booking-date.php`) with a red label but the next reservation is not accessible.

>**NOTE :**
>A performance under COVID-19 protocol is full when all the bookable seats has been booked.

### Handle users who book and cancel tickets
The spectapp database is updated on each new reservation and cancellation.

>**NOTE :**
>
>A seat can't be book two times.
>
>Every time a seat is cancelled, it's re-open to reservation.

#### Reservation
For each new reservation, the corresponding performance is updated in the Spectapp database with new booked seats in the `performance` table and the reservation is stored in the `reservation` table.

#### Cancelation
For each cancellation, the corresponding performance is updated in the Spectapp database with the cancelled seats in the `performance` table and the reservation is delete from the `reservation` table.

### Sell one seat out of two in case of COVID-19
If the performance is under COVID-19 protocol, only one seat out of two is bookable. Spectapp inform the user if the performance is under COVID-19 protocol when he chooses a date. When the user chooses his seats, the non-bookable seats are dissociated from the other and are not selectable.

## Other proposed functionnalities

### Search
If the user can't find a show, he can search a show (in `spectapp-src/show-search.html`) by clicking on *Ou rechercher un spectacle* in home page.

>**NOTE :**
> A user have to enter at least two letter to find a show.

### Admin
A spectapp administrator has access to the databse directly from the application via the admin functionnalities of Spectapp.

To connect to an admin session, you must go to `spectapp-src/admin/admin-portal.html` and connect with one of the two Spectapp administrator identifiers stored in the database :

| User name       | Password |
| -------------   |:-------: |
| spectappadmin1  | pass     |
| spectappadmin2  | pass     |

#### Add a show
A Spectapp administrator can add a new show in the *Ajout* tab of an admin session (in `spectapp-src/admin/admin-add.html`) by choosing all its new attributes : show name, show artist, description, poster file, performance date and COVID-19 protocol activated or not.

>**NOTE :**
> A show can't have more than 500 performances.

#### Delete a show
A Spectapp administrator can delete a show in the *Supression* tab of an admin session (in `spectapp-src/admin/admin-delete.php`).

> **NOTE :**
> The poster file and all the performances and reservations will also be removed.

### Coupon code
When the user is about to pay for his reservation in `spectapp-src/booking-confirmation.php`, he has the possibility to enter a promotionnal code to benefit from a discount.

There are two disponible promotionnal codes stored in the Spectapp database :

| Code          | Discount value |
| ------------- |:-------------: |
| SUPERNOEL     | 15%            |
| BLACKFRIDAY   | 20%            |

> **NOTE :**
> A user can't cumulate two coupon codes.

### Notes and comments
Every user can comment and note each show, by accessing to `spectapp-src/show-critics.php` after a click on *Voir les avis* for a show.

A show is noted and its note appears in the home page if its has at least one critic (note + comment). If there are several critics, its note is the average note of all critics.

>**NOTE :**
>A user can't give the note of 0 (out of 5) to a show.

## Author

**Gaspard Cothias Faure**

## Credits

Exhaustive list of the JavaScript libraries I used :

- [Bootstrap](https://github.com/twbs/bootstrap)

- [JQuery](https://github.com/jquery/jquery)

- [Toastr](https://github.com/CodeSeven/toastr)

- [Font-Awesome](https://github.com/FortAwesome/Font-Awesome)

- [Fuse](https://github.com/krisk/Fuse)

- [FullCalendar](https://github.com/fullcalendar/fullcalendar)
